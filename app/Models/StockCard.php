<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockCard extends Model
{
    protected $fillable = [
        'item_id',
        'tanggal',
        'jenis_transaksi',
        'referensi',
        'masuk',
        'keluar',
        'saldo',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'datetime',
            'masuk' => 'decimal:2',
            'keluar' => 'decimal:2',
            'saldo' => 'decimal:2',
        ];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
