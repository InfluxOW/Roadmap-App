<?php

namespace App\Repositories;

use App\Models\Preset;
use App\Models\Technology;
use Facades\App\Repositories\PresetsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PresetsGenerationRepository
{
    public function store(Request $request, Preset $preset = null)
    {
        return DB::transaction(function () use ($request, $preset) {
            $preset = $preset ?? PresetsRepository::store($request);
            $courses = collect($request->technologies)
                ->filter(function ($technology) {
                    return Technology::whereName($technology)->orWhere('slug', $technology)->exists();
                })
                ->map(function ($technology) {
                    return Technology::whereName($technology)->orWhere('slug', $technology)->first()->courses;
                })
                ->flatten()->unique('id', true);

            if ($courses->isEmpty()) {
                throw new NotFoundResourceException("No courses was found for the given technologies.");
            }

            $preset->courses()->attach($courses->pluck('id')->toArray(), ['assigned_at' => now()]);

            return $preset;
        });
    }

//    public function store(Request $request, Preset $preset = null)
//    {
//        return DB::transaction(function () use ($request, $preset) {
//            $preset = $preset ?? PresetsRepository::store($request);
//            $technologies = $this->getTechnologies($request->technologies);
//            $courses = $this->getCourses($technologies);
//
//            if ($courses->isEmpty()) {
//                throw new NotFoundResourceException("No courses was found for the given technologies.");
//            }
//
//            $preset->courses()->attach($courses->pluck('id')->toArray(), ['assigned_at' => now()]);
//
//            return $preset;
//        });
//    }
//
//    public function getTechnologies(array $input)
//    {
//        return collect($input)
//            ->filter(function (string $technology) {
//                return Technology::whereName($technology)->orWhere('slug', $technology)->exists();
//            })
//            ->map(function (string $technology) {
//                return $this->getRelatedTechnologies(Technology::whereName($technology)->orWhere('slug', $technology)->first());
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
