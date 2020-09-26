<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Technology extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name', 'description'];

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

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_technologies');
    }

    public function directions()
    {
        return $this->belongsToMany(DevelopmentDirection::class, 'technology_development_directions')
            ->using(TechnologyForDevelopmentDirection::class);
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_technologies', 'technology_id', 'employee_id');
    }
}
