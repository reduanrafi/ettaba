<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_no',
        'user_id',
        'reward_id',
        'account_type',
        'reward_title',
        'reward_name',
        'required_points',
        'reward_image',
        'user_name',
        'user_phone',
        'user_nid',
        'status',
        'admin_note',
        'collection_date',
        'collection_time',
        'collection_place',
        'points_deducted',
        'points_deducted_amount',
        'processed_by',
        'processed_at',
    ];

    protected $casts = [
        'required_points' => 'double',
        'points_deducted' => 'boolean',
        'points_deducted_amount' => 'double',
        'processed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reward()
    {
        return $this->belongsTo(Reward::class, 'reward_id');
    }

    public function processor()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
