<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Mail\EventRegistrationConfirmation;
use App\Events\AttendanceUpdated;
use App\Events\EventUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class EventRegistrationController extends Controller
{
    public function register(Request $request, Event $event)
    {
        $user = $request->user();

        if(EventRegistration::where('user_id',$user->id)->where('event_id',$event->id)->exists()) {
            return response()->json(['message'=>'Already registered'],422);
        }

        $reg = EventRegistration::create([
            'user_id'=>$user->id,
            'event_id'=>$event->id,
            'qr_token'=>Str::uuid()
        ]);

        // Send confirmation email
        try {
            Mail::to($user->email)->send(new EventRegistrationConfirmation($user, $event));
        } catch (\Exception $e) {
            \Log::error('Failed to send registration confirmation email: ' . $e->getMessage());
        }

        return response()->json($reg,201);
    }

    public function checkIn(Request $request, EventRegistration $registration)
    {
        $registration->checked_in_at = now();
        $registration->save();

        // Broadcast attendance update
        broadcast(new AttendanceUpdated($registration));

        return response()->json($registration);
    }

    public function myRegistrations(Request $request)
    {
        $regs = $request->user()->registrations()->with('event')->get();
        return response()->json($regs);
    }
}
