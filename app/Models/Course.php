<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Course extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name', 'description', 'source', 'employee_level_id'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->allowDuplicateSlugs();
    }

    /*
     * Relations
     * */

    public function level()
    {
        return $this->belongsTo(EmployeeLevel::class, 'employee_level_id');
    }

    public function completions()
    {
        return $this->hasMany(CourseCompletion::class);
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'course_technologies');
    }

    public function presets()
    {
        return $this->belongsToMany(Preset::class, 'preset_courses')->withPivot('assigned_at');
    }

    /*
     * Completion Check
     * */

    public function isCompletedBy(Employee $employee)
    {
        return $this->completions->where('employee_id', $employee->id)->isNotEmpty();
    }

    public function isIncompletedBy(Employee $employee)
    {
        return ! $this->isCompletedBy($employee);
    }

    public function scopeCompletedBy(Builder $query, Employee $employee)
    {
        return $query->whereHas('completions', function (Builder $query) use ($employee) {
            return $query->where('employee_id', $employee->id);
        });
    }

    public function scopeIncompletedBy(Builder $query, Employee $employee)
    {
        return $query->whereDoesntHave('completions', function (Builder $query) use ($employee) {
            return $query->where('employee_id', $employee->id);
        });
    }

    /*
     * Getters
     * */

    public function getAverageRatingAttribute()
    {
        return $this->completions->count() > 0 ? $this->completions->average('rating') : 0;
    }
}
