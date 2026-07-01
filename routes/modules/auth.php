<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\RegistrationController;
use App\Http\Controllers\User\ForgotPasswordController;
use App\Http\Controllers\User\GoogleController;

// Authentication 
Route::post('/authenticate', [AuthController::class, 'authenticate'])->name('auth');
Route::get('/two-factor', function () {
    return view('Admin.User.twofactor');
})->name('2fa.verify');
Route::post('/two-factor/verify', [AuthController::class, 'verify'])
->name('2fa.verify-otp');
Route::post('/2fa/resend', [AuthController::class, 'resendOtp'])->name('2fa.resend');


// Oauth 
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

// Registration
Route::get('/registration', [RegistrationController::class, 'index'])->name('registration');
Route::post('/register', [RegistrationController::class, 'store'])->name('register.store');



// Reset Password 
Route::get('/forgot-password', [ForgotPasswordController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/forgot-password-send-email', [ForgotPasswordController::class, 'forgotPasswordSend'])->name('forgotPasswordLink');
Route::post('/update-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
Route::get('/reset-password/{token}', function ($token) {
    return view('Admin.User.reset', [
        'token' => $token,
        'email' => request()->email
    ]);
})->name('password.reset');