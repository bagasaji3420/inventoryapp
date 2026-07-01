<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendResetPasswordEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $resetUrl;

    public function __construct($user, $resetUrl)
    {
        $this->user = $user;
        $this->resetUrl = $resetUrl;
    }

    public function handle()
    {
        Mail::send('emails.base', [
            'title' => 'Reset Password',
            'name' => $this->user->first_name . ' ' . $this->user->last_name,
            'view' => 'emails.parts.reset-password',
            'buttonUrl' => $this->resetUrl,
            'buttonText' => 'Reset Password',
            'footer' => 'If you did not request a password reset, please ignore this email.',
        ], function ($message) {
            $message->to($this->user->email)
                ->subject('Reset Your Password');
        });
    }
}
