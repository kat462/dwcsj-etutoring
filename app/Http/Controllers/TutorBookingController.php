<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class TutorBookingController extends Controller
{
    public function index()
    {
        $pendingBookings = Auth::user()->bookingsAsTutor()
            ->where('status', 'pending')
            ->with(['tutee', 'subject'])
            ->latest()
            ->get();

        $allBookings = Auth::user()->bookingsAsTutor()
            ->whereIn('status', ['accepted', 'declined', 'completed', 'cancelled'])
            ->with(['tutee', 'subject'])
            ->latest()
            ->get();

        return view('tutor.bookings', compact('pendingBookings', 'allBookings'));
    }

    public function accept(Booking $booking)
    {
        if ($booking->tutor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'This booking cannot be accepted');
        }

        $booking->update(['status' => 'accepted']);

        return redirect()->back()->with('success', 'Booking accepted successfully!');
    }

    public function decline(Booking $booking)
    {
        if ($booking->tutor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        if ($booking->status !== 'pending') {
            return redirect()->back()->with('error', 'This booking cannot be declined');
        }

        $booking->update(['status' => 'declined']);

        return redirect()->back()->with('success', 'Booking declined');
    }

    public function complete(Booking $booking)
    {
        if ($booking->tutor_id !== Auth::id()) {
            abort(403, 'Unauthorized action');
        }

        if ($booking->status !== 'accepted') {
            return redirect()->back()->with('error', 'Only accepted bookings can be marked as completed');
        }

        $booking->update(['status' => 'completed']);

        return redirect()->back()->with('success', 'Booking marked as completed!');
    }
}
