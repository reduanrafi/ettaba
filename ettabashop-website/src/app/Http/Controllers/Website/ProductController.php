<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Product;
use App\Models\TopType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function CategoryProducts(Request $request)
    {
        $category = new Category();
        $categories = $category->GetCategoryWithChildBySlug($request->slug);
        //dd($categories);
        $categoryIds = $category->GetChildCategoryIds($categories);
        $products = Product::whereIn('category_id',$categoryIds)->where('is_deleted',0)->paginate(18);


        $parents = $category->ParentCategories();
        $parentCategory = Category::where('slug',$request->slug)->first();

        $categoryBySlug = $category->ChildCategories($parentCategory->id);

        //dd($products);

        //$products = $category->GetProductsByCategory($request->slug);

        return view('website.category_products', [

            'products' => $products,
            'parent' => $parentCategory,
            'parents' => $parents,
            'categories' => $categoryBySlug

        ]);
    }

    public  function AllProducts(Request $request)
    {
        $category = new Category();
        $topProduct = new TopType();

        $product = new Product();
        //
        if ($request->type=="mostPoint")
        {
            $products = $product->MostPointProducts($request);

        }
        else if($request->type=="featured")
        {
            $products = Product::where('is_featured',1)->where('is_deleted',0)->paginate(20);
        }
        else if($request->type=="new")
        {
            $products = Product::orderBy('id','desc')->where('is_deleted',0)->paginate(20);
        }

        else{
            $products = $topProduct->GetCategoryProduct($request->type);
        }
        if ($products==null)
        {
            return redirect()->back();
        }

        $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        //dd($products);
        return view('website.all_products', [
            'products' => $products,
            'categories' => $categories
        ]);

    }



}
