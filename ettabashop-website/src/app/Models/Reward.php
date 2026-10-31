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
        $cleanImg = ltrim($img, '/');

        // If the image file exists locally in the website root or public path, serve directly via asset()
        if (file_exists(base_path('../' . $cleanImg)) || file_exists(base_path($cleanImg))) {
            return asset($cleanImg);
        }

        // If sibling ettabashop-admin folder has the file on the same server, copy it over so asset() works reliably
        $siblingAdminFile = dirname(base_path(), 2) . '/ettabashop-admin/' . $cleanImg;
        if (!file_exists($siblingAdminFile)) {
            $siblingAdminFile = dirname(base_path(), 2) . '/ettabashop-admin/src/' . $cleanImg;
        }
        if (file_exists($siblingAdminFile)) {
            try {
                $targetFile = base_path('../' . $cleanImg);
                $targetDir = dirname($targetFile);
                if (!file_exists($targetDir)) {
                    @mkdir($targetDir, 0755, true);
                }
                @chmod($targetDir, 0755);
                if (@copy($siblingAdminFile, $targetFile)) {
                    @chmod($targetFile, 0644);
                    return asset($cleanImg);
                }
            } catch (\Throwable $e) {
                // fallback to remote URL
            }
        }

        $baseUrl = rtrim(config('app.image_url', 'https://admin.ettabashop.com/'), '/');
        if (str_starts_with($cleanImg, 'assets/') && str_ends_with($baseUrl, '/storage')) {
            $baseUrl = substr($baseUrl, 0, -8);
        }

        return $baseUrl . '/' . $cleanImg;
    }

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
