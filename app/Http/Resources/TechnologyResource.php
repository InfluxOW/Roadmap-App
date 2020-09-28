<?php

namespace App\Http\Resources;

use App\Models\TechnologyForDevelopmentDirection;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class TechnologyResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'courses' => CourseResource::collection(
                isset($request->take['courses']) ? $this->courses->take($request->take['courses']) : $this->courses
            ),
            'possessed_by' => UserBasicInformationResource::collection(
                isset($request->take['possessed_by']) ? $this->ownedBy()->take($request->take['possessed_by']) : $this->ownedBy()
            ),
        ];

        return ($request->show && is_array($request->show) && array_key_exists('technology', $request->show)) ?
            Arr::only($attributes, explode(',', $request->show['technology'])) :
            $attributes;
    }

    private function ownedBy()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            return $this->employees;
        }

        return $this->employees->whereIn('id', $user->employees->pluck('id'));
    }
}
