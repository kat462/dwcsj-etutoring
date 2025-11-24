<?php

namespace App\Policies;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BookingPolicy
{
    use HandlesAuthorization;

    /** Determine whether the user can view the booking. */
    public function view(User $user, Booking $booking): bool
    {
        return $user->isAdmin() || $user->id === $booking->tutor_id || $user->id === $booking->tutee_id;
    }

    /** Tutor-only actions (accept/decline/complete) mapped to update */
    public function update(User $user, Booking $booking): bool
    {
        return $user->isAdmin() || $user->id === $booking->tutor_id;
    }

    /** Tutee may cancel their own pending/accepted bookings */
    public function cancel(User $user, Booking $booking): bool
    {
        return $user->isAdmin() || $user->id === $booking->tutee_id;
    }

    /** Allow creation by any authenticated tutee (handled in controller) */
    public function create(User $user): bool
    {
        return $user->isStudent() || $user->isAdmin();
    }
}
