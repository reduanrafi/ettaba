<?php

namespace App\Http\Controllers\Website;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\TopTypeResource;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Slider;
use App\Models\TopType;
use App\Models\User;
use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function _construct()
    {

    }


    public function about()
    {
        return view('website.about');
    }

    public function privacy()
    {
        return view('website.legal.privacy');
    }
    public function terms()
    {
        return view('website.legal.terms');
    }


}
