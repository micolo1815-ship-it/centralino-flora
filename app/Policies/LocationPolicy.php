<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Location;
use Illuminate\Auth\Access\HandlesAuthorization;

class LocationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the location.
     */
    public function update(User $user, Location $location): bool
    {
        // Allow the creator
        if ($user->id === $location->created_by) {
            return true;
        }

        return false;
    }
}
