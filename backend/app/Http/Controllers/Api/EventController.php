<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($request->query('status') === 'pending') {
            if (!$user || $user->role !== 'admin') {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            return Event::with(['organizer','registrations'])
                ->where('status', 'pending')
                ->orderByDesc('start_at')
                ->get();
        }

        return Event::with(['organizer','media'])
            ->where('status', 'approved')
            ->orderBy('start_at')
            ->get();
    }
    public function publicIndex()
    {
        return Event::with('organizer')
            ->where('status', 'approved')
            ->orderBy('start_at')
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
        return response()->json($event);
    }

    public function rejectEvent(Event $event)
    {
        $event->update(['status' => 'rejected']);
        return response()->json($event);
    }
}

