<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class TopProductCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $data=[];
        if (count($this->collection)>0) {
            foreach ($this->collection as $key => $product) {
                $data[$key]['id'] = $product->id;
                $data[$key]['productName_en'] = $product->name_en;
                $data[$key]['productName_bn'] = $product->name_bn;
                $data[$key]['photo'] = $product->featured_image;
                $data[$key]['price_en'] = $product->price_en;
                $data[$key]['price_bn'] = $product->price_bn;
                $data[$key]['taxPercent'] = $product->tax_percent;
                $data[$key]['vatPercent'] = $product->vat_percent;
                $data[$key]['discountPercent'] = $product->discount_percent;
                $data[$key]['createdDate'] = $product->created_at;
                $data[$key]['count'] = 0;
                $data[$key]['unit'] = 'unit';

            }
        }
        return  $data;
    }
}
