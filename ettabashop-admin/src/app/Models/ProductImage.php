<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory; use CommonFunctions;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'product_id',
        'image'
    ];

    /****************************
     * Model Relation area
     *****************************/

    public function product()
    {
        return $this->belongsTo(  Product::class, 'product_id');
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
        if (isset($data['image'])) {
            $data['image'] = $this->UploadImage($data['image'], 'products',500,500);

        }
        return $data;
    }

    public function GetImagesByProductId($id)
    {
        return ProductImage::where('product_id',$id)->get();
    }

    /****************************
     * Public Methods area
     *****************************/
}
