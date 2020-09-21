<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

/**
 *
 * @OA\Schema(
 * @OA\Xml(name="PresetResource"),
 * @OA\Property(property="name", type="string", example="Awesome PHP preset"),
 * @OA\Property(property="slug", type="string", example="awesome-php-preset"),
 * @OA\Property(property="description", type="string", example="The best PHP preset you've ever seen!"),
 * @OA\Property(property="creator", type="array", @OA\Items(type="string", ref="#/components/schemas/UserBasicInformationResource")),
 * @OA\Property(property="link", type="string", example="http://localhost:8000/api/presets/awesome-php-preset"),
 * @OA\Property(property="courses", type="array", @OA\Items(type="array", @OA\Items(type="string"), ref="#/components/schemas/CourseResource")),
 * @OA\Property(property="assigned_to", type="array", @OA\Items(type="array", @OA\Items(type="string"), ref="#/components/schemas/UserBasicInformationResource")),
 * )
 *
 */
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
            'courses' => $this->courses(),
            'assigned_to' => $this->when(
                ($request->is('api/presets') || $request->is('api/presets/*')),
                function () {
                    return UserBasicInformationResource::collection($this->assignedTo());
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

    private function courses()
    {
        $courses = [];

        foreach ($this->courses as $course) {
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
