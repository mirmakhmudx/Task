<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'last_name' => $this->last_name,
            'email'     => $this->email,
            'phone'     => $this->phone,
            'status'    => $this->status,
            'role'      => $this->role,
        ];
    }
}
