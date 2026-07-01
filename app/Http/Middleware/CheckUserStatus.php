<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Cache;

class CheckUserStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return $next($request);
        }

        // dd('middleware jalan');

        $user = Auth::user();

        // ❌ BLOCK BANNED
        if ($user->status === 'banned') {
            Auth::logout();

            Alert::error('Banned', 'This account was banned');

            return redirect('/login')->withErrors([
                'email' => 'This account was banned'
            ]);
        }

        // ⏳ AUTO UNSUSPEND
        if (
            $user->status === 'suspend' &&
            $user->suspended_until &&
            now()->greaterThan($user->suspended_until)
        ) {
            $user->update([
                'status' => 'active',
                'suspended_until' => null,
                'status_reason' => null,
            ]);

            // optional alert
            Alert::success('Welcome back', 'Your account is active again');
        }

        view()->share(
            'suspended_until',
            ($user->status === 'suspend' && $user->suspended_until && now()->lt($user->suspended_until))
                ? $user->suspended_until
                : null
        );

        // 🔥 LAST ACTIVITY (REALTIME)
        Cache::put(
            'user-is-online-' . $user->id,
            true,
            now()->addMinutes(1)
        );

        return $next($request);
    }
}
