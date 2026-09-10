<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Slider();
    }

    public function GetSliders()
    {
        $slider =  Slider::all();
        return response(SliderResource::collection($slider));
    }
}
