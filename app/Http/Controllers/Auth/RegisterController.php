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
            'captcha.required' => 'Captcha can\'t be none',
            'captcha.captcha' => 'Incorrent captcha',
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
        //发送邮件
        \Mail::raw(
        '请在'.$user->activity_expire.'前,点击链接激活您的账号'.route('user.activity',['token'=>$user->activity_token])
        ,function($message) use($user){
            $message->from('13854291622@163.com','scgs-web')
            ->subject('注册激活邮件')
            ->to($user->email);
        });
        //显示注册激活提示信息
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
