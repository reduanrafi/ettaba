<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Address;
use App\Http\Controllers\Controller;
use App\Http\Resources\AddressResource;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private $message;
    private $moduleName = "Address";
    private $singularVariableName = 'address';
    private $pluralVariableName = 'addresses';
    private $globalObject;

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new Address();
    }

    public function SaveAddress(Request $request)
    {
        $object = $this->globalObject->create($this->globalObject->GetData($request->all()));
        try {
            if ($object) {
                $this->message = $this->moduleName . ' saved Successfully';
            }
        } catch (QueryException $ex) {
            $this->message = $ex->getMessage();
        }

        return response()->json([
            'status' => 'ok',
            'message' => $this->message,
            'address' => new AddressResource($object)
        ]);
    }

    public function GetAddress()
    {
        $addresses = $this->globalObject->GetUserAddress();
        if ($addresses->count() <= 0) {
            return response()->json([
                'message' => 'Address not found'
            ], 404);
        }
        return response(AddressResource::collection($addresses), 200);
    }

    public function DeleteAddress($id)
    {

        if ($this->globalObject->DeleteUserAddress($id)) {
            return response()->json([
                'message' => 'Address Deleted Successfully'
            ], 200);
        }
        return response()->json([
            'message' => 'Unable to delete !w'
        ], 404);

    }
}
