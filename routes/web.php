<?php

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

Route::get('/', 'PagesController@root')->name('root');

Route::redirect('/', '/products')->name('root');
Route::get('products', 'ProductController@index')->name('products.index');

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

// auth 中间件代表需要登录，verified中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('user_addresses', 'UserAddressController@index')->name('user_addresses.index');

    Route::get('user_addresses/create', 'UserAddressController@create')->name('user_addresses.create');

    // Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressController@store')->name('user_addresses.store');
    Route::get('user_addresses/{user_address}', 'UserAddressController@edit')->name('user_addresses.edit');
    Route::put('user_addresses/{user_address}', 'UserAddressController@update')->name('user_addresses.update');

    Route::delete('user_addresses/{user_address}', 'UserAddressController@destroy')->name('user_addresses.destroy');

    //商品
    //收藏页面
    Route::get('products/favorites', 'ProductController@favorites')->name('products.favorites');



    Route::get('products/{product}', 'ProductController@show')->name('products.show');
    // Route::post('products/{product}/favorite', 'ProductsController@favor')->name('products.favor');
    // Route::delete('products/{product}/favorite', 'ProductsController@disfavor')->name('products.disfavor');
    Route::post('products/favorite', 'ProductController@favor')->name('products.favor');

    Route::post('products/disfavor', 'ProductController@disfavor')->name('products.disfavor');
    Route::get('products/favorites', 'ProductController@favorites')->name('products.favorites');
    //购物车
    Route::post('cart', 'CartController@add')->name('cart.add');
    Route::get('cart', 'CartController@index')->name('cart.index');
    Route::get('cart/{sku}', 'CartController@remove')->name('cart.remove');

     Route::post('orders', 'OrdersController@store')->name('orders.store');

      Route::get('orders', 'OrdersController@index')->name('orders.index');

});
