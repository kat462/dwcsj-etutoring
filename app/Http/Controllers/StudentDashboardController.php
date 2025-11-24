<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Feedback;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Services\DashboardMetricsService;

class StudentDashboardController extends Controller
{
    protected $metrics;

    public function __construct(DashboardMetricsService $metrics)
    {
        $this->metrics = $metrics;
    }
    public function index()
    {
        $student = Auth::user();

        // Get all metrics and data for dashboard
        $upcomingCount = $this->getUpcomingSessionsCount($student);
        $pendingCount = $this->getPendingRequestsCount($student);
        $completedCount = $this->getCompletedSessionsCount($student);
        $feedbackGivenCount = $this->getFeedbackGivenCount($student);

        // Bookings by status
        $pendingBookings = $this->getPendingBookings($student);
        $scheduledBookings = $this->getScheduledBookings($student);
        $completedBookings = $this->getCompletedBookings($student);

        // Charts
        $monthlyData = $this->metrics->getMonthlySessions($student);
        $statusData = $this->getSessionsByStatus($student);

        // Recent tutors
        $recentTutors = $this->getRecentTutors($student);

        return view('student.dashboard', compact(
            'upcomingCount',
            'pendingCount',
            'completedCount',
            'feedbackGivenCount',
            'pendingBookings',
            'scheduledBookings',
            'completedBookings',
            'monthlyData',
            'statusData',
            'recentTutors'
        ));
    }

    // Get count of upcoming sessions (next 7 days)
    private function getUpcomingSessionsCount($student)
    {
        return Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', '=', 'accepted')
            ->whereBetween('scheduled_at', [Carbon::now(), Carbon::now()->addDays(7)])
            ->count();
    }

    // Get count of pending requests (awaiting tutor response)
    private function getPendingRequestsCount($student)
    {
        return Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', '=', 'pending')
            ->count();
    }

    // Get count of completed sessions
    private function getCompletedSessionsCount($student)
    {
        return Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', '=', 'completed')
            ->count();
    }

    // Get count of feedback given
    private function getFeedbackGivenCount($student)
    {
        // Feedback model may use tutee_id; assume column exists as-is
        return Feedback::where('tutee_id', $student->id)->count();
    }

    // Get pending bookings (awaiting tutor response)
    private function getPendingBookings($student)
    {
        return Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', 'pending')
            ->with(['tutor', 'subject'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    // Get scheduled/accepted bookings for next 30 days
    private function getScheduledBookings($student)
    {
        return Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', 'accepted')
            ->whereBetween('scheduled_at', [Carbon::now(), Carbon::now()->addDays(30)])
            ->with(['tutor', 'subject'])
            ->orderBy('scheduled_at', 'asc')
            ->limit(5)
            ->get();
    }

    // Get recently completed bookings
    private function getCompletedBookings($student)
    {
        return Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', 'completed')
            ->with(['tutor', 'subject', 'feedback'])
            ->orderBy('updated_at', 'desc')
            ->limit(5)
            ->get();
    }

    // Get monthly session count for last 6 months
    private function getMonthlySessions($student)
    {
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = Booking::where(Booking::tuteeKey(), $student->id)
                ->whereYear('scheduled_at', $date->year)
                ->whereMonth('scheduled_at', $date->month)
                ->where('status', 'completed')
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    // Get sessions by status (for doughnut chart)
    private function getSessionsByStatus($student)
    {
        $pending = Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', 'pending')
            ->count();

        $scheduled = Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', 'accepted')
            ->count();

        $completed = Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', 'completed')
            ->count();

        $cancelled = Booking::where(Booking::tuteeKey(), $student->id)
            ->where('status', 'cancelled')
            ->count();

        return [
            'labels' => ['Pending', 'Scheduled', 'Completed', 'Cancelled'],
            'data' => [$pending, $scheduled, $completed, $cancelled],
            'colors' => [
                'rgba(251, 191, 36, 0.8)',   // yellow
                'rgba(59, 130, 246, 0.8)',   // blue
                'rgba(34, 197, 94, 0.8)',    // green
                'rgba(239, 68, 68, 0.8)'     // red
            ]
        ];
    }

    // Get recently interacted tutors (last 3)
    private function getRecentTutors($student)
    {
        return Booking::where(Booking::tuteeKey(), $student->id)
            ->with('tutor')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($booking) {
                return $booking->tutor;
            })
            ->unique('id')
            ->take(3)
            ->values();
    }
}
