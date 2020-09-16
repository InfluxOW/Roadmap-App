<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsersPolicy
{
    use HandlesAuthorization;

    public function viewEmployeeDashboard(User $user, Employee $employee)
    {
        return $user->is($employee) ||
            ($user->isManager() && $user->hasEmployee($employee)) ||
            $user->isAdmin();
    }

    public function viewManagerDashboard(User $user, Manager $manager)
    {
        return $user->is($manager) || $user->isAdmin();
    }

    public function manageCompletions(User $user)
    {
        return $user->isEmployee();
    }

    public function suggestCourse(User $user)
    {
        return true;
    }
}
