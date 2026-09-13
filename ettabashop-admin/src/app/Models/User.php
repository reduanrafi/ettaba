<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;


/**
 * @method static orderBy(string $string, string $string1)
 */
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
        'virtual_balance',
        'merchant_search_access',
        'direct_selling_balance',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function handCashCategories()
    {
        return $this->hasMany(  HandCashCategory::class);
    }
    public function shop()
    {
        return $this->hasOne(  Shop::class);
    }
    public function profile()
    {
        return $this->hasOne(  Profile::class);
    }

    public function trainings()
    {
        return $this->hasMany(  UserTraining::class);
    }

    public function pendingFund()
    {
        return $this->hasMany(UserPendingFund::class);
    }
    public function point()
    {
        return $this->hasOne(Point::class);
    }

    public function WithdrawRequests()
    {
        return $this->hasMany(WithdrawRequest::class);
    }
    public function Withdraw()
    {
        return $this->hasOne(Withdraw::class);
    }
    public function directOrder()
    {
        return $this->hasMany(DirectOrder::class);
    }
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
        $data['type'] = 'customer';
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = 0;
        $data['unique_id'] = 0;
        $data['referral_code'] = 'temp';
        if (isset($data['phone'])) {
            $data['phone'] = preg_replace('/[^a-zA-Z0-9]/', '', (string)$data['phone']);
        }

        return $data;
    }

    public function parents($userId)
    {
        return DB::select('SELECT @r AS user_id,
                               (SELECT @r := parent_id FROM users WHERE id = user_id) AS parent_id,
                               @l := @l + 1 AS level

                               FROM (SELECT @r := '.$userId.', @l := 0) val, users WHERE @r <> 0;');
    }

    public function getCustomerDetail($id)
    {
        return User::with('profile')->find($id);
    }

    public function GetCustomers($state=null)
    {
        if ($state =='new')
        {
            return User::with('point')->where('is_active',0)->where('is_new',1)->where('is_blocked',0)->where('type','customer')->orderby('id','desc')->get();
        }
        elseif ($state =='active')
        {
            return User::with('point')->where('is_active',1)->where('is_new',0)->where('is_blocked',0)->where('type','customer')->orderby('id','desc')->get();
        }
        elseif ($state =='inactive')
        {
            return User::with('point')->where('is_active',0)->where('is_new',0)->where('is_blocked',0)->where('type','customer')->orderby('id','desc')->get();
        }
        elseif ($state =='blocked')
        {
            return User::with('point')->where('is_active',0)->where('is_new',0)->where('is_blocked',1)->where('type','customer')->orderby('id','desc')->get();
        }
        elseif ($state == 'all')
        {
            return User::with('point')->where('type','customer')->orderby('id','desc')->get();
        }
        return User::with('point')->where('type','customer')->orderby('id','desc')->get();

    }
    public function GetCustomersWithPoints($state=null)
    {
        if ($state =='new')
        {
            return User::where('is_active',0)->where('is_new',1)->where('is_blocked',0)->where('type','customer')->orderby('id','desc')->get();
        }
        elseif ($state =='active')
        {
            return User::where('is_active',1)->where('is_new',0)->where('is_blocked',0)->where('type','customer')->orderby('id','desc')->get();
        }
        elseif ($state =='inactive')
        {
            return User::where('is_active',0)->where('is_new',0)->where('is_blocked',0)->where('type','customer')->orderby('id','desc')->get();
        }
        elseif ($state =='blocked')
        {
            return User::where('is_active',0)->where('is_new',0)->where('is_blocked',1)->where('type','customer')->orderby('id','desc')->get();
        }
        return User::where('is_active',0)->where('is_new',1)->where('is_blocked',0)->where('type','customer')->orderby('id','desc')->get();

    }
    public function GetShops($state=null)
    {
        if ($state =='new')
        {
            return User::with('shop')->where('is_active',0)->where('is_new',1)->where('is_blocked',0)->where('type','store_owner')->orderby('id','desc')->get();
        }
        elseif ($state =='active')
        {
            return User::with('shop')->where('is_active',1)->where('is_new',0)->where('is_blocked',0)->where('type','store_owner')->orderby('id','desc')->get();
        }
        elseif ($state =='inactive')
        {
            return User::with('shop')->where('is_active',0)->where('is_new',0)->where('is_blocked',0)->where('type','store_owner')->orderby('id','desc')->get();
        }
        elseif ($state =='blocked')
        {
            return User::with('shop')->where('is_active',0)->where('is_new',0)->where('is_blocked',1)->where('type','store_owner')->orderby('id','desc')->get();
        }
        elseif ($state == 'all')
        {
            return User::with('shop')->where('type','store_owner')->orderby('id','desc')->get();
        }
        return User::with('shop')->where('type','store_owner')->orderby('id','desc')->get();

    }

    public function GetMerchants($state=null)
    {
        if ($state =='new')
        {
            return User::with('shop')->where('is_active',0)->where('is_new',1)->where('is_blocked',0)->where('type','store_administrator')->orderby('id','desc')->get();
        }
        elseif ($state =='active')
        {
            return User::with('shop')->where('is_active',1)->where('is_new',0)->where('is_blocked',0)->where('type','store_administrator')->orderby('id','desc')->get();
        }
        elseif ($state =='inactive')
        {
            return User::with('shop')->where('is_active',0)->where('is_new',0)->where('is_blocked',0)->where('type','store_administrator')->orderby('id','desc')->get();
        }
        elseif ($state =='blocked')
        {
            return User::with('shop')->where('is_active',0)->where('is_new',0)->where('is_blocked',1)->where('type','store_administrator')->orderby('id','desc')->get();
        }
        elseif ($state == 'all')
        {
            return User::with('shop')->where('type','store_administrator')->orderby('id','desc')->get();
        }
        return User::with('shop')->where('type','store_administrator')->orderby('id','desc')->get();

    }
    public function ChangeUserStatus($id,$statusName)
    {
        $statusValue = 0;

        return DB::table('users')->where('id',$id)->update($this->UserStatus($statusName));
    }

    public function UserStatus($statusName)
    {
        if ($statusName=='inactivate')
        {
            return[
                'is_active'=>0,
                'is_blocked'=>0,
                'is_new'=>0
            ];
        }
        elseif ($statusName=='activate')
        {
           return  [
                'is_active'=>1,
                'is_blocked'=>0,
                'is_new'=>0
            ];
        }
        elseif ($statusName=='block')
        {
            return [
                'is_active'=>0,
                'is_blocked'=>1,
                'is_new'=>0
            ];
        }
        elseif ($statusName=='approve')
        {
            return [
                'is_approved'=>1,
                'is_active'=>0,
                'is_blocked'=>0,
                'is_new'=>1
            ];
        }
    }







}
