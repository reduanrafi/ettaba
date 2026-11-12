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
    | This controller handles authenticating customers for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect customers after login.
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

    protected function authenticated(Request $request, $user)
    {
        if ($user->type === 'customer') {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'Customer accounts cannot log in to the Seller/Admin panel. Please log in via the customer website.'
            ]);
        }

        if (isset($user->is_blocked) && $user->is_blocked == 1) {
            Auth::logout();
            return redirect()->route('login')->withErrors([
                'email' => 'This account has been blocked by administration. Customer accounts remain unaffected.'
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('website.index');
    }

    /**
     * Attempt to log the user into the application.
     * Supports both email and phone number input.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        $loginValue = trim((string)$request->input('email'));
        $password = $request->input('password');
        $remember = $request->filled('remember');

        // Check if value is a valid email
        if (filter_var($loginValue, FILTER_VALIDATE_EMAIL)) {
            if ($this->guard()->attempt(['email' => $loginValue, 'password' => $password], $remember)) {
                return true;
            }
        }

        // Check digits for phone
        $cleanPhone = preg_replace('/[^0-9]/', '', $loginValue);
        if (!empty($cleanPhone)) {
            if ($this->guard()->attempt(['phone' => $cleanPhone, 'password' => $password], $remember)) {
                return true;
            }
            if (strlen($cleanPhone) === 10 && $this->guard()->attempt(['phone' => '0' . $cleanPhone, 'password' => $password], $remember)) {
                return true;
            }
            if (strlen($cleanPhone) === 11 && str_starts_with($cleanPhone, '0') && $this->guard()->attempt(['phone' => substr($cleanPhone, 1), 'password' => $password], $remember)) {
                return true;
            }
        }

        // Direct fallback attempts
        if ($this->guard()->attempt(['email' => $loginValue, 'password' => $password], $remember)) {
            return true;
        }
        if ($this->guard()->attempt(['phone' => $loginValue, 'password' => $password], $remember)) {
            return true;
        }

        return false;
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        $loginValue = trim((string)$request->input('email'));
        $field = filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        return [
            $field => $loginValue,
            'password' => $request->input('password')
        ];
    }
}
