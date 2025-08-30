<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Events\NewNotification;
use App\Services\NotificationService;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $limit = $request->get('limit', 10);
        
        $notifs = Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        return response()->json($notifs);
    }

    public function markRead(Notification $notification)
    {
        $notification->read_at = now();
        $notification->save();

        return response()->json($notification);
    }
    public function sendNotificationToAll(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'recipients' => 'sometimes|in:all,students,organizers'
        ]);

        $query = User::query();
        $recipients = $data['recipients'] ?? 'all';
        
        if ($recipients === 'students') {
            $query->where('role', 'student');
        } elseif ($recipients === 'organizers') {
            $query->where('role', 'organizer');
        }

        $users = $query->get();
        
        $notificationService = new NotificationService();
        $sentCount = $notificationService->sendNotification(
            $users, 
            $data['title'], 
            $data['message'], 
            'admin_announcement'
        );

        return response()->json([
            'message' => "Notifications sent to {$sentCount} users",
            'sent_count' => $sentCount
        ]);
    }
}
