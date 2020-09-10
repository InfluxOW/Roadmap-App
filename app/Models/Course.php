<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Course extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name', 'description', 'source'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /*
     * Relations
     * */

    public function level()
    {
        return $this->belongsTo(EmployeeLevel::class, 'employee_level_id');
    }

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'course_technologies');
    }

    public function presets()
    {
        return $this->belongsToMany(Preset::class, 'preset_courses')->withPivot('assigned_at');
    }

    public function completions()
    {
        return $this->hasMany(CourseCompletion::class);
    }

    /*
     *
     * */

    public function isCompletedBy(Employee $employee)
    {
        return $employee->hasCompleted($this);
    }

    public function isIncompletedBy(Employee $employee)
    {
        return $employee->hasNotCompleted($this);
    }

    public function scopeCompletedBy(Builder $query, Employee $employee)
    {
        return $query->whereHas('completions', function (Builder $query) use ($employee) {
            return $query->where('employee_id', $employee->id);
        });
    }
}
