<?php

namespace App\Mail;

use App\Models\Event;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EventReminder extends Mailable
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
        return $this->subject('Event Reminder - ' . $this->event->title . ' Tomorrow!')
                    ->view('emails.event-reminder');
    }
}