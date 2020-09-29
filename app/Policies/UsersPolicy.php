<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Database\Eloquent\Model;

class UsersPolicy
{
    use HandlesAuthorization;

    /*
     * Roadmaps
     * */

    public function viewEmployeesRoadmaps()
    {
        return true;
    }

    public function viewEmployeeRoadmaps(User $user, Employee $employee)
    {
        return $user->is($employee) ||
            ($user->isManager() && $user->hasEmployee($employee)) ||
            $user->isAdmin();
    }

    public function manageRoadmaps(User $user)
    {
        return $user->isManager();
    }

    /*
     * Profiles
     * */

    public function viewProfiles()
    {
        return true;
    }

    public function viewProfile(User $user, User $profile)
    {
        return $user->is($profile) ||
            $user->isAdmin();
    }
}
