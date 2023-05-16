<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticateController extends Controller
{
    // login web service

    /**
     * authenticate user for components of SCHOOL web site.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $ipAddress = $request->ip();
        // only request from ip address XXX is allowed.
        $realip = $request->header('X-ORIGINAL-FORWARDED-FOR','0.0.0.0');
        //if ($realip != "")

        if ($request->has(['username','password'])) {
            $name = $request->input('username');
            $pass = $request->input('password');
            //return [$name, $pass];
            // validate user access rights
            $credentials = [
                    'email' => $name,
                    'password' => $pass,
                    'is_activity' => 1
            ];
            if (Auth::attempt($credentials)) {
                return response('OK!', 200)
                  ->header('Content-Type', 'text/plain');
            } else {
                return response('Illegal user!', 403)
                  ->header('Content-Type', 'text/plain');
            }
        } else {
            return response('Illegal access!', 400)
                  ->header('Content-Type', 'text/plain');
        }
 
        //
    }
}
