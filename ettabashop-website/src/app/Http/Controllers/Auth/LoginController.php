<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

//    public function login(Request $request)
//    {
//        $credentials = $request->only('phone', 'password');
//        $remember_me = $request->has('remember') ? true : false;
//        //dd($remember_me);
//        if (Auth::attempt($credentials,$remember_me)) {
//            $user = auth()->user();
//            //dd($user);
//            Auth::login($user,$remember_me);
//
//            return redirect()->intended('/');
//        }
//    }

    protected function credentials(Request $request)
    {
        $loginValue = $request->input($this->username());
        $cleanPhone = preg_replace('/[^0-9]/', '', (string)$loginValue);

        return [
            'phone' => $cleanPhone ?: $loginValue,
            'password' => $request->input('password'),
            'type' => 'customer',
        ];
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->is_blocked == 1) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                $this->username() => 'আপনার কাস্টমার একাউন্টটি সাময়িকভাবে ব্লক করা হয়েছে। অনুগ্রহ করে কর্তৃপক্ষের সাথে যোগাযোগ করুন।'
            ]);
        }
    }

    public function logout(Request $request)
    {
//        Auth::logout();
        Auth::logoutCurrentDevice(); // use this instead of Auth::logout()

//        request()->session()->invalidate();
//
//        request()->session()->regenerateToken();
        return redirect()->route('website.index');
    }

    public function username()
    {
        return 'phone';
    }
}
