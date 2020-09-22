<?php

namespace App\Http\Resources\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Preset")
 * )
 */
class Preset
{
    /**
     * @OA\Property()
     * @var string
     * @example Awesome PHP preset
     */
    public $name;

    /**
     * @OA\Property()
     * @var string
     * @example awesome-php-preset
     */
    public $slug;

    /**
     * @OA\Property()
     * @var string
     * @example Best PHP preset you have ever tried!
     */
    public $description;

    /**
     * @OA\Property()
     * @var \App\Http\Resources\Virtual\UserBasicInformation
     */
    public $creator;

    /**
     * @OA\Property()
     * @var string
     * @example http://best-php-preset.com
     */
    public $link;

    /**
     * @OA\Property()
     * @var \App\Http\Resources\Virtual\Course[]
     */
    public $courses;

    /**
     * @OA\Property()
     * @var \App\Http\Resources\Virtual\UserBasicInformation[]
     */
    public $assigned_to;
}
