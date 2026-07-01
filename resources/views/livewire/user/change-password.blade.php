<?php
use App\Models\Otp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use function Livewire\Volt\{state};

state([
    'currentPassword' => '',
    'newPassword' => '',
    'confirmPassword' => '',
    'otp' => '',
    'otpSent' => false,
    'otpVerified' => false,
]);

$sendOtp = function () {
    $user = Auth::user();

    // 🔥 generate dulu
    $code = rand(100000, 999999);

    $otp = Otp::updateOrCreate(
        [
            'user_id' => $user->id,
            'type' => 'reset_password',
        ],
        [
            'code' => $code,
            'expires_at' => now()->addMinutes(5),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ],
    );

    // 🔥 ambil nama (support profile)
    $name = trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''));

    // 🔥 kirim email
    Mail::send(
        'emails.base',
        [
            'title' => 'Password OTP',
            'name' => $name ?: $user->email,
            'view' => 'emails.parts.otp',
            'data' => [
                'otp' => $code, // ✅ FIX DISINI
                'expired' => '5 minutes',
            ],
            'footer' => 'Do not share this code with anyone.',
            'date' => date('Y'),
            'appname' => config('app.name'),
        ],
        function ($msg) use ($user) {
            $msg->to($user->email)->subject('OTP Verification');
        },
    );

    $this->otpSent = true;
};

$verifyOtp = function () {
    $user = Auth::user();

    $otp = Otp::where('user_id', $user->id)->where('type', 'reset_password')->latest()->first();

    if (!$otp) {
        $this->addError('otp', 'Kode tidak ditemukan');
        return;
    }

    // ❌ kalau masih plaintext
    if ($otp->code != $this->otp) {
        $this->addError('otp', 'Kode salah');
        return;
    }

    // expired check
    if (now()->gt($otp->expires_at)) {
        $this->addError('otp', 'Kode expired');
        return;
    }

    // ✅ hapus biar one-time use
    $otp->delete();

    $this->otpVerified = true;
};

$changePassword = function () {
    $user = Auth::user();

    if (!$this->otpVerified) {
        $this->addError('otp', 'Verifikasi OTP dulu');
        return;
    }

    if (!Hash::check($this->currentPassword, $user->password)) {
        $this->addError('currentPassword', 'The old password is wrong');
        return;
    }

    if ($this->newPassword !== $this->confirmPassword) {
        $this->addError('confirmPassword', 'New password cannot be the same as old password');
        return;
    }

    if (Hash::check($this->newPassword, $user->password)) {
        $this->addError('newPassword', 'Confirmed password not match');
        return;
    }

    $user->update([
        'password' => Hash::make($this->newPassword),
        'otp_code' => null,
        'otp_expires_at' => null,
    ]);

    session()->flash('success', 'Password berhasil diubah');

    // reset state (biar balik awal)
    $this->reset(['currentPassword', 'newPassword', 'confirmPassword', 'otp', 'otpSent', 'otpVerified']);
};

?>

<div>
    <div class="card pt-1 px  mb-5">

        <div class="card-body">
            {{-- STEP 1: KIRIM OTP --}}
            @if (!$otpSent)
                <h5>Change Password</h5>
                <p class="text-muted">
                    For security reasons, you must verify your identity before changing your password.
                    An OTP (One-Time Password) will be sent to your email <span
                        class="fw-bold">{{ Auth::user()->email }}</span>. Please enter the
                    code to continue.
                </p>

                <button wire:click="sendOtp" class="btn btn-primary mb-4">
                    Send OTP to Email
                </button>
                <div wire:loading wire:target="sendOtp" class="spinner-border text-warning" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            @endif

            {{-- STEP 2: INPUT OTP --}}
            @if ($otpSent && !$otpVerified)
                <div class="alert alert-success">
                    A verification code has been sent to your email. Please check your inbox.
                </div>

                <div class="mb-4">
                    <label class="form-label">Verification Code</label>
                    <input type="text" wire:model="otp" class="form-control" placeholder="Enter OTP">

                    @error('otp')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror

                    <button wire:click="verifyOtp" class="btn btn-primary mt-2">
                        Verify Code
                    </button>

                    <a href="https://mail.google.com" target="_blank" class="btn btn-outline-primary mt-2">
                        Open Gmail
                    </a>
                </div>
            @endif

            {{-- STEP 3: FORM PASSWORD --}}
            @if ($otpVerified)
                <div class="row">

                    {{-- CURRENT PASSWORD --}}
                    <div x-data="{ show: false }" class="mb-6 col-md-6 form-password-toggle form-control-validation">
                        <label class="form-label">Current Password</label>
                        <div class="input-group input-group-merge">
                            <input :type="show ? 'text' : 'password'" type="password" wire:model="currentPassword"
                                class="form-control"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer" @click="show = !show">
                                <i :class="show ? 'bx bx-show' : 'bx bx-hide'"></i>
                            </span>
                        </div>

                        @error('currentPassword')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                <div class="row">

                    {{-- NEW PASSWORD --}}
                    <div x-data="{ show: false }" class="mb-6 col-md-6 form-password-toggle form-control-validation">
                        <label class="form-label">New Password</label>
                        <div class="input-group input-group-merge">
                            <input :type="show ? 'text' : 'password'" wire:model="newPassword" class="form-control"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer" @click="show = !show">
                                <i :class="show ? 'bx bx-show' : 'bx bx-hide'"></i>
                            </span>
                        </div>

                        @error('newPassword')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- CONFIRM PASSWORD --}}
                    <div x-data="{ show: false }" class="mb-6 col-md-6 form-password-toggle form-control-validation">
                        <label class="form-label">Confirm New Password</label>
                        <div class="input-group input-group-merge">
                            <input :type="show ? 'text' : 'password'" wire:model="confirmPassword" class="form-control"
                                placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                            <span class="input-group-text cursor-pointer" @click="show = !show">
                                <i :class="show ? 'bx bx-show' : 'bx bx-hide'"></i>
                            </span>
                        </div>

                        @error('confirmPassword')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- REQUIREMENTS --}}
                <h6 class="text-body">Password Requirements:</h6>
                <ul class="ps-4 mb-3">
                    <li class="mb-2">Minimum 8 characters</li>
                    <li class="mb-2">At least one lowercase</li>
                    <li>At least one number / symbol</li>
                </ul>

                {{-- ACTION --}}
                <div class="mt-3">
                    <button wire:click="changePassword" class="btn btn-primary me-3">
                        Save changes
                    </button>

                    <button wire:click="$refresh" class="btn btn-label-secondary">
                        Reset
                    </button>
                </div>
            @endif
        </div>

    </div>
</div>
