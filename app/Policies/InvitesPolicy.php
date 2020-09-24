<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitesPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        return $user->isAdmin() || $user->isManager();
    }
}
