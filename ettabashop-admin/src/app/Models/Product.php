<?php

namespace App\Models;

use App\Services\SearchService;
use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
        'unique_id',
        'name_bn',
        'name_en',
        'description_en',
        'description_bn',
        'delivery_area_en',
        'delivery_area_bn',
        'featured_image',
        'sold_amount',
        'vat_percent',
        'discount',
        'rate_en',
        'rate_bn',

        'mrp_en',
        'mrp_bn',

        'erp_en',
        'erp_bn',

        'cb_en',
        'cb_bn',

        'tcb_en',
        'tcb_bn',

        'trp_en',
        'trp_bn',

        'quantity',
        'unit',
        'direct_refer_commission',
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

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
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

        if (!isset($data['id'])){

            $data['owner_id'] = Auth::user()->id;
        }

        $data['trp_en'] = $this->CalculateTRP($data);
        $data['trp_bn'] = $data['trp_en'];
        $data['tcb_en'] =$this->CalculateTCB($data,$data['trp_en']);

        //$data['is_sold_out'] = $this->GetCheckBoxValue($data,'is_sold_out');
        if (isset($data['featured_image'])) {
            $data['featured_image'] = $this->UploadImage($data['featured_image'], 'products',500,500);

        }

        return $data;
    }

    private function CalculateTRP($data)
    {
        $directRefer = isset($data['direct_refer_commission']) ? $data['direct_refer_commission'] : 0;
        $trp =  ($data['erp_en']-($data['rate_en']+$data['cb_en'] + $directRefer))/25;
        return number_format((float)$trp,2,'.','');

    }
    private function CalculateTCB($data,$trp)
    {
        $tcb =   $data['cb_en']+ ($trp*2);
        return number_format((float)$tcb,2,'.',',');

    }
    private function GetUniqueProductId()
    {

            $product = Product::orderBy('id', 'desc')->first();
            if ($product != null) {
                return $product->unique_id + 1;
            } else {
                return 14786;
            }


    }
    public function CheckShopOwnerEligibilityToAddProducts()
    {
        $user = User::with('shop')->where('users.id',Auth::user()->id)->first();
        if ($user->type=='store_owner')
        {
            if ($user->shop==null)
            {
                return 0;
            }
        }
        return 1;
    }

    public function GetProducts($id)
    {

        return Product::where('products.is_deleted','=',0)->find($id);

    }
    public function ProductsGetBySearchKeywords($keywords)
    {
//
//            return Product::where('is_deleted',0)
//                ->where(function ($query)use($keywords){
//                    $query->where('name_en',"LIKE","%{$keywords}%")->orwhere('name_bn',"LIKE","%{$keywords}%");
//                }
//                )->toSql();

        return Product::query()
            ->where('is_deleted', '=',0)
            ->whereLike(['name_en', 'name_bn'], $keywords)
            ->get();


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
        return Product::with('category', 'productImages', 'productReviews')->where('slug',$slug)->first();
    }

    public static function all($keys = null)
    {
        $products = [];
        if (Auth::user()->type=='store_owner')
        {
            $products = Product::where('is_deleted', 0)->where('owner_id',Auth::user()->id)->get();
        }
        else{
            $products = Product::where('is_deleted', 0)->get();
        }
        return $products;
    }
    public static function deletedProducts($keys = null)
    {
        if (Auth::user()->type=='store_owner')
        {
             return Product::where('is_deleted', 1)->where('owner_id',Auth::user()->id)->get();
        }
        return Product::where('is_deleted', 1)->get();
    }

    public static function Hide($keys = null)
    {
        return Product::where('id', $keys)->update(['is_deleted' => 1]);
    }
    public static function UnHide($keys = null)
    {
        return Product::where('id', $keys)->update(['is_deleted' => 0]);
    }

    public function GetDiscountedProducts()
    {
        return Product::where('discount','!=',null) ->where('is_deleted', 0)->limit(100)->get();
    }

    public function GetFeaturedProducts()
    {
        return Product::where('is_featured','=',1) ->where('is_deleted', 0)->limit(100)->get();
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
}
