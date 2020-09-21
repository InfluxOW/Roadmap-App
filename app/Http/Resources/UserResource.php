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
 * @OA\Property(property="company", type="string", example="John Doe Inc."),
 * @OA\Property(property="email", type="string", example="john_doe@gmail.com"),
 * @OA\Property(property="role", type="string", example="employee"),
 * @OA\Property(property="sex", type="string", example="male"),
 * @OA\Property(property="birthday", type="string", example="24.04.1991"),
 * @OA\Property(property="position", type="string", example="Fullstack PHP+Vue Developer"),
 * @OA\Property(property="teams", type="array", @OA\Items(type="string"), example={"Winners", "Losers"}),
 * @OA\Property(property="technologies", type="array", @OA\Items(type="string"), example={"PHP", "Laravel"}),
 * @OA\Property(property="development_directions", type="array", @OA\Items(type="string"), example={"Backend", "Frontend"}),
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
