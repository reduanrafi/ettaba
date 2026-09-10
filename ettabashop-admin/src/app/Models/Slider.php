<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    use CommonFunctions;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'title_en',
        'title_bn',
        'description_en',
        'description_bn',
        'image'
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
        if (isset($data['image'])){
            $data['image'] = $this->UploadImage($data['image'],'slider',500,450);
        }
        return $data;
    }

    /****************************
     * Public Methods area
     *****************************/
}
