<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class UserBasicInformationResource extends JsonResource
{
    public function toArray($request)
    {
        $attributes =  [
            'name' => $this->name,
            'username' => $this->username,
            'company' => $this->when(
                ($request->user()->isAdmin() || $this->isManager()) && ! $request->user()->isEmployee(),
                $this->company->name
            )
        ];

        return ($request->show && is_array($request->show) && array_key_exists('user', $request->show)) ?
            Arr::only($attributes, explode(',', $request->show['user'])) :
            $attributes;
    }
}
