<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\Division;
use App\Models\Upazila;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
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
    protected $redirectTo = "/profile/index";
    protected $parent;
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

    public function __construct(UserService $userService)
    {
        $this->middleware('guest');
        $this->userService = $userService;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function register(Request $request)
    {
        if ($request->has('phone')) {
            $cleanedPhone = preg_replace('/[^a-zA-Z0-9]/', '', (string)$request->phone);
            $request->merge(['phone' => $cleanedPhone]);
        }

        $this->validator($request->all())->validate();

        $parent = null;
        if ($request->customer_type !== 'direct_selling') {
            $parent = $this->userService->getTheParent($request->referral_code);

            if ($parent == null) {
                return redirect()->back()->withInput()->with(['error' => 'Invalid referral code']);
            } else if ($parent->is_active == 0) {
                return redirect()->back()->withInput()->with(['error' => 'The referrer is inactive']);
            }

            $customerType = $request->customer_type;
            if ($customerType == 'buy_earn') {
                $partnerCount = \App\Models\User::where('parent_id', $parent->id)->where('customer_type', 'buy_earn')->count();
                if ($parent->partner_limit <= $partnerCount) {
                    return redirect()->back()->withInput()->with(['error' => 'This referral code has reached its partner limit. Try another code.']);
                }
            } else if ($customerType == 'buy_only') {
                $customerCount = \App\Models\User::where('parent_id', $parent->id)->where('customer_type', 'buy_only')->count();
                if ($parent->customer_limit <= $customerCount) {
                    return redirect()->back()->withInput()->with(['error' => 'This referral code has reached its customer limit. Try another code.']);
                }
            }
        }

        $request['parent_id'] = $parent != null ? $parent->id : NULL;
        $request['parent'] = $parent;

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'alpha_num', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'customer_type' => ['required'],
            'account_number' => ['required_if:customer_type,buy_earn', 'nullable', 'string', 'in:first,subsequent'],
            //            'district_id' => [ 'required'],
//            'division_id' => [ 'required'],
//            'upazila_id' => [ 'required'],

        ], [
            'phone.required' => 'মোবাইল নাম্বার বা ইউজার আইডি দেওয়া আবশ্যক।',
            'phone.alpha_num' => 'মোবাইল নাম্বার বা আইডিতে কোনো স্পেস বা চিহ্ন (+, -, স্পেস ইত্যাদি) দেওয়া যাবে না। শুধু ইংরেজি সংখ্যা ও লেটার ব্যবহার করুন।',
            'phone.unique' => 'এই মোবাইল নাম্বার/আইডিটি ইতিমধ্যে ব্যবহার করা হয়েছে।',
        ]);

    }


    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // dd($data);
        $service = new UserService();

        $referralLimit = 0;

        $uniqueId = $service->UniqueId();

        $parentId = $data['parent_id'];

        $cleanPhone = preg_replace('/[^a-zA-Z0-9]/', '', (string)$data['phone']);

        if ($data['customer_type'] == 'buy_earn') {

            $referralLimit = 1;
        }

        $user = User::create([
            'name' => $data['name'],
            //            'division_id' => $data['division_id'],
//            'district_id' => $data['district_id'],
//            'upazila_id' => $data['upazila_id'],
            //'email' => $data['email'],
            'phone' => $cleanPhone,
            'type' => $data['type'],
            'referral_code' => "created",
            'referral_limit' => $referralLimit,
                        'is_active' => 1,
            'is_approved' => 1,

            'customer_type' => $data['customer_type'],
            'account_number' => $data['account_number'] ?? null,
            'unique_id' => 'created',
            'parent_id' => ($parentId != 0 ? $parentId : NULL),
            'password' => Hash::make($data['password']),
        ]);

        if ($user) {
            if (isset($data['referral_code']) && $parentId != null) {
                // $service->referralBonus($parentId,$user->id);
                $service->UpdateReferralCount($data['parent']);

            }
        }
        return $user;


    }

    public function showRegistrationForm()
    {
        //        $division = Division::all();
//        $district = District::all();
//        $upazila = Upazila::all();

        return view('auth.register', [
            //            'divisions' => $division,
//            'districts' => $district,
//            'upazilas' => $upazila
        ]);
    }
}
