<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Supplier extends Model
{
    use SoftDeletes;
    use LogsActivity;

    protected $fillable = [
        'nama_supplier',
        'kontak_person',
        'telepon',
        'alamat',
        'status',
    ];

    public function stockIns(): HasMany
    {
        return $this->hasMany(StockIn::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('supplier')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept(['created_at', 'updated_at', 'deleted_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "Supplier \"{$this->nama_supplier}\" has been {$eventName}");
    }
}
