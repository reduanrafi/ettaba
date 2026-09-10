<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TopProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this[0]->name);
        return [
            'categoryName'=>$this->name,
            'products'=>new CategoryProductCollection($this->products)

        ];
    }
}
