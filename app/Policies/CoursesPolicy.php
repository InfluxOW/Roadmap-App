<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;

class CoursesPolicy extends Policy
{
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
        return false;
    }

    public function update(User $user, Course $course)
    {
        return false;
    }

    public function delete(User $user, Course $course)
    {
        return false;
    }
}
