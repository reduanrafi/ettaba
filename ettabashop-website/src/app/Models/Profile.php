<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Profile extends Model
{
    use HasFactory;
    use CommonFunctions;

    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'user_id',
        'nid',
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'address',
        'blood_group',
        'profile_image',
        //'nid_image',
        'date_of_birth'
    ];

    /****************************
     * Model Relation area
     *****************************/

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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
        if (isset($data['profile_image']))
        {
            $data['profile_image'] = $this->UploadImage($data['profile_image'], 'profiles');

        }
        if (isset($data['nid_image']))
        {
            $data['nid_image'] = $this->UploadImage($data['nid_image'], 'nid');

        }
        return $data;
    }

    public function SaveOrUpdateProfile($data)
    {
        $result = false;
        $profile = $this->GetProfile();

        if ($profile != null) {
            //dd($profile);
            if ($profile->update($data)) {
                $result = true;
            }
        } else {

            if (Profile::create($data)) {
                $result = true;
            }
        }
        return $result;
    }

    public function GetProfile()
    {
        return Profile::with('user','user.order')->where('user_id',Auth::user()->id)->first();
    }

    public function GetOrders()
    {
        return Order::where('user_id',Auth::user()->id)->orderBy('id','desc')->get();
    }
    /****************************
     * Public Methods area
     *****************************/
}
