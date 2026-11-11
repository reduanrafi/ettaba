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
        'nid_tin',
        'date_of_birth',
        'division_id',
        'district_id',
        'upazila_id',
        'union_name',
        'ward_no',
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
        'instagram_link',
        'social_approved',
        'delivery_type',
        'erp_margin_percent',
        'seller_ebalance',
        'allowed_categories',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }

    public function upazila()
    {
        return $this->belongsTo(Upazila::class, 'upazila_id');
    }

    public function GetData($data)
    {
        $userId = !empty($data['user_id']) ? (int)$data['user_id'] : Auth::user()->id;
        $data['user_id'] = $userId;
        $data['unique_id'] = $this->GetUniqueShopId();

        $existingShop = $this->GetShop($userId);
        $ignoreId = !empty($data['shop']) ? (int) $data['shop'] : ($existingShop ? $existingShop->id : null);

        // Single field Shop Name (English/Bangla)
        if (!empty($data['name'])) {
            $data['name_en'] = $data['name'];
            $data['name_bn'] = $data['name'];
        }

        // Clean Mobile Number to 11 English digits
        if (!empty($data['mobile'])) {
            $data['mobile'] = preg_replace('/[^0-9]/', '', (string)$data['mobile']);
        }

        $rawSlug = !empty($data['slug']) ? $data['slug'] : ($data['name_en'] ?? ($data['name_bn'] ?? 'Seller'));
        $data['slug'] = self::generateUniqueSlug($rawSlug, $ignoreId);

        // Build full address text from cascading fields if present
        $addrParts = [];
        if (!empty($data['ward_no'])) {
            $addrParts[] = 'ওয়ার্ড নং ' . $data['ward_no'];
        }
        if (!empty($data['union_name'])) {
            $addrParts[] = $data['union_name'];
        }
        if (!empty($data['upazila_id'])) {
            $upz = Upazila::find($data['upazila_id']);
            if ($upz) $addrParts[] = $upz->bn_name ?: $upz->name;
        }
        if (!empty($data['district_id'])) {
            $dist = District::find($data['district_id']);
            if ($dist) $addrParts[] = $dist->bn_name ?: $dist->name;
        }
        if (!empty($data['division_id'])) {
            $div = Division::find($data['division_id']);
            if ($div) $addrParts[] = $div->bn_name ?: $div->name;
        }

        if (!empty($addrParts)) {
            $fullAddr = implode(', ', $addrParts);
            $data['address_bn'] = $fullAddr;
            $data['address_en'] = $fullAddr;
        }

        // Ensure delivery area sync
        if (!empty($data['delivery_area_bn'])) {
            $data['delivery_area_en'] = $data['delivery_area_bn'];
        }

        if (isset($data['logo_image'])) {
            $data['logo_image'] = $this->UploadImage($data['logo_image'], 'shops', 200, 200);
        }
        if (isset($data['banner_image'])) {
            $data['banner_image'] = $this->UploadImage($data['banner_image'], 'shops', 360, 400);
        }

        // Sync with User and Profile models
        try {
            $user = User::find($userId);
            if ($user) {
                if (!empty($data['division_id'])) $user->division_id = $data['division_id'];
                if (!empty($data['district_id'])) $user->district_id = $data['district_id'];
                if (!empty($data['upazila_id'])) $user->upazila_id = $data['upazila_id'];
                $user->save();

                $profile = Profile::firstOrNew(['user_id' => $userId]);
                if (!empty($data['nid_tin'])) $profile->nid = $data['nid_tin'];
                if (!empty($data['date_of_birth'])) $profile->date_of_birth = $data['date_of_birth'];
                if (!empty($data['union_name'])) $profile->union_name = $data['union_name'];
                if (!empty($data['ward_no'])) $profile->ward_no = $data['ward_no'];
                if (!empty($data['address_bn'])) $profile->address = $data['address_bn'];
                $profile->save();
            }
        } catch (\Throwable $e) {}

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
