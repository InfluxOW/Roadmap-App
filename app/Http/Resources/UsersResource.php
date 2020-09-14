<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 *
 * @OA\Schema(
 * @OA\Xml(name="UsersResource"),
 * @OA\Property(property="name", type="string", readOnly="true", example="John Doe"),
 * @OA\Property(property="username", type="string", example="john_doe"),
 * @OA\Property(property="email", type="string", readOnly="true", format="email", example="john_doe@gmail.com"),
 * )
 *
 */
class UsersResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'company' => $this->company->name,
            'sex' => $this->when(isset($this->sex), $this->sex),
            'birthday' => $this->when(isset($this->birthday), $this->birthday->format('d-M-Y')),
            'position' => $this->when(isset($this->position), $this->position),
        ];
    }
}
