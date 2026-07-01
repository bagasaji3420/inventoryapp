<?php

use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use function Livewire\Volt\{state};

state([
    'password' => '',
    'confirming' => false,
]);

$confirmPassword = function () {
    $this->validate([
        'password' => 'required',
    ]);

    if (!Hash::check($this->password, auth()->user()->password)) {
        $this->addError('password', 'Wrong password');
        return;
    }

    auth()
        ->user()
        ->update([
            'two_factor_status' => !auth()->user()->two_factor_status,
        ]);

    $this->reset('password', 'confirming');

    $this->dispatch('toast', [
        'type' => 'success', // success, error, warning, info
        'message' => '2FA ' . (auth()->user()->two_factor_status == 0) ? 'Disabled' : 'Enabled',
    ]);

    session();
};

?>

<div class="card">
    <div class="card-body">
        <h5>Two-Factor Authentication</h5>
        @if (!Auth::user()->email_verified_at)
            <span class="text-danger">
                Please verify your email address before enabling two-factor authentication.
            </span>
        @endif
        <p>
            Two-factor authentication adds an extra layer of protection to your account.
            To enable it, you’ll need to confirm your password and verify a one-time password (OTP) sent to your
            registered email address.

        </p>
        <p>
            Status:
            <strong class="{{ auth()->user()->two_factor_status == '1' ? 'text-success' : 'text-warning' }}">
                {{ auth()->user()->two_factor_status ? 'Active' : 'Non Active' }}
            </strong>
        </p>

        @if (!$confirming)
            <button wire:click="$set('confirming', true)" class="btn btn-primary"
                {{ Auth::user()->email_verified_at == null ? 'disabled' : '' }}>
                {{ auth()->user()->two_factor_status ? 'Disable' : 'Enable' }} 2FA
            </button>
        @else
            <div class="mt-3">
                <input type="password" wire:model="password" class="form-control mb-2" placeholder="Enter password">

                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

                <button wire:click="confirmPassword" class="btn btn-success">
                    Konfirmasi
                </button>

                <button wire:click="$set('confirming', false)" class="btn btn-secondary">
                    Batal
                </button>
            </div>
        @endif



    </div>

    
</div>
