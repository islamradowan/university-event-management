<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generateQR(EventRegistration $registration)
    {
        $qrData = json_encode([
            'registration_id' => $registration->id,
            'user_id' => $registration->user_id,
            'event_id' => $registration->event_id,
            'token' => $registration->qr_token
        ]);

        $qrCode = QrCode::size(200)->generate($qrData);

        return response()->json([
            'qr_code' => base64_encode($qrCode),
            'qr_token' => $registration->qr_token
        ]);
    }

    public function scanQR(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string'
        ]);

        $registration = EventRegistration::where('qr_token', $request->qr_token)->first();

        if (!$registration) {
            return response()->json(['message' => 'Invalid QR code'], 404);
        }

        if ($registration->checked_in_at) {
            return response()->json([
                'message' => 'Already checked in',
                'checked_in_at' => $registration->checked_in_at
            ], 400);
        }

        $registration->update(['checked_in_at' => now()]);

        return response()->json([
            'message' => 'Check-in successful',
            'registration' => $registration->load(['user', 'event'])
        ]);
    }

    public function getRegistrationQR(Request $request, $eventId)
    {
        $user = $request->user();
        
        $registration = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        return $this->generateQR($registration);
    }
}