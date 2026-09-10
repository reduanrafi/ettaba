<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SearchKeywordController;
use App\Http\Controllers\Api\V1\SliderController;
use App\Http\Controllers\Api\V1\TopTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login',[AuthController::class,"login"]);
Route::post('/check-otp', [AuthController::class, 'VerifyOTP']);

Route::post('/forget-password', [AuthController::class, 'ForgetPassword']);
Route::post('/update-password', [AuthController::class, 'ForgotPasswordChange']);

Route::post('/register',[AuthController::class,"register"]);
Route::post('/password-reset-token', [AuthController::class, 'CreatePasswordResetToken']);
//Route::post('/logout',['uses'=>'Api\V1\AuthController@Logout']);

Route::group(['prefix'=>'sliders'],function(){
    Route::get('/',[SliderController::class,'GetSliders']);
});

Route::group(['prefix'=>'categories'],function(){
    Route::get('/',[CategoryController::class,'GetCategories']);
    Route::get('/{slug}/products',[CategoryController::class,'GetProductsByCategory']);
});

Route::group(['prefix' => 'top-types'], function(){
    Route::get('/',[TopTypeController::class,'TopTypes']);
    Route::get('/{id}',[TopTypeController::class,'Products']);

});

Route::group(['prefix' => 'products'], function () {
    //  Route::get('/{id}', ['uses' => 'Api\V1\ProductController@Products']);
    Route::get('/search/{keywords}', [ ProductController::class,'Search']);
    Route::get('/detail/{id}', [ ProductController::class,'GetProductDetail']);

    Route::get('/by-brand/{id}', [ProductController::class, 'GetProductsByBrand']);
    Route::get('/{id}/details', [ProductController::class, 'GetProductDetail']);
    Route::get('/offers', [ProductController::class, 'TodayOffers']);

});

/***
 * Auth restricted routes
 */
Route::group(['middleware'=>['auth:sanctum']],function () {
    Route::get('/les',[SliderController::class,'GetSliders']);
    Route::post('/logout', [AuthController::class,"logout"]);

    Route::group(['prefix'=>'profile'],function(){

        //One route for save or update profile.
        Route::post('/save', [ProfileController::class,'SaveProfile']);

        Route::get('/get', [ProfileController::class,'GetProfile']);
        Route::get('/referral', [ProfileController::class,'CreateReferralCode']);
        Route::post('/update', [ProfileController::class,'UpdateProfile']);

        Route::post('/change-email', [ProfileController::class, 'ChangeEmail']);
        Route::post('/change-phone', [ProfileController::class, 'ChangePhone']);

    });

    Route::group(['prefix'=>'address'],function(){
        Route::post('/save', [AddressController::class ,'SaveAddress']);
        Route::get('/get', [AddressController::class ,'GetAddress']);
        Route::get('//delete/{id}', [AddressController::class ,'DeleteAddress']);
       
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::post('/save', [OrderController::Class,'SaveOrder']);
        Route::get('/by-user/',[OrderController::Class,'GetOrdersByUser']);
        Route::get('/by-order-id/{id}',[OrderController::Class,'GetOrderDetail']);
        Route::get('/status/{id}',[OrderController::Class,'GetOrderStatus']);


    });
});
//URL::forceScheme('https');