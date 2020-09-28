<?php

namespace App\Repositories;

use App\Models\Preset;
use App\Models\Technology;
use Facades\App\Repositories\PresetsRepository;
use Illuminate\Http\Request;

class PresetsGenerationRepository
{
    public function store(Request $request, Preset $preset = null)
    {
        $preset = $preset ?? PresetsRepository::store($request);
        $courses = collect($request->technologies)->map(function ($technology) {
            return Technology::whereName($technology)->first()->courses;
        })->flatten()->unique('id', true);

        $preset->courses()->attach($courses->pluck('id')->toArray(), ['assigned_at' => now()]);

        return $preset;
    }

//    public function store(Request $request, Preset $preset = null)
//    {
//        $preset = $preset ?? PresetsRepository::store($request);
//        $technologies = $this->getTechnologies($request->technologies);
//        $courses = $this->getCourses($technologies);
//        $preset->courses()->attach($courses->pluck('id')->toArray(), ['assigned_at' => now()]);
//
//        return $preset;
//    }

//    public function getTechnologies(array $input)
//    {
//        return collect($input)
//            ->map(function(string $technology) {
//                return Technology::whereName($technology)->firstOrFail();
//            })
//            ->map(function(Technology $technology) {
//                return $this->getRelatedTechnologies($technology);
//            })
//            ->flatten()
//            ->unique('id', true);
//    }
//
//    public function getRelatedTechnologies(Technology $technology)
//    {
//        if ($technology->hasRelatedTechnologies()) {
//            return $technology->relatedTechnologies->map(function(Technology $technology) {
//                return $this->getRelatedTechnologies($technology);
//            });
//        }
//
//        return $technology;
//    }
//
//    public function getCourses($technologies)
//    {
//        return $technologies
//            ->map(function($technology) {
//                return $technology->courses;
//            })
//            ->flatten()
//            ->unique('id', true);
//    }
}
