<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Availability;
use Illuminate\Support\Facades\Auth;
use App\Models\Subject;
use App\Models\User;
use App\Notifications\NewBookingRequest;

class BookingController extends Controller
{
    // Show request session form
    public function requestSession()
    {
        $subjects = Subject::all();
        $tutors = User::where('role', 'tutor')->where('is_active', 1)->get();
        return view('student.request_session', compact('subjects', 'tutors'));
    }

    // Show tutee calendar view
    public function calendar()
    {
        return view('student.calendar');
    }
    // Tutee: request a booking for a tutor (choose availability or custom datetime)
    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Booking::class);


        $tutorId = $request->input('tutor_id');
        if (Auth::id() == $tutorId) {
            return redirect()->back()->with('error', 'You cannot book yourself.');
        }

        $request->validate([
            'availability_id' => 'nullable|exists:availabilities,id',
            'scheduled_at' => 'nullable|date',
            'session_date' => 'nullable|date',
            'subject_id' => 'nullable|exists:subjects,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $tutee = Auth::user();

        $availability = null;
        // Allow either `scheduled_at` (from tutor profile form) or `session_date` (from student form)
        $scheduledAt = $request->input('scheduled_at') ?? $request->input('session_date');

        if ($request->filled('availability_id')) {
            $availability = Availability::findOrFail($request->availability_id);
            $scheduledAt = $availability->date->format('Y-m-d') . ' ' . $availability->start_time;
        }

        // Get payment info from tutor profile
        $tutor = User::find($tutorId);
        $isPaid = $tutor && $tutor->profile && $tutor->profile->is_paid ? 1 : 0;
        $rate = $tutor && $tutor->profile ? $tutor->profile->rate : null;
        $booking = Booking::create([
            'tutor_id' => $tutorId,
            'tutee_id' => $tutee->id,
            'availability_id' => $availability ? $availability->id : null,
            'scheduled_at' => $scheduledAt,
            'subject_id' => $request->input('subject_id') ?? null,
            'status' => 'pending',
            'notes' => $request->notes,
            'is_paid' => $isPaid,
            'rate' => $rate,
            'payment_status' => $isPaid ? 'pending' : 'free',
        ]);

        if ($availability) {
            $availability->is_booked = true;
            $availability->save();
        }

        // Notify the tutor (email + in-app)
        $tutor = User::find($tutorId);
        if ($tutor) {
            $tutor->notify(new NewBookingRequest($booking));
        }

        return redirect()->back()->with('success','Booking request sent');
    }

    // Tutor: view incoming bookings
    public function tutorIndex()
    {
        $user = Auth::user();
        $bookings = Booking::where('tutor_id', $user->id)->orderBy('created_at','desc')->get();
        return view('tutor.bookings.index', compact('bookings'));
    }

    // Student: bookings index and form data
    public function index()
    {
        $subjects = Subject::all();
        $tutors = User::where('role', 'tutor')->where('is_active', 1)->with('subjects')->get();
        $myBookings = Booking::with(['tutor','subject','feedback'])->where('tutee_id', Auth::id())->latest()->get();

        return view('student.bookings', compact('subjects','tutors','myBookings'));
    }

    // Tutee cancels their booking (only when pending)
    public function cancel($id)
    {
        $booking = Booking::findOrFail($id);
        $this->authorize('cancel', $booking);

        if ($booking->status !== 'pending' && $booking->status !== 'accepted') {
            return redirect()->back()->with('error', 'Only pending or accepted bookings can be cancelled.');
        }

        // Only allow tutee to cancel their own bookings
        if (Auth::id() !== $booking->tutee_id) {
            abort(403);
        }

        $booking->status = 'cancelled';
        $booking->save();

        // Free up availability if tied
        if ($booking->availability) {
            $booking->availability->is_booked = false;
            $booking->availability->save();
        }

        return redirect()->route('student.bookings')->with('success', 'Booking cancelled.');
    }

    // Tutor accepts booking
    public function accept($id)
    {
        $booking = Booking::findOrFail($id);
        $this->authorize('update', $booking);
        $booking->status = 'accepted';
        $booking->save();
        return back()->with('success','Booking accepted');
    }

    // Tutor declines booking
    public function decline($id)
    {
        $booking = Booking::findOrFail($id);
        $this->authorize('update', $booking);
        $booking->status = 'declined';
        if ($booking->availability) {
            $booking->availability->is_booked = false;
            $booking->availability->save();
        }
        $booking->save();
        return back()->with('success','Booking declined');
    }

    // Mark completed (could be triggered by tutor after session ended)
    public function complete($id)
    {
        $booking = Booking::findOrFail($id);
        $this->authorize('update', $booking);
        $booking->status = 'completed';
        $booking->save();
        return back()->with('success','Booking marked as completed');
    }

    /**
     * Get all bookings (sessions) as calendar events (admin view)
     */
    public function calendarEvents()
    {
        $bookings = Booking::with(['tutor', 'tutee', 'subject'])->get();

        $events = $bookings->map(function ($booking) {
            $startDateTime = $booking->scheduled_at;
            
            // Map status to color
            $colorMap = [
                'pending' => '#fbbf24',    // amber
                'accepted' => '#60a5fa',   // blue
                'completed' => '#10b981',  // green
                'cancelled' => '#ef4444',  // red
                'declined' => '#6b7280',   // gray
            ];

            $color = $colorMap[$booking->status] ?? '#9ca3af';

            return [
                'id' => $booking->id,
                'title' => "{$booking->tutee->name} - {$booking->tutor->name}",
                'start' => $startDateTime,
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'booking_id' => $booking->id,
                    'tutor_name' => $booking->tutor->name,
                    'tutee_name' => $booking->tutee->name,
                    'subject' => $booking->subject ? $booking->subject->name : 'N/A',
                    'status' => $booking->status,
                ],
            ];
        });

        return response()->json($events);
    }

    /**
     * Show admin calendar view (all sessions)
     */
    public function adminCalendar()
    {
        return view('admin.calendar');
    }
}
