<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DirectSeller
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
        if (Auth::check() && (Auth::user()->type == 'admin' || Auth::user()->type == 'direct_selling')) {
            return $next($request);
        } else {
            return redirect()->route('website.index')->with(['message' => 'Sorry! You cannot access this.']);
        }
    }
}
