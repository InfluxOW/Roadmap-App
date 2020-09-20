<?php

namespace App\Models\UserTypes;

use App\Models\Company;
use App\Models\Course;
use App\Models\CourseCompletion;
use App\Models\DevelopmentDirection;
use App\Models\Preset;
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

    public function presets()
    {
        return $this->hasManyDeep(
            Preset::class,
            [Roadmap::class],
            ['employee_id', 'id'],
            ['id', 'preset_id']
        );
    }

    public function courses()
    {
        return $this->hasManyDeep(
            Course::class,
            ['employee_roadmaps', Preset::class, 'preset_courses'],
            ['employee_id', 'id', 'preset_id'],
            ['id', 'preset_id', 'id']
        )->distinct();
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

    public function scopeWithCompletedCourse(Builder $query, Course $course)
    {
        return $query->whereHas('completions', function (Builder $query) use ($course) {
            return $query->where('course_id', $course->id);
        });
    }

    public function complete(Course $course)
    {
        $completion = $this->completions()->make();
        $completion->course()->associate($course);
        $completion->completed_at = now();

        return $completion->save();
    }

    public function incomplete(Course $course)
    {
        $completion = CourseCompletion::where('course_id', $course->id)->where('employee_id', $this->id)->firstOrFail();

        return $completion->delete();
    }

    public function rate(Course $course, int $rating)
    {
        if ($rating > 10 || $rating < 0) {
            throw new \InvalidArgumentException("Please, use ten-point scale for rating.");
        }

        $completion = CourseCompletion::where('course_id', $course->id)->where('employee_id', $this->id)->first();

        return $completion->update(['rating' => $rating]);
    }

    public function attachCertificateTo(Course $course, string $certificate)
    {
        $completion = CourseCompletion::where('course_id', $course->id)->where('employee_id', $this->id)->first();

        return $completion->update(['certificate' => $certificate]);
    }

    /*
     * Helpers
     * */

    public function doesntHaveCourse(Course $course)
    {
        return ! $this->hasCourse($course);
    }

    public function hasCourse(Course $course)
    {
        return $this->getCourses()->pluck('id')->contains($course->id);
    }

    /*
     * Getters
     * */

    public function getCourses()
    {
        return $this->roadmaps->map(function ($roadmap) {
            return $roadmap->preset->courses;
        })->flatten()->unique('id', true);
    }
}
