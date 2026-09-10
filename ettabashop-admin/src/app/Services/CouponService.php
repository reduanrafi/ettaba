<?php


namespace App\Services;


use App\Models\UserCoupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CouponService
{
    function UserCoupon($coupon)
    {
        if (isset($coupon->coupon_id)) {
            $couponId = $coupon->coupon_id;

            $existingCoupon = $this->CheckCoupon($couponId);
            if ($existingCoupon != null) {
                $this->UpdateCouponFrequency($existingCoupon);
            } else {

                $this->SaveUserCoupon($couponId);

            }
        }
    }

    function CheckCoupon($couponId)
    {
        return UserCoupon::where('user_id', Auth::user()->id)->where('coupon_id', $couponId)->first();

    }

    function SaveUserCoupon($couponId)
    {
        $keywordObj = new UserCoupon();
        return UserCoupon::create([
            'user_id' => Auth::user()->id,
            'coupon_id' => $couponId,
            'frequency' => 1
        ]);
    }

    function UpdateCouponFrequency($existingCoupon)
    {
        return $existingCoupon->update(['frequency' => $existingCoupon->frequency + 1]);
    }
}