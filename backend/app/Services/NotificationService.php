<?php

namespace App\Services;

use App\Models\User;
use App\Models\Notification;
use App\Events\NewNotification;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    public function sendNotification($users, $title, $message, $type = 'general')
    {
        $sentCount = 0;
        
        foreach ($users as $user) {
            $preferences = $user->notification_preferences ?? [
                'email' => true,
                'sms' => false,
                'push' => true,
                'in_app' => true
            ];

            // In-app notification
            if ($preferences['in_app'] ?? true) {
                try {
                    $notification = Notification::create([
                        'user_id' => $user->id,
                        'title' => $title,
                        'message' => $message,
                        'type' => $type
                    ]);
                    
                    broadcast(new NewNotification($notification));
                    $sentCount++;
                } catch (\Exception $e) {
                    Log::error('Failed to create in-app notification: ' . $e->getMessage());
                }
            }

            // SMS notification
            if (($preferences['sms'] ?? false) && $user->phone) {
                $this->sendSMS($user->phone, $title, $message);
            }

            // Push notification
            if (($preferences['push'] ?? true) && $user->push_token) {
                $this->sendPushNotification($user->push_token, $title, $message);
            }
        }

        return $sentCount;
    }

    private function sendSMS($phone, $title, $message)
    {
        try {
            // Mock SMS - replace with actual provider like Twilio
            Log::info("SMS sent to $phone: $title - $message");
        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
        }
    }

    private function sendPushNotification($pushToken, $title, $message)
    {
        try {
            // Mock Push - replace with FCM
            Log::info("Push notification sent to $pushToken: $title - $message");
        } catch (\Exception $e) {
            Log::error('Push notification failed: ' . $e->getMessage());
        }
    }
}