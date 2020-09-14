<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class PresetsResource extends JsonResource
{
    public function toArray($request)
    {
        $default = [
            'name' => $this->name,
            'description' => $this->description,
            'link' => $this->when(
                ! $request->is('api/presets/*'),
                route('presets.show', ['preset' => $this->resource])
            ),
            'courses' => CoursesResource::collection($this->courses),
        ];

        $attributes = $default;

        if (isset($this->manager)) {
            $attributes['manager'] = $this->manager->name;
        }

        if (Auth::user()->isManager() || Auth::user()->isAdmin()) {
            $attributes['assigned_to'] = UsersResource::collection($this->assignedTo());
        }

        return $attributes;
    }

    private function assignedTo()
    {
        $user = Auth::user();

        if ($user->isManager()) {
            return $this->roadmaps
                ->whereIn('employee_id', $user->getEmployees()->pluck('id'))
                ->map(fn($roadmap) => $roadmap->employee);
        }

        return $this->roadmaps
            ->map(fn($roadmap) => $roadmap->employee);
    }
}
