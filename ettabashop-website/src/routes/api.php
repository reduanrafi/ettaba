<?php

use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\SliderController;
use App\Http\Controllers\Api\V1\TopTypeController;
use Illuminate\Support\Facades\Route;

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
Route::post('/check-otp',['uses'=>'Api\V1\AuthController@VerifyOTP']);

Route::post('/forget-password',['uses'=>'Api\V1\AuthController@ForgetPassword']);
Route::post('/update-password',['uses'=>'Api\V1\AuthController@ForgotPasswordChange']);

Route::post('/register',[AuthController::class,"register"]);
Route::post('/password-reset-token',['uses'=>'Api\V1\AuthController@CreatePasswordResetToken']);
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

    Route::get('/by-brand/{id}', ['uses' => 'Api\V1\ProductController@GetProductsByBrand']);
    Route::get('/{id}/details', ['uses' => 'Api\V1\ProductController@GetProductDetail']);
    Route::get('/offers', ['uses' => 'Api\V1\ProductController@TodayOffers']);

});
Route::group(['prefix' => 'orders'], function () {
    Route::post('/save', [OrderController::class,'SaveOrder']);
    Route::get('/by-user/',[OrderController::class,'GetOrdersByUser']);
    Route::get('/by-order-id/{id}',[OrderController::class,'GetOrderDetail']);
    Route::get('/status/{id}',[OrderController::class,'GetOrderStatus']);


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

        Route::post('/change-email', ['uses' => 'Api\V1\ProfileController@ChangeEmail']);
        Route::post('/change-phone', ['uses' => 'Api\V1\ProfileController@ChangePhone']);

    });

    Route::group(['prefix'=>'address'],function(){
        Route::post('/save', [AddressController::class ,'SaveAddress']);
        Route::get('/get', [AddressController::class ,'GetAddress']);
        Route::get('//delete/{id}', [AddressController::class ,'DeleteAddress']);
       
    });

    Route::group(['prefix' => 'orders'], function () {
        Route::post('/save', [OrderController::class,'SaveOrder']);
        Route::get('/by-user/',[OrderController::class,'GetOrdersByUser']);
        Route::get('/by-order-id/{id}',[OrderController::class,'GetOrderDetail']);
        Route::get('/status/{id}',[OrderController::class,'GetOrderStatus']);

    });
});
//URL::forceScheme('https');