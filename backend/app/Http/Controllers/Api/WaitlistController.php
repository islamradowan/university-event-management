<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Http\Request;

class WaitlistController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    public function join(Request $request, Event $event)
    {
        try {
            $waitlistEntry = $this->eventService->joinWaitlist($event->id, $request->user()->id);
            
            return response()->json([
                'message' => 'Added to waitlist',
                'position' => $waitlistEntry->position
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function getWaitlist(Event $event)
    {
        $waitlist = $event->waitlist()->with('user')->orderBy('position')->get();
        
        return response()->json($waitlist);
    }

    public function promote(Request $request, Event $event)
    {
        $promoted = $this->eventService->promoteFromWaitlist($event->id);
        
        return response()->json([
            'message' => $promoted ? 'User promoted from waitlist' : 'No users to promote',
            'promoted' => $promoted
        ]);
    }
}