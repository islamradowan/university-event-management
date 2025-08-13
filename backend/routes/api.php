<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\EventRegistrationController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// test start
// Route::get('/user', function() {
//     return response()->json(['message' => 'API works']);
// });

// Route::get('/login', function () {
//     return 'Web login page';
// })->name('login');
// //test end


//login, register and logout
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class,'me'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Event routes (CRUD)
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/events','API\EventController@index');
    Route::get('/events/{event}','API\EventController@show');
    Route::post('/events','API\EventController@store'); // organizer only (use role middleware)
    Route::put('/events/{event}','API\EventController@update');
    Route::delete('/events/{event}','API\EventController@destroy');

    Route::post('/events/{event}/register','API\EventRegistrationController@register');
    Route::post('/registrations/{registration}/checkin','API\EventRegistrationController@checkIn');
    Route::get('/my-registrations','API\EventRegistrationController@myRegistrations');

    Route::post('/events/{event}/feedback','API\FeedbackController@submit');
    Route::get('/events/{event}/feedbacks','API\FeedbackController@list');

    Route::get('/notifications','API\NotificationController@index');
    Route::post('/notifications/{notification}/read','API\NotificationController@markRead');
});