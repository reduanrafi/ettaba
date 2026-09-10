<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{
    private $message;
    private $moduleName = "Profile";
    private $singularVariableName = 'profile';
    private $pluralVariableName = 'profiles';
    private $globalObject;

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new User();
    }

    public function GetUser()
    {

        $user  = $this->globalObject->GetUser();

        if(!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }
       return response(new UserResource($user), 200);
    }

}
