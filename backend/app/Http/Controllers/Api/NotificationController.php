<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifs = $request->user()->notifications()->get();
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
        ]);

        $users = User::all();
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'message' => $data['message'],
            ]);
        }

        return response()->json(['message' => 'Notifications sent to all users']);
    }
}
