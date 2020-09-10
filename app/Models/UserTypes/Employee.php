<?php

namespace App\Models\UserTypes;

use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\DevelopmentDirection;
use App\Models\Roadmap;
use App\Models\Team;
use App\Models\Technology;
use App\Models\User;
use Parental\HasParent;

class Employee extends User
{
    use HasParent;

    /*
     * Relations
     * */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function directions()
    {
        return $this->belongsToMany(DevelopmentDirection::class, 'employee_development_directions', 'employee_id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'employee_technologies', 'employee_id');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id')->withPivot('assigned_at');
    }

    public function completions()
    {
        return $this->hasMany(CourseCompletion::class, 'employee_id');
    }

    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class, 'employee_id');
    }

    /*
     * Course Completions Logic
     * */

    public function hasCompleted(Course $course)
    {
        return $this->completions->where('course_id', $course->id)->isNotEmpty();
    }

    public function hasNotCompleted(Course $course)
    {
        return ! $this->hasCompleted($course);
    }

    public function complete(Course $course)
    {
        if ($this->hasNotCompleted($course)) {
            $completion = $this->completions()->make();
            $completion->course()->associate($course);
            $completion->completed_at = now();

            return $completion->save();
        }
    }

    public function incomplete(Course $course)
    {
        if ($this->hasCompleted($course)) {
            $completion = CourseCompletion::where('course_id', $course->id)->where('employee_id', $this->id)->firstOrFail();

            return $completion->delete();
        }
    }

    public function rate(Course $course, int $rating)
    {
        if ($this->hasCompleted($course)) {
            $completion = CourseCompletion::where('course_id', $course->id)->where('employee_id', $this->id)->first();

            return $completion->update(['rating' => $rating]);
        }
    }
}
