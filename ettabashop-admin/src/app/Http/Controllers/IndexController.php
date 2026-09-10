<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request){
        //dd($request->all());
        return view('index');
    }

    public function detail(Request $request){
        //dd($request->all());
        return view('detail');
    }
}
