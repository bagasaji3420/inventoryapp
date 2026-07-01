<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class StockOpname extends Model
{
    use LogsActivity;

    protected $fillable = [
        'no_opname',
        'tanggal_opname',
        'petugas_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_opname' => 'date',
        ];
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockOpnameItem::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('stock-opname')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Stok opname \"{$this->no_opname}\" has been {$eventName}");
    }
}
