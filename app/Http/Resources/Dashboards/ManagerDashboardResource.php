<?php

namespace App\Http\Resources\Dashboards;

use App\Http\Resources\UsersResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ManagerDashboardResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'user' => new UsersResource($this->resource),
//            'teams' => $this->teams->pluck('name'),
//            'owned_teams' => $this->ownedTeams->pluck('name'),
            'employees' => EmployeeDashboardResource::collection($this->employees),
        ];
    }
}
