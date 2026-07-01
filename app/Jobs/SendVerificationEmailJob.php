<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmailJob implements ShouldQueue
{
    public $user;
    public $token;

    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    public function handle()
    {
        $url = url("/email/verify/{$this->token}");

        Mail::send('emails.base', [
            'title' => 'Verify Email',
            'name' =>  $this->user->first_name . ' ' . $this->user->last_name,
            'view' => 'emails.parts.verify-email',
            'buttonUrl' => $url,
            'buttonText' => 'Verify Email',
            'footer' => 'If you did not create an account, no further action is required.',
        ], function ($message) {
            $message->to($this->user->email)
                ->subject('Verify Your Email Address');
        });
    }
}
