<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\District;
use App\Models\Division;
use App\Models\Profile;
use App\Models\Upazila;
use App\Models\User;
use App\Models\UserGenerationGroup;
use App\Providers\RouteServiceProvider;
use App\Services\CustomerGenerationCommissionGroupService;
use App\Services\UserService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = "/profile/index";
    protected $userService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('guest')->except(['getDistricts', 'getUpazilas', 'checkUsername', 'checkEid']);
        $this->userService = $userService;
    }

    /**
     * Show registration form with divisions list.
     */
    public function showRegistrationForm()
    {
        $divisions = Division::select('id', 'name', 'bn_name')->orderBy('bn_name')->get();
        return view('auth.register', compact('divisions'));
    }

    /**
     * Get districts for a division.
     */
    public function getDistricts($division_id)
    {
        $districts = District::where('division_id', $division_id)
            ->select('id', 'name', 'bn_name')
            ->orderBy('bn_name')
            ->get();
        return response()->json($districts);
    }

    /**
     * Get upazilas for a district.
     */
    public function getUpazilas($district_id)
    {
        $upazilas = Upazila::where('district_id', $district_id)
            ->select('id', 'name', 'bn_name')
            ->orderBy('bn_name')
            ->get();
        return response()->json($upazilas);
    }

    /**
     * Real-time username / phone uniqueness check.
     */
    public function checkUsername(Request $request)
    {
        $username = preg_replace('/[^a-zA-Z0-9]/', '', (string)$request->get('username', ''));

        if (strlen($username) < 5) {
            return response()->json([
                'valid' => false,
                'message' => 'ইউজার নেম কমপক্ষে ৫টি সংখ্যা বা লেটার হতে হবে।'
            ]);
        }

        $exists = User::where('phone', $username)->exists();
        if ($exists) {
            return response()->json([
                'valid' => false,
                'message' => 'এই ইউজার নেম/মোবাইল নম্বরটি ইতিমধ্যে ব্যবহার করা হয়েছে।'
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => 'ইউজার নেমটি ব্যবহারযোগ্য।'
        ]);
    }

    /**
     * Real-time referral eID check.
     */
    public function checkEid(Request $request)
    {
        $eid = trim($request->get('eid', ''));

        if (!preg_match('/^13[0-9]+$/', $eid)) {
            return response()->json([
                'valid' => false,
                'message' => 'আপনি যে eID লিখেছেন, সেটি সঠিক ফরম্যাটের নয়। অনুগ্রহ করে একটি সঠিক eID দিয়ে পুনরায় চেষ্টা করুন।'
            ]);
        }

        $user = User::where('referral_code', $eid)->orWhere('unique_id', $eid)->first();
        if (!$user) {
            return response()->json([
                'valid' => false,
                'message' => 'এই eID এর কোনো সক্রিয় একাউন্ট পাওয়া যায়নি।'
            ]);
        }

        if ($user->is_active == 0) {
            return response()->json([
                'valid' => false,
                'message' => 'এই রেফারকারী অ্যাকাউন্টটি বর্তমানে নিষ্ক্রিয় রয়েছে।'
            ]);
        }

        return response()->json([
            'valid' => true,
            'name' => $user->name,
            'message' => 'সঠিক eID (' . $user->name . ')'
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                'regex:/^[\p{Bengali}a-zA-Z\s]+$/u'
            ],
            'phone' => [
                'required',
                'string',
                'alpha_num',
                'min:5',
                'max:255',
                'unique:users,phone'
            ],
            'customer_type' => ['required', 'in:buy_only,buy_earn'],
            'account_number' => ['required_if:customer_type,buy_earn', 'nullable', 'string', 'in:first,subsequent'],
            'nid' => ['required_if:customer_type,buy_earn', 'nullable', 'numeric'],
            'date_of_birth' => ['required_if:customer_type,buy_earn', 'nullable', 'date'],
            'father_name' => ['nullable', 'string', 'max:255'],
            'mother_name' => ['nullable', 'string', 'max:255'],
            'blood_group' => ['nullable', 'string', 'in:A+,A-,B+,B-,O+,O-,AB+,AB-'],
            'password' => ['required', 'string', 'min:8', 'max:32', 'confirmed'],
            'division_id' => ['required', 'integer'],
            'district_id' => ['required', 'integer'],
            'upazila_id' => ['required', 'integer'],
            'union_name' => ['required', 'string', 'max:255'],
            'ward_no' => ['required', 'string', 'max:50'],
        ], [
            'name.required' => 'আপনার পুরো নাম লিখুন।',
            'name.min' => 'নাম কমপক্ষে ২টি বর্ণের হতে হবে।',
            'name.regex' => 'নামে শুধু বাংলা অথবা ইংরেজি বর্ণ ব্যবহার করুন (কোনো সংখ্যা বা চিহ্ন নয়)।',
            'phone.required' => 'মোবাইল নম্বর/ইউজার নেম দেওয়া আবশ্যক।',
            'phone.alpha_num' => 'শুধু ইংরেজি সংখ্যা ও ইংরেজি লেটার লেখা যাবে।',
            'phone.min' => 'মোবাইল নম্বর/ইউজার নেম কমপক্ষে ৫টি অক্ষরের হতে হবে।',
            'phone.unique' => 'এই মোবাইল নম্বর/ইউজার নেমটি ইতিমধ্যে ব্যবহার করা হয়েছে।',
            'customer_type.required' => 'একাউন্টের ধরন নির্বাচন করুন।',
            'account_number.required_if' => 'পার্টনার একাউন্টের জন্য কত নম্বর একাউন্ট তা নির্বাচন করুন।',
            'nid.required_if' => 'পার্টনার একাউন্টের জন্য জাতীয় পরিচয়পত্র (NID) নম্বর আবশ্যক।',
            'nid.numeric' => 'NID নম্বরে শুধু ইংরেজি সংখ্যা ব্যবহার করুন।',
            'date_of_birth.required_if' => 'পার্টনার একাউন্টের জন্য জন্মতারিখ নির্বাচন করুন।',
            'division_id.required' => 'বিভাগ নির্বাচন করুন।',
            'district_id.required' => 'জেলা নির্বাচন করুন।',
            'upazila_id.required' => 'উপজেলা/সিটি কর্পোরেশন নির্বাচন করুন।',
            'union_name.required' => 'পৌরসভা/ইউনিয়ন লিখুন বা নির্বাচন করুন।',
            'ward_no.required' => 'ওয়ার্ড নম্বর নির্বাচন করুন।',
            'password.required' => 'পাসওয়ার্ড প্রদান করুন।',
            'password.min' => 'পাসওয়ার্ড কমপক্ষে ৮ বর্ণ/সংখ্যার হতে হবে।',
            'password.max' => 'পাসওয়ার্ড সর্বোচ্চ ৩২ বর্ণ/সংখ্যার হতে হবে।',
            'password.confirmed' => 'পাসওয়ার্ড কনফার্মেশন মেলেনি।',
        ]);
    }

    /**
     * Handle a registration request.
     */
    public function register(Request $request)
    {
        // 1. Sanitize phone / username
        if ($request->has('phone')) {
            $cleanedPhone = preg_replace('/[^a-zA-Z0-9]/', '', (string)$request->phone);
            $request->merge(['phone' => $cleanedPhone]);
        }

        // 2. Adjust referral codes based on customer_type
        $customerType = $request->input('customer_type', 'buy_only');

        if ($customerType === 'buy_only') {
            // If Customer account has referral_code_2 filled, use it as referral_code
            $referralCode = $request->input('referral_code_2') ?: $request->input('referral_code');
            if (empty($referralCode)) {
                // Default fallback to company referrer 134 if none provided
                $referralCode = '134';
            }
            $request->merge([
                'referral_code' => $referralCode,
                'referral_code_2' => $referralCode,
            ]);
        } else {
            // For Partner account: placement is referral_code, sponsor is referral_code_2
            if (!$request->filled('referral_code_2') && $request->filled('referral_code')) {
                $request->merge(['referral_code_2' => $request->referral_code]);
            }
        }

        // 3. Validate eID format (must start with 13 and be numeric)
        if ($request->filled('referral_code')) {
            $refCode = (string)$request->referral_code;
            if (!preg_match('/^13[0-9]+$/', $refCode)) {
                $msg = 'আপনি যে eID লিখেছেন, সেটি সঠিক ফরম্যাটের নয়। অনুগ্রহ করে একটি সঠিক eID দিয়ে পুনরায় চেষ্টা করুন।';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg, 'errors' => ['referral_code' => [$msg]]], 422);
                }
                return redirect()->back()->withInput()->with(['error' => $msg]);
            }
        }

        if ($request->filled('referral_code_2')) {
            $refCode2 = (string)$request->referral_code_2;
            if (!preg_match('/^13[0-9]+$/', $refCode2)) {
                $msg = 'আপনি যে eID লিখেছেন, সেটি সঠিক ফরম্যাটের নয়। অনুগ্রহ করে একটি সঠিক eID দিয়ে পুনরায় চেষ্টা করুন।';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg, 'errors' => ['referral_code_2' => [$msg]]], 422);
                }
                return redirect()->back()->withInput()->with(['error' => $msg]);
            }
        }

        // 4. Validate input fields
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // 5. Verify Parent / Placement
        $parent = null;
        if ($request->filled('referral_code')) {
            $parent = $this->userService->getTheParent($request->referral_code);

            if ($parent == null) {
                $msg = 'প্লেসমেন্ট eID সঠিক নয় বা সিস্টেমে খুঁজে পাওয়া যায়নি।';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->withInput()->with(['error' => $msg]);
            } else if ($parent->is_active == 0) {
                $msg = 'রেফারকারী একাউন্টটি বর্তমানে নিষ্ক্রিয় রয়েছে।';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->withInput()->with(['error' => $msg]);
            }

            // Check limits if enforced (> 0)
            if ($customerType == 'buy_earn') {
                $partnerCount = User::where('parent_id', $parent->id)->where('customer_type', 'buy_earn')->count();
                if ($parent->partner_limit > 0 && $parent->partner_limit <= $partnerCount) {
                    $msg = 'এই রেফার কোডের পার্টনার লিমিট পূর্ণ হয়ে গেছে। অনুগ্রহ করে অন্য কোড ব্যবহার করুন।';
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => $msg], 422);
                    }
                    return redirect()->back()->withInput()->with(['error' => $msg]);
                }
            } else if ($customerType == 'buy_only') {
                $customerCount = User::where('parent_id', $parent->id)->where('customer_type', 'buy_only')->count();
                if ($parent->customer_limit > 0 && $parent->customer_limit <= $customerCount) {
                    $msg = 'এই রেফার কোডের কাস্টমার লিমিট পূর্ণ হয়ে গেছে। অনুগ্রহ করে অন্য কোড ব্যবহার করুন।';
                    if ($request->wantsJson() || $request->ajax()) {
                        return response()->json(['success' => false, 'message' => $msg], 422);
                    }
                    return redirect()->back()->withInput()->with(['error' => $msg]);
                }
            }
        }

        $request['parent_id'] = $parent != null ? $parent->id : null;
        $request['parent'] = $parent;

        // 6. Verify Sponsor
        $sponsor = null;
        if ($request->filled('referral_code_2')) {
            $sponsor = $this->userService->getTheParent($request->referral_code_2);
            if ($sponsor == null) {
                $msg = 'রেফার eID সঠিক নয় বা খুঁজে পাওয়া যায়নি।';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->withInput()->with(['error' => $msg]);
            } else if ($sponsor->is_active == 0) {
                $msg = 'রেফার eID নিষ্ক্রিয় রয়েছে।';
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['success' => false, 'message' => $msg], 422);
                }
                return redirect()->back()->withInput()->with(['error' => $msg]);
            }
        }

        $request['sponsor_id'] = $sponsor != null ? $sponsor->id : null;

        // 7. Create User & Profile in Database Transaction
        try {
            DB::beginTransaction();

            $user = $this->create($request->all(), $request);

            event(new Registered($user));

            $this->guard()->login($user);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $msg = 'রেজিস্ট্রেশন সম্পন্ন করতে সমস্যা হয়েছে: ' . $e->getMessage();
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $msg], 500);
            }
            return redirect()->back()->withInput()->with(['error' => $msg]);
        }

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'অভিনন্দন! আপনার একাউন্ট সফলভাবে তৈরি হয়েছে।',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'unique_id' => $user->unique_id ?: ('13' . $user->id),
                    'phone' => $user->phone
                ],
                'home_url' => route('website.index'),
                'profile_url' => route('user.profile')
            ], 201);
        }

        return redirect($this->redirectPath());
    }

    /**
     * Create a new user and corresponding profile and address instances.
     */
    protected function create(array $data, Request $request = null)
    {
        $referralLimit = ($data['customer_type'] == 'buy_earn') ? 1 : 0;
        $cleanPhone = preg_replace('/[^a-zA-Z0-9]/', '', (string)$data['phone']);
        $parentId = $data['parent_id'] ?? null;

        $user = User::create([
            'name' => $data['name'],
            'phone' => $cleanPhone,
            'type' => $data['type'] ?? 'customer',
            'referral_code' => "created",
            'referral_limit' => $referralLimit,
            'is_active' => 1,
            'is_approved' => 1,
            'customer_type' => $data['customer_type'],
            'account_number' => $data['account_number'] ?? null,
            'unique_id' => 'created',
            'parent_id' => ($parentId != 0 ? $parentId : null),
            'sponsor_id' => $data['sponsor_id'] ?? null,
            'division_id' => $data['division_id'] ?? null,
            'district_id' => $data['district_id'] ?? null,
            'upazila_id' => $data['upazila_id'] ?? null,
            'password' => Hash::make($data['password']),
        ]);

        if ($user) {
            // Update parent referral count if needed
            if (isset($data['referral_code']) && $parentId != null && isset($data['parent'])) {
                $this->userService->UpdateReferralCount($data['parent']);
            }

            // Handle Profile Image Upload or Base64 (Compressed)
            $profileImagePath = null;
            if ($request && $request->hasFile('profile_image')) {
                $image = $request->file('profile_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $destPath = public_path('profiles');
                if (!file_exists($destPath)) {
                    mkdir($destPath, 0755, true);
                }
                $image->move($destPath, $imageName);
                $profileImagePath = 'profiles/' . $imageName;
            } elseif (!empty($data['profile_image_base64'])) {
                $imageData = $data['profile_image_base64'];
                if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
                    $imageData = substr($imageData, strpos($imageData, ',') + 1);
                    $type = strtolower($type[1]);
                    $imageData = base64_decode($imageData);
                    $imageName = time() . '_' . uniqid() . '.' . $type;
                    $destPath = public_path('profiles');
                    if (!file_exists($destPath)) {
                        mkdir($destPath, 0755, true);
                    }
                    file_put_contents($destPath . '/' . $imageName, $imageData);
                    $profileImagePath = 'profiles/' . $imageName;
                }
            }

            // Build Cascading Full Address String
            $divisionName = '';
            $districtName = '';
            $upazilaName = '';

            if (!empty($data['division_id'])) {
                $div = Division::find($data['division_id']);
                $divisionName = $div ? ($div->bn_name ?: $div->name) : '';
            }
            if (!empty($data['district_id'])) {
                $dist = District::find($data['district_id']);
                $districtName = $dist ? ($dist->bn_name ?: $dist->name) : '';
            }
            if (!empty($data['upazila_id'])) {
                $upz = Upazila::find($data['upazila_id']);
                $upazilaName = $upz ? ($upz->bn_name ?: $upz->name) : '';
            }

            $unionName = $data['union_name'] ?? '';
            $wardNo = $data['ward_no'] ?? '';

            $addressParts = [];
            if ($wardNo) { $addressParts[] = 'ওয়ার্ড নং: ' . $wardNo; }
            if ($unionName) { $addressParts[] = $unionName; }
            if ($upazilaName) { $addressParts[] = $upazilaName; }
            if ($districtName) { $addressParts[] = $districtName; }
            if ($divisionName) { $addressParts[] = $divisionName; }
            $fullAddress = implode(', ', $addressParts);

            // Create Profile Record so user does not need to fill profile again!
            Profile::create([
                'user_id' => $user->id,
                'first_name' => $data['name'],
                'last_name' => '',
                'father_name' => !empty($data['father_name']) ? $data['father_name'] : null,
                'mother_name' => !empty($data['mother_name']) ? $data['mother_name'] : null,
                'nid' => !empty($data['nid']) ? $data['nid'] : '',
                'date_of_birth' => !empty($data['date_of_birth']) ? $data['date_of_birth'] : null,
                'blood_group' => !empty($data['blood_group']) ? $data['blood_group'] : null,
                'address' => $fullAddress,
                'union_name' => $unionName,
                'ward_no' => $wardNo,
                'profile_image' => $profileImagePath,
            ]);

            // Create Default Active Address for Orders
            Address::create([
                'user_id' => $user->id,
                'address' => $fullAddress,
                'is_active' => 1,
            ]);

            // Initialize Generation Commission Group
            try {
                if (!UserGenerationGroup::where('user_id', $user->id)->exists()) {
                    $commissionService = new CustomerGenerationCommissionGroupService(new User());
                    $commissionService->SaveUserGenerationGroup($user->id);
                }
            } catch (\Exception $ex) {
                // Ignore silent generation group creation log
            }
        }

        return $user;
    }
}
