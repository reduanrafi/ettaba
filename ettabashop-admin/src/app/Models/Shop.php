<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    use CommonFunctions;
    use HasFactory;

    protected $fillable = [
        'user_id',
        'unique_id',
        'slug',
        'name_en',
        'name_bn',
        'description_en',
        'description_bn',
        'mobile',
        'address_en',
        'address_bn',
        'delivery_area_en',
        'delivery_area_bn',
        'banner_image',
        'logo_image',
        'sales',
        'reviews',
        'ratings',
        'fb_link',
        'youtube_link',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function GetData($data)
    {
        $data['user_id'] = Auth::user()->id;
        $data['unique_id'] = $this->GetUniqueShopId();

        $existingShop = $this->GetShop();
        $ignoreId = !empty($data['shop']) ? (int) $data['shop'] : ($existingShop ? $existingShop->id : null);

        $rawSlug = !empty($data['slug']) ? $data['slug'] : ($data['name_en'] ?? ($data['name_bn'] ?? 'Seller'));
        $data['slug'] = self::generateUniqueSlug($rawSlug, $ignoreId);

        if (isset($data['logo_image'])) {
            $data['logo_image'] = $this->UploadImage($data['logo_image'], 'shops', 200, 200);
        }
        if (isset($data['banner_image'])) {
            $data['banner_image'] = $this->UploadImage($data['banner_image'], 'shops', 360, 400);
        }

        return $data;
    }

    public static function formatSlugString($raw)
    {
        $slug = trim((string) $raw);
        $slug = preg_replace('/\s+/', '_', $slug);
        $slug = preg_replace('/[^A-Za-z0-9_\-\x{0980}-\x{09FF}]/u', '', $slug);
        return $slug !== '' ? $slug : 'Shop';
    }

    public static function generateUniqueSlug($rawSlugOrName, $ignoreId = null)
    {
        $baseSlug = self::formatSlugString($rawSlugOrName);

        // Reserved system route prefixes that a shop slug must not collide with
        $reserved = [
            'login', 'register', 'logout', 'forgot-password', 'products', 'category-products',
            'all-products', 'search', 'detail', 'checkout', 'about', 'contact', 'shop',
            'shop-search', 'shop-detail', 'privacy', 'terms', 'orders', 'profile', 'notices',
            'offer', 'section', 'api', 'admin', 'clear-cache', 'run-migrate', 'payment',
        ];

        $slug = $baseSlug;
        if (in_array(strtolower($slug), $reserved, true)) {
            $slug = $baseSlug . '_Shop';
        }

        $counter = 1;
        while (self::whereRaw('LOWER(slug) = ?', [strtolower($slug)])
            ->when($ignoreId, function ($q) use ($ignoreId) {
                $q->where('id', '!=', $ignoreId);
            })
            ->exists()
        ) {
            $slug = $baseSlug . '_' . $counter;
            $counter++;
        }

        return $slug;
    }

    public function GetShop($id = 0)
    {
        $userId = ($id > 0 ? $id : Auth::user()->id);
        return Shop::where('user_id', $userId)->first();
    }

    private function GetUniqueShopId()
    {
        $existingShop = $this->GetShop();
        if (!$existingShop) {
            $shop = Shop::orderBy('id', 'desc')->first();
            if ($shop != null) {
                return $shop->unique_id + 1;
            } else {
                return 15786;
            }
        }
        return $existingShop['unique_id'];
    }
}
