<?php

namespace App\Http\Resources\Dashboards;

use App\Http\Resources\RoadmapsResource;
use App\Http\Resources\UsersResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeDashboardResource extends JsonResource
{

    public function toArray($request)
    {
        return [
            'user' => new UsersResource($this->resource),
            'teams' => $this->when($request->is('api/dashboard/employees/*'), $this->teams->pluck('name')),
            'roadmaps' => RoadmapsResource::collection($this->roadmaps),
        ];
    }
}
