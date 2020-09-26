<?php

namespace App\Models;

use App\Http\Requests\PresetGenerationRequest;
use App\Models\UserTypes\Manager;
use Facades\App\Repositories\PresetsRepository;
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
            ->generateSlugsFrom(['name', 'manager.name'])
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

    /*
     * Helpers
     * */

    public static function generateFromRequest(PresetGenerationRequest $request)
    {
        $preset = PresetsRepository::store($request);

        $courses = collect($request->technologies)->map(function ($technology) {
            return Technology::whereName($technology)->first()->courses;
        })->flatten()->unique('id', true);

        $preset->courses()->attach($courses->pluck('id')->toArray(), ['assigned_at' => now()]);

        return $preset;
    }
}
