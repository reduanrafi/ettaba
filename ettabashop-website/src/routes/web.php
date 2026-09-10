<?php

use App\Http\Controllers\Api\V1\AnonymousOrderController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Website\CheckoutController;
use App\Http\Controllers\Website\DirectOrderController;
use App\Http\Controllers\Website\IndexController;
use App\Http\Controllers\Website\ProductController;
use App\Http\Controllers\Website\Profile\ProfileController;
use App\Http\Controllers\Website\Profile\OrderController as order;
use App\Http\Controllers\Website\Profile\WithdrawRequestController;
use App\Http\Controllers\Website\ShopController;
use App\Http\Controllers\Website\StaticPageController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('route:clear');
    $exitCode = Artisan::call('view:clear');
    return redirect()->back();
});


//WEBSITE PUBLIC ROUTES
Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/register', function () {
    return view('auth.login');
});

Route::get('/forgot-password', [\App\Http\Controllers\Website\PasswordController::class, 'forgotPassword'])->name('forgotPassword');
Route::post('/generate-otp', [\App\Http\Controllers\Website\PasswordController::class, 'GenerateOTP'])->name('generateOtp');
Route::post('/verify-otp', [\App\Http\Controllers\Website\PasswordController::class, 'VerifyOTP'])->name('verifyOTP');
Route::post('/reset-password', [\App\Http\Controllers\Website\PasswordController::class, 'ForgotPasswordChange'])->name('resetPassword');

Route::get('/test', [\App\Http\Controllers\Website\TestController::class, 'index'])->name('website.index');
Route::view('/design-v2', 'website.home_new')->name('website.design-v2');


Route::get('/', [IndexController::class, 'index'])->name('website.index');
Route::get('/products/{slug}', [IndexController::class, 'products'])->name('products');
Route::get('/category-products/{slug}', [ProductController::class, 'CategoryProducts'])->name('categoryProducts');
Route::get('/all-products/', [ProductController::class, 'AllProducts'])->name('allProducts');
Route::get('/search', [IndexController::class, 'search'])->name('search');
Route::get('/detail/{slug}', [IndexController::class, 'detail'])->name('product.detail');

Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('website.checkout');
Route::post('/api/check-cart-payment-rules', [CheckoutController::class, 'checkCartPaymentRules']);
Route::get('/about', [IndexController::class, 'about'])->name('website.about');
Route::get('/contact', [IndexController::class, 'contact'])->name('website.contact');

Route::get('/shop', [ShopController::class, 'index'])->name('website.shop');
Route::get('/shop-search', [ShopController::class, 'search'])->name('shop.search');
Route::get('/shop-detail/', [ShopController::class, 'detail'])->name('shop.detail');

//Legal Routes

Route::get('/privacy', [StaticPageController::class, 'privacy'])->name('website.privacy');
Route::get('/terms', [StaticPageController::class, 'terms'])->name('website.terms');
Route::post('/save-anonymous-order', [AnonymousOrderController::class, 'SaveOrder']);
/* Auth routes */
Auth::routes();
Route::get('/logout', [LoginController::class, 'logout']);
Route::group(['prefix' => 'orders'], function () {
    Route::post('/save', [OrderController::class, 'SaveOrder']);

    Route::get('/by-user/', [OrderController::class, 'GetOrdersByUser']);
    Route::get('/by-order-id/{id}', [OrderController::class, 'GetOrderDetail']);
    Route::get('/status/{id}', [OrderController::class, 'GetOrderStatus']);


});

Route::post('direct-order', [DirectOrderController::class, 'Order'])->name('directOrder');

/*Auth restricted routes*/

Route::group(['middleware' => 'auth'], function () {
    Route::group(['prefix' => 'profile'], function () {

        Route::get('/index', [ProfileController::class, 'GetProfile'])->name('user.profile');
        Route::get('/orders', [ProfileController::class, 'Orders'])->name('profile.order');
        Route::get('/hand-cash-orders', [\App\Http\Controllers\Website\Profile\OrderController::class, 'handcashOrders'])->name('profile.handcash.order');
        Route::get('/order-detail', [order::class, 'Detail'])->name('order.detail');
        Route::get('/order-requests', [DirectOrderController::class, 'DirectOrders'])->name('directOrders');
        Route::post('/save', [ProfileController::class, 'SaveProfile'])->name('profile.save');

        Route::get('/trainings', [ProfileController::class, 'Trainings'])->name('profile.trainings');
        Route::get('/referral', [ProfileController::class, 'Referral'])->name('profile.referral');
        Route::get('/withdraw-requests', [WithdrawRequestController::class, 'index'])->name('profile.withdrawRequests');
        Route::get('/withdraw-histories', [WithdrawRequestController::class, 'histories'])->name('profile.withdrawHistories');
        Route::post('/withdraw-request-save', [WithdrawRequestController::class, 'Store'])->name('profile.withdrawRequestSave');
        Route::post('/virtual-balance-save', [\App\Http\Controllers\Website\VirtualBalanceController::class, 'Store'])->name('profile.virtualBalanceSave');
        Route::get('/team-tree', [ProfileController::class, 'TeamTree'])->name('profile.teamTree');

        Route::group(['middleware' => 'active-users'], function () {
        });
    });



});

