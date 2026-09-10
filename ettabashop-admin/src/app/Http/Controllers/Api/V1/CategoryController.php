<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use DemeterChain\C;
use Illuminate\Http\Request;
use App\Http\Resources\CategoryProductResource;
use App\Http\Resources\CategoryResource;
class CategoryController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Category();
    }
    public function GetCategories(Request $request)
    {
        return response (CategoryResource::collection($this->globalObject->GetCategoriesWithChild()), 200);
    }

    public function GetProductsByCategory($slug) {

        return response(new CategoryProductResource($this->globalObject->GetProductsByCategory($slug)), 200);
    }
}
