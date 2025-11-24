<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\Feedback;
use Illuminate\Support\Facades\Auth;

class FeedbackController extends Controller
{
    public function create(Booking $booking)
    {
        // Ensure the booking belongs to the logged-in student
        if ($booking->tutee_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Only allow feedback for completed bookings
        if ($booking->status !== 'completed') {
            return redirect()->back()->with('error', 'You can only give feedback for completed sessions.');
        }

        // Check if feedback already exists
        if ($booking->feedback) {
            return redirect()->back()->with('error', 'You have already submitted feedback for this session.');
        }

        return view('student.feedback', compact('booking'));
    }

    public function store(Request $request, Booking $booking)
    {
        // Validate ownership
        if ($booking->tutee_id != Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validate status
        if ($booking->status !== 'completed') {
            return redirect()->back()->with('error', 'You can only give feedback for completed sessions.');
        }

        // Check if feedback already exists
        if ($booking->feedback) {
            return redirect()->back()->with('error', 'You have already submitted feedback for this session.');
        }

        // Validate input
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Create feedback
        Feedback::create([
            'booking_id' => $booking->id,
            'tutor_id' => $booking->tutor_id,
            'tutee_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'approved', // Auto-approve for now
        ]);

        return redirect()->route('student.bookings')->with('success', 'Thank you for your feedback!');
    }

    // Legacy methods
    public function showForm($bookingId)
    {
        $booking = Booking::with('tutor')->findOrFail($bookingId);
        if ($booking->tutee_id != Auth::id()) abort(403);
        return view('tutee.feedback', compact('booking'));
    }

    public function submit(Request $req, $bookingId)
    {
        $req->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500'
        ]);
        
        $booking = Booking::findOrFail($bookingId);
        if ($booking->tutee_id != Auth::id()) abort(403);
        
        Feedback::create([
            'booking_id' => $booking->id,
            'tutor_id' => $booking->tutor_id,
            'tutee_id' => Auth::id(),
            'rating' => $req->rating,
            'comment' => $req->comment,
            'status' => 'approved'
        ]);
        
        return redirect()->route('dashboard')->with('success', 'Thanks for your feedback!');
    }

    public function manage()
    {
        $tutor = Auth::user();
        if ($tutor->role !== 'tutor') abort(403);

        // Get feedback with tutee info via booking relation
        $feedbacks = Feedback::with(['booking.tutee'])
            ->whereHas('booking', function ($query) use ($tutor) {
                $query->where('tutor_id', $tutor->id);
            })
            ->latest()
            ->get();

        // Average rating
        $averageRating = $feedbacks->count() ? round($feedbacks->avg('rating'), 1) : null;

        // Count per star
        $ratingCounts = [];
        for ($i = 5; $i >= 1; $i--) {
            $ratingCounts[$i] = $feedbacks->where('rating', $i)->count();
        }

        return view('tutor.feedback', compact('feedbacks', 'averageRating', 'ratingCounts'));
    }

    public function respond(Request $req, $id)
    {
        $fb = Feedback::findOrFail($id);
        if (Auth::id() != $fb->tutor_id) abort(403);
        
        $req->validate([
            'action' => 'required|in:approve,decline',
            'decline_reason' => 'nullable|string|max:500'
        ]);
        
        $fb->status = ($req->action == 'approve') ? 'approved' : 'declined';
        $fb->decline_reason = $req->decline_reason;
        $fb->save();
        
        return back()->with('success', 'Feedback updated');
    }
}
