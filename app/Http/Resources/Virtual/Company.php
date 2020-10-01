<?php

namespace App\Http\Resources\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Company")
 * )
 */
class Company
{
    /**
     * @OA\Property()
     * @var string
     * @example https://google.com
     */
    public $website;

    /**
     * @OA\Property()
     * @var string
     * @example Google
     */
    public $name;

    /**
     * @OA\Property()
     * @var string
     * @example Search System
     */
    public $description;

    /**
     * @OA\Property()
     * @var integer
     * @example 1998
     */
    public $foundation_year;

    /**
     * @OA\Property()
     * @var string
     * @example Internet
     */
    public $industry;

    /**
     * @OA\Property()
     * @var string
     * @example 1600 Amphitheatre Parkway, Mountain View, California, U.S.
     */
    public $location;
}
