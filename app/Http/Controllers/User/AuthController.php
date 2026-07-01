<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Services\Auth\TwoFactorService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{
    public function __construct(
        protected AuthService $authService,
        protected TwoFactorService $twoFactorService
    ) {}

    public function authenticate(Request $request): RedirectResponse
    {
        $request->validate([
            'email-username' => ['required'],
            'password' => ['required'],
        ]);

        $loginInput = $request->input('email-username');

        $field = filter_var($loginInput, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $field => $loginInput,
            'password' => $request->password,
        ];

        $remember = $request->has('remember');

        if (!Auth::attempt($credentials, $remember)) {
            Alert::error('Failed', 'The provided credentials do not match our records.');

            return back()->withErrors([
                'email-username' => 'The provided credentials do not match our records.',
            ])->onlyInput('email-username');
        }

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->two_factor_status) {

            $this->twoFactorService->sendOtp($user);

            session(['2fa_user_id' => $user->id]);
            Auth::logout();

            return redirect()->route('2fa.verify');
        }

        if ($user->hasRole('guest')) {
            return redirect('/');
        }

        return redirect()->intended(route('dashboard'));
    }

    public function verify(Request $request)
    {

        $request->validate([
            'otp' => 'required|digits:6'
        ]);

        /** @var \App\Models\User|null $user */
        $user = User::find(session('2fa_user_id'));

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$this->twoFactorService->verify($user, $request->otp)) {
            Alert::error('Failed', 'Invalid or expired OTP');

            return back()->withErrors([
                'otp' => 'Invalid or expired OTP'
            ]);
        }

        session()->forget('2fa_user_id');

        return redirect()->intended(route('dashboard'));
    }

    public function resendOtp()
    {
        /** @var \App\Models\User|null $user */
        $user = User::find(session('2fa_user_id'));

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'otp' => 'Session expired. Please login again.'
            ]);
        }

        if (!$this->twoFactorService->canResend($user)) {
            Alert::warning('Cooldown', 'Please wait before requesting a new OTP.');
            return back();
        }

        $this->twoFactorService->sendOtp($user);

        Alert::success('Success', 'A new OTP has been sent to your email.');

        return back();
    }

    public function logout()
    {
        $this->authService->logout();

        return redirect('/login');
    }
}
