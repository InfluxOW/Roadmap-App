<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 * @OA\Xml(name="UserResource"),
 * @OA\Property(property="name", type="string", readOnly="true", example="John Doe"),
 * @OA\Property(property="username", type="string", example="john_doe"),
 * @OA\Property(property="email", type="string", readOnly="true", format="email", example="john_doe@gmail.com"),
 * )
 *
 */
class UserResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
        ];
    }
}
