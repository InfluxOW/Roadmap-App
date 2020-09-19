<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursesPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function view(User $user)
    {
        return $user->isManager() || $user->isAdmin();
    }

    /*
     * Courses (Behind The CRUD)
     * */

    public function manageCompletions(User $user)
    {
        return $user->isEmployee();
    }

    public function suggestCourse()
    {
        return true;
    }
}
