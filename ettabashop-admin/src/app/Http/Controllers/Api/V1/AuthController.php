<?php

namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Users\UserRegistrationRequest;
use App\Http\Resources\AuthResource;

use App\Models\User;
use function GuzzleHttp\Promise\all;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    private $smsGatewayUser = "";
    private $smsGatewayPass = "";
    private $sender = "";
    public function Login(Request $request)
    {
        $credentials = $request->only('phone', 'password','email');
        //dd($request->all());
        if (isset($request->phone))
        {
            $user = User::where('phone', $request->phone)->where('is_active',1)->first();

        }
        else if (isset($request->email))
        {
            $user = User::where('email', $request->email)->where('is_active',1)->first();

        }
        if (!isset($user) || !Hash::check($request->password, $user->password)) {
            
            return response([
                'message' => 'These credentials do not match our records. or you might have not verified your phone !',
            ]);
        }
        //dd($user);
        $user->tokens()->where('name', $user->name)->delete();
        $token = $user->createToken($user->name);

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response(new AuthResource($response), 200);

    }
//    public function Login(Request $request)
//    {
//
//
//        $loginDetail = $request->only('username','phone','email', 'password');
////        dd($token = JWTAuth::attempt($loginDetail));
//
//        $token = NULL;
//        try {
//            $token = JWTAuth::attempt($loginDetail);
////            dd($token);
//            if (!$token) {
//
//                return response()->json([
//                    'status' => 'failed',
//                    'message' => 'Invalid Username or Password'
//                ]);
//            }
//        } catch (JWTException $e) {
//
//            return response()->json([
//                'status' => 'failed',
//                'message' => 'failed to create token'
//            ]);
//        }
////        $user = $this->getAuthUser($token);
//        $user = Auth::user();
//
//        return response()->json([
//            'status' => 'ok',
//            'token' => $token,
//            'user' => $user,
//            'message' => 'Logged in successfully'
//        ]);
//
//    }
    public function Register(Request $request)
    {
        $message = '';
        //dd($request->all());
        $userObj = new User();
        $data = $userObj->GetData($request->all());
        try
        {
            $user = $userObj->create($data);
            if ($user) {

                    return response()->json([
                        'message' => 'Your account is created ! It will take 1-72 hours to activate your account',
                    ]);

            }
        } catch (QueryException $ex) {

            return response()->json([
                'message' => $ex->getMessage(),
            ]);
        }
        return response()->json([
            'message' => "Unable to create your account, please wait sometimes",
        ]);
    }

    public function sendSMS($message,$mobileNumber){
        $endpoint = "https://vas.banglalinkgsm.com/sendSMS/sendSMS";
        $client = new \GuzzleHttp\Client();

        $response = $client->request('GET', $endpoint, ['query' => [
            "msisdn"=>$mobileNumber,
            "message"=>$message,
            "userID"=>$this->smsGatewayUser,
            "passwd"=>$this->smsGatewayPass,
            "sender"=>$this->sender,
        ]]);

// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();
        if ($statusCode==200)
        {
            return 1;
        }
        return 0;
    }

    public function VerifyOTP(Request $request)
    {
        $credentials = $request->only('phone', 'otp');
        $user = User::where('phone',$request->phone)->where('otp',$request->otp)->first();

        if ($user)
        {
            if ($user->is_verified==1)
            {
                return response()->json(['message' => 'Your number is already verified!'], 200);

            }
            if($user->update(['is_verified'=>1]))
            {
                return response()->json(['message' => 'Your number verified successfully !'], 200);
            }

        }
        return response()->json(['message' => 'Unable to verify your number'], 400);
    }
    public function GenerateOTP()
    {
        return "ES".rand(1000,9999);
    }
    public function ForgetPassword(Request $request)
    {
        $user = User::where('phone',$request->phone)->first();
        if ($user)
        {
            $otp = $this->GenerateOTP();
            if ( $user->update(['otp'=>$otp,'is_verified'=>0]))
            if ($this->sendSMS($otp,$request->phone)) {

                return response()->json([
                    'message' => 'We have sent an verification code to your number. Please verify the code to continue',
                ]);
            }
            return response()->json([
                'message' => 'unable to generate verification code !',
            ]);
        }
        return response()->json([
            'message' => 'Invalid Phone number',
        ]);
    }

    public function Logout()
    {

        if (Auth::user()) {
            Auth::user()->tokens()->delete();
            return response()->json(['message' => 'Logged Out'], 200);

        }

    }

    public function ForgotPasswordChange(Request $request)
    {
        $user = User::where('phone',$request->phone)->first();
        $password = Hash::make($request->password);
        if ($user->update(['password' => $password])) {
            return response([
                'message' => 'Password has been updated.'
            ], 200);
        }
    }
    public function ChangePassword(Request $request)
    {
        $user = Auth::user();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response([
                'message' => 'These credentials do not match our records.'
            ], 404);
        } else {
            $password = Hash::make($request->new_password);
            if ($user->update(['password' => $password])) {
                return response([
                    'message' => 'Password has been updated.'
                ], 200);
            }
        }

    }

    public function CreatePasswordResetToken(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();

        if (!$user)  {
            return response([
                'message' => 'Incorrect email address !'
            ]);
        }

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => Str::random(60),
            'created_at' => Carbon::now()
        ]);

        $tokenData = DB::table('password_resets')
            ->where('email', $request->email)->first();

        if ($this->sendResetEmail($request->email, $tokenData->token)) {
            return response(['message'=>'A reset link has been sent to your email address.']);
        } else {
            return response(['message'=>'A Network Error occurred. Please try again..']);
        }
    }

    private function sendResetEmail($email, $token)
    {

        $user = DB::table('customers')->where('email', $email)->select( 'email')->first();

        $link = config('base_url') . 'password/reset/' . $token . '?email=' . urlencode($user->email);

        try {
            event(new PasswordResetEvent($link));
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function ResetPassword(Request $request)
    {
        //Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:customers,email',
            'password' => 'required',
            'token' => 'required']);

        //check if payload is valid before moving on
        if ($validator->fails()) {
            return redirect()->back()->withErrors(['email' => 'Please complete the form']);
        }

        $password = $request->password;

        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)->first();

        if (!$tokenData) return view('auth.passwords.email');

        $user = User::where('email', $tokenData->email)->first();

        if (!$user) return response(['message' => 'Email not found']);

        $user->password = Hash::make($password);
        $user->update(); //or $user->save();

        //login the user immediately they change password successfully
        Auth::login($user);

        //Delete the token
        DB::table('password_resets')->where('email', $user->email)
            ->delete();

        //Send Email Reset Success Email
        if ($this->sendSuccessEmail($tokenData->email)) {
            return view('index');
        } else {
            return redirect()->back()->withErrors(['email' => trans('A Network Error occurred. Please try again.')]);
        }

    }


}
