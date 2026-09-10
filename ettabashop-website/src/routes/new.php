<?php

use App\Http\Controllers\NewV\HomeController;
use App\Http\Controllers\NewV\ProductController;
use Illuminate\Support\Facades\Route;

Route::prefix('new')->group(function(){
    Route::get('/' ,[HomeController::class, 'home'])->name('new.home');
    Route::prefix('products')->group(function(){
        Route::get('/{id}' ,[ProductController::class, 'details'])->name('new.product.show');
    });
    Route::prefix('cart')->group(function(){
        Route::get('add-to-cart')->name('new.cart.add');
    });
    Route::prefix('checkout')->group(function(){
        Route::get('/')->name('new.checkout.buyNow');
    });
});