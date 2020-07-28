<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use App\Labs;
use Closure;

class BanLabsUpdate
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
            $user = Auth::user();
            $labID = $request->input('labID');
            $isPI = Labs::where([['id', $labID], ['principleInvestigator', $user->name]])->get()->count() > 0;
            if ($user->email == 'admin@123.com' || $isPI) {
                return $next($request);
            } else {
                return abort(403);
            }
        } else {
            return abort(403);
        }
    }
}
