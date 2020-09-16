<?php

namespace App\Policies;

use App\Models\Preset;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PresetsPolicy
{
    use HandlesAuthorization;

    public function before(User $user, $ability)
    {
        if ($user->isAdmin()) {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return $user->isManager();
    }

    public function view(User $user)
    {
        return $user->isManager();
    }

    public function create(User $user)
    {
        return $user->isManager();
    }

    public function update(User $user, Preset $preset)
    {
        return isset($preset->manager) && $preset->manager->is($user);
    }

    public function delete(User $user, Preset $preset)
    {
        return isset($preset->manager) && $preset->manager->is($user);
    }
}
