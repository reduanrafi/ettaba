<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this['user']->id,
            'name' => $this['user']->name,
            'email' => $this['user']->email,
            'joinDate' => $this['user']->created_at,
            'token' => $this['token']->plainTextToken
        ];
    }
}
