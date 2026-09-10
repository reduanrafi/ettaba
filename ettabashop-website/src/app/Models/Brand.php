<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use CommonFunctions;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'icon',
        'promotional_image',
        'slug',
    ];

    /****************************
     * Model Relation area
     *****************************/

    public function products()
    {
        return $this->hasMany(  Product::class)->where('is_deleted',0);
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

        if (isset($data['logo']))
        {
            $data['logo']= $this->UploadImage($data['logo'],'brand_logos');

        }
        return $data;
    }

    /****************************
     * Public Methods area
     *****************************/

    public function GetProductByBrand($slug)
    {
        $product = Brand::with('products')->where('brands.slug',$slug)->first();

        return $product;

    }

    public function getLogoAttribute($value) {
        return url('/').'/'.$value;
    }
}
