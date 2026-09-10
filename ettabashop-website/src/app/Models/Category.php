<?php

namespace App\Models;
use App\Traits;
use App\Traits\CommonFunctions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Category extends Model
{
    use HasFactory;
    use CommonFunctions;
    /****************************
     * Property area
     *****************************/
    protected $fillable = [
        'parent_id',
        'slug',
        'name_en',
        'name_bn',
        'image',
        'icon',
        'slug',
        'advance_payment_required'
    ];
    public $category=[];
    /****************************
     * Model Relation area
     *****************************/

//    public function parent()
//    {
//        return $this->belongsTo(Category::class);
//    }
//    public function subCategories()
//    {
//        return $this->hasMany(Category::class,'parent_id');
//    }
    public function products()
    {
        return $this->hasMany(Product::class,'category_id')->where('products.is_deleted','=',0);
    }
    public function categories()
    {
        return $this->hasMany(Category::class,'parent_id');
    }
    public function childrenCategories()
    {
        return $this->hasMany(Category::class,'parent_id')->with('categories')->where('is_deleted','=',0);
    }
    
    /****************************
     * Public Methods area
     *****************************/

    /***
     * Method to get data.
     * @param $data
     * @return
     */

    public function GetData($data)
    {

        if (isset($data['image']))
        {
            $data['image']= $this->UploadImage($data['image'],'categories');

        }
        if (isset($data['icon']))
        {
            $data['icon'] = $this->UploadImage($data['icon'],'icons');

        }
        //$data['parent_id']=$this->GetParentId($data);
        $data['advance_payment_required'] = isset($data['advance_payment_required']) ? 1 : 0;

        return $data;
    }

    /**
     * Override methods
     * @param null $keys
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public static function all($keys = null)
    {
        return Category::where('is_deleted',0)->get();
    }

    public function GetCategoriesWithChild()
    {
        $categories = Category::whereNull('parent_id')
            ->with('childrenCategories')
            ->where('is_deleted',0)
            //->orderBy('priority','asc')
            ->get();
        return $categories;
    }
    public function GetCategoryWithChildBySlug($slug)
    {
        return Category::with('childrenCategories')
            ->where('is_deleted',0)
            ->where('slug',$slug)
            ->get();
//        $categories = Cache::remember($slug,15,function () use($slug) {
//
//            return Category::with('childrenCategories')
//                ->where('is_deleted',0)
//                ->where('slug',$slug)
//                ->get();
//        });
  //      return $categories;
    }
    public function ParentCategories()
    {
        return Category::where('parent_id',null)->where('is_deleted',0)->get();
    }

    public function ChildCategories($id)
    {
        return Category::where('parent_id',$id)->where('is_deleted',0)->get();
    }

    public function GetChildCategoryIds($categories)
    {
        $ids =[] ;
        $subIds = [];
        $stubCategories = [];
        foreach ($categories as $k=>$category)
        {
            $ids[$k]=$category->id;
            if ($category->childrenCategories!=null)
            {

              foreach($category->childrenCategories as $key=>$cat)
                {

                    $subIds[$key]=$cat->id;

                    if ($cat->categories!=null)
                    {

                        foreach ($cat->categories as $j=>$sub)
                        {
                            $stubCategories[$j] = $sub->id;
                        }
                    }
                }
            }
        }

        $mainIds = array_merge($ids,array_merge($subIds,$stubCategories)) ;

        return $mainIds;
    }

//    public function GetCategories()
//    {
//        $parents = Category::where('parent_id',null)->get();
//        foreach($parents as $k=>$category)
//        {
//            $parents[$k]['sub_cat']=$this->getChild($category->id);
//        }
//
//        return $parents;
//    }
//
//    public function getChild($parentId)
//    {
//        $child = Category::where('parent_id',$parentId)->get();
//        foreach ($child as $k=>$category)
//        {
//            $child[$k]['sub_cat']= $this->getChild($category->id);
//        }
//        return $child;
//    }
    public static function destroy($keys = null)
    {
        return Category::where('id',$keys)->update(['is_deleted'=>1]) ;
    }

    public function GetProductsByCategory($slug)
    {

        return Category::with('products')->where('categories.slug',$slug)->first();
    }

    /****************************
     * private Methods area
     *****************************/
    private function GetParentId($parentID)
    {
        return isset($parentID['parent_id'])?$parentID['parent_id']:NULL;

    }

    private function ImageUpload($data)
    {


    }

    private function IconUpload($data)
    {

    }
}
