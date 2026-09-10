<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DirectSeller\DashboardController;
use App\Http\Controllers\DirectSeller\ProductController;
use App\Http\Controllers\DirectSeller\OrderController;
use App\Http\Controllers\DirectSeller\AddMoneyController;

Route::group(['middleware' => ['auth', 'direct_seller']], function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('direct-seller.dashboard');

    // Products CRUD
    Route::get('/products', [ProductController::class, 'index'])->name('direct-seller.product.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('direct-seller.product.create');
    Route::post('/products', [ProductController::class, 'store'])->name('direct-seller.product.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('direct-seller.product.edit');
    Route::put('/products/{id}', [ProductController::class, 'update'])->name('direct-seller.product.update');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('direct-seller.product.destroy');

    // Orders / Sales Confirm
    Route::get('/orders', [OrderController::class, 'index'])->name('direct-seller.order.index');
    Route::get('/orders/search-customer', [OrderController::class, 'searchCustomer'])->name('direct-seller.order.search_customer');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('direct-seller.order.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('direct-seller.order.store');

    // Add Money Balance Replenishment
    Route::get('/add-money', [AddMoneyController::class, 'create'])->name('direct-seller.add_money.create');
    Route::post('/add-money/initiate', [AddMoneyController::class, 'initiate'])->name('direct-seller.add_money.initiate');
    Route::get('/add-money/success/{transaction_id}', [AddMoneyController::class, 'paymentSuccess'])->name('direct-seller.add_money.success');
    Route::get('/add-money/fail/{transaction_id}', [AddMoneyController::class, 'paymentFail'])->name('direct-seller.add_money.fail');
    Route::get('/add-money/cancel/{transaction_id}', [AddMoneyController::class, 'paymentCancel'])->name('direct-seller.add_money.cancel');
    Route::get('/add-money/gateway/{transaction_id}', [AddMoneyController::class, 'simulateGateway'])->name('direct-seller.add_money.gateway');
    Route::post('/add-money/gateway/{transaction_id}/process', [AddMoneyController::class, 'simulateProcess'])->name('direct-seller.add_money.gateway.process');
});
