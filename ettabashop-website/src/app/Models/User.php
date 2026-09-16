<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    use Notifiable;
    use HasFactory, Notifiable, HasApiTokens;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'unique_id',
        'parent_id',
        'division_id',
        'district_id',
        'upazila_id',
        'name',
        'type',
        'customer_type',
        'account_number',
        'referral_code',
        'referral_count',
        'referral_limit',
        'partner_limit',
        'customer_limit',
        'merchant_limit',
        'is_active',
        'otp',
        'is_verified',
        'email',
        'phone',
        'password',
        'is_approved',
        'is_active',
        'is_new',
        'is_blocked',
        'sponsor_id',
        'direct_selling_balance',
    ];

    public function profile(){
        return $this->hasOne(Profile::class);
    }

    public function trainings()
    {
        return $this->hasMany(  UserTraining::class);
    }

    public function order()
    {
        return $this->hasMany(Order::class);
    }
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function GetData($data)
    {
        $data['unique_id'] =config('settings.user_unique_id');
        $data['type'] = 'customer';
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = 0;
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/[^a-zA-Z0-9]/', '', (string)$data['phone']);
        }

        return $data;
    }
    public function parents($userId)
    {
        return DB::select('SELECT @r AS user_id, 
                               (SELECT @r := parent_id FROM users WHERE id = user_id ) AS parent_id, 
                               @l := @l + 1 AS level 
                            
                               FROM (SELECT @r := '.$userId.', @l := 0) val, users WHERE @r <> 0 limit 33;');
    }

    public function GetUserGenerationCommissionGroup($users)
    {   $group6=[];
        $group7=[];
        $g6='';
        $g7='';
        foreach ($users as $k=>$user)
        {

            if ($user->level>=6 && $user->level<=8)
            {

                array_push($group6,$user->parent_id);
                $g6 = implode(', ', $group6);
                $test6 = explode(', ', $g6);


            }
            elseif ($user->level>=9)
            {

                array_push($group7,$user->parent_id);
                $g7 = implode(', ', $group7);
            }

        }

    }
}
