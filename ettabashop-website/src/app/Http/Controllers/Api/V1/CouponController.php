<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Coupon();
    }
    public function CheckCoupon(Request $request)
    {  
        return response()->json($this->globalObject->CheckCoupon($request['code'],$request['amount']));
    }

    public function ApplyCoupon()
    {

    }
}
