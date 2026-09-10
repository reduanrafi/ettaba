<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class WishListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this->id);
        return [
            'id'=>$this->id,
            'user_id'=>$this->user_id,
            'addedAt'=>$this->created_at,
            'product_detail'=> new ProductResource($this->products)
        ];
    }
}
