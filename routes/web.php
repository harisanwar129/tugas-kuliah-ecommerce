<?php


use Illuminate\Support\Facades\Route;
use Kavist\RajaOngkir\Facades\RajaOngkir;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

if (file_exists(app_path('Http/Controllers/LocalizationController.php')))
{
    Route::get('lang/{locale}', [App\Http\Controllers\LocalizationController::class , 'lang'])->name('lang.change');
}

Route::post('rajaongkir/cek_ongkir', 'RajaOngkirController@cek_ongkir')->name('rajaongkir.cek_ongkir');
Route::get('city/fetch/{province_id}', 'ProvinceController@fetch')->name('province.fetch');

Route::get('/', 'FrontPageController@index')->name('welcome');
Route::get('/mobile', 'FrontPageController@index')->name('mobile');
Route::get('/shopping', 'FrontPageController@shopping')->name('front.shopping');
Route::get('/ipaymu/cancel', 'IPayMuController@cancel')->name('ipaymu.cancel');
Route::get('/ipaymu/return', 'IPayMuController@return')->name('ipaymu.return');
Route::get('/product/{slug}', 'FrontPageController@show_product')->name('product.show');
Route::get('/page/{slug}', 'FrontPageController@page_show')->name('page.show');
Route::get('/shopping/category/{slug}', 'FrontPageController@shopping_category')->name('front.shopping.shopping_category');
Route::get('/shopping/shop/{username}', 'FrontPageController@shopping_shop')->name('front.shopping.shopping_shop');
Route::get('/shopping/price_range/{from}/{to}', 'FrontPageController@shopping_price_range')->name('front.shopping.price_range');
Route::post('/shopping/search', 'FrontPageController@search')->name('front.search');
Route::get('/shopping/search', 'FrontPageController@search')->name('front.search');

// Route untuk MEMBER
Route::group(['middleware' => ['auth', 'member']], function() {
    Route::get('/my_cart', 'FrontPageController@my_cart')->name('my_cart');
    Route::get('/my_wishlist', 'FrontPageController@my_wishlist')->name('my_wishlist');
    Route::get('/checkout', 'FrontPageController@checkout')->name('checkout');
    Route::post('/checkout_store', 'FrontPageController@store_checkout')->name('checkout.store');
    Route::get('/my_account', 'FrontPageController@my_account')->name('my_account');
    Route::get('/pesanan_saya', 'FrontPageController@pesanan_saya')->name('front.pesanan_saya');
    Route::put('/cancel_pesanan/{id}', 'FrontPageController@cancel_pesanan')->name('front.cancel_pesanan');
    Route::get('/detail_pesanan_saya/{id}', 'FrontPageController@detail_pesanan_saya')->name('front.detail_pesanan_saya');
    Route::get('/detail_pesanan_saya/print/{id}', 'FrontPageController@detail_pesanan_saya_print')->name('front.detail_pesanan_saya.print');
    Route::put('/my_profile', 'FrontPageController@my_profile')->name('front.my_profile.update');
    Route::get('/my_profile', 'FrontPageController@my_profile')->name('front.my_profile');
});


Route::group(['prefix' => 'dashboard', 'as' => 'dashboard', 'middleware' => ['auth']], function() {
    Route::group(['as' => '.'], function() {
        // Route All Role
        Route::get('/', 'DashboardController@index')->name('index');
        Route::put('transaction/terima_pesanan/{id}', 'TransactionController@terima_pesanan')->name('transaction.terima_pesanan');

        // Route untuk ADMIN DAN SELLER
        Route::group(['middleware' => 'role:ADMIN,SELLER'], function() {
            Route::get('/histori_penjualan', 'TransactionController@histori_penjualan')->name('histori_penjualan');
            Route::put('transaction/kirim_pesanan/{id}', 'TransactionController@kirim_pesanan')->name('transaction.kirim_pesanan');
            Route::resource('transaction', 'TransactionController');
            Route::get('/grafik_penjualan', 'DashboardController@grafik_penjualan')->name('grafik_penjualan');
            Route::group(['prefix' => 'master', 'as' => 'master.'], function() {
                Route::put('/user/update/{id}', 'UserController@update')->name('user.update');
                Route::put('/user/update_profile', 'UserController@update_profile')->name('user.update_profile');
                Route::get('/profile', 'UserController@profile')->name('user.profile');
                Route::get('/user/show/{id}', 'UserController@show')->name('user.show');
                Route::delete('product/mass_delete', 'ProductController@mass_destroy')->name('product.mass_destroy');
                Route::resource('product', 'ProductController');
                Route::get('/product_gallery/{product_id}', 'ProductGalleryController@index')->name('product_gallery.index');
                Route::post('/product_gallery/store/{product_id}', 'ProductGalleryController@store')->name('product_gallery.store');
                Route::delete('/product_gallery/destroy/{product_id}', 'ProductGalleryController@destroy')->name('product_gallery.destroy');
                Route::get('/product_stock/{product_id}', 'ProductStockController@index')->name('product_stock.index');
                Route::post('/product_stock/store/{product_id}', 'ProductStockController@store')->name('product_stock.store');
                Route::delete('/product_stock/destroy/{product_id}', 'ProductStockController@destroy')->name('product_stock.destroy');
            });

        });

        // Route untuk ADMIN
        Route::group(['middleware' => 'role:ADMIN'], function() {
            Route::group(['prefix' => 'master', 'as' => 'master.'], function() {
                Route::delete('/user/delete/{id}', 'UserController@destroy')->name('user.delete');
                Route::post('/user/store', 'UserController@store')->name('user.store');
                Route::get('/user/create', 'UserController@create')->name('user.create');
                Route::get('/user/index', 'UserController@index')->name('user.index');
                Route::resource('menu', 'MenuController');
                Route::resource('submenu', 'SubMenuController');
                Route::resource('page', 'PageController');
                Route::resource('slider', 'SliderController');
                Route::resource('product_category', 'ProductCategoryController');
            });
            Route::put('/setting', 'SettingWebController@setting_update')->name('setting');
            Route::get('/setting', 'SettingWebController@setting')->name('setting');

        });

        // Route untuk SELLER
        Route::group(['middleware' => 'role:SELLER'], function() {
            Route::get('/my_shop', 'DashboardController@my_shop')->name('my_shop');
            Route::group(['prefix' => 'master', 'as' => 'master.'], function() {
                Route::put('/user/update_toko', 'UserController@update_toko')->name('user.update_toko');
            });
        });

    });
});

Auth::routes();
