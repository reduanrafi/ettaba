<?php

namespace App\Observers;
use App\Models\Product;

use App\Services\ProductService;
use Illuminate\Http\Client\Request;
use Illuminate\Routing\Route;

class ProductObserver
{

    /**
     * Handle the order "created" event.
     *
     * @param \App\Models\Product $product
     * @param \App\Services\prductService
     * @return void
     */
    public function created(Product $product)
    {
        $productService = new ProductService();

        $productService->UpdateSlug($product);
        $productService->UpdateUniqueID($product);

    }

    /**
     * Handle the order "updated" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function updated(Product $product)
    {
        //
    }

    /**
     * Handle the order "deleted" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function deleted(Product $product)
    {
        //
    }

    /**
     * Handle the order "restored" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function restored(Product $product)
    {
        //
    }

    /**
     * Handle the order "force deleted" event.
     *
     * @param \App\Models\Product $product
     * @return void
     */
    public function forceDeleted(Product $product)
    {
        //
    }
}
