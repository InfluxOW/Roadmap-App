<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

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
     * Course Completions
     * */

    public function complete(User $user, Course $course)
    {
        if ($user->doesntHaveCourse($course)) {
            return Response::deny("You can't complete a course that doesn't belong to any of your roadmaps.");
        }

        if ($user->hasCompletedCourse($course)) {
            return Response::deny("You can't complete a completed course.");
        }

        return Response::allow();
    }

    public function incomplete(User $user, Course $course)
    {
        if ($user->doesntHaveCourse($course)) {
            return Response::deny("You can't incomplete a course that doesn't belong to any of your roadmaps.");
        }

        if ($user->hasNotCompletedCourse($course)) {
            return Response::deny("You can't incomplete an incompleted course.");
        }

        return Response::allow();
    }

    public function updateCompletion(User $user, Course $course)
    {
        if ($user->doesntHaveCourse($course)) {
            return Response::deny("You can't update course completion information for a course that doesn't belong to any of your roadmaps.");
        }

        if ($user->hasNotCompletedCourse($course)) {
            return Response::deny("You can't update course completion information for an incompleted course.");
        }

        return Response::allow();
    }

    /*
     * Courses (Behind the CRUD)
     * */

    public function suggest()
    {
        return true;
    }
}
