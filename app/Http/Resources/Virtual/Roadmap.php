<?php

namespace App\Http\Resources\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Roadmap")
 * )
 */
class Roadmap
{
    /**
     * @OA\Property()
     * @var \App\Http\Resources\Virtual\Preset
     */
    public $preset;

    /**
     * @OA\Property()
     * @var \DateTime
     * @example 2020-09-18 14:33:19
     */
    public $assigned_at;

    /**
     * @OA\Property()
     * @var \App\Http\Resources\Virtual\UserBasicInformation
     */
    public $assigned_by;
}
