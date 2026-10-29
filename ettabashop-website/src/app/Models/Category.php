<?php

namespace App\Models;

use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

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
        $data['advance_payment_required'] = isset($data['advance_payment_required']) ? 1 : 0;

        return $data;
    }

    public static function all($keys = null)
    {
        return Category::where('is_deleted', 0)->get();
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

    public function GetCategoryWithChildBySlug($slug)
    {
        return Category::with('childrenCategories')
            ->where('is_deleted', 0)
            ->where('slug', $slug)
            ->get();
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

    public function ChildCategories($id)
    {
        return Category::where('parent_id', $id)->where('is_deleted', 0)->get();
    }

    public function GetChildCategoryIds($categories)
    {
        $ids = [];
        $subIds = [];
        $stubCategories = [];
        foreach ($categories as $k => $category) {
            $ids[$k] = $category->id;
            if ($category->childrenCategories != null) {
                foreach ($category->childrenCategories as $key => $cat) {
                    $subIds[$key] = $cat->id;
                    if ($cat->categories != null) {
                        foreach ($cat->categories as $j => $sub) {
                            $stubCategories[$j] = $sub->id;
                        }
                    }
                }
            }
        }

        return array_values(array_unique(array_merge($ids, $subIds, $stubCategories)));
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
