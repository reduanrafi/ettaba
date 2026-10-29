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

    public static function formatSlugString($raw)
    {
        $slug = trim((string) $raw);
        $slug = preg_replace('/\s+/', '_', $slug);
        $slug = preg_replace('/[^A-Za-z0-9_\-\x{0980}-\x{09FF}]/u', '', $slug);
        return $slug !== '' ? $slug : 'Shop';
    }

    public function getUrlSlugAttribute()
    {
        if (!empty($this->slug)) {
            return preg_replace('/\s+/', '_', trim($this->slug));
        }
        if (!empty($this->name_en)) {
            return self::formatSlugString($this->name_en);
        }
        return (string) ($this->unique_id ?: $this->user_id);
    }

    public function getCustomUrlAttribute()
    {
        return url('/' . $this->url_slug);
    }

    public function getDeliveryAreaDisplayAttribute()
    {
        if (!empty(trim($this->delivery_area_bn ?? ''))) {
            return trim($this->delivery_area_bn);
        }
        if (!empty(trim($this->delivery_area_en ?? ''))) {
            return trim($this->delivery_area_en);
        }

        try {
            $sampleProduct = Product::where('owner_id', $this->user_id)
                ->where('is_deleted', 0)
                ->where(function ($q) {
                    $q->whereNotNull('delivery_area_bn')
                      ->orWhereNotNull('delivery_area_en');
                })
                ->orderByDesc('id')
                ->first();

            if ($sampleProduct) {
                $area = trim($sampleProduct->delivery_area_bn ?: ($sampleProduct->delivery_area_en ?? ''));
                if ($area !== '') {
                    return $area;
                }
            }
        } catch (\Throwable $e) {
        }

        if (!empty(trim($this->address_bn ?? ''))) {
            return 'শুধু ' . trim($this->address_bn);
        }
        if (!empty(trim($this->address_en ?? ''))) {
            return 'শুধু ' . trim($this->address_en);
        }

        return 'সমগ্র বাংলাদেশ';
    }

    public function GetData($data)
    {
        $data['user_id'] = Auth::user()->id;
        $data['unique_id'] = $this->GetUniqueShopId();

        if (isset($data['logo_image'])) {
            $data['logo_image'] = $this->UploadImage($data['logo_image'], 'shops', 200, 200);
        }
        if (isset($data['banner_image'])) {
            $data['banner_image'] = $this->UploadImage($data['banner_image'], 'shops', 360, 400);
        }
        return $data;
    }

    public function GetShop($id)
    {
        return Shop::where('user_id', $id)->first();
    }

    public function GetShopProducts($id)
    {
        return Product::where('owner_id', $id)->where('is_deleted', 0)->paginate(12);
    }

    public function SearchShop($keywords)
    {
        return Shop::query()
            ->where(function ($q) use ($keywords) {
                $q->where('name_en', 'LIKE', "%{$keywords}%")
                  ->orWhere('name_bn', 'LIKE', "%{$keywords}%")
                  ->orWhere('unique_id', 'LIKE', "%{$keywords}%")
                  ->orWhere('slug', 'LIKE', "%{$keywords}%");
            })
            ->get();
    }
}
