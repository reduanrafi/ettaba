<?php

namespace App\Http\Controllers\Website;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\TopTypeResource;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Slider;
use App\Models\TopType;
use App\Models\User;
use App\Services\CustomerGenerationCommissionDistributionService;
use App\Services\EarningStatisticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{

    private $smsGatewayUser = "";
    private $smsGatewayPass = "";
    private $sender = "1480";
    private $apiKey = "SXN0aWFrMjI6SXN0aWFrQDEyMjMj";

    public function _construct()
    {

    }


    public function forgotPassword()
    {
        return view('auth.forgot_password');
    }

//    public function techno_bulk_sms($sender_id, $apiKey, $mobileNo, $message)
//    {
//        $url = 'https://24smsbd.com/api/bulkSmsApi';
//        $data = array('sender_id' => $sender_id,
//            'apiKey' => $apiKey,
//            'mobileNo' => $mobileNo,
//            'message' => $message
//        );
//
//        $curl = curl_init($url);
//        curl_setopt($curl, CURLOPT_POST, true);
//        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
//        curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
//        $output = curl_exec($curl);
//        curl_close($curl);
//
//        echo $output;
//    }

    public function OTP()
    {
        return "ES" . rand(1000, 9999);
    }

    public function GenerateOTP(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        if ($user) {

            $otp = $this->OTP();
            if ($user->update(['otp' => $otp, 'is_verified' => 0]))
                if ($this->sendSMS($otp, $request->phone)) {

                    return redirect()->back()->with([
                        'otp' => $otp,
                        'phone' => $request->phone,
                        'success' => 'We have sent an verification code to your number. Please verify the code to continue',
                    ]);
                }
            return redirect()->back()->with([
                'error' => 'unable to generate verification code !',
            ]);
        }
        return redirect()->back()->with([
            'error' => 'Invalid Phone number',
        ]);
    }

    public function sendSMS($message, $mobileNumber)
    {

//        $endpoint = " http://sms.itsian.net/smsapi/masking";
        $endpoint = "https://24smsbd.com/api/bulkSmsApi";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', $endpoint, ['form_params' => [
            'sender_id' => $this->sender,
            'apiKey' => $this->apiKey,
            'mobileNo' => $mobileNumber,
            'message' => $message
        ]]);

       // dd($response);

// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();
        if ($statusCode == 200) {
            return 1;
        }
        return 0;
    }

    public function VerifyOTP(Request $request)
    {
        //return view('auth.reset_password', ['phone' => $request->phone]);

        $credentials = $request->only('phone', 'otp');

        $user = User::where('phone', $request->phone)->where('otp', $request->otp)->first();
        //dd($user);
        if ($user) {
            return view('auth.reset_password', ['phone' => $request->phone]);

        }
        return redirect()->back()->with([
            'error' => 'Invalid Phone number or OTP',
        ]);

    }


    public function ForgotPasswordChange(Request $request)
    {
        $user = User::where('phone', $request->phone)->first();
        //dd($request->all());
        $password = Hash::make($request->password);
        if ($user->update(['password' => $password,'is_verified'=>1])) {
//            return response([
//                'message' => 'Password has been updated.'
//            ], 200);

            return redirect()->route("login")->with([
                'success' => 'আপনার পাসওয়ার্ড আপডেট হয়েছে , লগিন করতে নতুন পাসওয়ার্ডটি ব্যাবহার করুণ !',
            ]);
        }
    }
}
