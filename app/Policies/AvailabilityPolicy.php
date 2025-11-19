<?php

namespace App\Policies;

use App\Models\Availability;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AvailabilityPolicy
{
    use HandlesAuthorization;

    /** Determine whether the user can view any availability (not used) */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isTutor();
    }

    /** Determine whether the user can view a given availability. */
    public function view(User $user, Availability $availability): bool
    {
        return $user->isAdmin() || $user->id === $availability->user_id;
    }

    /** Determine whether the user can create availabilities. */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isTutor();
    }

    /** Determine whether the user can update the availability. */
    public function update(User $user, Availability $availability): bool
    {
        return $user->isAdmin() || $user->id === $availability->user_id;
    }

    /** Determine whether the user can delete the availability. */
    public function delete(User $user, Availability $availability): bool
    {
        return $user->isAdmin() || $user->id === $availability->user_id;
    }
}
