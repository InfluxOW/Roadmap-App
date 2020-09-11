<?php

namespace App\Models\UserTypes;

use App\Models\Company;
use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\DevelopmentDirection;
use App\Models\Roadmap;
use App\Models\Team;
use App\Models\Technology;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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

    public function completions()
    {
        return $this->hasMany(CourseCompletion::class, 'employee_id');
    }

    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class, 'employee_id');
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

    /*
     * Course Completions Logic
     * */

    public function hasCompletedCourse(Course $course)
    {
        return $this->completions->where('course_id', $course->id)->isNotEmpty();
    }

    public function hasNotCompletedCourse(Course $course)
    {
        return ! $this->hasCompletedCourse($course);
    }

    public function complete(Course $course)
    {
        if ($this->hasNotCompletedCourse($course)) {
            $completion = $this->completions()->make();
            $completion->course()->associate($course);
            $completion->completed_at = now();

            return $completion->save();
        }
    }

    public function incomplete(Course $course)
    {
        if ($this->hasCompletedCourse($course)) {
            $completion = CourseCompletion::where('course_id', $course->id)->where('employee_id', $this->id)->firstOrFail();

            return $completion->delete();
        }
    }

    public function scopeWithCompletedCourse(Builder $query, Course $course)
    {
        return $query->whereHas('completions', function (Builder $query) use ($course) {
           return $query->where('course_id', $course->id);
        });
    }

    public function rate(Course $course, int $rating)
    {
        if ($this->hasCompletedCourse($course) && $rating <= 10 && $rating > 0) {
            $completion = CourseCompletion::where('course_id', $course->id)->where('employee_id', $this->id)->first();

            return $completion->update(['rating' => $rating]);
        }
    }
}
