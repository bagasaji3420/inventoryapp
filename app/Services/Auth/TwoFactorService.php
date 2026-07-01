<?php

namespace App\Services\Auth;

use App\Jobs\SendOtpMailJob;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TwoFactorService
{
    public function sendOtp(User $user): void
    {
        $code = rand(100000, 999999);

        Otp::updateOrCreate(
            [
                'user_id' => $user->id,
                'type' => '2fa',
            ],
            [
                'code' => Hash::make($code),
                'expires_at' => now()->addMinutes(5),
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]
        );

        $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

        

        dispatch(new SendOtpMailJob(
            $user->email,
            $name ?: $user->email,
            $code
        ));
    }

    public function verify(User $user, string $otpInput): bool
    {
        $otp = Otp::where('user_id', $user->id)
            ->where('type', '2fa')
            ->latest()
            ->first();

        if (!$otp || now()->gt($otp->expires_at)) {
            return false;
        }

        if (!Hash::check($otpInput, $otp->code)) {
            return false;
        }

        $otp->delete();

        Auth::login($user);

        return true;
    }

    public function canResend(User $user): bool
    {
        $lastOtp = Otp::where('user_id', $user->id)
            ->where('type', '2fa')
            ->latest()
            ->first();

        return !($lastOtp && $lastOtp->created_at->gt(now()->subSeconds(60)));
    }
}
