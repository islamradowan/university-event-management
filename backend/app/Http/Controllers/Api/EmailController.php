<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|in:all,students,organizers',
            'sendEmail' => 'boolean',
            'sendInApp' => 'boolean'
        ]);

        $query = User::query();
        
        if ($request->recipients === 'students') {
            $query->where('role', 'student');
        } elseif ($request->recipients === 'organizers') {
            $query->where('role', 'organizer');
        }

        $users = $query->get();
        $emailSentCount = 0;
        $notificationSentCount = 0;

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No users found for the selected recipient type',
                'email_sent' => 0,
                'notifications_sent' => 0,
                'total_users' => 0
            ]);
        }

        foreach ($users as $user) {
            // Send email if requested
            if ($request->sendEmail) {
                try {
                    Mail::raw($request->message, function ($message) use ($request, $user) {
                        $message->to($user->email, $user->name)
                            ->subject($request->title);
                    });
                    $emailSentCount++;
                } catch (\Exception $e) {
                    \Log::error('Failed to send email to ' . $user->email . ': ' . $e->getMessage());
                }
            }

            // Send in-app notification if requested
            if ($request->sendInApp) {
                try {
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => $request->title,
                        'message' => $request->message
                    ]);
                    $notificationSentCount++;
                } catch (\Exception $e) {
                    \Log::error('Failed to create notification for user ' . $user->id . ': ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'message' => "Announcement sent successfully",
            'email_sent' => $emailSentCount,
            'notifications_sent' => $notificationSentCount,
            'total_users' => $users->count()
        ]);
    }

    public function sendOrganizerAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'sendEmail' => 'boolean',
            'sendInApp' => 'boolean'
        ]);

        // Organizers can only send to students
        $users = User::where('role', 'student')->get();
        $emailSentCount = 0;
        $notificationSentCount = 0;

        if ($users->isEmpty()) {
            return response()->json([
                'message' => 'No students found',
                'email_sent' => 0,
                'notifications_sent' => 0,
                'total_users' => 0
            ]);
        }

        foreach ($users as $user) {
            // Send email if requested
            if ($request->sendEmail) {
                try {
                    Mail::raw($request->message, function ($message) use ($request, $user) {
                        $message->to($user->email, $user->name)
                            ->subject($request->title);
                    });
                    $emailSentCount++;
                } catch (\Exception $e) {
                    \Log::error('Failed to send email to ' . $user->email . ': ' . $e->getMessage());
                }
            }

            // Send in-app notification if requested
            if ($request->sendInApp) {
                try {
                    Notification::create([
                        'user_id' => $user->id,
                        'title' => $request->title,
                        'message' => $request->message
                    ]);
                    $notificationSentCount++;
                } catch (\Exception $e) {
                    \Log::error('Failed to create notification for user ' . $user->id . ': ' . $e->getMessage());
                }
            }
        }

        return response()->json([
            'message' => "Announcement sent to students successfully",
            'email_sent' => $emailSentCount,
            'notifications_sent' => $notificationSentCount,
            'total_users' => $users->count()
        ]);
    }
}