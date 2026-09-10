<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'fullName' => $this->first_name . ' ' . $this->last_name,
            'address' => $this->address,
            'phone' => $this->user->phone,
            'avatar' => url('/').'/'.$this->profile_image,
            'blood' => $this->blood_group,
            'dob' => $this->date_of_birth,
            'join' => $this->created_at->format('Y-m-d H:i A')
        ];
    }
}
