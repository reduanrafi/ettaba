<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        try {
            if ($this->globalObject->SaveOrUpdateProfile($this->globalObject->GetData($request->all()))) {
                $this->message = 'profile saved Successfully';
            }
        } catch (QueryException $ex) {
            $this->message = $ex->getMessage();
        }

        return response()->json([
            'status' => 'ok',
            'message' => $this->message
        ]);
    }



    public function GetProfile()
    {
        $profile  = $this->globalObject->GetProfile();
        if(!$profile) {
            return response()->json([
                'message' => 'Profile not found'
            ], 404);
        }
       return response(new ProfileResource($profile), 200);
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
}
