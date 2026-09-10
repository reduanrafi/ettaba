<?php


namespace App\Services;


use App\Models\User;
use Carbon\Carbon;

class UserService
{
    public function ReferralCode()
    {
        $code =  User::orderBy('id', 'desc')->Limit(1)->get('referral_code')[0]->referral_code;
        if ($code==null|| $code=='')
        {
            $code='etShY2213';
        }

        $staticString=substr($code,0,7);
        $lastDigit=substr($code,7);

        $updatedDigit = $lastDigit+1;

        return $staticString.''.$updatedDigit;
    }
    public  function UniqueId ()
    {
        $user =  User::orderBy('id','desc')->Limit(1)->first();
        if (!$user){
            return 130;
        }
        return '13' . ($user->id + 1);
    }

    public function getTheParent($data)
    {
        if (isset($data['referral_code'])){
            return User::where('referral_code',$data['referral_code'])->first()->id;
        }
        return 0;
    }

    public function getUserWithProfileByRefferalCode($code)
    {
        return User::with('profile')->where('referral_code', '=', $code)
            ->first();
    }

}
