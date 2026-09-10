<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [];

    /****************************
     * Model Relation area
     *****************************/

    public function division()
    {
        return $this->belongsTo(  Division::class);
    }
    public function upazila()
    {
        return $this->hasMany(  Upazila::class);
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
