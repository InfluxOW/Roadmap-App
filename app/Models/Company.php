<?php

namespace App\Models;

use App\Models\UserTypes\Employee;
use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Company extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = ['website', 'name', 'description', 'foundation_year', 'industry', 'location'];

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

    public function managers()
    {
        return $this->hasMany(Manager::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }
}
