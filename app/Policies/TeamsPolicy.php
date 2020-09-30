<?php

namespace App\Policies;

use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeamsPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->isManager();
    }

    public function update(User $user, Team $team)
    {
        return $user->isManager() && $team->owner->is($user);
    }

    public function delete(User $user, Team $team)
    {
        return $user->isManager() && $team->owner->is($user);
    }
}
