<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'product_id',
        'review_message',
        'rating',
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
        return $data;
    }

    /****************************
     * Public Methods area
     *****************************/
}
