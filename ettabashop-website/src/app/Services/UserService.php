<?php


namespace App\Services;


use App\Models\User;
use App\Models\UserPendingFund;
use Carbon\Carbon;

class UserService
{

    public function ReferralCode()
    {
        $code = User::orderBy('id', 'desc')->Limit(1)->get('referral_code');

        if ($code == null || $code == '' || count($code) <= 0) {
            $code = 'etShY2213';
        } else {

            $code = $code[0]->referral_code;
        }

        $staticString = substr($code, 0, 7);

        $lastDigit = substr($code, 7);

        $updatedDigit = $lastDigit + 1;

        return $staticString . '' . $updatedDigit;
    }

    public function UniqueId()
    {
        $user = User::orderBy('id', 'desc')->Limit(1)->first();

        if (!$user) {
            return 130;
        }

        return '13' . ($user->id + 1);
    }

    public function getTheParent($code)
    {
        if (isset($code)) {

            return $parent = User::where('referral_code',$code)->first();
        }
        return null;

    }

    public function getParentId($parent)
    {


        return $parent->id;
    }

    public function referralBonus($parentId,$child_id, $amount = 100)
    {
        $earningService = new EarningService();

        $earningHistoryService = new EarningHistoryService();

        $message = "Earned Tk " . $amount . " as Referral bonus !";

        $earningService->SavPendingFund($parentId,null, $amount,$child_id);

        $earningHistoryService->CreateEarningHistory($parentId, $amount, $message);

        return;

    }

    public function UpdateReferralCount($user)
    {
        if ($user!=null)
        {
            return $user->update(['referral_count' => $user->referral_count + 1]);
        }
    }
    public function UpdateUniqueId($user)
    {
        $uniqId = '13'.$user->id;
        if ($user!=null)
        {
            return $user->update([
                'unique_id' => $uniqId,
                'referral_code' => $uniqId,
            ]);
        }
    }

}
