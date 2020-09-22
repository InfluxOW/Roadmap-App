<?php

namespace App\Http\Resources\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Course")
 * )
 */
class Course
{
    /**
     * @OA\Property()
     * @var string
     * @example Best PHP course
     */
    public $name;

    /**
     * @OA\Property()
     * @var string
     * @example best-php-course
     */
    public $slug;

    /**
     * @OA\Property()
     * @var string
     * @example Best PHP course you've ever seen!
     */
    public $description;

    /**
     * @OA\Property()
     * @var string
     * @example http://best-php-course.com
     */
    public $source;

    /**
     * @OA\Property()
     * @var string
     * @example Junior
     */
    public $level;

    /**
     * @OA\Property()
     * @var string
     * @example http://localhost:8000/api/courses/awesome-php-course
     */
    public $link;

    /**
     * @OA\Property()
     * @var int
     * @example 6
     */
    public $average_rating;

    /**
     * @OA\Property(@OA\Items(type="string"))
     * @var array
     * @example {"PHP", "Laravel"}
     */
    public $technologies;

    /**
     * @OA\Property()
     * @var \App\Http\Resources\Virtual\UserBasicInformation
     */
    public $completed_by;

    /**
     * @OA\Property()
     * @var \DateTime
     * @example 2020-09-18 14:33:19
     */
    public $completed_at;

    /**
     * @OA\Property()
     * @var int
     * @example 10
     */
    public $employee_rating;

    /**
     * @OA\Property()
     * @var string
     * @example http://best-php-course.com/certificate.jpg
     */
    public $certificate;
}
