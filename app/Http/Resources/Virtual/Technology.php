<?php

namespace App\Http\Resources\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Technology")
 * )
 */
class Technology
{
    /**
     * @OA\Property()
     * @var string
     * @example PHP
     */
    public $name;

    /**
     * @OA\Property()
     * @var string
     * @example php
     */
    public $slug;

    /**
     * @OA\Property()
     * @var string
     * @example PHP is a general-purpose scripting language especially suited to web development.
     */
    public $description;

    /**
     * @OA\Property()
     * @var \App\Http\Resources\Virtual\Course[]
     */
    public $courses;

    /**
     * @OA\Property(@OA\Items(type="string"))
     * @var array
     * @example {"Backend", "Frontend"}
     */
    public $directions;

    /**
     * @OA\Property()
     * @var \App\Http\Resources\Virtual\UserBasicInformation[]
     */
    public $possessed_by;
}
