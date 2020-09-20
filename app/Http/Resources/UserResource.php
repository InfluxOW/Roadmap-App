<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

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
        $attributes = [
            'name' => $this->name,
            'username' => $this->username,
            'company' => $this->when($request->user()->isAdmin(), $this->company->name),
            'email' => $this->email,
            'role' => $this->role,
            'sex' => $this->when(isset($this->sex), $this->sex),
            'birthday' => $this->when(isset($this->birthday), $this->birthday->format('d-M-Y')),
            'position' => $this->when(isset($this->position), $this->position),
            'teams' => $this->teams->pluck('name'),
            'technologies' => $this->when(
                $this->isEmployee(),
                function () {
                    return $this->technologies->pluck('name');
                }
            ),
            'development_directions' => $this->when(
                $this->isEmployee(),
                function () {
                    return $this->directions->pluck('name');
                }
            ),
        ];

        return $request->show && is_array($request->show) && array_key_exists('user', $request->show) ?
            Arr::only($attributes, explode(',', $request->show['user'])) :
            $attributes;
    }
}
