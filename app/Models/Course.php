<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
