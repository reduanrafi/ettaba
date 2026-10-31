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

    protected $fillable = [
        'user_id',
        'nid',
        'first_name',
        'last_name',
        'father_name',
        'mother_name',
        'address',
        'union_name',
        'ward_no',
        'blood_group',
        'profile_image',
        //'nid_image',
        'date_of_birth'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GetData($data)
    {
        $data['user_id'] = Auth::user()->id;
        if (isset($data['profile_image']) && $data['profile_image']) {
            $uploaded = $this->UploadImage($data['profile_image'], 'profiles');
            if ($uploaded) {
                $data['profile_image'] = $uploaded;
            } else {
                unset($data['profile_image']);
            }
        } else {
            unset($data['profile_image']);
        }

        if (isset($data['nid_image']) && $data['nid_image']) {
            $uploadedNid = $this->UploadImage($data['nid_image'], 'nid');
            if ($uploadedNid) {
                $data['nid_image'] = $uploadedNid;
            } else {
                unset($data['nid_image']);
            }
        } else {
            unset($data['nid_image']);
        }
        return $data;
    }

    public function SaveOrUpdateProfile($data)
    {
        $result = false;
        $profile = $this->GetProfile();

        if ($profile != null) {
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
        return Profile::with('user', 'user.order')->where('user_id', Auth::user()->id)->first();
    }

    public function GetOrders()
    {
        return Order::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->get();
    }
}
