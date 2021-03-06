<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
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
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'captcha' => ['required', 'captcha'],
        ], [
            'captcha.required' => 'The captcha field is required.',
            'captcha.captcha' => 'The captcha you entered is wrong.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'activity_token'=>\Str::random(60),
            'activity_expire'=>Carbon::now()->addDays(1),
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();
        $user = $this->create($request->all());
        //send e-mail
        \Mail::raw(
        'Please click the link '.route('user.activity',['token'=>$user->activity_token]).' to activate your account within '. $user->activity_expire
        ,function($message) use($user){
            $message->from(env('MAIL_FROM_ADDRESS'),env('APP_NAME','scgs-web'))
            ->subject('activate e-mail')
            ->to($user->email);
        });
        //activate tips
        return view('auth.registed',['user'=>$user]);
    }

    function activity($token){
        $user = User::where('activity_token',$token)->get();
        $res = false;
        if($user && strtotime($user[0]->activity_expire)>time())
        {
            $user[0]->is_activity = 1;
            $res = $user[0]->save();
        }else{
            $user[0]->delete();
        }
        return view('auth.activityres',['res'=>$res]);
    }

}
