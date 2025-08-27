<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventRegistration;
use Illuminate\Support\Facades\Response;

class AdminReportsController extends  Controller {

    public function exportRegistrations(Request $request)
    {
        $from = $request->query('from');
        $to = $request->query('to');
    
        $registrations = EventRegistration::with('user', 'event')
            ->whereBetween('created_at', [$from, $to])
            ->get();
    
        $csvData = "Event Title, User Name, User Email, Registration Date\n";
    
        foreach ($registrations as $registration) {
            $csvData .= "{$registration->event->title}, {$registration->user->name}, {$registration->user->email}, {$registration->created_at}\n";
        }
    
        return response()->make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="registrations.csv"',
        ]);
    }
}

