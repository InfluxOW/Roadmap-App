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

    public function view(User $user, Course $course)
    {
        return $user->isManager();
    }

    public function create(User $user)
    {
        return $user->isManager();
    }

    public function update(User $user, Course $course)
    {
        return isset($course->manager) && $course->manager->is($user);
    }

    public function delete(User $user, Course $course)
    {
        return isset($course->manager) && $course->manager->is($user);
    }
}
