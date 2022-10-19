<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::group(['prefix' => 'bigbuy'], function () {
        //get bigbuy all importable products id [array format]
        Route::post('importable/products','Api\Bigbuy\ProductApiController@getBigbuyImportableProduct');
        //get bigbuy all updateable products id [array format]
        Route::post('updateable/products','Api\Bigbuy\ProductApiController@getBigbuyUpdateableProducts');
        
        //get bigbuy single product details by ds product id
        Route::get('product/details/{dsProductId?}','Api\Bigbuy\ProductApiController@getBigbuyProductDetailsByDsProductId');
        
        //get bigbuy product variation by ds product id 
        Route::get('product/variation/{dsProductId?}','Api\Bigbuy\ProductApiController@getProductVariationByDsProductId');
        

        Route::post('product/import','Api\Bigbuy\ProductApiController@getImportableProduct');
        Route::post('product/update','Api\Bigbuy\ProductApiController@getUpdateableProduct');
    });

});


Route::post('/give-me-my-coupon', 'Api\SubscribeController@subscribe')->name('give.me.my.coupon');


