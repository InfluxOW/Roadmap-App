<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;

class UsersPolicy extends Policy
{
    public function viewEmployeeDashboard(User $user, Employee $employee)
    {
        return $user->is($employee) || ($user->isManager() && $user->getEmployees()->pluck('id')->contains($employee->id));
    }

    public function viewManagerDashboard(User $user, Manager $manager)
    {
        return $user->is($manager);
    }
}
