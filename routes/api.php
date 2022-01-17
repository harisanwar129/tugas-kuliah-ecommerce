<?php

use App\Http\Controllers\API\FrontAPIController;
use App\Http\Controllers\API\StatistikController;
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

Route::group(['prefix' => 'v1', 'as' => 'v1.'], function() {
    Route::post('/ipaymu/notify', 'IPayMuController@notify')->name('ipaymu.notify');
    Route::post('/ipaymu/check_transaction', [FrontAPIController::class, 'check_transaction'])->name('ipaymu.check_transaction');

    Route::post('front/fetch_detail_product', [FrontAPIController::class, 'fetch_detail_product'])->name('fetch_detail_product');
    Route::post('front/add_to_cart', [FrontAPIController::class, 'add_to_cart'])->name('add_to_cart');
    Route::post('front/add_to_wishlist', [FrontAPIController::class, 'add_to_wishlist'])->name('add_to_wishlist');
    Route::post('front/update_cart_qty', [FrontAPIController::class, 'update_cart_qty'])->name('update_cart_qty');
    Route::post('front/delete_cart_item', [FrontAPIController::class, 'delete_cart_item'])->name('delete_cart_item');
    Route::post('front/delete_wishlist_item', [FrontAPIController::class, 'delete_wishlist_item'])->name('delete_wishlist_item');
    Route::post('front/recalculate_total', [FrontAPIController::class, 'recalculate_total'])->name('recalculate_total');
    Route::post('front/reload_cart_items', [FrontAPIController::class, 'reload_cart_items'])->name('reload_cart_items');

    // Statistik Route
    Route::group(['prefix' => 'statistik', 'as' => 'statistik.'], function(){
        Route::post('/statistik/get_top_sales_product', [StatistikController::class, 'get_top_sales_product'])->name('get_top_sales_product');
        Route::post('/statistik/get_penjualan_harian', [StatistikController::class, 'get_penjualan_harian'])->name('get_penjualan_harian');
    });
});
