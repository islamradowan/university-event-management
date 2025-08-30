<?php

namespace App\Console\Commands;

use App\Mail\EventReminder;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send reminder emails for events happening tomorrow';

    public function handle()
    {
        $tomorrow = now()->addDay()->startOfDay();
        $endOfTomorrow = now()->addDay()->endOfDay();

        $events = Event::where('status', 'approved')
            ->whereBetween('start_at', [$tomorrow, $endOfTomorrow])
            ->with(['registrations.user'])
            ->get();

        $sentCount = 0;

        foreach ($events as $event) {
            foreach ($event->registrations as $registration) {
                if ($registration->user) {
                    try {
                        Mail::to($registration->user->email)->send(
                            new EventReminder($registration->user, $event)
                        );
                        $sentCount++;
                    } catch (\Exception $e) {
                        $this->error('Failed to send reminder to ' . $registration->user->email);
                    }
                }
            }
        }

        $this->info("Sent {$sentCount} reminder emails for " . $events->count() . " events");
        return 0;
    }
}