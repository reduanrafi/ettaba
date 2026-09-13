<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Services\UserService;
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
    | This controller handles the registration of new customers as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect customers after registration.
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
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'alpha_num', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'type' => ['required', 'string', 'in:store_owner,store_administrator,direct_selling'],
        ];

        if (isset($data['type']) && $data['type'] == 'store_administrator') {
            $rules['referral_code'] = ['required', 'string', 'exists:users,referral_code'];
            if (!empty($data['email'])) {
                $rules['email'] = ['string', 'email', 'max:255', 'unique:users'];
            }
        } else {
            $rules['email'] = ['required', 'string', 'email', 'max:255', 'unique:users'];
        }

        return Validator::make($data, $rules, [
            'phone.required' => 'The phone number / ID is required.',
            'phone.alpha_num' => 'The phone number / ID must contain only letters and numbers without any spaces or symbols (+, -, space, etc.).',
            'phone.unique' => 'This phone number / ID has already been taken.',
            'referral_code.exists' => 'The referral code is invalid.',
        ]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function register(\Illuminate\Http\Request $request)
    {
        if ($request->has('phone')) {
            $cleanedPhone = preg_replace('/[^a-zA-Z0-9]/', '', (string)$request->phone);
            $request->merge(['phone' => $cleanedPhone]);
        }

        $this->validator($request->all())->validate();

        if ($request->type == 'store_administrator') {
            $parent = User::where('referral_code', $request->referral_code)->first();
            if (!$parent) {
                return redirect()->back()->withInput()->withErrors(['referral_code' => 'Invalid referral code']);
            }
            if ($parent->is_active == 0) {
                return redirect()->back()->withInput()->withErrors(['referral_code' => 'The referrer is inactive']);
            }
            $merchantCount = \App\Models\MerchantReferral::where('referrer_id', $parent->id)->count();
            if ($parent->merchant_limit <= $merchantCount) {
                return redirect()->back()->withInput()->withErrors(['referral_code' => 'This referral code has reached its merchant limit. Try another code.']);
            }
        }

        event(new \Illuminate\Auth\Events\Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new \Illuminate\Http\JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $service = new UserService();

        $parentId = $service->getTheParent($data);
        //dd($parentId);

        $cleanPhone = preg_replace('/[^a-zA-Z0-9]/', '', (string)$data['phone']);
        $email = !empty($data['email']) ? $data['email'] : $cleanPhone . '@ettaba.com';

        $user = User::create([
            'name' => $data['name'],
            'email' => $email,
            'phone' => $cleanPhone,
            'type' => $data['type'],
            'customer_type' => $data['type'] == 'direct_selling' ? 'direct_selling' : null,
            'unique_id' => 0, // temporary, will be updated by observer
            'parent_id' => ($parentId != 0 ? $parentId : null),
            'referral_code' => 'temp', // temporary, will be updated by observer
            'password' => Hash::make($data['password']),
        ]);

        if ($user && $data['type'] == 'store_administrator' && $parentId != 0) {
            \App\Models\MerchantReferral::create([
                'merchant_id' => $user->id,
                'referrer_id' => $parentId,
            ]);
        }

        return $user;
    }

}
