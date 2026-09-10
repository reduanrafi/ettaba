<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryChargeResource extends JsonResource
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
            'id'=>$this->id,
            'chargeAmount'=>$this->charge_amount,
            'minimumAmount'=>$this->minimum_amount,
            'maximumAmount'=>$this->maximum_amount
        ];
    }
}
