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

Auth::routes(['verify' => true]);

Route::get('/home', 'HomeController@index')->name('home');

// auth 中间件代表需要登录，verified中间件代表需要经过邮箱验证
Route::group(['middleware' => ['auth', 'verified']], function() {
    Route::get('user_addresses', 'UserAddressController@index')->name('user_addresses.index');

    Route::get('user_addresses/create', 'UserAddressController@create')->name('user_addresses.create');

    // Route::get('user_addresses/create', 'UserAddressesController@create')->name('user_addresses.create');
    Route::post('user_addresses', 'UserAddressController@store')->name('user_addresses.store');

});