Route::get('/diagnose-and-fix-payment', function() {
    $results = [];

    // 1. Run migrations
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate');
        $results['Migrations'] = 'Executed: ' . \Illuminate\Support\Facades\Artisan::output();
    } catch (\Exception $e) {
        $results['Migrations_Error'] = $e->getMessage();
    }

    // 2. Clear all Laravel caches and OPcache
    try {
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('config:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        
        $opcacheCleared = false;
        if (function_exists('opcache_reset')) {
            $opcacheCleared = opcache_reset();
        }
        
        $results['Caches'] = 'All Laravel caches cleared successfully! OPcache cleared: ' . ($opcacheCleared ? 'Yes' : 'No/Not active');
    } catch (\Exception $e) {
        $results['Caches_Error'] = $e->getMessage();
    }

    // 3. Inspect database schema and apply SQL fix if needed
    try {
        $column = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM orders WHERE Field = 'status'");
        if (!empty($column)) {
            $type = $column[0]->Type;
            $results['Database_Status_Column_Type'] = $type;
            if (str_contains($type, 'pending_payment')) {
                $results['Database_Status'] = "OK - 'pending_payment' exists in ENUM options.";
            } else {
                $results['Database_Status'] = "ERROR - 'pending_payment' was MISSING from ENUM. Running SQL fix...";
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'accepted', 'canceled', 'on_delivery', 'delivered', 'completed', 'pending_payment') DEFAULT 'pending'");
                \Illuminate\Support\Facades\DB::statement("ALTER TABLE anonymous_orders MODIFY COLUMN status ENUM('pending', 'accepted', 'canceled', 'on_delivery', 'delivered', 'completed', 'pending_payment') DEFAULT 'pending'");
                $results['Database_Manual_Fix'] = "Successfully ran ALTER TABLE SQL queries.";
            }
        } else {
            $results['Database_Status_Column'] = "Not found!";
        }
    } catch (\Exception $e) {
        $results['Database_Check_Error'] = $e->getMessage();
    }

    // 4. Verify Model Fillable Status
    try {
        $orderModel = new \App\Models\Order();
        $fillable = $orderModel->getFillable();
        $results['Order_Model_Fillable'] = $fillable;
        if (in_array('status', $fillable)) {
            $results['Order_Model_Status'] = "OK - 'status' is fillable.";
        } else {
            $results['Order_Model_Status'] = "ERROR - 'status' is not fillable! Ensure you uploaded the updated app/Models/Order.php file.";
        }
    } catch (\Exception $e) {
        $results['Order_Model_Error'] = $e->getMessage();
    }

    // 5. Read last 150 lines of storage/logs/laravel.log and extract relevant errors
    try {
        $logPath = storage_path('logs/laravel.log');
        if (file_exists($logPath)) {
            $file = file($logPath);
            $linesCount = count($file);
            $lastLines = array_slice($file, max(0, $linesCount - 150));
            $filteredLines = [];
            foreach ($lastLines as $index => $line) {
                if (preg_match('/(error|exception|eps|payment|order|pointhistory)/i', $line)) {
                    $filteredLines[] = ($linesCount - 150 + $index + 1) . ': ' . trim($line);
                }
            }
            $results['Log_Filtered_Lines'] = array_slice($filteredLines, -50); // limit to last 50 matches for readability
        } else {
            $results['Log_File'] = "Not found!";
        }
    } catch (\Exception $e) {
        $results['Log_Read_Error'] = $e->getMessage();
    }

    // 6. Check APP_URL and Route generation
    try {
        $results['App_Url_Config'] = config('app.url');
        $results['Route_Success_Generated'] = route('payment.success');
    } catch (\Exception $e) {
        $results['Route_Check_Error'] = $e->getMessage();
    }

    return response()->json($results, 200, [], JSON_PRETTY_PRINT);
});

// EPS Payment Callback Routes
Route::match(['get', 'post'], '/payment/success', [\App\Http\Controllers\Website\EpsPaymentController::class, 'paymentSuccess'])->name('payment.success');
Route::match(['get', 'post'], '/payment/fail', [\App\Http\Controllers\Website\EpsPaymentController::class, 'paymentFail'])->name('payment.fail');
Route::match(['get', 'post'], '/payment/cancel', [\App\Http\Controllers\Website\EpsPaymentController::class, 'paymentCancel'])->name('payment.cancel');

//URL::forceScheme('https');


require __DIR__ . '/new.php';