<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class CourseResource extends JsonResource
{
    public function toArray($request)
    {
        $default = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'source' => $this->source,
            'level' => $this->level->name,
            'link' => $this->when(
                ! ($request->is('api/courses/*') || $request->user()->isEmployee()),
                route('courses.show', ['course' => $this->resource])
            ),
            'average_rating' => $this->average_rating,
        ];

        $attributes = $default;

        if (isset($this->additional['employee'])) {
            $completion = $this->completions->where(
                'employee_id',
                $this->additional['employee']->id
            )->first();

            if ($completion) {
                $attributes['completed_at'] = $completion->completed_at->format('d-M-Y H:i:s T');

                if (isset($completion->rating)) {
                    $attributes['employee_rating'] = $completion->rating;
                }

                if (isset($completion->certificate)) {
                    $attributes['certificate'] = $completion->certificate;
                }
            }
        }

        if ($request->is('api/courses/*') || $request->is('api/courses')) {
            $attributes['technologies'] = $this->technologies->pluck('name');
        }

        if ($request->is('api/courses/*') || $request->is('api/courses')) {
            $attributes['completed_by'] = UserBasicInformationResource::collection($this->completedBy());
        }

        return $request->show && is_array($request->show) && array_key_exists('course', $request->show) ?
            Arr::only($attributes, explode(',', $request->show['course'])) :
            $attributes;
    }

    private function completedBy()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->completions
                ->map(fn($completion) => $completion->employee);
        }

        return $this->completions
            ->whereIn('employee_id', $user->employees->pluck('id'))
            ->map(fn($completion) => $completion->employee);
    }
}
