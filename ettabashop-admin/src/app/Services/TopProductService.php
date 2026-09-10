<?php


namespace App\Services;


use App\Models\Category;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\TopProduct;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TopProductService
{
    public function GetLatestProduct()
    {
        return Product::where('is_active', 1)
            ->where('is_deleted', 0)
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->orderby('id','desc')
            ->take(20)
            ->get();
    }

    public function GetMostRatedProducts()
    {
        return ProductReview::select('product_id as id', DB::raw('COUNT(product_id) as count'))
            ->where('is_deleted', 0)
            ->groupBy('product_id')
            ->orderBy('count', 'desc')
            ->take(20)
            ->get();
    }

    public function GetFeaturedProducts()
    {
        return Product::where('is_active', 1)
            ->where('is_deleted', 0)
            ->where('is_featured', 1)
            ->get();
    }

    public function GetDiscountedProducts()
    {
        return Product::where('discount','!=',null) ->where('is_deleted', 0)->limit(20)->get();
    }
    public function GetMostPointProducts()
    {
        return DB::select(DB::raw('SELECT products.id, MAX(CONVERT(trp_en,DECIMAL (10,2)) )  as test FROM products WHERE is_deleted = 0 GROUP BY products.id ORDER BY test desc LIMIT 20;'));


    }

    public function GetCategoryProduct($typeId)
    {
        $slug = config('catslugs.'.$typeId);

        $categories = $this->GetCategoryWithChildBySlug($slug);

        $categoryIds = $this->GetChildCategoryIds($categories);

        $products =  Product::whereIn('category_id',$categoryIds)->where('is_deleted',0)
            ->inrandomOrder()
            ->take(20)
            ->get();
        return $products;

    }

    public function SaveTopProducts($products, $typeId)
    {

        //dd($this->DeleteProducts($typeId));
        DB::transaction(function () use($typeId,$products) {
            $this->DeleteProducts($typeId);
            TopProduct::insert($this->MakeData($products,$typeId));
        });
    }

    public function MakeData($products,$typeId)
    {
        $topProducts = [];
        foreach ($products as $key=>$product) {
            $topProducts[$key]['product_id'] = $product->id ;
            $topProducts[$key]['type_id'] = $typeId;
            $topProducts[$key]['created_at'] = Carbon::now();
            $topProducts[$key]['updated_at'] = Carbon::now();
        }

        return $topProducts;
    }

    public function DeleteProducts($typeId)
    {
        $products = TopProduct::where('type_id',$typeId)->count();

        if ($products>0)
        {
            return DB::table('top_products')->where('type_id',$typeId)->delete();

        }
        return true;
    }

    public function GetCategoryWithChildBySlug($slug)
    {
        return Category::with('childrenCategories')
            ->where('is_deleted',0)
            ->where('slug',$slug)
            ->get();
    }

    public function GetChildCategoryIds($categories)
    {
        $ids =[] ;
        $subIds = [];
        $stubCategories = [];
        foreach ($categories as $k=>$category)
        {
            $ids[$k]=$category->id;
            if ($category->childrenCategories!=null)
            {

                foreach($category->childrenCategories as $key=>$cat)
                {

                    $subIds[$key]=$cat->id;

                    if ($cat->categories!=null)
                    {

                        foreach ($cat->categories as $j=>$sub)
                        {
                            $stubCategories[$j] = $sub->id;
                        }
                    }
                }
            }
        }

        $mainIds = array_merge($ids,array_merge($subIds,$stubCategories)) ;

        return $mainIds;
    }
}