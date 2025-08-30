<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\AdminReportsController;
use App\Http\Controllers\API\NotificationController; // Ensure this class exists in the specified namespace
use App\Http\Controllers\API\EventRegistrationController;
use App\Http\Controllers\API\FileUploadController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\QRCodeController;
use App\Http\Controllers\API\EmailController;
use App\Http\Controllers\API\AdminController;
use App\Http\Controllers\API\AnalyticsController;


Route::get('/public/events', [EventController::class, 'publicIndex']);


// Public auth
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
Route::post('/forgot-password', [AuthController::class,'forgotPassword']);
Route::post('/reset-password', [AuthController::class,'resetPassword']);


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

    // Waitlist
    Route::post('/events/{event}/waitlist/join', [App\Http\Controllers\API\WaitlistController::class, 'join']);
    Route::get('/events/{event}/waitlist', [App\Http\Controllers\API\WaitlistController::class, 'getWaitlist'])->middleware('role:organizer,admin');
    Route::post('/events/{event}/waitlist/promote', [App\Http\Controllers\API\WaitlistController::class, 'promote'])->middleware('role:organizer,admin');

    // Recurring events and templates
    Route::post('/events/recurring', [EventController::class, 'createRecurring'])->middleware('role:organizer,admin');
    Route::get('/events/templates', [EventController::class, 'getTemplates'])->middleware('role:organizer,admin');
    Route::post('/events/from-template', [EventController::class, 'createFromTemplate'])->middleware('role:organizer,admin');

    //Export Registration
    Route::get('/admin/export-registrations', [AdminReportsController::class, 'exportRegistrations'])->middleware('role:admin');

    // Feedback
    Route::post('/events/{event}/feedback', [FeedbackController::class, 'submit']);
    Route::get('/events/{event}/feedbacks', [FeedbackController::class, 'list']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    Route::post('/notifications/send-all', [NotificationController::class, 'sendNotificationToAll'])->middleware('role:admin');

    // File uploads
    Route::post('/upload/avatar', [FileUploadController::class, 'uploadUserAvatar']);
    Route::post('/events/{event}/upload/poster', [FileUploadController::class, 'uploadEventPoster'])->middleware('role:organizer,admin');
    Route::post('/events/{event}/upload/gallery', [FileUploadController::class, 'uploadEventGallery'])->middleware('role:organizer,admin');
    Route::delete('/media/{media}', [FileUploadController::class, 'deleteEventMedia'])->middleware('role:organizer,admin');

    // User profile
    Route::put('/user/profile', [UserController::class, 'updateProfile']);
    Route::get('/user/notification-preferences', [UserController::class, 'getNotificationPreferences']);
    Route::put('/user/notification-preferences', [UserController::class, 'updateNotificationPreferences']);

    // QR Code system
    Route::get('/events/{event}/qr-code', [QRCodeController::class, 'getRegistrationQR']);
    Route::post('/qr/scan', [QRCodeController::class, 'scanQR'])->middleware('role:organizer,admin');
    Route::get('/registrations/{registration}/qr', [QRCodeController::class, 'generateQR'])->middleware('role:organizer,admin');

    // Email system
    Route::post('/admin/send-announcement', [EmailController::class, 'sendAnnouncement'])->middleware('role:admin');

    // Admin management
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'getUsers']);
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);
        Route::get('/admin/stats', [AdminController::class, 'getStats']);
    });

    // Analytics routes
    Route::middleware('role:admin')->prefix('analytics')->group(function () {
        Route::get('/dashboard', [AnalyticsController::class, 'getDashboardStats']);
        Route::get('/events', [AnalyticsController::class, 'getEventMetrics']);
        Route::get('/engagement', [AnalyticsController::class, 'getUserEngagement']);
        Route::get('/reports', [AnalyticsController::class, 'getAdvancedReports']);
        Route::get('/export', [AnalyticsController::class, 'exportReport']);
    });
});
