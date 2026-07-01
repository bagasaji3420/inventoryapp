<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Password as Passwords;
use Illuminate\Support\Facades\Mail;
use App\Jobs\SendResetPasswordEmail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    public function forgotPassword()
    {
        return view('Admin.User.forgot');
    }

    public function forgotPasswordSend(Request $request)
    {

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->with('error', 'Email not registered.');
        }

        $token = Passwords::createToken($user);
        $resetUrl = url("/reset-password/{$token}?email={$user->email}");
        SendResetPasswordEmail::dispatch($user, $resetUrl);

        Alert::toast('Success', 'Reset link has been successfully sent to your email.');

        return back()->with('success', 'Reset link has been sent to your email.');
    }

    public function resetPassword(Request $request)
    {
        Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'token' => 'required'
        ], [
            'email.required' => 'Email is required',
            'email.email' => 'Email format is invalid',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 8 characters',
            'password.confirmed' => 'Password confirmation does not match',
            'token.required' => 'Invalid or expired token'
        ]);

        $status = Passwords::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        $message = match ($status) {
            Passwords::INVALID_TOKEN => 'This reset link is invalid or has expired.',
            Passwords::INVALID_USER => 'We can’t find a user with that email address.',
            Passwords::RESET_THROTTLED => 'Please wait before trying again.',
            Passwords::PASSWORD_RESET => 'Password reset successfully!',
            default => 'Something went wrong.',
        };

        if ($status === Passwords::PASSWORD_RESET) {
            Alert::toast('Success', $message);

            return redirect()
                ->route('login')
                ->with('success', $message);
        }

        Alert::toast('Error', $message);

        return back()->withErrors([
            'email' => $message
        ]);
    }
}
