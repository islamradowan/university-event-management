<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Events\EventUpdated;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $limit = $request->get('limit', 20);

        // Admin can see all events, others only approved
        $query = Event::with(['organizer:id,name', 'media:id,event_id,file_path,type'])
            ->withCount('registrations');

        if ($user && $user->role === 'admin') {
            // Admin sees all events
            $query->orderBy('created_at', 'desc');
        } else {
            // Non-admin users only see approved events
            $query->where('status', 'approved')->orderBy('start_at');
        }
        
        return $query->limit($limit)->get();
    }
    public function publicIndex(Request $request)
    {
        $limit = $request->get('limit', 10);
        
        return Event::with('organizer:id,name')
            ->where('status', 'approved')
            ->where('start_at', '>=', now())
            ->orderBy('start_at')
            ->limit($limit)
            ->get();
    }

    public function show(Event $event)
    {
        $event->load(['organizer','media','registrations','feedbacks']);
        return response()->json($event);
    }

    public function myEvents(Request $request)
    {
        $user = $request->user();
        return Event::with('registrations', 'feedbacks')
                    ->where('organizer_id', $user->id)
                    ->orderByDesc('start_at')
                    ->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'start_at'=>'required|date',
            'end_at'=>'required|date|after:start_at',
            'location'=>'nullable|string',
            'category'=>'nullable|string',
            'capacity'=>'nullable|integer',
            'featured'=>'boolean',
        ]);

        $data['organizer_id'] = $request->user()->id;
        $event = Event::create($data);
        return response()->json($event,201);
    }

    public function update(Request $request, Event $event)
    {
        // $this->authorize('update',$event); // optional: use policy
        $data = $request->validate([
            'title'=>'sometimes|required|string|max:255',
            'description'=>'nullable|string',
            'start_at'=>'sometimes|date',
            'end_at'=>'sometimes|date|after:start_at',
            'location'=>'nullable|string',
            'category'=>'nullable|string',
            'capacity'=>'nullable|integer',
            'featured'=>'boolean',
            'status'=>'in:pending,approved,rejected'
        ]);

        $event->update($data);
        broadcast(new EventUpdated($event));
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete',$event); // optional
        $event->delete();
        return response()->json(['message'=>'Deleted']);
    }

    // public function approveEvent($eventId)
    // {
    //     $event = Event::findOrFail($eventId);
    //     $event->status = 'approved';
    //     $event->save();

    //     return response()->json($event, 200);
    // }

    // public function rejectEvent(Event $event)
    // {
    //     $event->update(['status' => 'rejected']);
    //     return response()->json($event);
    // }


    public function approveEvent(Event $event)
    {
        $event->update(['status' => 'approved']);
        broadcast(new EventUpdated($event));
        return response()->json($event);
    }

    public function rejectEvent(Event $event)
    {
        $event->update(['status' => 'rejected']);
        broadcast(new EventUpdated($event));
        return response()->json($event);
    }

    public function createRecurring(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'start_date' => 'required|date',
            'start_time' => 'required',
            'duration' => 'required|integer|min:1',
            'location' => 'nullable|string',
            'category' => 'nullable|string',
            'capacity' => 'nullable|integer',
            'enable_waitlist' => 'boolean',
            'recurring_pattern' => 'required|array'
        ]);

        $eventData = [
            'title' => $data['title'],
            'description' => $data['description'],
            'start_at' => $data['start_date'] . ' ' . $data['start_time'],
            'location' => $data['location'],
            'category' => $data['category'],
            'capacity' => $data['capacity'],
            'enable_waitlist' => $data['enable_waitlist'],
            'organizer_id' => $request->user()->id,
            'status' => 'pending'
        ];

        $eventService = new EventService();
        $events = $eventService->createRecurringEvents($eventData, $data['recurring_pattern']);

        return response()->json(['events' => $events]);
    }

    public function getTemplates()
    {
        $templates = Event::where('is_template', true)->get();
        return response()->json($templates);
    }

    public function createFromTemplate(Request $request)
    {
        $data = $request->validate([
            'template_id' => 'required|exists:events,id',
            'title' => 'required|string',
            'start_at' => 'required|date',
            'end_at' => 'required|date'
        ]);

        $eventService = new EventService();
        $event = $eventService->createFromTemplate($data['template_id'], [
            'title' => $data['title'],
            'start_at' => $data['start_at'],
            'end_at' => $data['end_at'],
            'organizer_id' => $request->user()->id,
            'status' => 'pending'
        ]);

        return response()->json($event);
    }
}

