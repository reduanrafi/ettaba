<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategoryProductResource extends JsonResource
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
            'slug'=>$this->slug,
            'name'=> [
                'en' => $this->name_en,
                'bn' => $this->name_bn,
            ],
            'icon'=> url('/') .'/'.$this->icon,
            'banner'=> url('/').'/'.$this->image,
            'products'=> ProductResource::collection($this->products)
        ];
    }
}
