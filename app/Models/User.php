<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasRoles;
    use HasFactory, Notifiable;
    use LogsActivity;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'otp_expires_at' => 'datetime',
            'last_seen' => 'datetime',
            'suspended_until' => 'datetime',
            'notification_enabled' => 'boolean',
            'two_factor_status' => 'boolean',
        ];
    }

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
        'profile_link',
        'google_id',
        'avatar',
        'first_name',
        'last_name',
        'mobile',
        'pincode',
        'address',
        'city',
        'country',
        'email_verified_at',
        'two_factor_status',
        'status',
        'last_seen',
        'suspended_until',
        'status_reason',
        'notification_enabled'
    ];

    public function getAvatarUrlAttribute()
    {

        // kalau dari external (google, dll)
        if (Str::startsWith($this->avatar, ['http://', 'https://'])) {
            return $this->avatar;
        }

        // default avatar
        if (!$this->avatar) {
            return asset('assets/img/avatars/21.jpg');
        }

        // kalau dari storage Laravel
        return Storage::url($this->avatar);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isSuspend()
    {
        return $this->status === 'suspend';
    }

    public function isBanned()
    {
        return $this->status === 'banned';
    }

    public function isOnline()
    {
        return $this->last_seen && $this->last_seen->gt(now()->subMinutes(5));
    }

    public function emailVerification()
    {
        return $this->hasOne(EmailVerification::class);
    }

    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('user')
            ->logAll()
            ->logOnlyDirty()
            ->logExcept([
                'password',
                'otp_code',
                'otp_expires_at',
                'last_seen',
                'google_id',
                'email_verified_at',
                'remember_token',
                'created_at',
                'updated_at',
            ])
            ->setDescriptionForEvent(fn(string $eventName) => "User has been {$eventName}");
    }
}
