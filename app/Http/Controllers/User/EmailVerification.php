<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\EmailVerification as Email;
use App\Jobs\SendVerificationEmailJob;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;


class EmailVerification extends Controller
{
    public function send(Request $request)
    {
        $user = Auth::user();

        // hapus token lama
        Email::where('user_id', $user->id)->delete();

        $token = Str::random(64);

        Email::create([
            'user_id' => $user->id,
            'token' => $token,
            'expires_at' => now()->addMinutes(60)
        ]);

        dispatch(new SendVerificationEmailJob($user, $token));

        return back()->with('success', 'Verification email sent!');
    }

    public function verify($token)
    {
        $record = Email::where('token', $token)->first();

        if (!$record) {
            return redirect('/login')->with('error', 'Invalid token');
        }

        if ($record->isExpired()) {
            return redirect('/login')->with('error', 'Token expired');
        }

        $user = $record->user;

        $user->update([
            'email_verified_at' => now()
        ]);

        // ✅ AUTO LOGIN
        Auth::login($user);

        $record->delete();

        Alert::success('Email Verified', '');

        return redirect()->route('dashboard')
            ->with('success', 'Email verified!');
    }
}
