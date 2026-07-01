<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendOtpMailJob implements ShouldQueue
{
    use Queueable;

    protected $email;
    protected $name;
    protected $otp;

    public function __construct($email, $name, $otp)
    {
        $this->email = $email;
        $this->name = $name;
        $this->otp = $otp;
    }

    public function handle(): void
    {
        Mail::send('emails.base', [
            'title' => 'Your OTP Code',
            'name' => $this->name,
            'view' => 'emails.parts.otp',
            'data' => [
                'otp' => $this->otp,
                'expired' => '5 minutes',
            ],
            'footer' => 'Do not share this code with anyone.',
        ], function ($message) {
            $message->to($this->email)
                ->subject('Your OTP Code');
        });
    }
}
