<?php

namespace App\Models;

use App\Services\SearchService;
use App\Traits\CommonFunctions;
use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use CommonFunctions;
    use HasFactory;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'category_id',
        'owner_id',
        'brand_id',
        'name_bn',
        'name_en',
        'description_en',
        'description_bn',
        'featured_image',
        'sold_amount',
        'vat_percent',
        'discount',
        'price_en',
        'price_bn',
        'quantity',
        'unit',
        'is_sold_out',
        'is_featured',
        'slug'

    ];
    protected $appends = [
        'count',
        'quantity_unit',
        'image_with_base_url',

    ];
    public $product = [];

    /****************************
     * Model Relation area
     *****************************/

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function productDetail()
    {
        return $this->hasOne(ProductDetail::Class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::Class);
    }

    public function productReviews()
    {
        return $this->hasMany(ProductReview::Class);
    }

    public function topType()
    {
        return $this->belongsToMany('App\Models\TopType');
    }

    public function wishList()
    {
        return $this->belongsTo(WishList::class);
    }
    public function bundleItems()
    {
        return $this->belongsToMany('App\Models\Bundle');
    }
    /****************************
     * Public Methods area
     *****************************/

    /***
     * Method to get data.
     * @param $data
     * @return
     */

    public function GetData($data)
    {
        $data['owner_id'] = Auth::user()->id;

        //$data['is_sold_out'] = $this->GetCheckBoxValue($data,'is_sold_out');
        if (isset($data['featured_image'])) {
            $data['featured_image'] = $this->UploadImage($data['featured_image'], 'products',350,300);

        }
        return $data;
    }

    public function GetProducts($id)
    {

        return Product::where('products.is_deleted','=',0)->find($id);

    }
    public function ProductsGetBySearchKeywords($keywords)
    {
        return Product::query()
            ->where('is_deleted', 0)
            ->where(function ($query) use ($keywords) {
                $query->whereLike(['name_en', 'name_bn'], $keywords)
                      ->orWhere('unique_id', 'LIKE', "%{$keywords}%");
            })
            ->paginate(20);
    }

    public function ManageSearchKeywords($keywords)
    {
        $searchService = new  SearchService();
        $searchService->Keyword($keywords);
    }
    public function GetProductByCategory($id)
    {
        $product = Category::with('product')->find($id);

        return $product;
    }

    public function GetProductByBrand($id)
    {
        $product = Brand::with('products')->find($id);

        return $product;

    }

    public function GetProductDetail($slug)
    {
        //return Product::with('category', 'productImages', 'productReviews')->find($id);
        return Product::with('category','productImages' )->where('slug',$slug)->first();
    }

//    public static function all($keys = null)
//    {
//        $products = [];
//        if (Auth::user()->type=='store_owner')
//        {
//            $products = Product::where('is_deleted', 0)->where('owner_id',Auth::user()->id)->get();
//        }
//        else{
//            $products = Product::where('is_deleted', 0)->get();
//        }
//        return $products;
//    }
    public static function deletedProducts($keys = null)
    {
        return Product::where('is_deleted', 1)->get();
    }

    public static function destroy($keys = null)
    {
        return Product::where('id', $keys)->update(['is_deleted' => 1]);
    }

    private function ImageUpload($data)
    {
        if (isset($data['featured_image'])) {
            $this->category['featured_image'] = $this->UploadImage($data['featured_image'], 'products');

        }

    }

    private function GetBrandId($brandId)
    {
        return $brandId=="NULL"?NULL:$brandId;

    }
    public function MarkUnMarkFeatured($id)
    {
        $product = Product::find($id);
        $status = ($product->is_featured==1?0:1);

        return $product->update(['is_featured'=>$status]);
    }
    public function Restore($id)
    {
        return Product::where('id', $id)->update(['is_deleted' => 0]);
    }

    /****************************
     * Accessor Methods area
     *****************************/

    public function getCountAttribute()
    {
        return 0;
    }

    public function getImageWithBaseUrlAttribute() {
        return url('/') .'/'. $this->featured_image;
    }

    public function getQuantityUnitAttribute()
    {
        if(!$this->quantity) return false;
        if(!$this->unit) return false;
        return "{$this->quantity} {$this->unit}";
    }


    public function MostPointProducts($request)
    {
        $products =  DB::select(DB::raw('SELECT *, MAX(CONVERT(trp_en,DECIMAL (10,2)) )  as test FROM products WHERE is_deleted = 0 GROUP BY products.id ORDER BY test desc;'));

        return $this->arrayPaginator($products,$request);
    }


    public function arrayPaginator($array, $request)
    {

        $page = ($request->page==null?1:$request->page);
        $perPage = 20;
        $offset = ($page * $perPage) - $perPage;

        return new LengthAwarePaginator(array_slice($array, $offset, $perPage, true), count($array), $perPage, $page,
            ['path' => $request->url(), 'query' => $request->query()]);
    }
}
