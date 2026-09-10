<?php

use App\Http\Controllers\Admin\AnonymousOrderController;
use App\Http\Controllers\Admin\BlockedUserController;
use App\Http\Controllers\Admin\BonusDistributionController;
use App\Http\Controllers\Admin\BrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DirectOrderController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\OrderStatusController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductImageController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\TestController;
use App\Http\Controllers\Admin\TopProductController;
use App\Http\Controllers\Admin\WithdrawRequestController;
use App\Http\Controllers\Admin\MerchantController;
use App\Http\Controllers\Admin\MerchantHistoryController;
use App\Http\Controllers\Shopper\WithdrawRequestController as WRController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\website\IndexController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;


Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    return redirect()->back();
});
Route::get('/test', [TestController::class, 'test']);

//WEBSITE PUBLIC ROUTES
Route::get('/', function () {
    return view('auth.login');
})->middleware('guest')->name('website.index');

Route::get('/register', function () {
    return view('auth.login');
});
Route::get('/about', [IndexController::class, 'about'])->name('website.about');
Route::get('/contact', [IndexController::class, 'contact'])->name('website.contact');
Route::get('/detail/{id}', [IndexController::class, 'detail'])->name('product.detail');

/* Auth routes */
Auth::routes();
Route::get('/logout', [LoginController::class, 'logout']);

