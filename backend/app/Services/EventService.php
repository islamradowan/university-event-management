<?php

namespace App\Services;

use App\Models\Event;
use App\Models\EventWaitlist;
use App\Models\EventRegistration;
use Carbon\Carbon;

class EventService
{
    public function joinWaitlist($eventId, $userId)
    {
        $event = Event::findOrFail($eventId);
        
        if (!$event->enable_waitlist) {
            throw new \Exception('Waitlist not enabled for this event');
        }

        $position = EventWaitlist::where('event_id', $eventId)->max('position') + 1;

        return EventWaitlist::create([
            'user_id' => $userId,
            'event_id' => $eventId,
            'position' => $position,
            'joined_at' => now()
        ]);
    }

    public function promoteFromWaitlist($eventId)
    {
        $event = Event::findOrFail($eventId);
        $currentRegistrations = EventRegistration::where('event_id', $eventId)->count();
        
        if ($currentRegistrations < $event->capacity) {
            $waitlistEntry = EventWaitlist::where('event_id', $eventId)
                ->orderBy('position')
                ->first();
                
            if ($waitlistEntry) {
                EventRegistration::create([
                    'user_id' => $waitlistEntry->user_id,
                    'event_id' => $eventId,
                    'qr_token' => \Str::uuid()
                ]);
                
                $waitlistEntry->delete();
                
                // Update positions
                EventWaitlist::where('event_id', $eventId)
                    ->where('position', '>', $waitlistEntry->position)
                    ->decrement('position');
                    
                return true;
            }
        }
        
        return false;
    }

    public function createRecurringEvents($eventData, $pattern)
    {
        $events = [];
        $baseDate = Carbon::parse($eventData['start_at']);
        
        for ($i = 0; $i < ($pattern['occurrences'] ?? 5); $i++) {
            $newDate = $this->calculateNextDate($baseDate, $pattern, $i);
            
            $newEventData = array_merge($eventData, [
                'start_at' => $newDate,
                'end_at' => $newDate->copy()->addHours(2),
                'title' => $eventData['title'] . ' #' . ($i + 1),
                'recurring_pattern' => $pattern,
                'parent_event_id' => $i === 0 ? null : $events[0]->id ?? null
            ]);
            
            $event = Event::create($newEventData);
            $events[] = $event;
        }
        
        return $events;
    }

    private function calculateNextDate($baseDate, $pattern, $occurrence)
    {
        switch ($pattern['frequency']) {
            case 'daily':
                return $baseDate->copy()->addDays($occurrence * ($pattern['interval'] ?? 1));
            case 'weekly':
                return $baseDate->copy()->addWeeks($occurrence * ($pattern['interval'] ?? 1));
            case 'monthly':
                return $baseDate->copy()->addMonths($occurrence * ($pattern['interval'] ?? 1));
            default:
                return $baseDate->copy()->addDays($occurrence);
        }
    }

    public function createFromTemplate($templateId, $eventData)
    {
        $template = Event::where('is_template', true)->findOrFail($templateId);
        
        return Event::create(array_merge([
            'title' => $template->title,
            'description' => $template->description,
            'location' => $template->location,
            'category' => $template->category,
            'capacity' => $template->capacity,
            'enable_waitlist' => $template->enable_waitlist
        ], $eventData));
    }
}