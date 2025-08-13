<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Event;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function submit(Request $request, Event $event)
    {
        $data = $request->validate([
            'rating'=>'required|integer|min:1|max:5',
            'comment'=>'nullable|string',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['event_id'] = $event->id;

        $feedback = Feedback::create($data);

        return response()->json($feedback,201);
    }

    public function list(Event $event)
    {
        $feedbacks = $event->feedbacks()->with('user')->get();
        return response()->json($feedbacks);
    }
}
