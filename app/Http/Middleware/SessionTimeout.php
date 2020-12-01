<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class SessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */


    private $key_session_last_active = 'last_activity';
    private $list_except_path = ['login'];

    //Timeout
    private $time_out = 3000;

    private function determineLogout()
    {
        $route_name = request()->route()->getName();
        return $route_name == 'logout';
    }

    public function handle($request, Closure $next)
    {
        $path = $request->path();
        //unlog or login
        if (!auth()->check() || in_array($path, $this->list_except_path)) {
            return $next($request);
        }

        //session中有last_active
        if (session()->has($this->key_session_last_active)) {
            $time_decay = time() - session($this->key_session_last_active);
            if ($time_decay > $this->time_out) {
                Auth::logout();
                session()->forget($this->key_session_last_active);
                flash('Login timeout, please login again')->warning();
                return redirect('/login');
            }
        }

        //log out
        if ($this->determineLogout()) {
            session()->has($this->key_session_last_active) && session()->forget($this->key_session_last_active);
        } else {
            session()->put($this->key_session_last_active, time());
        }

        return $next($request);
    }
}
