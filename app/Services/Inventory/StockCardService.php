<?php

namespace App\Services\Inventory;

use App\Models\Item;
use App\Models\StockCard;
use App\Services\CacheVersionService;

class StockCardService
{
    public const CACHE_NAMESPACE = 'stock_cards.index';

    public static function masuk(Item $item, float $qty, string $referensi): StockCard
    {
        $item->increment('stok', $qty);

        $stockCard = StockCard::create([
            'item_id' => $item->id,
            'tanggal' => now(),
            'jenis_transaksi' => 'masuk',
            'referensi' => $referensi,
            'masuk' => $qty,
            'keluar' => null,
            'saldo' => $item->fresh()->stok,
        ]);

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        return $stockCard;
    }

    public static function keluar(Item $item, float $qty, string $referensi): StockCard
    {
        $item->decrement('stok', $qty);

        $stockCard = StockCard::create([
            'item_id' => $item->id,
            'tanggal' => now(),
            'jenis_transaksi' => 'keluar',
            'referensi' => $referensi,
            'masuk' => null,
            'keluar' => $qty,
            'saldo' => $item->fresh()->stok,
        ]);

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        return $stockCard;
    }

    public static function opname(Item $item, float $selisih, string $referensi): StockCard
    {
        $item->increment('stok', $selisih);

        $stockCard = StockCard::create([
            'item_id' => $item->id,
            'tanggal' => now(),
            'jenis_transaksi' => 'opname',
            'referensi' => $referensi,
            'masuk' => $selisih > 0 ? $selisih : null,
            'keluar' => $selisih < 0 ? abs($selisih) : null,
            'saldo' => $item->fresh()->stok,
        ]);

        CacheVersionService::bump(self::CACHE_NAMESPACE);

        return $stockCard;
    }
}
