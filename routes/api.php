<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BookingController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Calendar API endpoints
Route::middleware('auth')->group(function () {
    // Tutor calendar events
    Route::get('/availability/calendar-events', [AvailabilityController::class, 'calendarEvents']);
    Route::put('/availability/{id}/update-time', [AvailabilityController::class, 'updateTime']);

    // Student calendar events (view tutor's availability)
    Route::get('/tutor/{tutorId}/calendar-events', [AvailabilityController::class, 'getTutorCalendarEvents']);

    // Admin calendar events (all sessions)
    Route::get('/bookings/calendar-events', [BookingController::class, 'calendarEvents']);

    // Student's own session calendar events (for tutee calendar)
    Route::get('/student/calendar/events', [\App\Http\Controllers\StudentDashboardController::class, 'calendarEvents']);
});

