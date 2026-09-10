<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    use CommonFunctions;
    use HasFactory;

    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'user_id',
        'unique_id',
        'slug',
        'name_en',
        'name_bn',
        'description_en',
        'description_bn',
        'mobile',
        'address_en',
        'address_bn',

        'banner_image',
        'logo_image',

        'sales',
        'reviews',
        'ratings',
        'fb_link',
        'youtube_link',
    ];

    /****************************
     * Model Relation area
     *****************************/

    public function user()
    {
        return $this->belongsTo(  User::class, 'user_id');
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
        $data['user_id'] = Auth::user()->id;
        $data['unique_id'] = $this->GetUniqueShopId();

        //$data['is_sold_out'] = $this->GetCheckBoxValue($data,'is_sold_out');
        if (isset($data['logo_image'])) {
            $data['logo_image'] = $this->UploadImage($data['logo_image'], 'shops',200,200);

        }
        if (isset($data['banner_image'])) {
            $data['banner_image'] = $this->UploadImage($data['banner_image'], 'shops',360,400);

        }

        return $data;
    }

    public function GetShop($id = 0)
    {
        $userId = ($id>0?$id:Auth::user()->id);
        return Shop::where('user_id',$userId)->first();
    }


    /****************************
     * Private Methods area
     *****************************/

    private function GetUniqueShopId()
    {
        $existingShop = $this->GetShop();
        if (!$existingShop) {
            $shop = Shop::orderBy('id', 'desc')->first();
            if ($shop != null) {
                return $shop->unique_id + 1;
            } else {
                return 15786;
            }
        }
        return $existingShop['unique_id'];
    }
}
