<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [];
    protected $table='divisions';
    /****************************
     * Model Relation area
     *****************************/

    public function district()
    {
        return $this->hasMany(  District::class);
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
