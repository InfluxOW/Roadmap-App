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
            'description' => $this->description,
            'link' => $this->when(
                ! $request->is('api/presets/*'),
                route('presets.show', ['preset' => $this->resource])
            ),
            'manager' => $this->when(
                isset($this->manager) && $this->manager->company->is($request->user()->company),
                new UsersResource($this->manager)
            ),
            'courses' => CoursesResource::collection($this->courses),
            'assigned_to' => UsersResource::collection($this->assignedTo())
        ];
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
