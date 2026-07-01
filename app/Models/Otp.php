<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Otp extends Model
{
    protected $fillable = [
        'user_id',
        'code',
        'type',
        'expires_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (BIAR ENAK DIPAKAI)
    |--------------------------------------------------------------------------
    */

    // cek expired
    public function isExpired()
    {
        return now()->gt($this->expires_at);
    }
}
