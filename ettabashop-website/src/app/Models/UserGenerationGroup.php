<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGenerationGroup extends Model
{
    use HasFactory;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'user_id',
        'g1',
        'g2',
        'g3',
        'g4',
        'g5',
        'g6',
        'g7',
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
        return $data;
    }

    /****************************
     * Public Methods area
     *****************************/
}
