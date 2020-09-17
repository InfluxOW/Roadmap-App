<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoadmapsResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'preset' => (new PresetsResource($this->preset))
                ->additional(
                    ['employee' => new UsersResource($this->employee)]
                ),
            'assigned_at' => $this->assigned_at->format('d-M-Y H:i:s T')
        ];
    }
}
