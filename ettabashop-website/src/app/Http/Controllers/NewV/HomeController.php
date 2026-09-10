<?php

namespace App\Http\Controllers\NewV;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home(){
        return view('new.Home');
    }
}
