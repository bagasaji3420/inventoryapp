<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockInItem extends Model
{
    protected $fillable = [
        'stock_in_id',
        'item_id',
        'unit_id',
        'qty_input',
        'qty_base',
    ];

    protected function casts(): array
    {
        return [
            'qty_input' => 'decimal:2',
            'qty_base' => 'decimal:2',
        ];
    }

    public function stockIn(): BelongsTo
    {
        return $this->belongsTo(StockIn::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }
}
