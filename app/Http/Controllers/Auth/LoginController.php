<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'account';
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string',
            'captcha' => 'required|captcha',
            'password' => 'required|string',
        ], [
            'captcha.required' => 'The captcha field is required.',
            'captcha.captcha' => 'The captcha you entered is wrong.',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        return collect(['name', 'email'])->contains(function ($value) use ($request) {
            $account = $request->get($this->username());
            $password = $request->get('password');
            return $this->guard()->attempt(
                [
                    $value => $account,
                    'password' => $password,
                    'is_activity' => 1
                ]
                , $request->filled('remember'));
        });
    }

}
