<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockOpnameItem extends Model
{
    protected $fillable = [
        'stock_opname_id',
        'item_id',
        'stok_sistem',
        'stok_fisik',
        'selisih',
    ];

    protected function casts(): array
    {
        return [
            'stok_sistem' => 'decimal:2',
            'stok_fisik' => 'decimal:2',
            'selisih' => 'decimal:2',
        ];
    }

    public function stockOpname(): BelongsTo
    {
        return $this->belongsTo(StockOpname::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
