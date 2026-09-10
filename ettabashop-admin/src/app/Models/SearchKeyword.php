<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SearchKeyword extends Model
{
    /****************************
     * Property area
     *****************************/
    protected $fillable = ['title','frequency'];

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

    public function GetSearchKeywords($kewords)
    {
        return SearchKeyword::where('title','like','%'.$kewords.'%')->orderby('frequency','desc')->get();
    }



    /****************************
     * Public Methods area
     *****************************/
}
