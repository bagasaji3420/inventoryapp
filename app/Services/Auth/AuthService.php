<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class AuthService
{
    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            $user->update([
                'last_seen' => now()
            ]);

            Cache::forget('user-is-online-' . $user->id);
        }

        Auth::logout();

        request()->session()->invalidate();
        request()->session()->regenerateToken();
    }
}