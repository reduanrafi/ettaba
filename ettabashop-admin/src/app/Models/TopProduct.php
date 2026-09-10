<?php

namespace App\Models;

use App\Services\TopProductService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TopProduct extends Model
{
    use HasFactory;
    protected $fillable=['product_id','type_id'];

    private $service;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->service = new TopProductService();
    }

    public function UpdateTopProducts($data)
    {

        $typeId = intval($data['id']);

        //$this->service->SaveTopProducts($this->service->GetCategoryProduct(7),$typeId);
        if ($typeId==1)
        {
            $this->service->SaveTopProducts($this->service->GetLatestProduct(),$typeId);
            return true;
        }
        elseif ($typeId==2)
        {
            $this->service->SaveTopProducts($this->service->GetFeaturedProducts(),$typeId);
            return true;
        }
        elseif ($typeId==3)
        {
            $this->service->SaveTopProducts($this->service->GetMostRatedProducts(),$typeId);
            return true;
        }
        elseif ($typeId==4)
        {
            $this->service->SaveTopProducts($this->service->GetDiscountedProducts(),$typeId);
            return true;
        }
        elseif ($typeId==7)
        {

            $this->service->SaveTopProducts($this->service->GetMostPointProducts(),$typeId);
            return true;

        }
        elseif($typeId>=8 && $typeId<=13)
        {
            $this->service->SaveTopProducts($this->service->GetCategoryProduct($typeId),$typeId);
            return true;

        }
        return false;
    }


}
