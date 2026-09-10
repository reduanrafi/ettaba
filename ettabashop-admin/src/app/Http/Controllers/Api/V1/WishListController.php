<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\WishListResource;
use App\Models\WishList;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WishListController extends Controller
{
    private $globalObject;
    private $moduleName = "WishList";
    private $singularVariableName = 'wishList';
    private $pluralVariableName = 'wishLists';
    private $message;
    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new WishList();
    }

    public function Store(Request $request)
    {
        //dd($request->validated());
        $userId = Auth::user()->id;
        $productId = $request->product_id;
        $rules = [
            'product_id'=>'required'
        ];


        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            if ($this->globalObject->CheckWishList($userId,$productId)==true)
            {
                $this->message = 'This product is already in your wish list';
            }
            else {
                $object = $this->globalObject->create($this->globalObject->GetData($request->all()));
                try {
                    if ($object) {
                        $this->message = $this->moduleName . ' saved Successfully';
                    }
                } catch (QueryException $ex) {
                    $this->message = $ex->getMessage();
                }
            }

            return response()->json([
                'status' => 'ok',
                'message' => $this->message,

            ]);

        }
        else {

            return response($validator->errors()->all(), 422);
        }
    }

    public function GetWishListsByUser()
    {
        $userId = Auth::user()->id;
        return response( WishListResource::collection($this->globalObject->GetWishListByUserId($userId)), 200);
    }
}
