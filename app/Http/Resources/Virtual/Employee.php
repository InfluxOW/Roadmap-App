<?php

namespace App\Http\Resources\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="Employee")
 * )
 */
class Employee
{
    /**
     * @OA\Property()
     * @var string
     * @example John Doe
     */
    public $name;

    /**
     * @OA\Property()
     * @var string
     * @example john_doe
     */
    public $username;

    /**
     * @OA\Property()
     * @var string
     * @example John Doe Inc.
     */
    public $company;

    /**
     * @OA\Property()
     * @var string
     * @example john_doe@gmail.com
     */
    public $email;

    /**
     * @OA\Property()
     * @var string
     * @example employee
     */
    public $role;

    /**
     * @OA\Property(enum={"male", "female"})
     * @var string
     */
    public $sex;

    /**
     * @OA\Property()
     * @var \DateTime
     * @example 2020-09-18 14:33:19
     */
    public $birthday;

    /**
     * @OA\Property()
     * @var string
     * @example PHP Developer
     */
    public $position;

    /**
     * @OA\Property(@OA\Items(type="string"))
     * @var array
     * @example {"Winners", "Losers"}
     */
    public $teams;

    /**
     * @OA\Property(@OA\Items(type="string"))
     * @var array
     * @example {"PHP", "Laravel"}
     */
    public $technologies;

    /**
     * @OA\Property(@OA\Items(type="string"))
     * @var array
     * @example {"Backend", "Frontend"}
     */
    public $development_directions;
}
