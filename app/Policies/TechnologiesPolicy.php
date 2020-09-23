<?php

namespace App\Policies;

use App\Models\Technology;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TechnologiesPolicy
{
    use HandlesAuthorization;

    public function before(User $user)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->isManager();
    }

    public function view(User $user, Technology $technology)
    {
        //
    }

    public function create(User $user)
    {
        //
    }

    public function update(User $user, Technology $technology)
    {
        //
    }

    public function delete(User $user, Technology $technology)
    {
        //
    }
}
