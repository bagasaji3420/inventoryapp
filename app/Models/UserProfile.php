<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class UserProfile extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id',
        'mobile',
        'address',
        'city',
        'country',
        'birth_date',
        'gender',
        'pincode',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('profile')
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "User profile has been {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}