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
        return $this->belongsToMany(DevelopmentDirection::class, 'employee_development_directions');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'employee_technologies');
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id')->withPivot('assigned_at');
    }

    public function completions()
    {
        return $this->hasMany(CourseCompletion::class);
    }

    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class, 'employee_id');
    }
}
