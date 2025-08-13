<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with(['organizer','media','registrations'])->get();
        return response()->json($events);
    }

    public function show(Event $event)
    {
        $event->load(['organizer','media','registrations','feedbacks']);
        return response()->json($event);
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
        $this->authorize('update',$event); // optional: use policy
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
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete',$event); // optional
        $event->delete();
        return response()->json(['message'=>'Deleted']);
    }
}
