<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\AdminReportsController;
use App\Http\Controllers\API\NotificationController; // Ensure this class exists in the specified namespace
use App\Http\Controllers\API\EventRegistrationController;


Route::get('/public/events', [EventController::class, 'publicIndex']);


// Public auth
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');


// Read: all events for authenticated users
Route::middleware(['auth:sanctum', 'role:organizer,admin'])->group(function () {
    Route::get('/my-events', [EventController::class, 'myEvents']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    // Read: all authenticated users
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{event}', [EventController::class, 'show']);

    // Write: organizers/admins only
    Route::post('/events', [EventController::class, 'store'])->middleware('role:organizer,admin');
    Route::put('/events/{event}', [EventController::class, 'update'])->middleware('role:organizer,admin');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->middleware('role:organizer,admin');

    //event approve and reject
    Route::put('/events/{event}/approve', [EventController::class, 'approveEvent'])->middleware('role:admin');
    Route::put('/events/{event}/reject',  [EventController::class, 'rejectEvent'])->middleware('role:admin');

    // Registrations & attendance
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'register']);
    Route::post('/registrations/{registration}/checkin', [EventRegistrationController::class, 'checkIn'])->middleware('role:organizer,admin');
    Route::get('/my-registrations', [EventRegistrationController::class, 'myRegistrations']);

    //Export Registration
    Route::get('/admin/export-registrations', [AdminReportsController::class, 'exportRegistrations'])->middleware('role:admin');

    // Feedback
    Route::post('/events/{event}/feedback', [FeedbackController::class, 'submit']);
    Route::get('/events/{event}/feedbacks', [FeedbackController::class, 'list']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    Route::post('/notifications/send-all', [NotificationController::class, 'sendNotificationToAll'])->middleware('role:admin');
});
