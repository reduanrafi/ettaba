<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyFund;
use App\Services\BonusDistributionService;
use App\Services\EarningService;
use Illuminate\Http\Request;

class BonusDistributionController extends Controller
{
    private $bonusService;
    private $earningService;
    private $activeUsers ;
    private $bonusAmount ;
    private $amountPerUser;

    public function __construct()
    {
        $this->bonusService     = new BonusDistributionService();
        $this->earningService   = new EarningService();
        $this->activeUsers      = $this->bonusService->GetActiveUsers();
        $this->bonusAmount      = floatval($this->bonusService->GetDailyBonus());
        $count = count($this->activeUsers);
        $this->amountPerUser    = $count > 0 ? ($this->bonusAmount) / $count : 0;
    }

    public function index()
    {
        return view('admin.bonus_distributions.index');

    }
    public function DailyBonusDistribution()
    {
       // dd($this->activeUsers);
        foreach ($this->activeUsers as $user)
        {
            $this->bonusService->UpdateCustomerEarning($user->id,$this->amountPerUser);

            $this->earningService->CreateEarningHistory($user->id,$this->amountPerUser,"Earned from Daily bonus !");

        }

        $this->bonusService->UpdateBonusFund(DailyFund::class);

        return redirect()->back()->with(['success'=>"Daily bonus distributed to active users !"]);

    }


}