Route::get('/forgot-password', [\App\Http\Controllers\Auth\PasswordController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/generate-otp', [\App\Http\Controllers\Auth\PasswordController::class, 'GenerateOTP'])->name('generateOtp');
Route::post('/verify-otp', [\App\Http\Controllers\Auth\PasswordController::class, 'VerifyOTP'])->name('verifyOTP');
Route::post('/reset-password', [\App\Http\Controllers\Auth\PasswordController::class, 'ForgotPasswordChange'])->name('resetPassword');

/*Auth restricted routes*/

Route::group(['prefix' => 'private-panel', 'middleware' => ['auth']], function () {


    Route::get('/index', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/admin', [DashboardController::class, 'AdminDashboard'])->name('dashboard.admin');
    Route::get('/shop-dashboard', [DashboardController::class, 'ShopDashboard'])->name('dashboard.shop');
    Route::get('/sales-dashboard', [DashboardController::class, 'SalesDashboard'])->name('dashboard.sales');
    Route::get('/hand-cash-dashboard', [DashboardController::class, 'HandCashDashboard'])->name('dashboard.handcash');


    /**********************************************************************************/
    /*********************************Admin Routes************************************/
    /**********************************************************************************/

    Route::group(['middleware' => 'admin'], function () {

//        single routes
        Route::get('/toggle-hide-categories', [CategoryController::class,'toggleHide'])->name('category.toggleHide');
        Route::get('/toggle-featured-product', [ProductController::class,'MarkUnMarkFeatured'])->name('product.toggleFeature');


        Route::resource('/slider', SliderController::class);
        Route::resource('/brand', BrandController::class);

        Route::resource('/category', CategoryController::class);



        Route::group( ['blocked-user'],  function ()
        {

            Route::get('/blocked-users', [BlockedUserController::class,'index'])->name('blockedUser.index');
            Route::post('/store-blocked-users', [BlockedUserController::class,'Store'])->name('blockedUser.store');
            Route::get('/remove-blocked-users/{id}', [BlockedUserController::class,'delete'])->name('blockedUser.remove');
            Route::get('/check-nid', [BlockedUserController::class,'CheckNID'])->name('blockedUser.checkNID');

        });


        Route::group( ['prefix'=>'bonus'],  function ()
        {

            Route::get('/index', [BonusDistributionController::class,'index'])->name('bonus.index');
            Route::get('/daily-bonus', [BonusDistributionController::class,'DailyBonusDistribution'])->name('bonus.daily');


        });
        Route::group( ['prefix'=>'virtual-balance'],  function ()
        {

            Route::get('/index', [\App\Http\Controllers\Admin\VirtualBalanceController::class,'index'])
                ->name('vb.index');
            Route::get('/status-update', [\App\Http\Controllers\Admin\VirtualBalanceController::class,'ChangeStatus'])
                ->name('vb.status');
            Route::post('/gateway-charges/update', [\App\Http\Controllers\Admin\GatewayChargeController::class, 'update'])
                ->name('admin.gateway-charges.update');

        });
    });


    /**********************************************************************************/
    /******************************Sales stuff Routes************************************/
    /**********************************************************************************/

    Route::group(['middleware' => 'sales'], function () {
        Route::group(['prefix' => 'top-products'], function ()
        {
            Route::get('index', [TopProductController::class,'index'])->name('topProduct.index');
            Route::get('update', [TopProductController::class,'update'])->name('topProduct.update');
            Route::get('delete/{id}', [TopProductController::class,'delete'])->name('topProduct.delete');

        });
        Route::group(['prefix' => 'withdraw-requests'], function ()
        {
            Route::get('index', [WithdrawRequestController::class,'index'])->name('withdrawRequest.index');
            Route::get('histories', [WithdrawRequestController::class,'Histories'])->name('withdrawRequest.histories');
            Route::get('update', [WithdrawRequestController::class,'update'])->name('withdrawRequest.update');
            Route::get('done', [WithdrawRequestController::class,'done'])->name('withdrawRequest.done');
            Route::get('/delete/{id}', [WithdrawRequestController::class,'delete'])->name('withdrawRequest.delete');


        });
        Route::group( ['customer'],  function ()
        {
            Route::resource('/customer',  CustomerController::class);

            Route::get('/change-customer-status', [CustomerController::class,'ChangeStatus'])->name('customer.changeStatus');
            Route::get('/change-customer-search-access', [CustomerController::class,'ChangeSearchAccess'])->name('customer.changeSearchAccess');
            Route::get('/add-referral-bonus', [CustomerController::class,'AddReferralBonus'])->name('customer.addReferralBonus');
            Route::post('/customer-update-referral-limit', [CustomerController::class,'UpdateReferralLimit'])->name('customer.referralLimit');

        });
        Route::group( ['stores'],  function ()
        {
            Route::resource('/store',  StoreController::class);
            Route::get('/change-shop-status', [StoreController::class,'ChangeStatus'])->name('store.changeStatus');

        });
        Route::group( ['merchants'],  function ()
        {
            Route::resource('/merchant',  MerchantController::class);
            Route::get('/change-merchant-status', [MerchantController::class,'ChangeStatus'])->name('merchant.changeStatus');
        });
        Route::get('/merchant-history', [MerchantHistoryController::class, 'Index'])->name('merchant.history');
        Route::resource('/product', ProductController::class);
        Route::resource('/product-image', ProductImageController::class);
        Route::get('/hide-product/{product}', [ProductController::class,'Hide'])->name('product.softDelete');
        Route::get('/un-hide-product/{product}', [ProductController::class,'UnHide'])->name('product.revert');
        Route::get('/delete-product/{product}', [ProductController::class,'Delete'])->name('product.hardDelete');
        Route::get('/deleted-products/', [ProductController::class,'HiddenProducts'])->name('product.deleted');
        Route::get('/delete-product-image/{product}', [ProductImageController::class,'Delete'])->name('image.hardDelete');
    });

    /**********************************************************************************/
    /******************************Shop Owner Routes************************************/
    /**********************************************************************************/

    Route::group(['middleware' => ['shop']], function () {

        Route::resource('/shop',  ShopController::class);

        Route::get('/my-withdraws/',   [WRController::class,'index'])->name('mywithdraws');
        Route::get('/create-withdraw-request',   [WRController::class,'create'])->name('withdraw.create');
        Route::post('/save-withdraw-request',   [WRController::class,'Store'])->name('withdraw.save');


    });


   // Route::get('/invoice', 'Admin\InvoiceController@inv')->name('invoice');
    Route::get('/order/show', function () {
        return view('admin.Orders.show');
    });


    Route::group(['prefix' => 'orders'], function () {
        Route::get('/', [OrderController::class,'Index'])->name('order.index');
        Route::get('/detail/{id}', [OrderController::class,'Detail'])->name('order.detail');
        Route::get('/remove-item/', [\App\Http\Controllers\Admin\OrderItemController::class,'RemoveItem'])->name('item.remove');
        Route::get('/invoice/{id}', [OrderController::class,'invoice'])->name('order.invoice');
        Route::get('/shop', [OrderController::class,'Shop'])->name('order.shop');
        Route::get('/change-status', [OrderStatusController::class,'ChangeStatus'])->name('order.changeStatus');
        Route::get('/order-done', [OrderStatusController::class,'Done'])->name('order.done');
        Route::get('/verify-eps/{id}', [OrderController::class,'verifyEpsPayment'])->name('order.verifyEps');
        Route::get('/pending-eps', [OrderController::class,'pendingEpsOrders'])->name('order.pendingEps');
        Route::post('/bulk-verify-eps', [OrderController::class,'bulkVerifyEps'])->name('order.bulkVerifyEps');

//        Route::get('/refund', ['uses' => 'Admin\OrderItemController@Refund', 'as' => 'order.refund']);
//        Route::get('remove-items/{id}', ['uses' => 'Admin\OrderItemController@RemoveItem', 'as' => 'order.removeItem']);
//        Route::get('delete/{id}', ['uses' => 'Admin\OrderController@destroy', 'as' => 'order.delete']);
//        Route::get('invoice/{id}', ['uses' => 'Admin\OrderController@invoice', 'as' => 'order.invoice']);
//        Route::post('add-items/{id}', ['uses' => 'Admin\OrderItemController@AddItem', 'as' => 'order.addItem']);
//
//        Route::get('monthly-order-graph', 'Admin\OrderReportController@MonthlyOrderReportGraph')->name('monthlyOrderGraph');
//        Route::get('yearly-order-graph', 'Admin\OrderReportController@YearlyOrderReportGraph')->name('yearlyOrderGraph');
//        Route::get('daily-order-graph', 'Admin\OrderReportController@DailyOrderReportGraph')->name('dailyOrderGraph');
//        Route::get('delivered_orders', 'Admin\OrderController@GetDeliveredOrders')->name('deliveredOrdersIndex');
//        Route::get('undelivered_orders', 'Admin\OrderController@GetUnDeliveredOrders')->name('unDeliveredOrdersIndex');
//        Route::get('list-ssl-ipn-response', 'Admin\OrderController@SSLResponseIndex')->name('sslIpnResposne');

    });
    Route::group(['prefix' => 'anonymous-orders'], function () {
        Route::get('/', [AnonymousOrderController::class,'Index'])->name('anonymousOrder.index');
        Route::get('/detail/{id}', [AnonymousOrderController::class,'Detail'])->name('anonymousOrder.detail');
        Route::get('/remove-item/', [\App\Http\Controllers\Admin\AnonymousOrderItemController::class,'RemoveItem'])->name('anonymousitem.remove');
        Route::get('/invoice/{id}', [AnonymousOrderController::class,'invoice'])->name('anonymousOrder.invoice');
        Route::get('/shop', [AnonymousOrderController::class,'Shop'])->name('anonymousOrder.shop');
        Route::get('/change-status', [AnonymousOrderController::class,'ChangeStatus'])->name('anonymousOrder.changeStatus');
        Route::get('/order-done', [AnonymousOrderController::class,'Done'])->name('anonymousOrder.done');

    });

    Route::group(['prefix' => 'direct_orders'], function () {
        Route::get('/', [DirectOrderController::class,'Index'])->name('dOrder.index');
        Route::get('/detail/{id}', [DirectOrderController::class,'Detail'])->name('dOrder.detail');

        Route::get('/invoice/{id}', [OrderController::class,'invoice'])->name('order.invoice');
        Route::get('/shop', [OrderController::class,'Shop'])->name('order.shop');
        Route::get('/change-status', [OrderStatusController::class,'ChangeStatus'])->name('order.changeStatus');
        Route::get('/order-done', [OrderStatusController::class,'Done'])->name('order.done');

    });
});



// Hand cash related routes***************/
Route::group(['middleware' => ['handcash']], function () {


    Route::resource('/handcash-category',App\Http\Controllers\HandCash\CategoryController::class);
    Route::resource('/handcash-product',App\Http\Controllers\HandCash\ProductController::class);

    Route::get('/hand-cash-sale/',   [\App\Http\Controllers\HandCash\SaleController::class,'index'])->name('handcash.sale.index');
    Route::get('/ENC/{id}',   [\App\Http\Controllers\HandCash\SaleController::class,'index']);
    Route::get('/hand-cash-orders/',   [\App\Http\Controllers\HandCash\OrdersController::class,'index'])->name('handcash.orders.index');

    Route::get('/my-withdraws/',   [WRController::class,'index'])->name('mywithdraws');
    Route::get('/create-withdraw-request',   [WRController::class,'create'])->name('withdraw.create');
    Route::post('/save-withdraw-request',   [WRController::class,'Store'])->name('withdraw.save');

    Route::get('/add-money', [\App\Http\Controllers\HandCash\MerchantAddMoneyController::class, 'create'])->name('handcash.virtual_balance.create');
    Route::post('/add-money/initiate', [\App\Http\Controllers\HandCash\MerchantAddMoneyController::class, 'initiate'])->name('handcash.add_money.initiate');
    Route::get('/add-money/success/{transaction_id}', [\App\Http\Controllers\HandCash\MerchantAddMoneyController::class, 'paymentSuccess'])->name('handcash.add_money.success');
    Route::get('/add-money/fail/{transaction_id}', [\App\Http\Controllers\HandCash\MerchantAddMoneyController::class, 'paymentFail'])->name('handcash.add_money.fail');
    Route::get('/add-money/cancel/{transaction_id}', [\App\Http\Controllers\HandCash\MerchantAddMoneyController::class, 'paymentCancel'])->name('handcash.add_money.cancel');
    Route::get('/add-money/gateway/{transaction_id}', [\App\Http\Controllers\HandCash\MerchantAddMoneyController::class, 'simulateGateway'])->name('handcash.add_money.gateway');
    Route::post('/add-money/gateway/{transaction_id}/process', [\App\Http\Controllers\HandCash\MerchantAddMoneyController::class, 'simulateProcess'])->name('handcash.add_money.gateway.process');

    //handcash api routes

    Route::get('/hand-cash-customer/',   [\App\Http\Controllers\HandCash\SaleController::class,'searchCustomerByEId'])->name('handcash.sale.customer');
    Route::get('/hand-cash-product/',   [\App\Http\Controllers\HandCash\SaleController::class,'getProductByShortNameOrName'])->name('handcash.product');
    Route::post('/hand-cash-order-save/',   [\App\Http\Controllers\Api\V1\HandCashOrderController::class,'SaveOrder'])->name('handcash.order.save');
    Route::post('/hand-cash-direct-pay/',   [\App\Http\Controllers\Api\V1\HandCashOrderController::class,'DirectPay'])->name('handcash.direct.pay');
    Route::get('/get-price', [TestController::class, 'getPrice']);
});




//URL::forceScheme('https');
