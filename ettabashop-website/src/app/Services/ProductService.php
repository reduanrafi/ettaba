<?php


namespace App\Services;


use Illuminate\Support\Facades\DB;

class ProductService
{
    public function GetHomeProducts()
    {

        $products =  DB::select(DB::raw('
                        
                        SELECT * FROM top_products tp
                        JOIN products p on p.id = tp.product_id 
                
                '));


        return $this->MakeHomeProducts($products);
    }
    public function MakeHomeProducts($homeProducts)
    {
        $products =[];
        foreach ($homeProducts as $k=>$product)
        {
            if ($product->type_id==1)
            {
                $products['new'][$k]=$product;

            }

            else if ($product->type_id==2)
            {
                $products['featured'][$k]=$product;
            }

            else if ($product->type_id==3)
            {
                $products['topRated'][$k]=$product;
            }
            else if ($product->type_id==4)
            {
                $products[$k]['mostDiscounted']=$product;
            }
            else if ($product->type_id==5)
            {
                $products[$k]['offers']=$product;
            }
            else if ($product->type_id==6)
            {
                $products[$k]['discountProduct']=$product;
            }

            else if ($product->type_id==7)
            {
                $products['mostPoint'][$k]=$product;
            }
            else if ($product->type_id==8)
            {
                $products['dailyNeeds'][$k]=$product;
            }
            else if ($product->type_id==9)
            {
                $products['fashion'][$k]=$product;
            }

            else if ($product->type_id==10)
            {
                $products['library'][$k]=$product;
            }
            else if ($product->type_id==11)
            {
                $products['jewelry'][$k]=$product;
            }
            else if ($product->type_id==12)
            {
                $products['electronicGoods'][$k]=$product;
            }
            else if ($product->type_id==13)
            {
                $products['electronicAccessories'][$k]=$product;
            }
        }
        //dd($products['featured']);
        return $products;
    }
}