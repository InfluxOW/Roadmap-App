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

    public function assignCourse(Course $course)
    {
        if ($this->hasCourse($course)) {
            throw new \LogicException("You can't assign a course to the preset twice.");
        }

        $this->courses()->attach($course, ['assigned_at' => now()]);
    }

    public function unassignCourse(Course $course)
    {
        if ($this->doesntHaveCourse($course)) {
            throw new \LogicException("You can't unassign an unsigned course from the preset.");
        }

        $this->courses()->detach($course);
    }

    public function hasCourse(Course $course)
    {
        return $this->courses->contains($course);
    }

    public function doesntHaveCourse(Course $course)
    {
        return ! $this->hasCourse($course);
    }
}
