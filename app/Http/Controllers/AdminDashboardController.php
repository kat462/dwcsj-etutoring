<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use App\Models\Feedback;
use App\Models\Subject;
use App\Models\TutorProfile;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Get all metrics and data for dashboard
        $totalUsers = $this->getTotalUsers();
        $totalTutors = $this->getTotalTutors();
        $totalStudents = $this->getTotalStudents();
        $totalSessions = $this->getTotalSessions();
        $totalFeedback = $this->getTotalFeedback();
        $averagePlatformRating = $this->getAveragePlatformRating();

        // Session breakdown
        $sessionsByStatus = $this->getSessionsByStatus();

        // Charts
        $monthlySessionsData = $this->getMonthlySessions();
        $statusData = $this->getSessionsStatusChart();
        $subjectsData = $this->getTopSubjects();

        // Tables
        $recentBookings = $this->getRecentBookings();
        $topTutors = $this->getTopTutors();
        $topSubjects = $this->getMostRequestedSubjects();

        // Education level breakdown
        $educationBreakdown = $this->getEducationLevelBreakdown();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalTutors',
            'totalStudents',
            'totalSessions',
            'totalFeedback',
            'averagePlatformRating',
            'sessionsByStatus',
            'monthlySessionsData',
            'statusData',
            'subjectsData',
            'recentBookings',
            'topTutors',
            'topSubjects',
            'educationBreakdown'
        ));
    }

    // Get total user count
    private function getTotalUsers()
    {
        return User::count();
    }

    // Get total tutor count
    private function getTotalTutors()
    {
        return User::where('role', 'tutor')->count();
    }

    // Get total student count
    private function getTotalStudents()
    {
        return User::where('role', 'tutee')->count();
    }

    // Get total session count
    private function getTotalSessions()
    {
        return Booking::count();
    }

    // Get total feedback count
    private function getTotalFeedback()
    {
        return Feedback::count();
    }

    // Get average rating across platform
    private function getAveragePlatformRating()
    {
        $average = Feedback::avg('rating');
        return $average ? number_format($average, 1) : '0.0';
    }

    // Get sessions breakdown by status
    private function getSessionsByStatus()
    {
        $pending = Booking::where('status', 'pending')->count();
        $accepted = Booking::where('status', 'accepted')->count();
        $completed = Booking::where('status', 'completed')->count();
        $cancelled = Booking::where('status', 'cancelled')->count();

        return [
            'pending' => $pending,
            'accepted' => $accepted,
            'completed' => $completed,
            'cancelled' => $cancelled,
            'total' => $pending + $accepted + $completed + $cancelled
        ];
    }

    // Get monthly sessions for last 6 months
    private function getMonthlySessions()
    {
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');

            $count = Booking::whereYear('scheduled_at', $date->year)
                ->whereMonth('scheduled_at', $date->month)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    // Get sessions by status for chart
    private function getSessionsStatusChart()
    {
        $pending = Booking::where('status', 'pending')->count();
        $scheduled = Booking::where('status', 'accepted')->count();
        $completed = Booking::where('status', 'completed')->count();
        $cancelled = Booking::where('status', 'cancelled')->count();

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

    // Get top 5 subjects
    private function getTopSubjects()
    {
        $subjects = Subject::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get();

        $labels = $subjects->pluck('name')->toArray();
        $data = $subjects->pluck('bookings_count')->toArray();

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }

    // Get recent bookings (last 10)
    private function getRecentBookings()
    {
        return Booking::with(['tutee', 'tutor', 'subject'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    // Get top tutors by feedback rating
    private function getTopTutors()
    {
        return User::where('role', 'tutor')
            ->with('feedbacks')
            ->get()
            ->map(function ($tutor) {
                $avgRating = $tutor->feedbacks()->avg('rating') ?? 0;
                $feedbackCount = $tutor->feedbacks()->count();
                return (object)[
                    'id' => $tutor->id,
                    'name' => $tutor->name,
                    'rating' => round($avgRating, 1),
                    'feedbacks' => $feedbackCount
                ];
            })
            ->sortByDesc('rating')
            ->take(5)
            ->values();
    }

    // Get most requested subjects
    private function getMostRequestedSubjects()
    {
        return Subject::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($subject) {
                return (object)[
                    'name' => $subject->name,
                    'bookings' => $subject->bookings_count,
                    'education_level' => $subject->education_level
                ];
            });
    }

    // Get education level breakdown
    private function getEducationLevelBreakdown()
    {
        return User::where('role', 'tutor')
            ->select('education_level')
            ->selectRaw('count(*) as count')
            ->groupBy('education_level')
            ->get()
            ->map(function ($item) {
                return (object)[
                    'level' => $item->education_level,
                    'count' => $item->count
                ];
            });
    }
}
