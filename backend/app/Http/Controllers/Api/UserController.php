<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    public function getNotificationPreferences(Request $request)
    {
        $user = $request->user();
        
        return response()->json([
            'phone' => $user->phone,
            'notification_preferences' => $user->notification_preferences ?? [
                'in_app' => true,
                'email' => true,
                'sms' => false,
                'push' => true
            ]
        ]);
    }

    public function updateNotificationPreferences(Request $request)
    {
        $user = $request->user();
        
        $data = $request->validate([
            'phone' => 'nullable|string|max:20',
            'notification_preferences' => 'required|array'
        ]);

        $user->update([
            'phone' => $data['phone'],
            'notification_preferences' => $data['notification_preferences']
        ]);

        return response()->json([
            'message' => 'Notification preferences updated successfully'
        ]);
    }
}