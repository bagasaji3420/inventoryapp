<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class DeliveryNote extends Model
{
    use LogsActivity;

    protected $fillable = [
        'no_surat',
        'stock_out_id',
        'tanggal',
        'alamat_tujuan',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function stockOut(): BelongsTo
    {
        return $this->belongsTo(StockOut::class);
    }

    public static function generateNoSurat(): string
    {
        $count = static::whereYear('tanggal', now()->year)
            ->whereMonth('tanggal', now()->month)
            ->count() + 1;
        $prefix = Settings::current()->kode_surat_jalan;

        return sprintf('%s-%04d/%s/%s', $prefix, $count, now()->format('m'), now()->format('Y'));
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('delivery-note')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Surat jalan \"{$this->no_surat}\" has been {$eventName}");
    }
}
