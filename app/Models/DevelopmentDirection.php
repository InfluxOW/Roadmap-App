<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class DevelopmentDirection extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['name'];
    public $timestamps = false;

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

    public function technologies()
    {
        return $this->belongsToMany(Technology::class, 'technology_development_directions');
    }

    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'employee_development_directions', 'development_direction_id', 'employee_id');
    }
}
