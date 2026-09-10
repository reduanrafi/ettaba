<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TopProduct extends Model
{
    use HasFactory;
    protected $fillable=['product_id','type_id'];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'top_products',
            'type_id', 'product_id')->where('products.is_deleted',0);
    }
    public function pt(){
        return $this->belongsToMany(Product::class,'top_products','product_id');
    }
    public function UpdateTopProducts($data)
    {

        $typeId = $data['id'];
        if ($typeId==1)
        {
            $this->SaveTopProducts($this->GetLatestProduct(),$typeId);
        }
        elseif ($typeId==2)
        {
            $this->SaveTopProducts($this->GetFeaturedProducts(),$typeId);
        }
        elseif ($typeId==3)
        {
            $this->SaveTopProducts($this->GetMostRatedProducts(),$typeId);
        }
        elseif ($typeId==4)
        {
            $this->SaveTopProducts($this->GetDiscountedProducts(),$typeId);
        }



    }

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

    public function SaveTopProducts($products, $typeId)
    {
        //dd($products);
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
}
