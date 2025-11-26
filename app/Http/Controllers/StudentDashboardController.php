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

    // API endpoint for FullCalendar events
    public function calendarEvents(\Illuminate\Http\Request $request)
    {
        $user = Auth::user();
        $bookings = Booking::where('tutee_id', $user->id)
            ->whereNotNull('scheduled_at')
            ->get();

        $events = $bookings->map(function($booking) {
            return [
                'title' => $booking->subject->name ?? 'Session',
                'start' => $booking->scheduled_at->toIso8601String(),
                'end' => optional($booking->scheduled_at)->addHour()->toIso8601String(), // adjust duration as needed
                'status' => $booking->status,
            ];
        });

        return response()->json($events);
    }

    /**
     * Browse/search/filter tutors (for students)
     */
    public function browseTutors(\Illuminate\Http\Request $request)
    {
        $subjects = \App\Models\Subject::all();
        $query = \App\Models\User::where('role', 'tutor')->where('is_active', 1)->with(['profile', 'subjects']);
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function($sub) use ($q) {
                $sub->where('name', 'like', "%$q%")
                    ->orWhereHas('subjects', function($sq) use ($q) {
                        $sq->where('name', 'like', "%$q%");
                    });
            });
        }
        if ($request->filled('subject_id')) {
            $query->whereHas('subjects', function($sq) use ($request) {
                $sq->where('id', $request->input('subject_id'));
            });
        }
        if ($request->filled('education_level')) {
            $query->whereHas('profile', function($sq) use ($request) {
                $sq->where('education_level', $request->input('education_level'));
            });
        }
        // Payment filter
        if ($request->filled('payment')) {
            if ($request->input('payment') === 'paid') {
                $query->whereHas('profile', function($sq) {
                    $sq->where('is_paid', 1);
                });
            } elseif ($request->input('payment') === 'free') {
                $query->whereHas('profile', function($sq) {
                    $sq->where('is_paid', 0);
                });
            }
        }
        // Payment filter
        if ($request->filled('payment')) {
            if ($request->input('payment') === 'paid') {
                $query->whereHas('profile', function($sq) {
                    $sq->where('is_paid', 1);
                });
            } elseif ($request->input('payment') === 'free') {
                $query->whereHas('profile', function($sq) {
                    $sq->where('is_paid', 0);
                });
            }
        }
        // Rate filter
        if ($request->filled('min_rate')) {
            $query->whereHas('profile', function($sq) use ($request) {
                $sq->where('rate', '>=', $request->input('min_rate'));
            });
        }
        $tutors = $query->paginate(12)->appends($request->query());

        // Calculate stats for stat cards
        $stats = [
            'totalTutors' => \App\Models\User::where('role', 'tutor')->count(),
            'activeTutors' => \App\Models\User::where('role', 'tutor')->where('is_active', 1)->count(),
            // Average rating from feedback table for tutors only
            'averageRating' => \App\Models\Feedback::whereIn('tutor_id', \App\Models\User::where('role', 'tutor')->pluck('id'))
                ->whereNotNull('rating')
                ->avg('rating') ?? 0,
        ];

        return view('student.browse_tutors', compact('tutors', 'subjects', 'stats'));
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
        $monthlyRaw = $this->metrics->getMonthlySessions($student);
        // Ensure $monthlyData['labels'] and ['data'] are always arrays for the view
        if (is_array($monthlyRaw) && isset($monthlyRaw['labels']) && isset($monthlyRaw['data'])) {
            $monthlyData = $monthlyRaw;
        } elseif ($monthlyRaw instanceof \Illuminate\Support\Collection) {
            // If collection, convert to arrays for chart
            $labels = $monthlyRaw->keys()->map(function($m) { return Carbon::create()->month($m)->format('M Y'); })->toArray();
            $data = $monthlyRaw->values()->toArray();
            $monthlyData = ['labels' => $labels, 'data' => $data];
        } else {
            $monthlyData = ['labels' => [], 'data' => []];
        }
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

