<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Unit extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'nama_satuan',
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'satuan_id');
    }

    public function conversionsFrom(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'from_unit_id');
    }

    public function conversionsTo(): HasMany
    {
        return $this->hasMany(UnitConversion::class, 'to_unit_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('unit')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at', 'deleted_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Satuan \"{$this->nama_satuan}\" has been {$eventName}");
    }
}
