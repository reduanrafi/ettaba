<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandCashCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'parent_id',
        'slug',
        'name',
        'image',
        'icon',
        'slug'
    ];
    public function User()
    {
        return $this->hasMany(User::class,'user_id');
    }
    public function products()
    {
        return $this->hasMany(HandCashProduct::class,'category_id')->where('products.is_deleted','=',0);
    }
    public function categories()
    {
        return $this->hasMany(HandCashCategory::class,'parent_id');
    }
    public function childrenCategories()
    {
        return $this->hasMany(HandCashCategory::class,'parent_id')->with('hand_cash_category')->where('is_deleted','=',0);
    }

    public function GetData($data)
    {

        return $data;
    }
}
