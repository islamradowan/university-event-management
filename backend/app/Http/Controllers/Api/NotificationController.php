<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
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
}
