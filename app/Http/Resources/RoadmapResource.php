<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class RoadmapResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes = [
            'preset' => (new PresetResource($this->preset))
                ->additional(
                    ['employee' => new UserResource($this->employee)]
                ),
            'assigned_at' => $this->assigned_at->format('d-M-Y H:i:s T')
        ];

        return ($request->show && array_key_exists('roadmap', $request->show)) ?
            Arr::only($attributes, explode(',', $request->show['roadmap']))
            : $attributes;
    }
}
