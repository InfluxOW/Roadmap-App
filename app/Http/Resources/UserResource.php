<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes = [
            'name' => $this->name,
            'username' => $this->username,
            'company' => $this->when(
                $request->user()->isAdmin() && ! $this->isAdmin(),
                function () {
                    return $this->company->name;
                }
            ),
            'email' => $this->email,
            'role' => $this->role,
            'sex' => $this->when(isset($this->sex), $this->sex),
            'birthday' => $this->when(
                isset($this->birthday),
                function () {
                    return $this->birthday->format('d-M-Y');
                }
            ),
            'position' => $this->when(isset($this->position), $this->position),
            'teams' => $this->when(
                isset($this->teams),
                function () {
                    return $this->teams->map(function ($team) {
                        return [
                            'name' => $team->name,
                            'slug' => $team->slug,
                        ];
                    });
                }
            ),
            'technologies' => $this->when(
                $this->isEmployee(),
                function () {
                    return $this->technologies->pluck('name');
                }
            ),
            'development_directions' => $this->when(
                $this->isEmployee(),
                function () use ($request) {
                    return isset($request->take['development_directions']) ?
                        $this->directions->take($request->take['development_directions'])->pluck('name') :
                        $this->directions->pluck('name');
                }
            ),
        ];

        return $request->show && is_array($request->show) && array_key_exists('user', $request->show) ?
            Arr::only($attributes, explode(',', $request->show['user'])) :
            $attributes;
    }
}
