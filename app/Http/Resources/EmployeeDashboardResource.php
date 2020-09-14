<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeDashboardResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'user' => new UsersResource($this->resource),
            'teams' => $this->teams->pluck('name'),
            'roadmaps' => RoadmapsResource::collection($this->roadmaps),
        ];
    }
}
