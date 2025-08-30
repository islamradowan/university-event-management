<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminAnnouncement extends Mailable
{
    use Queueable, SerializesModels;

    public $announcementTitle;
    public $announcementMessage;
    public $userName;

    public function __construct($title, $message, $userName)
    {
        $this->announcementTitle = $title;
        $this->announcementMessage = $message;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject($this->announcementTitle)
                    ->view('emails.admin-announcement');
    }
}