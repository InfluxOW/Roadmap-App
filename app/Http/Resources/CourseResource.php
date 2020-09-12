<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'source' => $this->source,
            'level' => $this->level->name,
            'link' => $this->when(
                ! $request->is('api/courses/*'),
                route('courses.show', ['course' => $this->resource])
            ),
            'manager' => $this->when(
                isset($this->manager) && $this->manager->company->is($request->user()->company),
                new UserResource($this->manager)
            ),
            'completed_by' => $this->completedBy(),
        ];
    }

    private function completedBy()
    {
        $user = Auth::user();

        if ($user->isManager()) {
            return $this->completions
                ->whereIn('employee_id', $user->getEmployees()->pluck('id'))
                ->map(fn($completion) => $completion->employee);
        }

        return $this->completions
            ->map(fn($completion) => $completion->employee);
    }
}
