<?php

namespace App\Http\Controllers\Website\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use App\Services\CustomerGenerationCommissionGroupService;
use Facades\App\Services\EarningStatisticsService;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    private $message;
    private $moduleName = "Profile";
    private $singularVariableName = 'profile';
    private $pluralVariableName = 'profiles';
    private $globalObject;

    private $retrievedDataList;
    private $singleData;

    public function __construct()
    {
        $this->globalObject = new Profile();
    }

    public function SaveProfile(Request $request)
    {

       // dd($request->all());
        $validator =  $request->validate([
            //'nid' => ['required', 'string', 'unique:profiles'],
        ]);
        $userObject = new User();

        try {
            $data = $request->all();
            $existingProfile = $this->globalObject->GetProfile();
            if ($existingProfile) {
                if (!empty($existingProfile->nid)) {
                    unset($data['nid']);
                }
                if (!empty($existingProfile->date_of_birth)) {
                    unset($data['date_of_birth']);
                }
            }

            if ($this->globalObject->SaveOrUpdateProfile($this->globalObject->GetData($data))) {

                $customerGenerationCommissionGroupService = new CustomerGenerationCommissionGroupService($userObject);

                if (!\App\Models\UserGenerationGroup::where('user_id', Auth::user()->id)->exists()) {
                    $status = $customerGenerationCommissionGroupService->SaveUserGenerationGroup(Auth::user()->id);
                    //dd($status);
                    if ($status==0)
                    {
                        return redirect()->back()->with(['error'=>"Generation group already created or there is error in this section"]);
                    }
                }

                $this->message = 'profile saved Successfully';
            }
        } catch (QueryException $ex) {
            $this->message = $ex->getMessage();

        }

        return redirect()->back()->with([
            'status' => 'ok',
            'message' => $this->message
        ]);
    }



    public function GetProfile()
    {
        $message='';
        $profile  = $this->globalObject->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);

        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }

       return view('website.profile.index',[
           'profile'=>$profile,
           'message'=>$message,
           'earnings'=>$earnings
       ]);
    }
    public function Orders()
    {
        $message='';
        $profile  = $this->globalObject->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);
        $orders = $this->globalObject->GetOrders();

        foreach ($orders as $order) {
            if ($order && ($order->paymentMethod->short_code ?? '') === 'EPS' && ($order->payment_status ?? '') !== 'paid') {
                $this->autoVerifyEpsOrder($order, false);
            }
        }

        // Refresh orders list
        $orders = $this->globalObject->GetOrders();

        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }

        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings,
            'orders'=>$orders,
        ]);
    }
    public function Trainings()
    {
        $message='';
        $profile  = $this->globalObject->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);
        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }

        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings
        ]);
    }

    public function Referral()
    {
        $message='';
        $profile  = $this->globalObject->GetProfile();
        $earnings = EarningStatisticsService::EarningStatistics(Auth::user()->id);
        if(!$profile) {
            $message =  'You do not have a profile, please create a profile to activate you account !';
        }

        return view('website.profile.index',[
            'profile'=>$profile,
            'message'=>$message,
            'earnings'=>$earnings
        ]);
    }



    public function ChangeEmail(Request $request)
    {
        $user = Auth::user();
        if ($request->email!='')
        {
            if($user->update(['email'=>$request->email]))
            {
                return response()->json([
                    'status' => 'ok',
                    'message ' => 'Email updated successful'
                ]);
            }
        }

        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unable to update email !'
            ]);
        }

    }

    public function ChangePhone(Request $request)
    {
        $user = Auth::user();
        if ($request->phone!='')
        {
            if($user->update(['phone'=>$request->phone]))
            {
                return response()->json([
                    'status' => 'ok',
                    'message ' => 'Phone updated successful'
                ]);
            }
        }

        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unable to update phone !'
            ]);
        }

    }

    public function CreateReferralCode()
    {
        $user = Auth::user();
        $code = '';

        if ($user)
        {
            $code = "ES".$user->id.Carbon::now()->month;

                return response()->json([
                    'status' => 'ok',
                    'referral' => $code,
                    'message ' => 'Successfully created referral code '
                ]);

        }

        else{
            return response()->json([
                'status' => 'failed',
                'message' => 'unable to create referral code !'
            ]);
        }
    }

    public function autoVerifyEpsOrder($order, $isAnonymous = false)
    {
        if ($order && $order->transaction_id && ($order->payment_status ?? '') !== 'paid') {
            $epsService = new \App\Services\EpsPaymentService();
            $statusResult = $epsService->checkTransactionStatus($order->transaction_id);

            if ($statusResult['status'] === 'success' && isset($statusResult['data']['Status']) && strtolower($statusResult['data']['Status']) === 'success') {
                try {
                    \Illuminate\Support\Facades\DB::beginTransaction();

                    $statusChanged = false;
                    if ($order->status === 'pending_payment') {
                        $order->status = 'delivered';
                        $statusChanged = true;
                    }
                    $order->payment_status = 'paid';
                    $order->save();

                    // Log status change to order_statuses
                    \Illuminate\Support\Facades\DB::table('order_statuses')->insert([
                        'order_id' => $order->id,
                        'status' => 'delivered',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    if ($statusChanged && !$isAnonymous && $order->virtual_balance_used > 0) {
                        $vbService = new \App\Services\VirtualBalanceService();
                        $vbService->UpdateUsersVirtualBalance($order->virtual_balance_used, $order->user_id);
                        $vbService->SaveVirtualBalance($order->id, $order->virtual_balance_used);
                    }

                    if (!$isAnonymous) {
                        // Check if commissions were already distributed
                        $hasPendingFunds = \App\Models\UserPendingFund::where('order_id', $order->id)->exists();
                        $hasPendingPoints = \App\Models\PendingPoint::where('order_id', $order->id)->exists();

                        if (!$hasPendingFunds && !$hasPendingPoints) {
                            $commissionService = new \App\Services\CustomerGenerationCommissionDistributionService();
                            $commissionService->DistributeCommission($order->id);

                            $merchantCommissionService = new \App\Services\MerchantCommissionDistributionService();
                            $merchantCommissionService->DistributeRateAndPoint($order->id);
                        }

                    }

                    \Illuminate\Support\Facades\DB::commit();
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\DB::rollBack();
                    \Illuminate\Support\Facades\Log::error("Auto-verify EPS Order #{$order->id} on Website failed: " . $e->getMessage());
                }
            }
        }
    }

    public function TeamTree(Request $request)
    {
        $authUser = Auth::user();
        $message = '';
        
        // Sidebar layout needs profile and earnings of the authenticated user
        $profile = $this->globalObject->GetProfile();
        $earnings = \Facades\App\Services\EarningStatisticsService::EarningStatistics($authUser->id);
        
        // activeUser is the one whose tree is being viewed
        $activeUser = $authUser;
        
        if ($request->has('uid') && !empty($request->uid)) {
            $searchUid = trim($request->uid);
            
            if (substr($searchUid, 0, 2) !== '13') {
                $message = 'আপনি যে eIDটি লিখেছেন, সেটি সঠিক ফরম্যাটের নয়। অনুগ্রহ করে একটি সঠিক eID দিয়ে পুনরায় চেষ্টা করুন। ধন্যবাদ।';
            } else {
                $targetUser = User::where('unique_id', $searchUid)->first();
                if (!$targetUser) {
                    $message = 'আপনি যে eIDটি সার্চ করেছেন, সেটি খুঁজে পাওয়া যায়নি।';
                } else {
                    $isValidDescendant = false;
                    $currentCheck = $targetUser;
                    
                    if ($targetUser->id == $authUser->id) {
                        $isValidDescendant = true;
                    } else {
                        $depth = 0;
                        // Prevent infinite loop by capping depth
                        while ($currentCheck && $currentCheck->parent_id != 0 && $currentCheck->parent_id != null && $depth < 100) {
                            if ($currentCheck->parent_id == $authUser->id) {
                                $isValidDescendant = true;
                                break;
                            }
                            $currentCheck = User::find($currentCheck->parent_id);
                            $depth++;
                        }
                    }
                    
                    if (!$isValidDescendant) {
                        $message = 'আপনি যে eIDটি সার্চ করেছেন, সেটি আপনার টিমের অন্তর্ভুক্ত নয়। কোম্পানির পার্টনার নীতিমালা অনুযায়ী আপনি শুধুমাত্র আপনার নিজস্ব টিমের ট্রি দেখতে পারবেন। অনুগ্রহ করে আপনার টিমের কোনো eID দিয়ে পুনরায় সার্চ করুন। ধন্যবাদ।';
                    } else {
                        $activeUser = $targetUser;
                    }
                }
            }
        }
        
        $activeProfile = Profile::where('user_id', $activeUser->id)->first();
        $activeEarnings = \Facades\App\Services\EarningStatisticsService::EarningStatistics($activeUser->id);
        
        $referrer = null;
        if ($activeUser->parent_id) {
            $referrer = User::with('profile')->find($activeUser->parent_id);
        }
        
        $partners = User::with('profile')->where('parent_id', $activeUser->id)->where('customer_type', 'buy_earn')->get();
        
        $customers = User::with('profile')->where('parent_id', $activeUser->id)
            ->where('type', 'customer')
            ->where(function($q) {
                $q->whereNull('customer_type')->orWhere('customer_type', '!=', 'buy_earn');
            })->get();
        
        $merchants = User::with('profile')->where('parent_id', $activeUser->id)
            ->whereIn('type', ['store_owner', 'store_administrator'])
            ->get();
        
        // Closure to fetch stats for each downline member
        $enrichChild = function($child) {
            $childEarnings = \Facades\App\Services\EarningStatisticsService::EarningStatistics($child->id);
            $child->total_earned = ($childEarnings['totalEarning'] ?? 0) + ($childEarnings['totalWithdraw'] ?? 0);
            $child->total_reward = $childEarnings['totalPoint'] ?? 0;
            
            $child->total_partners = User::where('parent_id', $child->id)->where('customer_type', 'buy_earn')->count();
            $child->total_customers = User::where('parent_id', $child->id)
                ->where('type', 'customer')
                ->where(function($q) {
                    $q->whereNull('customer_type')->orWhere('customer_type', '!=', 'buy_earn');
                })->count();
            $child->total_merchants = User::where('parent_id', $child->id)
                ->whereIn('type', ['store_owner', 'store_administrator'])
                ->count();
            
            return $child;
        };
        
        $partners->transform($enrichChild);
        $customers->transform($enrichChild);
        $merchants->transform(function($merchant) use ($enrichChild) {
            $merchant = $enrichChild($merchant);
            $shop = \App\Models\Shop::where('user_id', $merchant->id)->first();
            $merchant->merchant_id = $shop ? $shop->unique_id : $merchant->unique_id;
            return $merchant;
        });
        
        if(!$profile) {
            $message = 'You do not have a profile, please create a profile to activate you account !';
        }
        
        return view('website.profile.index',[
            'profile' => $profile,
            'message' => $message,
            'earnings' => $earnings,
            'activeUser' => $activeUser,
            'activeProfile' => $activeProfile,
            'activeEarnings' => $activeEarnings,
            'referrer' => $referrer,
            'partners' => $partners,
            'customers' => $customers,
            'merchants' => $merchants
        ]);
    }
}
