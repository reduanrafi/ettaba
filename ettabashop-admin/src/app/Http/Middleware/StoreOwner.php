<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StoreOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && (Auth::user()->type == 'admin' ||Auth::user()->type == 'store_owner')) {
            return $next($request);
        }
        else{
            return redirect()->back()->with(['message'=>'Sorry ! you can not access this']);
        }
    }
}
