<?php


namespace App\Services;


use App\Models\Product;
use Carbon\Carbon;

class ProductService
{
    function CheckSlug($slug)
    {
        $product =  Product::where('slug',$slug)->first();

        if($product!=null)
        {
            return 1;
        }
        return 0;
    }

    public function GenerateSlug($productTitle)
    {
        return str_replace(' ', '-', $productTitle);
    }

    public function UpdateSlug(Product $product)
    {
        $slug = $this->GenerateSlug($product->name_en);

        if ($this->CheckSlug($slug)!=0)
        {
            $slug = $slug.$product->id;
        }

        return $product->update(['slug'=>$slug]);
    }
    public function UpdateUniqueID($product)
    {
        $uniqueId = '14'.$product->id;
        return $product->update(['unique_id'=>$uniqueId]);
    }
}