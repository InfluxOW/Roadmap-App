<?php

namespace App\Http\Resources;

use App\Models\UserTypes\Employee;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CoursesResource extends JsonResource
{
    public function toArray($request)
    {
        $default = [
            'name' => $this->name,
            'description' => $this->description,
            'source' => $this->source,
            'level' => $this->level->name,
            'link' => $this->when(
                ! $request->is('api/courses/*'),
                route('courses.show', ['course' => $this->resource])
            ),
            'average_rating' => $this->average_rating,
        ];

        $attributes = $default;

        if (isset($this->manager)) {
            $attributes['manager'] = $this->manager->name;
        }

        if ($request->is('api/dashboard/employees/*')) {
            $completion = $this->completions->where(
                'employee_id',
                $request->employee->id ?? Employee::whereUsername($request->route('employee'))->first()->id
            )->first();

            if ($completion) {
                $attributes['completed_at'] = $completion->completed_at->format('d-M-Y H:i:s T');

                if (isset($completion->rating)) {
                    $attributes['employee_rating'] = $completion->rating;
                }
            }
        }

        if ($request->is('api/courses/*') || $request->is('api/courses')) {
            $attributes['completed_by'] = UsersResource::collection($this->completedBy());
        }

        if ($request->is('api/courses/*') || $request->is('api/courses')) {
            $attributes['technologies'] = $this->technologies->pluck('name');
        }

        return $attributes;
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
