<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;

class BanOperation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            if (Auth::user()->email == 'admin@123.com') {
                return $next($request);
            } else {
                return abort('403');
            }
        } else {
            return redirect('/login');
        }
    }
}
