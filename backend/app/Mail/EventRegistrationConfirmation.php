<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventRegistrationConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $event;

    public function __construct(User $user, Event $event)
    {
        $this->user = $user;
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('Event Registration Confirmation - ' . $this->event->title)
                    ->view('emails.registration-confirmation');
    }
}