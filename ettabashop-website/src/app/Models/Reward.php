<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'account_type',
        'level_no',
        'title',
        'subtitle',
        'reward_name',
        'description',
        'image',
        'required_points',
        'sort_order',
        'is_visible',
        'info_title',
        'info_items',
        'howto_title',
        'howto_items',
    ];

    protected $casts = [
        'required_points' => 'double',
        'level_no' => 'integer',
        'sort_order' => 'integer',
        'is_visible' => 'boolean',
    ];

    public function applications()
    {
        return $this->hasMany(RewardApplication::class, 'reward_id');
    }

    public function getInfoItemsArrayAttribute()
    {
        if (empty($this->info_items)) {
            return [];
        }
        $decoded = json_decode($this->info_items, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return array_filter(array_map('trim', explode("\n", str_replace("\r", "", $this->info_items))));
    }

    public function getHowtoItemsArrayAttribute()
    {
        if (empty($this->howto_items)) {
            return [];
        }
        $decoded = json_decode($this->howto_items, true);
        if (is_array($decoded)) {
            return $decoded;
        }
        return array_filter(array_map('trim', explode("\n", str_replace("\r", "", $this->howto_items))));
    }
}
