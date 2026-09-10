<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderStatusResource;
use App\Models\OrderStatus;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new OrderStatus();
    }

    public function GetOrderStatus($id)
    {
        return response( OrderStatusResource::collection($this->globalObject->GetOrderStatusByOrder($id))) ;
    }

}
