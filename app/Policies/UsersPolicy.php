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

    public function suggestCourse()
    {
        return true;
    }

    public function manageRoadmaps(User $user)
    {
        return $user->isManager();
    }

    public function viewEmployees(User $user)
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function viewManagers(User $user)
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function viewManager(User $user, Manager $manager)
    {
        return $user->is($manager) || $user->isAdmin();
    }
}
