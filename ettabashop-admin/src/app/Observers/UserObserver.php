<?php

namespace App\Observers;

use App\Models\Earning;
use App\Models\Point;
use App\Models\User;
use App\Services\CustomerGenerationCommissionGroupService;
use App\Services\EarningService;
use App\Services\PointService;

class UserObserver
{
    /**
     * Handle the order "created" event.
     *
     * @param \App\Models\User $order
     * @param \App\Services\UserService
     * @return void
     */
    public function created(User $user)
    {
        $earningService = new  EarningService();
        $pointService = new  PointService();
       // $generationCommissionService = new  CustomerGenerationCommissionGroupService($user);

        $earningService->CreateEarning($user->id);

        $pointService->CreatePoint($user->id);

        //$generationCommissionService->SaveUserGenerationGroup($user->id);

        // Update the unique_id and referral_code to '13' + ID
        $uniqId = '13' . $user->id;
        \Illuminate\Support\Facades\DB::table('users')->where('id', $user->id)->update([
            'unique_id' => $uniqId,
            'referral_code' => $uniqId,
        ]);

    }

    /**
     * Handle the order "updated" event.
     *
     * @param \App\Models\User $order
     * @return void
     */
    public function updated(User $order)
    {
        //
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param \App\Models\User $order
     * @return void
     */
    public function deleted(User $order)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param \App\Models\User $order
     * @return void
     */
    public function restored(User $order)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param \App\Models\User $order
     * @return void
     */
    public function forceDeleted(User $order)
    {
        //
    }
}
