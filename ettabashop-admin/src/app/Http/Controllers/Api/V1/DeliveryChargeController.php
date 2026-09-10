<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryChargeResource;
use App\Models\DeliveryCharge;
use Illuminate\Http\Request;

class DeliveryChargeController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new DeliveryCharge();
    }

    public function GetDeliveryCharges()
    {
        return response(DeliveryChargeResource::collection($this->globalObject->all()));
    }
}
