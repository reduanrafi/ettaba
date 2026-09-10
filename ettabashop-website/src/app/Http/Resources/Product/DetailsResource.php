<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Resources\Json\JsonResource;

class DetailsResource extends JsonResource
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
            'id' => $this->id,
            'name' => [
                'en' => $this->name_en,
                'bn' => $this->name_bn,
            ],
            'unit' => $this->quantity_unit,
            'price' => [
                'en' => $this->price_en,
                'bn' => $this->price_bn
            ],
            'discount' => $this->discount,
            'description' => [
                'en' => $this->description_en,
                'bn' => $this->description_bn
            ],
            'image' => $this->image_with_base_url,
            'images' => PhotoResource::collection($this->productImages),
            'category' => new CategoryResource($this->category),
            'reviews' => ReviewResource::collection($this->productReviews),
            'count' => $this->count,
        ];
    }
}
