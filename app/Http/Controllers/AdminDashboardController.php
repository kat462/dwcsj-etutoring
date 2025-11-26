<?php

namespace App\Http\Controllers;


use App\Models\User;
use App\Models\Booking;
use App\Models\Subject;
use App\Services\DashboardMetricsService;

class AdminDashboardController extends Controller
{
    protected $metrics;

    public function __construct(DashboardMetricsService $metrics)
    {
        $this->metrics = $metrics;
    }

    public function index()
    {
        // Use shared metrics service for common metrics
        $totalUsers = $this->metrics->getTotalUsers();
        $totalTutors = $this->metrics->getTotalTutors();
        $totalStudents = $this->metrics->getTotalStudents();
        $totalSessions = $this->metrics->getTotalSessions();
        $totalFeedback = $this->metrics->getTotalFeedback();
        $averagePlatformRating = $this->metrics->getAveragePlatformRating();
        $sessionsByStatus = $this->metrics->getSessionsByStatus();
        // Get monthly sessions as [month => count]
        $monthlyRaw = $this->metrics->getMonthlySessions();
        // Build labels and data for all 12 months
        $labels = [];
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = date('M', mktime(0, 0, 0, $m, 1));
            $data[] = isset($monthlyRaw[$m]) ? $monthlyRaw[$m] : 0;
        }
        $monthlySessionsData = [
            'labels' => $labels,
            'data' => $data,
        ];

        // The following remain as controller-specific for now
        $statusData = $this->getSessionsStatusChart();
        $subjectsData = $this->getTopSubjects();
        $recentBookings = $this->getRecentBookings();
        $topTutors = $this->getTopTutors();
        $topSubjects = $this->getMostRequestedSubjects();
        $educationBreakdown = $this->getEducationLevelBreakdown();

        // Payment analytics: monthly totals for current year
        $monthlyPaymentsRaw = \App\Models\Payment::selectRaw('MONTH(created_at) as month, SUM(amount) as total')
            ->whereYear('created_at', now()->year)
            ->where('status', 'paid')
            ->groupBy('month')
            ->pluck('total', 'month');
        $paymentLabels = [];
        $paymentData = [];
        for ($m = 1; $m <= 12; $m++) {
            $paymentLabels[] = date('M', mktime(0, 0, 0, $m, 1));
            $paymentData[] = isset($monthlyPaymentsRaw[$m]) ? (float)$monthlyPaymentsRaw[$m] : 0;
        }
        $monthlyPaymentsData = [
            'labels' => $paymentLabels,
            'data' => $paymentData,
        ];

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
            'educationBreakdown',
            'monthlyPaymentsData'
        ));
    }

    public function analytics()
    {
        // Bookings per month
        $monthlyRaw = $this->metrics->getMonthlySessions();
        $labels = [];
        $data = [];
        for ($m = 1; $m <= 12; $m++) {
            $labels[] = date('M', mktime(0, 0, 0, $m, 1));
            $data[] = isset($monthlyRaw[$m]) ? $monthlyRaw[$m] : 0;
        }
        $monthlySessionsData = [
            'labels' => $labels,
            'data' => $data,
        ];

        // User roles
        $roles = [
            'Admin' => User::where('role', 'admin')->count(),
            'Tutor' => User::where('role', 'tutor')->count(),
            'Tutee' => User::where('role', 'tutee')->count(),
        ];
        $rolesData = [
            'labels' => array_keys($roles),
            'data' => array_values($roles),
        ];

        // Subjects
        $subjects = Subject::withCount('bookings')->orderBy('bookings_count', 'desc')->limit(5)->get();
        $subjectsData = [
            'labels' => $subjects->pluck('name')->toArray(),
            'data' => $subjects->pluck('bookings_count')->toArray(),
        ];

        return view('admin.analytics', compact('monthlySessionsData', 'rolesData', 'subjectsData'));
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
