<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BundleResource;
use App\Models\Bundle;
use Illuminate\Http\Request;

class BundleController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Bundle();
    }
    public function Bundles()
    {
        return response(BundleResource::collection($this->globalObject->GetBundles()),200);
    }
}
