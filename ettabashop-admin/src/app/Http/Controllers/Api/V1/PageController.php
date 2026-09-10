<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private $globalObject ;

    function __construct()
    {
        $this->globalObject = new Page();
    }

    public function GetPages()
    {
        $slider =  $this->globalObject->all();

        return response(PageResource::collection($slider));
    }

    public function SinglePage($slug)
    {
        $page = $this->globalObject->GetSinglePage($slug);
        if (!$page)
        {
            return response(['message'=>"page not found"],404);
        }
        return response( new PageResource($page));
    }
}
