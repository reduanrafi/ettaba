<?php

namespace App\Http\Controllers\NewV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function details($id){
        return view('new.products.details');
    }
}
