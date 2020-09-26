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
        return $this->belongsToMany(DevelopmentDirection::class, 'technology_development_directions');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_technologies', 'technology_id', 'employee_id');
    }

    public function relatedTechnologies()
    {
        return $this->belongsToMany(self::class, 'technologies_connections', 'technology_id', 'related_technology_id');
    }

    public function relatedToTechnologies()
    {
        return $this->belongsToMany(self::class, 'technologies_connections', 'related_technology_id', 'technology_id');
    }
}
