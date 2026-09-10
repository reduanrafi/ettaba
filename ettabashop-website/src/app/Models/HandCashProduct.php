<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class HandCashProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'owner_id',
        'brand_id',
        'unique_id',
        'name',
        'short_name',
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
        'direct_refer_commission',
        'quantity',
        'unit',
        'is_sold_out',
        'is_featured',
        'slug'

    ];
//    protected $appends = [
//        'count',
//        'quantity_unit',
//        'image_with_base_url',
//
//    ];
    public $product = [];

    /****************************
     * Model Relation area
     *****************************/

    public function category()
    {
        return $this->belongsTo(HandCashCategory::class, 'category_id');
    }


    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
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

        $data['trp_en'] = max(0,$this->CalculateTRP($data));
        $data['trp_bn'] = $data['trp_en'];
        $data['tcb_en'] = max(0, $this->CalculateTCB($data, $data['trp_en']));

        //$data['is_sold_out'] = $this->GetCheckBoxValue($data,'is_sold_out');


        return $data;
    }

    private function CalculateTRP($data)
    {
        $trp =  ($data['erp_en']-($data['rate_en']+$data['cb_en']))/25;
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
}
