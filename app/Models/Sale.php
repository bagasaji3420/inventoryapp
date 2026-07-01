<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Sale extends Model
{
    use LogsActivity;

    protected $fillable = [
        'no_faktur',
        'customer_id',
        'tanggal',
        'total',
        'status_pembayaran',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'total' => 'decimal:2',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class);
    }

    public function stockOut(): HasOne
    {
        return $this->hasOne(StockOut::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sale')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Penjualan \"{$this->no_faktur}\" has been {$eventName}");
    }
}
