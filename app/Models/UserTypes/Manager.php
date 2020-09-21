<?php

namespace App\Models\UserTypes;

use App\Models\Company;
use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\Team;
use App\Models\User;
use Parental\HasParent;

class Manager extends User
{
    use HasParent;

    /*
     * Relations
     * */

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class, 'owner_id');
    }

    public function employees()
    {
        return $this->hasManyDeep(
            Employee::class,
            [Team::class, 'team_members'],
            ['owner_id', 'team_id', 'id'],
            ['id', 'id', 'user_id'],
        );
    }

    public function presets()
    {
        return $this->hasMany(Preset::class, 'manager_id');
    }

    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class, 'manager_id');
    }

    /*
     * Helpers
     * */

    public function hasEmployee(Employee $employee)
    {
        return $this->employees->contains($employee);
    }

    public function doesntHaveEmployee(Employee $employee)
    {
        return ! $this->hasEmployee($employee);
    }
}
