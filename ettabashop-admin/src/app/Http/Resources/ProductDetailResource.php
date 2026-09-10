<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //dd($this);
        if ($this['product']!=null){
            return [
                'id' => $this['product']->id,
                'productName_en' => $this['product']->name_en,
                'productName_bn' => $this['product']->name_bn,
                'photo' => $this['product']->featured_image,
                'price_en' => $this['product']->price_en,
                'price_bn' => $this['product']->price_bn,
                'taxPercent' => $this['product']->tax_percent,
                'vatPercent' => $this['product']->vat_percent,
                'discountPercent' => $this['product']->discount_percent,
                'createdDate' => $this['product']->created_at,
                'categoryName' => new ProductCategoryResource($this['product']->category),
                'productImages ' => new ProductImageResource($this['product']->productImages),

            ];
        }

    }
}
