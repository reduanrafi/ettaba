<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model
{
    /****************************
     * Property area
     *****************************/
    protected $fillable = [];

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
        return $data;
    }

    /****************************
     * Public Methods area
     *****************************/
}
