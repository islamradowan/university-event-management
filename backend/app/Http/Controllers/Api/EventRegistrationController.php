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

        // Check if user is authenticated
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Check if event exists and is approved
        if ($event->status !== 'approved') {
            return response()->json(['message' => 'Event is not available for registration'], 422);
        }

        // Check if already registered
        if (EventRegistration::where('user_id', $user->id)->where('event_id', $event->id)->exists()) {
            return response()->json(['message' => 'Already registered'], 422);
        }

        try {
            $reg = EventRegistration::create([
                'user_id' => $user->id,
                'event_id' => $event->id,
                'qr_token' => Str::uuid()
            ]);

            // Send confirmation email (optional, don't fail registration if email fails)
            try {
                Mail::to($user->email)->send(new EventRegistrationConfirmation($user, $event));
            } catch (\Exception $e) {
                \Log::error('Failed to send registration confirmation email: ' . $e->getMessage());
            }

            return response()->json($reg, 201);
        } catch (\Exception $e) {
            \Log::error('Registration failed: ' . $e->getMessage());
            return response()->json(['message' => 'Registration failed'], 500);
        }
    }

    public function unregister(Request $request, Event $event)
    {
        $user = $request->user();
        
        $registration = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();
            
        if (!$registration) {
            return response()->json(['message' => 'Not registered for this event'], 422);
        }
        
        $registration->delete();
        
        return response()->json(['message' => 'Unregistered successfully']);
    }

    public function checkIn(Request $request, EventRegistration $registration)
    {
        $registration->checked_in_at = now();
        $registration->save();

        return response()->json($registration);
    }

    public function myRegistrations(Request $request)
    {
        $regs = $request->user()->registrations()->with('event')->get();
        return response()->json($regs);
    }
}
