<?php

namespace App\Models;

use App\Models\UserTypes\Manager;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Preset extends Model
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

    public function manager()
    {
        return $this->belongsTo(Manager::class);
    }

    public function roadmaps()
    {
        return $this->hasMany(Roadmap::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'preset_courses')->withPivot('assigned_at');
    }
}
