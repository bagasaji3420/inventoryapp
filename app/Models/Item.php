<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Item extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'kode_barang',
        'barcode',
        'nama_barang',
        'satuan_id',
        'jenis_id',
        'harga',
        'stok_minimum',
        'stok',
        'foto',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'decimal:2',
            'stok_minimum' => 'decimal:2',
            'stok' => 'decimal:2',
        ];
    }

    public function satuan(): BelongsTo
    {
        return $this->belongsTo(Unit::class, 'satuan_id');
    }

    public function jenis(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'jenis_id');
    }

    public function stockInItems(): HasMany
    {
        return $this->hasMany(StockInItem::class);
    }

    public function stockOutItems(): HasMany
    {
        return $this->hasMany(StockOutItem::class);
    }

    public function saleItems(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockCards(): HasMany
    {
        return $this->hasMany(StockCard::class);
    }

    public function stockOpnameItems(): HasMany
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('item')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept([
                'created_at',
                'updated_at',
                'deleted_at',
                // stok changes are already tracked in the stock_cards ledger
                // via StockCardService, so logging it here would just duplicate noise.
                'stok',
            ])
            ->setDescriptionForEvent(fn (string $eventName) => "Barang \"{$this->nama_barang}\" has been {$eventName}");
    }
}
