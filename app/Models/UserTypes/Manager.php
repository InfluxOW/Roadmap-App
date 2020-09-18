<?php

namespace App\Models\UserTypes;

use App\Models\Company;
use App\Models\Preset;
use App\Models\Roadmap;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
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
     * Roadmap Assignments
     * */

    public function createRoadmap(Preset $preset, Employee $employee)
    {
        if ($this->doesntHaveEmployee($employee)) {
            throw new \LogicException("You can't create a roadmap for the employee which doesn't belong to any of your teams.");
        }

        $roadmap = $this->roadmaps()->make();
        $roadmap->employee()->associate($employee);
        $roadmap->preset()->associate($preset);
        $roadmap->assigned_at = now();

        return $roadmap->save();
    }

    public function deleteRoadmap(Preset $preset, Employee $employee)
    {
        if ($this->doesntHaveEmployee($employee)) {
            throw new \LogicException("You can't delete a roadmap of the employee which doesn't belong to any of your teams.");
        }

        $roadmap = Roadmap::where('employee_id', $employee->id)->where('preset_id', $preset->id);

        if ($roadmap->doesntExist()) {
            throw new \LogicException("You can't delete nonexistent roadmap");
        }

        return $roadmap->delete();
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
