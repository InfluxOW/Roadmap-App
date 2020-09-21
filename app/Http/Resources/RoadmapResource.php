<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

/**
 *
 * @OA\Schema(
 * @OA\Xml(name="RoadmapResource"),
 * @OA\Property(property="preset", type="array", @OA\Items(type="array", @OA\Items(type="string"), ref="#/components/schemas/CourseResource")),
 * @OA\Property(property="assigned_at", type="string", format="date-time", example="2020-09-18 14:33:19"),
 * @OA\Property(property="assigned_by", type="array", @OA\Items(type="array", @OA\Items(type="string"), ref="#/components/schemas/UserBasicInformationResource")),
 * )
 *
 */
class RoadmapResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes = [
            'preset' => (new PresetResource($this->preset))
                ->additional(
                    ['employee' => new UserResource($this->employee)]
                ),
            'assigned_by' => new UserBasicInformationResource($this->manager),
            'assigned_at' => $this->assigned_at->format('d-M-Y H:i:s T')
        ];

        return ($request->show && is_array($request->show) && array_key_exists('roadmap', $request->show)) ?
            Arr::only($attributes, explode(',', $request->show['roadmap'])) :
            $attributes;
    }
}
