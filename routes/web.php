<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TutorProfileController;
use App\Http\Controllers\TutorSubjectController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TutorBookingController;
use App\Http\Controllers\FeedbackController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Healthcheck endpoint for Railway (returns 200 OK)
Route::get('/health', function () {
    return response('OK', 200);
});

// Backwards-compatible redirects for legacy/quick links to avoid 404s
// these keep older UI links working and point to meaningful pages
Route::get('/tutors', function () {
    return redirect()->route('student.bookings');
});

// Generic calendar link (legacy) -> student's bookings/calendar page
Route::get('/calendar', function () {
    return redirect()->route('student.bookings');
});

// Legacy create bookings link — redirect to the tutor profile if tutor_id provided,
// otherwise go to bookings index for the student.
Route::get('/bookings/create', function (Request $request) {
    $tutorId = $request->query('tutor_id');
    if ($tutorId) {
        return redirect()->route('tutors.show', ['id' => $tutorId]);
    }
    return redirect()->route('student.bookings');
});

// Backwards-compatible student calendar/feedback shorthand routes
Route::get('/student/calendar', function () {
    return redirect()->route('student.bookings');
});

Route::get('/student/feedback', function () {
    return redirect()->route('student.bookings');
});


// Role-based dashboards
Route::middleware(['auth', 'role:tutee'])->group(function () {
    Route::get('/student/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/student/bookings', [BookingController::class, 'index'])->name('student.bookings');
    Route::post('/student/bookings', [BookingController::class, 'store'])->name('student.bookings.store');
    Route::post('/bookings/{id}/cancel', [\App\Http\Controllers\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::get('/student/feedback/{booking}', [FeedbackController::class, 'create'])->name('feedback.create');
    Route::post('/student/feedback/{booking}', [FeedbackController::class, 'store'])->name('feedback.store');
    // Student calendar booking
    Route::get('/student/tutor/{tutor_id}/calendar', [\App\Http\Controllers\AvailabilityController::class, 'getTutorCalendar'])->name('student.tutor.calendar');
});

Route::middleware(['auth', 'role:tutor'])->group(function () {
    Route::get('/tutor/dashboard', [\App\Http\Controllers\TutorDashboardController::class, 'index'])->name('tutor.dashboard');
    Route::get('/tutor/profile', [TutorProfileController::class, 'show'])->name('tutor.profile.show');
    Route::get('/tutor/profile/edit', [TutorProfileController::class, 'edit'])->name('tutor.profile.edit');
    Route::post('/tutor/profile/update', [TutorProfileController::class, 'update'])->name('tutor.profile.update');
    Route::get('/tutor/subjects', [TutorSubjectController::class, 'index'])->name('tutor.subjects');
    Route::post('/tutor/subjects/update', [TutorSubjectController::class, 'update'])->name('tutor.subjects.update');
    Route::get('/tutor/bookings', [TutorBookingController::class, 'index'])->name('tutor.bookings');
    Route::post('/tutor/bookings/{booking}/accept', [TutorBookingController::class, 'accept'])->name('tutor.bookings.accept');
    Route::post('/tutor/bookings/{booking}/decline', [TutorBookingController::class, 'decline'])->name('tutor.bookings.decline');
    Route::post('/tutor/bookings/{booking}/complete', [TutorBookingController::class, 'complete'])->name('tutor.bookings.complete');
    // Tutor feedback listing
    Route::get('/tutor/feedback', [FeedbackController::class, 'manage'])->name('tutor.feedback');
    // Tutor availability CRUD (basic)
    Route::get('/tutor/availabilities', [\App\Http\Controllers\AvailabilityController::class, 'index'])->name('tutor.availabilities.index');
    Route::get('/tutor/availabilities/create', [\App\Http\Controllers\AvailabilityController::class, 'create'])->name('tutor.availabilities.create');
    Route::post('/tutor/availabilities', [\App\Http\Controllers\AvailabilityController::class, 'store'])->name('tutor.availabilities.store');
    Route::delete('/tutor/availabilities/{id}', [\App\Http\Controllers\AvailabilityController::class, 'destroy'])->name('tutor.availabilities.destroy');
    // Tutor calendar
    Route::get('/tutor/calendar', [\App\Http\Controllers\AvailabilityController::class, 'index'])->name('tutor.calendar');

    // Tutor bookings (incoming requests handled by BookingController)
    Route::get('/tutor/requests', [\App\Http\Controllers\BookingController::class, 'tutorIndex'])->name('tutor.requests.index');
    Route::post('/tutor/requests/{id}/accept', [\App\Http\Controllers\BookingController::class, 'accept'])->name('tutor.requests.accept');
    Route::post('/tutor/requests/{id}/decline', [\App\Http\Controllers\BookingController::class, 'decline'])->name('tutor.requests.decline');
    Route::post('/tutor/requests/{id}/complete', [\App\Http\Controllers\BookingController::class, 'complete'])->name('tutor.requests.complete');
});

// Public tutor profile (viewable by anyone)
Route::get('/tutors/{id}', [TutorProfileController::class, 'publicShow'])->name('tutors.show');
Route::get('/tutors/{id}/reviews', [TutorProfileController::class, 'publicReviews'])->name('tutors.reviews');
// Tutee booking: request a booking for a tutor
Route::post('/tutors/{id}/book', [\App\Http\Controllers\BookingController::class, 'store'])->middleware('auth')->name('tutors.book');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    // Allowed Student IDs management
    Route::get('/admin/allowed-student-ids', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'index'])->name('admin.allowed-student-ids.index');
    Route::get('/admin/allowed-student-ids/create', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'create'])->name('admin.allowed-student-ids.create');
    Route::post('/admin/allowed-student-ids', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'store'])->name('admin.allowed-student-ids.store');
    Route::get('/admin/allowed-student-ids/{allowed_student_id}/edit', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'edit'])->name('admin.allowed-student-ids.edit');
    Route::put('/admin/allowed-student-ids/{allowed_student_id}', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'update'])->name('admin.allowed-student-ids.update');
    Route::delete('/admin/allowed-student-ids/{allowed_student_id}', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'destroy'])->name('admin.allowed-student-ids.destroy');
    Route::post('/admin/allowed-student-ids/import', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'import'])->name('admin.allowed-student-ids.import');
    Route::post('/admin/allowed-student-ids/reset-used', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'resetUsed'])->name('admin.allowed-student-ids.resetUsed');
    Route::post('/admin/allowed-student-ids/export', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'export'])->name('admin.allowed-student-ids.export');
    Route::post('/admin/allowed-student-ids/{id}/restore', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'restore'])->name('admin.allowed-student-ids.restore');
    // Bulk actions
    Route::post('/admin/allowed-student-ids/bulk-delete', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'bulkDelete'])->name('admin.allowed-student-ids.bulkDelete');
    Route::post('/admin/allowed-student-ids/bulk-restore', [\App\Http\Controllers\Admin\AllowedStudentIdController::class, 'bulkRestore'])->name('admin.allowed-student-ids.bulkRestore');
    // Admin users management
    Route::get('/admin/users', [\App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::delete('/admin/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
    Route::post('/admin/users/{id}/restore', [\App\Http\Controllers\Admin\UserController::class, 'restore'])->name('admin.users.restore');
    // Admin feedback moderation
    Route::get('/admin/feedback', [\App\Http\Controllers\Admin\FeedbackController::class, 'index'])->name('admin.feedback.index');
    Route::delete('/admin/feedback/{feedback}', [\App\Http\Controllers\Admin\FeedbackController::class, 'destroy'])->name('admin.feedback.destroy');
    Route::post('/admin/feedback/{id}/restore', [\App\Http\Controllers\Admin\FeedbackController::class, 'restore'])->name('admin.feedback.restore');
    // Admin calendar
    Route::get('/admin/calendar', [\App\Http\Controllers\BookingController::class, 'adminCalendar'])->name('admin.calendar');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
