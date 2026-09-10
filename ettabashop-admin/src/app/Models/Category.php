<?php

namespace App\Models;
use App\Traits;
use App\Traits\CommonFunctions;
use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
//    public static function all($keys = null)
//    {
//        return Category::where('is_deleted',0)->get();
//    }

    public function GetCategoriesWithChild()
    {
        $categories = Category::whereNull('parent_id')
            ->with('childrenCategories')
            ->where('is_deleted',0)
            //->orderBy('priority','asc')
            ->get();
        return $categories;
    }

    public function ParentCategories()
    {
        return Category::where('parent_id',null)->where('is_deleted',0)->orderBy('priority','asc')->get();
    }
    public static function toggleHide($request)
    {
        return Category::where('id',$request->id)->update(['is_deleted'=>$request->hide]) ;
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
