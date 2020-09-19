<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserBasicInformationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'company' => $this->when(
                $request->user()->isAdmin() || $this->isManager(),
                $this->company->name
            )
        ];
    }
}
