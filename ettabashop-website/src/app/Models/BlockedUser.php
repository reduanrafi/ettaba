<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nid',
        'phone',
        'reason',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function GetData($data)
    {
        return $data;
    }

    public function checkNid($nid, $type = null)
    {
        $q = BlockedUser::where('nid', $nid);
        if ($type) {
            $q->where(function ($query) use ($type) {
                $query->where('type', $type)->orWhere('type', 'all')->orWhereNull('type');
            });
        }
        return $q->first();
    }

    public function checkPhone($phone, $type = null)
    {
        $q = BlockedUser::where('phone', $phone);
        if ($type) {
            $q->where(function ($query) use ($type) {
                $query->where('type', $type)->orWhere('type', 'all')->orWhereNull('type');
            });
        }
        return $q->first();
    }
}

