<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;
    use CommonFunctions;

    protected $fillable = [
        'parent_id',
        'slug',
        'name_en',
        'name_bn',
        'image',
        'icon',
        'icon_class',
        'icon_color',
        'priority',
        'show_on_home',
        'is_active',
        'is_deleted',
        'advance_payment_required'
    ];

    public $category = [];

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id')->where('products.is_deleted', '=', 0);
    }

    public function categories()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function childrenCategories()
    {
        return $this->hasMany(Category::class, 'parent_id')->with('categories')->where('is_deleted', '=', 0);
    }

    public function GetData($data)
    {
        if (isset($data['image']) && is_object($data['image'])) {
            $data['image'] = $this->UploadImage($data['image'], 'categories');
        }
        if (isset($data['icon']) && is_object($data['icon'])) {
            $data['icon'] = $this->UploadImage($data['icon'], 'icons');
        }
        if (empty($data['name_en']) && !empty($data['name_bn'])) {
            $data['name_en'] = $data['name_bn'];
        }
        if (empty($data['name_bn']) && !empty($data['name_en'])) {
            $data['name_bn'] = $data['name_en'];
        }
        if (empty($data['slug'])) {
            $baseSlug = Str::slug($data['name_en'] ?? $data['name_bn'] ?? 'cat');
            $data['slug'] = $baseSlug ?: ('cat-' . time());
        }
        $data['advance_payment_required'] = isset($data['advance_payment_required']) ? 1 : 0;
        $data['show_on_home'] = isset($data['show_on_home']) ? (int)$data['show_on_home'] : 1;
        $data['is_active'] = isset($data['is_active']) ? (int)$data['is_active'] : 1;
        $data['priority'] = isset($data['priority']) ? (int)$data['priority'] : 0;

        return $data;
    }

    public function GetCategoriesWithChild()
    {
        $query = Category::whereNull('parent_id')
            ->with('childrenCategories')
            ->where('is_deleted', 0);

        try {
            if (Schema::hasColumn('categories', 'is_active')) {
                $query->where('is_active', 1);
            }
            if (Schema::hasColumn('categories', 'priority')) {
                $query->orderBy('priority', 'asc');
            }
        } catch (\Throwable $e) {
            // fallback
        }

        return $query->orderBy('id', 'asc')->get();
    }

    public function ParentCategories()
    {
        $query = Category::where('parent_id', null)->where('is_deleted', 0);
        try {
            if (Schema::hasColumn('categories', 'priority')) {
                $query->orderBy('priority', 'asc');
            }
        } catch (\Throwable $e) {}
        return $query->get();
    }

    public static function toggleHide($request)
    {
        return Category::where('id', $request->id)->update(['is_deleted' => $request->hide]);
    }

    public static function destroy($keys = null)
    {
        return Category::where('id', $keys)->update(['is_deleted' => 1]);
    }

    public function GetProductsByCategory($slug)
    {
        return Category::with('products')->where('categories.slug', $slug)->first();
    }
}
