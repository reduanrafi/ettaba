<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryProductCollection;
use App\Http\Resources\CategoryProductResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\TopProductResource;
use App\Http\Resources\Product\DetailsResource;
use App\Http\Resources\TopTypeResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Product();
    }
    public function TodayOffers()
    {
        return response(ProductResource::collection(($this->globalObject->GetDiscountedProducts())),200);
    }
    public function Products($id)
    {

      // No need more, 
      //  return response($this->globalObject->GetProducts($id)) ;

      //  dd(Product::find(1));
        return ProductResource(Product::with(['category', 'productImages'])->find($id));
        
      //  return response(new TopProductResource($this->globalObject->GetProducts($id)),200);
    }
    public function GetLatestProducts()
    {
        $response = [
            'categoryName'=>"Latest Product",
            'products'=>$this->globalObject->GetLatestProduct()
        ];

        return response(new ProductCollection($response),200);
    }

    public function GetTopRatedProducts()
    {
        $response = [
            'categoryName'=>"Top Rated Product",
            'products'=>$this->globalObject->GetLatestProduct()
        ];

        return response(new ProductCollection($response),200);

    }

    public function GetFeaturedProducts()
    {
        return response()->json([
            'status'=>'ok',
            'featuredProducts'=> 'list'
        ]);
    }

    public function GetProductsByCategory(Request $request,$id)
    {
        // No need more, 
        //dd($this->globalObject->GetProductByCategory($id));
        $response = [
            'category'=>$this->globalObject->GetProductByCategory($id)
        ];

        return response(new CategoryProductResource($response),200);
    }

    public function Search(Request $request,$keywords)
    {
        //$this->globalObject->ManageSearchKeywords($keywords);
        $response = [
            'categoryName'=>"Searched Product",
            'products'=>$this->globalObject->ProductsGetBySearchKeywords($keywords)
        ];

        return response(ProductResource::collection($this->globalObject->ProductsGetBySearchKeywords($keywords)));
    }

    public function GetProductDetail($slug)
    {
        $product = $this->globalObject->GetProductDetail($slug);
        if ($product)
        {
            return response(new DetailsResource($product),200);

        }
        else{
            return response('Not Found',404);
        }

    }
}
