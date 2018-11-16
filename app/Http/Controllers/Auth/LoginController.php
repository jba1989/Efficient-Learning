<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;

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
    protected $redirectTo = '/index';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        preg_match_all('/\/index(.*)/', url()->previous(), $matches);
        
        if (isset ($matches[1][0])) {
            if (substr_count($matches[1][0], 'class') > 0) {
                session(['preUrl' => $matches[1][0]]);
            }
        }
    }
    
    public function redirectTo(){
        $preUrl = session('preUrl', '/index');
        session()->forget('preUrl');
        
        return 'index' . $preUrl;
    }

    public function logout(){
        Auth::logout();
        return redirect('/index');
    }
}
