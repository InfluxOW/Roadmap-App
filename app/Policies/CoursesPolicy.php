<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursesPolicy
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
