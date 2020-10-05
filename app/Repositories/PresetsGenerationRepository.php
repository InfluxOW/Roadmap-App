<?php

namespace App\Repositories;

use App\Models\Preset;
use App\Models\Technology;
use Facades\App\Repositories\PresetsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class PresetsGenerationRepository
{
    protected Collection $technologies;

    public function __construct()
    {
        $this->technologies = collect([]);
    }

    public function store(Request $request, Preset $preset = null)
    {
        return DB::transaction(function () use ($request, $preset) {
            $preset = $preset ?? PresetsRepository::store($request);
            $this->findAllTechnologies($request->technologies);
            $courses = $this->getCourses($this->technologies);

            if ($courses->isEmpty()) {
                throw new NotFoundResourceException("No courses was found for the given technologies.");
            }

            $preset->courses()->attach($courses->pluck('id')->toArray(), ['assigned_at' => now()]);

            return $preset;
        });
    }

    public function findAllTechnologies(array $input)
    {
        return collect($input)
            ->filter(function (string $technology) {
                return Technology::whereName($technology)->orWhere('slug', $technology)->exists();
            })
            ->map(function (string $technology) {
                return $this->getRelatedTechnologies(Technology::whereName($technology)->orWhere('slug', $technology)->first());
            });
    }

    public function getRelatedTechnologies(Technology $technology)
    {
        if (! $this->technologies->pluck('name')->contains($technology->name)) {
            $this->technologies->add($technology);
        }

        if ($technology->hasRelatedTechnologies()) {
            return $technology->relatedTechnologies
                ->filter(function (Technology $technology) {
                    return ! $this->technologies->pluck('name')->contains($technology->name);
                })->map(function (Technology $technology) {
                    return $this->getRelatedTechnologies($technology);
                });
        }
    }

    public function getCourses($technologies)
    {
        return $technologies->map->courses->flatten()->unique('id', true);
    }
}
