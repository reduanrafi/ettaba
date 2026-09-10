<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Wallet();
    }
    public function GetWallet()
    {
        return response(new WalletResource($this->globalObject->GetUserWallet()));
    }
}
