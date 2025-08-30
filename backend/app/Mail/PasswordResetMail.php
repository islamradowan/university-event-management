<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $email;

    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function build()
    {
        return $this->subject('Reset Your Password - UniEvents')
                    ->view('emails.password-reset')
                    ->with([
                        'resetUrl' => env('CLIENT_URL', 'http://localhost:8080') . '/reset-password?token=' . $this->token . '&email=' . urlencode($this->email)
                    ]);
    }
}