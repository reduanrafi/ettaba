<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TopType extends Model
{
    protected $fillable=['name'];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'top_products',
            'type_id', 'product_id')->where('products.is_deleted',0);
    }
    public function TopTypes()
    {
        return TopType::all();
    }
    public function TopProducts($id)
    {
        $products =  TopType::with(['products'])->limit(10)->find($id);

        if ($products!=null)
        {
            return $products;
        }

        return null;

    }

    public function MostPointProducts()
    {
        return DB::select(DB::raw('SELECT *, MAX(CONVERT(trp_en,DECIMAL (10,2)) )  as test FROM products WHERE is_deleted = 0 GROUP BY products.id ORDER BY test desc LIMIT 20;'));


    }

    public function GetHomeProducts()
    {

        $products =  DB::select(DB::raw('
                        
                        SELECT * FROM top_products tp
                        LEFT JOIN products p on p.id = tp.product_id 
                
                '));

        dd($products);



    }
    public function GetCategoryProduct($categorySlug)
    {

        $category = new Category();
        $categories = $category->GetCategoryWithChildBySlug($categorySlug);

        $categoryIds = $category->GetChildCategoryIds($categories);

        $products =  Product::whereIn('category_id',$categoryIds)->where('is_deleted',0)->paginate(12);


        return $products;

    }

}
