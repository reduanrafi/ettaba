<?php

namespace App\Http\Controllers\Website;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\TopTypeResource;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Shop;
use App\Models\Slider;
use App\Models\TopType;
use App\Models\User;
use App\Services\ProductService;
use Facades\App\Services\EarningStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $productService = new ProductService();

        $statistics = [];

        $shops = Shop::paginate(10);

        $category = new Category();

        $topProduct = new TopType();


       // dd($statistics);
        $slider = Slider::all();

        $categories = CategoryResource::collection($category->GetCategoriesWithChild());



        $products = $productService->GetHomeProducts();

        if (Auth::user()) {
            $statistics = EarningStatisticsService::EarningStatistics(Auth::user()->id);
        }
        //dd(Auth::user()->id);

       //dd($statistics);

        return view('website.index', [
            'sliders' => $slider,
            'categories' => $categories,
            'shops'=>$shops,
            'statistics'=>$statistics,

            'featured' => isset($products['featured'])?$products['featured']:[],
            'newArrival' => isset($products['new'])?$products['new']:[],
            'mostPoint' => isset($products['mostPoint'])?$products['mostPoint']:[],
            'dailyNeeds'=>isset($products['dailyNeeds'])?$products['dailyNeeds']:[],
            'fashion'=>isset($products['fashion'])?$products['fashion']:[],
            'library'=>isset($products['library'])?$products['library']:[],
            'jewelry'=>isset($products['jewelry'])?$products['jewelry']:[],
            'electronicGoods'=>isset($products['electronicGoods'])?$products['electronicGoods']:[],
            'electronicAccessories'=>isset($products['electronicAccessories'])?$products['electronicAccessories']:[],
        ]);
    }

    public function detail(Request $request)
    {
        $category = new Category();
        $productObj = new Product();
        $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        $product = $productObj->GetProductDetail($request->slug);
        //dd($product);
        return view('website.detail', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function products(Request $request)
    {
        $category = new Category();

        $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        $categoryBySlug = $category->ChildCategories(Category::where('slug',$request->slug)->first()->id);
        //dd($categoryBySlug);
        $products = $category->GetProductsByCategory($request->slug);
       // dd($products);
        return view('website.products', [

            'products' => $products,
            'categories' => $categories

        ]);
    }

    public function search(Request $request)
    {
        $category = new Category();

        $product = new Product();

        $categories = CategoryResource::collection($category->GetCategoriesWithChild());

        $products = $product->ProductsGetBySearchKeywords($request->keywords);

        //dd($products);
        return view('website.search', [
            'products' => $products,
            'categories' => $categories
        ]);
    }

    public function profile($id)
    {
        $category = new Category();
        $categories = CategoryResource::collection($category->GetCategoriesWithChild());
        $product = Product::where('id', $id)->first();
        //dd($product);
        return view('website.profile', [
            'product' => $product,
            'categories' => $categories
        ]);
    }

    public function about()
    {
        return view('website.about');
    }


    public function contact()
    {
        return view('website.contact');
    }
}
