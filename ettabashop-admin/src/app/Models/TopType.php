<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TopType extends Model
{
    protected $fillable=['name'];

    public function products()
    {
        return $this->belongsToMany('App\Models\Product', 'top_products',
            'type_id', 'product_id')->where('products.is_deleted',0);
    }

    public function TopTypes()
    {
        return TopType::all();
    }
    public function TopProducts($id)
    {
        return TopType::with(['products'])->find($id);
    }

}
