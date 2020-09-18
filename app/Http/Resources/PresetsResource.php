<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PresetsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'creator' => $this->when(isset($this->manager), function () {
                return $this->manager->name;
            }),
            'link' => $this->when(
                ! $request->is('api/presets/*'),
                route('presets.show', ['preset' => $this->resource])
            ),
            'courses' => $this->courses(),
            'assigned_to' => $this->when(
                ($request->is('api/presets') || $request->is('api/presets/*')),
                UsersResource::collection($this->assignedTo())
            )
        ];
    }

    private function assignedTo()
    {
        $user = Auth::user();

        if ($user->isManager()) {
            return $this->roadmaps
                ->whereIn('employee_id', $user->employees->pluck('id'))
                ->map(fn($roadmap) => $roadmap->employee);
        }

        return $this->roadmaps
            ->map(fn($roadmap) => $roadmap->employee);
    }

    private function courses()
    {
        $courses = [];
        foreach ($this->courses as $course) {
            foreach ($course->technologies as $technology) {
                $courses[$course->level->name][$technology->name][] =
                    isset($this->additional['employee']) ?
                        (new CoursesResource($course))->additional(['employee' => $this->additional['employee']]) :
                        (new CoursesResource($course));
            }
        }

        ksort($courses);

        return $courses;
    }
}
