<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Feedback;
use App\Models\User;
use App\Models\Subject;
use Carbon\Carbon;

class DashboardMetricsService
{
    // Example: Get total users
    public function getTotalUsers()
    {
        return User::count();
    }

    // Example: Get total tutors
    public function getTotalTutors()
    {
        return User::where('role', 'tutor')->count();
    }

    // Example: Get total students
    public function getTotalStudents()
    {
        return User::where('role', 'tutee')->count();
    }

    // Example: Get total sessions
    public function getTotalSessions()
    {
        return Booking::count();
    }

    // Example: Get total feedback
    public function getTotalFeedback()
    {
        return Feedback::count();
    }

    // Example: Get average platform rating
    public function getAveragePlatformRating()
    {
        return Feedback::avg('rating');
    }

    // Example: Get sessions by status
    public function getSessionsByStatus()
    {
        return Booking::select('status', \DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');
    }

    // Example: Get monthly sessions data
    public function getMonthlySessions($user = null)
    {
        $query = Booking::query();
        if ($user) {
            $query->where(function($q) use ($user) {
                $q->where('tutor_id', $user->id)
                  ->orWhere('tutee_id', $user->id);
            });
        }
        return $query->selectRaw('MONTH(scheduled_at) as month, COUNT(*) as count')
            ->whereYear('scheduled_at', Carbon::now()->year)
            ->groupBy('month')
            ->pluck('count', 'month');
    }

    // Add more shared metric methods as needed...
}
