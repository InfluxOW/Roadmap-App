<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class PresetResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'creator' => $this->when(isset($this->manager), function () {
                return new UserBasicInformationResource($this->manager);
            }),
            'link' => $this->when(
                ! ($request->is('api/presets/*') || $request->user()->isEmployee()),
                route('presets.show', ['preset' => $this->resource])
            ),
            'courses' => $this->courses($request),
            'assigned_to' => $this->when(
                ($request->is('api/presets') || $request->is('api/presets/*')),
                function () use ($request) {
                    return UserBasicInformationResource::collection(
                        isset($request->take['assigned_to']) ? $this->assignedTo()->take($request->take['assigned_to']) : $this->assignedTo()
                    );
                }
            )
        ];

        return $request->show && is_array($request->show) && array_key_exists('preset', $request->show) ?
            Arr::only($attributes, explode(',', $request->show['preset'])) :
            $attributes;
    }

    private function assignedTo()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->roadmaps
                ->map(fn($roadmap) => $roadmap->employee);
        }

        return $this->roadmaps
            ->whereIn('employee_id', $user->employees->pluck('id'))
            ->map(fn($roadmap) => $roadmap->employee);
    }

    private function courses($request)
    {
        $courses = [];
        $coursesToTake = isset($request->take['courses']) ? $this->courses->take($request->take['courses']) : $this->courses;

        foreach ($coursesToTake as $course) {
            foreach ($course->technologies as $technology) {
                $courses[$course->level->name][$technology->name][] =
                    isset($this->additional['employee']) ?
                        (new CourseResource($course))->additional(['employee' => $this->additional['employee']]) :
                        (new CourseResource($course));
            }
        }

        ksort($courses);

        return $courses;
    }
}
