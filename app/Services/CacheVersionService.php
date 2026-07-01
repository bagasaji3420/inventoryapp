<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheVersionService
{
    public static function key(string $namespace, array $parts = []): string
    {
        $version = static::version($namespace);

        $suffix = collect($parts)
            ->map(fn ($value, $name) => "{$name}:{$value}")
            ->implode('|');

        return "{$namespace}:v{$version}" . ($suffix ? ":{$suffix}" : '');
    }

    public static function version(string $namespace): int
    {
        return Cache::get("cache_version:{$namespace}", 1);
    }

    public static function bump(string $namespace): void
    {
        if (! Cache::has("cache_version:{$namespace}")) {
            Cache::forever("cache_version:{$namespace}", 1);
        }

        Cache::increment("cache_version:{$namespace}");
    }
}
