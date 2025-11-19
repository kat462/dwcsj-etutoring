<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Availability;
use App\Models\Feedback;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TutorDashboardController extends Controller
{
    /**
     * Show tutor dashboard with metrics and widgets
     */
    public function index()
    {
        $tutor = Auth::user();
        
        // Get key metrics
        $upcomingSessions = $this->getUpcomingSessions($tutor);
        $bookingRequests = $this->getPendingBookings($tutor);
        $recentlySessions = $this->getRecentlySessions($tutor, 5);
        $averageRating = $this->getAverageRating($tutor);
        $recentFeedback = $this->getRecentFeedback($tutor, 3);
        $totalEarnings = $this->getTotalEarnings($tutor);
        $totalHours = $this->getTotalHours($tutor);
        $completionRate = $this->getCompletionRate($tutor);
        $acceptanceRate = $this->getAcceptanceRate($tutor);
        $subjects = $tutor->subjects;
        
        // Weekly activity data for chart
        $weeklyData = $this->getWeeklyActivity($tutor);
        
        // Monthly trend data
        $monthlyTrend = $this->getMonthlySessions($tutor);
        
        // Subject popularity
        $subjectStats = $this->getSubjectStats($tutor);
        
        return view('tutor.dashboard', compact(
            'upcomingSessions',
            'bookingRequests',
            'recentlySessions',
            'averageRating',
            'recentFeedback',
            'totalEarnings',
            'totalHours',
            'completionRate',
            'acceptanceRate',
            'subjects',
            'weeklyData',
            'monthlyTrend',
            'subjectStats'
        ));
    }

    /**
     * Get upcoming sessions for the next 7 days
     */
    private function getUpcomingSessions($tutor, $days = 7)
    {
        return Booking::where('tutor_id', $tutor->id)
            ->where('status', '!=', 'cancelled')
            ->whereBetween('scheduled_at', [
                Carbon::now(),
                Carbon::now()->addDays($days)
            ])
            ->with(['tutee', 'subject'])
            ->orderBy('scheduled_at')
            ->get();
    }

    /**
     * Get pending booking requests
     */
    private function getPendingBookings($tutor)
    {
        return Booking::where('tutor_id', $tutor->id)
            ->where('status', 'pending')
            ->with(['tutee', 'subject'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    /**
     * Get recently completed sessions
     */
    private function getRecentlySessions($tutor, $limit = 5)
    {
        return Booking::where('tutor_id', $tutor->id)
            ->where('status', 'completed')
            ->with(['tutee', 'subject', 'feedback'])
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Calculate average rating from feedback
     */
    private function getAverageRating($tutor)
    {
        $feedback = Feedback::where('tutor_id', $tutor->id)
            ->where('status', 'approved')
            ->whereNotNull('rating')
            ->avg('rating');

        return round($feedback ?? 0, 1);
    }

    /**
     * Get recent feedback comments
     */
    private function getRecentFeedback($tutor, $limit = 3)
    {
        return Feedback::where('tutor_id', $tutor->id)
            ->where('status', 'approved')
            ->whereNotNull('comment')
            ->with(['tutee', 'booking'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Calculate total earnings (sessions completed)
     */
    private function getTotalEarnings($tutor)
    {
        $completedSessions = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'completed')
            ->count();

        // Placeholder: $10 per session (can be updated with real pricing)
        return $completedSessions * 10;
    }

    /**
     * Calculate total hours tutored
     */
    private function getTotalHours($tutor)
    {
        $sessions = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'completed')
            ->get();

        $totalSeconds = 0;
        foreach ($sessions as $session) {
            // Assume 1 hour per session (can be customized based on availability duration)
            $totalSeconds += 3600;
        }

        return round($totalSeconds / 3600, 1);
    }

    /**
     * Calculate session completion rate (completed vs accepted)
     */
    private function getCompletionRate($tutor)
    {
        $completed = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'completed')
            ->count();

        $accepted = Booking::where('tutor_id', $tutor->id)
            ->where('status', 'accepted')
            ->orWhere('status', 'completed')
            ->count();

        if ($accepted == 0) {
            return 0;
        }

        return round(($completed / $accepted) * 100, 1);
    }

    /**
     * Calculate booking acceptance rate
     */
    private function getAcceptanceRate($tutor)
    {
        $accepted = Booking::where('tutor_id', $tutor->id)
            ->whereIn('status', ['accepted', 'completed'])
            ->count();

        $total = Booking::where('tutor_id', $tutor->id)
            ->count();

        if ($total == 0) {
            return 0;
        }

        return round(($accepted / $total) * 100, 1);
    }

    /**
     * Get weekly activity (sessions per day for the past 7 days)
     */
    private function getWeeklyActivity($tutor)
    {
        $days = [];
        $data = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            $days[] = Carbon::parse($date)->format('D');

            $count = Booking::where('tutor_id', $tutor->id)
                ->where('status', '!=', 'cancelled')
                ->whereDate('scheduled_at', $date)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $days,
            'data' => $data
        ];
    }

    /**
     * Get monthly sessions trend (past 6 months)
     */
    private function getMonthlySessions($tutor)
    {
        $months = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');

            $count = Booking::where('tutor_id', $tutor->id)
                ->where('status', '!=', 'cancelled')
                ->whereYear('scheduled_at', $date->year)
                ->whereMonth('scheduled_at', $date->month)
                ->count();

            $data[] = $count;
        }

        return [
            'labels' => $months,
            'data' => $data
        ];
    }

    /**
     * Get subject popularity (most booked subjects)
     */
    private function getSubjectStats($tutor)
    {
        $subjectStats = Booking::where('tutor_id', $tutor->id)
            ->whereNotNull('subject_id')
            ->groupBy('subject_id')
            ->selectRaw('subject_id, COUNT(*) as count')
            ->with('subject')
            ->orderByRaw('count DESC')
            ->limit(5)
            ->get();

        $labels = [];
        $data = [];

        foreach ($subjectStats as $stat) {
            if ($stat->subject) {
                $labels[] = $stat->subject->name;
                $data[] = $stat->count;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data
        ];
    }
}
