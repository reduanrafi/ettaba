<?php

namespace App\Http\Controllers\website;

use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use App\Models\Slider;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $slider = Slider::all();
        $products = Product::all();
        $featured = Product::orderBy('id','desc')->limit(3)->get();

        return view('website.index',[
                'sliders'=>$slider,
                'products'=>$products,
                'featuredProducts'=>$featured
            ]);
    }

    public function detail($id)
    {
        $product = Product::where('id',$id)->first();
        return view('website.detail',['product'=>$product]);
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
