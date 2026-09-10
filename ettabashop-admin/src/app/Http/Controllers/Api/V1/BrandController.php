<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\BrandResource as BrandProductResource;
use App\Http\Resources\BrandResource as BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Brand();
    }
    public function GetBrands()
    {
       
        return response(BrandResource::collection($this->globalObject->all()));
    }
    public function GetProductsByCategory($id) {

        return response(new BrandProductResource($this->globalObject->GetProductByBrand($id)), 200);
    }
}
