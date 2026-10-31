<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reward extends Model
{
    use HasFactory, SoftDeletes, CommonFunctions;

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

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        if (empty($this->image)) {
            return null;
        }
        $img = trim($this->image);
        if (preg_match('#^https?://#i', $img)) {
            return $img;
        }
        return asset(ltrim($img, '/'));
    }

    public function applications()
    {
        return $this->hasMany(RewardApplication::class, 'reward_id');
    }

    public function GetData($data)
    {
        if (isset($data['image']) && $data['image'] instanceof \Illuminate\Http\UploadedFile) {
            $data['image'] = $this->UploadImage($data['image'], 'rewards', 600, 600);
        }
        return $data;
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
