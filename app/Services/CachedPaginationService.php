<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;

class CachedPaginationService
{
    /**
     * Paginate a query while only caching plain scalar data (ids + total).
     *
     * The app's cache store disallows unserializing arbitrary objects
     * (config('cache.serializable_classes') === false) to prevent cache
     * poisoning, so Eloquent models/paginators can never be cached directly.
     * Instead we cache the cheap "which ids are on this page" lookup and
     * re-hydrate full models (with relations) from the database every time.
     */
    public static function paginate(
        Builder $listQuery,
        Builder $hydrateQuery,
        string $cacheKey,
        int $page,
        int $perPage,
        $ttl
    ): LengthAwarePaginator {
        $cacheData = Cache::remember($cacheKey, $ttl, function () use ($listQuery, $page, $perPage) {
            $paginator = $listQuery->paginate($perPage, ['id'], 'page', $page);

            return [
                'ids' => $paginator->pluck('id')->all(),
                'total' => $paginator->total(),
            ];
        });

        $ids = $cacheData['ids'];
        $table = $hydrateQuery->getModel()->getTable();

        $items = empty($ids)
            ? collect()
            : $hydrateQuery->whereIn("{$table}.id", $ids)
                ->get()
                ->sortBy(fn ($model) => array_search($model->id, $ids))
                ->values();

        return new LengthAwarePaginator(
            $items,
            $cacheData['total'],
            $perPage,
            $page,
            ['path' => Request::url(), 'query' => Request::query()]
        );
    }
}
