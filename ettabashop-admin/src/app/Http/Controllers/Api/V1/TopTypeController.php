<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TopType;
use App\Http\Resources\TopTypeResource;
class TopTypeController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new TopType();
    }
    public function TopTypes() {
        return response($this->globalObject::all(['id', 'name']),200);
    }
    public function Products($id)
    {
        return response(new TopTypeResource($this->globalObject->TopProducts($id)),200);
    }
}
