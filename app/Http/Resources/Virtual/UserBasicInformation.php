<?php

namespace App\Http\Resources\Virtual;

/**
 * @OA\Schema(
 *   @OA\Xml(name="UserBasicInformation")
 * )
 */
class UserBasicInformation
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
}
