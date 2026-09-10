<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class SalesStuff
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
        // this is moderator middleware
        if (Auth::check() && (Auth::user()->type == 'admin' ||Auth::user()->type == 'sales_stuff')) {
            return $next($request);
        }
        else{
            return redirect()->back()->with(['message'=>'Sorry ! you can not access this']);
        }
    }
}
