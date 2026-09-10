<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductRequestInsertRequest;
use App\Models\ProductRequest;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductRequestController extends Controller
{
    private $globalObject;
    private $moduleName = "ProductRequest";
    private $singularVariableName = 'productRequest';
    private $pluralVariableName = 'productRequests';
    private $message;
    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new ProductRequest();
    }

    public function Store(Request $request)
    {
        //dd($request->validated());
        $rules = [
            'title'=>'required',
            'phone'=>'required'
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $object = $this->globalObject->create($this->globalObject->GetData($request->all()));
            try {
                if ($object) {
                    $this->message =  'Your request saved Successfully';
                }
            } catch (QueryException $ex) {
                $this->message = $ex->getMessage();
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
}
