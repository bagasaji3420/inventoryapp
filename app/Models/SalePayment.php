<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class SalePayment extends Model
{
    use LogsActivity;

    protected $fillable = [
        'sale_id',
        'tanggal',
        'jumlah',
        'keterangan',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
            'jumlah' => 'decimal:2',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('sale-payment')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Pembayaran penjualan #{$this->sale_id} has been {$eventName}");
    }
}
