<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class StockOut extends Model
{
    use LogsActivity;

    protected $fillable = [
        'no_transaksi',
        'customer_id',
        'sale_id',
        'tanggal',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockOutItem::class);
    }

    public function deliveryNote(): HasOne
    {
        return $this->hasOne(DeliveryNote::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('stock-out')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Barang keluar \"{$this->no_transaksi}\" has been {$eventName}");
    }
}
