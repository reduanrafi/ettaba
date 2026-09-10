<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function GetOffers(Request $request)
    {
        return response()->json([
            'status'=>'ok',
            'offers'=> 'list'
        ]);
    }
}
