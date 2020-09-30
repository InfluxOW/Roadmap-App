<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Team extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(['name', 'owner.name'])
            ->saveSlugsTo('slug')
            ->allowDuplicateSlugs();
    }

    /*
     * Relations
     * */

    public function owner()
    {
        return $this->belongsTo(Manager::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'team_members', 'team_id', 'user_id')->withPivot('assigned_at');
    }

    /*
     * Helpers
     * */

    public function assignEmployee(Employee $employee)
    {
        if ($this->hasEmployee($employee)) {
            throw new \LogicException("You can't assign an employee to the team twice.");
        }

        $this->employees()->attach($employee, ['assigned_at' => now()]);
    }

    public function unassignEmployee(Employee $employee)
    {
        if ($this->doesntHaveEmployee($employee)) {
            throw new \LogicException("You can't unassign an unsigned employee from the team.");
        }

        $this->employees()->detach($employee);
    }

    public function hasEmployee(Employee $employee)
    {
        return $this->employees->contains($employee);
    }

    public function doesntHaveEmployee(Employee $employee)
    {
        return ! $this->hasEmployee($employee);
    }
}
