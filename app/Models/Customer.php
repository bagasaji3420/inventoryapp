<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Customer extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'nama_pelanggan',
        'email',
        'telepon',
        'alamat',
        'status',
    ];

    public function stockOuts(): HasMany
    {
        return $this->hasMany(StockOut::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('customer')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at', 'deleted_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Pelanggan \"{$this->nama_pelanggan}\" has been {$eventName}");
    }
}
