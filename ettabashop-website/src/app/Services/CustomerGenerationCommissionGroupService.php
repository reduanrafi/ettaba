<?php


namespace App\Services;


use App\Models\User;
use App\Models\UserGenerationGroup;
use Carbon\Carbon;

class CustomerGenerationCommissionGroupService
{
    private $user;
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function SaveUserGenerationGroup($userId)
    {
        //dd($this->user->id);
        $parents = $this->user->parents($userId);
       // dd($parents);

        $userGenerationGroup = $this->GetUserGenerationCommissionGroup($userId,$parents);
        if(UserGenerationGroup::create($userGenerationGroup)) return 1;

        return 0;
    }
    public function GetUserGenerationCommissionGroup($userId,$users)
    {
        //dd($users);
        $group=[];
        $group6=[];
        $group7=[];
        $group8=[];
        $group['user_id']=$userId;
        $g=1;
        foreach ($users as $k=>$user)
        {
            if ($user->level<=5)
            {
                $group['g'.$g++]=$user->parent_id;
            }

            if ($user->level>=6 && $user->level<=8)
            {

                array_push($group6,$user->parent_id);
                $group['g6'] = implode(', ', $group6);



            }
            elseif ($user->level>=9 && $user->level<=31)
            {

                array_push($group7,$user->parent_id);
                $group['g7'] = implode(', ', $group7);
            }
            elseif ($user->level>=32 && $user->level<=33)
            {

                array_push($group8,$user->parent_id);
                $group['g8'] = implode(', ', $group8);
            }

        }


//        if(count($users)>=6)$group['g6']=implode(',',$group6);
//        if(count($users)>=9)$group['g7']=implode(',',$group7);
       // dd($group);
        return $group;
    }

}
