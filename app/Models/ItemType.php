<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class ItemType extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'nama_jenis',
        'status',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class, 'jenis_id');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('item-type')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at', 'deleted_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Jenis barang \"{$this->nama_jenis}\" has been {$eventName}");
    }
}
