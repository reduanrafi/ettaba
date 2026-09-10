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
use App\Services\CustomerGenerationCommissionDistributionService;
use App\Services\EarningStatisticsService;
use App\Services\MerchantCommissionDistributionService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

class TestController extends Controller
{
    public function _construct()
    {

    }


    public function index(Request $request)
    {
        //$products = Cache::get('test');
        $products =  Cache::remember("test", 15, function() {

            return Product::all();

        });

       dd($products);

    }
    public function GetCategoryProduct($categoryName)
    {
        //dd("categories.".$categoryName);
        return Product::where('category_id',config("categories.".$categoryName))->paginate(15);
    }
    public function GetMostPointedProducts($request)
    {
        $test  = DB::select(DB::raw('SELECT products.id, MAX(CONVERT(trp_en,DECIMAL (10,2)) )  as test FROM products WHERE is_deleted = 0 GROUP BY products.id ORDER BY test desc;'));
        $test = $this->arrayPaginator($test,$request);
        dd( $test);
    }
    public function arrayPaginator($array, $request)
    {
        $page = $request->page;
        $perPage = 2;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
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
