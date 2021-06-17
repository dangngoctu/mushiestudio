<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Client\ClientController@index')->name('main.home.get');
Route::get('/about-us', 'Client\ClientController@about_us')->name('main.about_us.get');
Route::get('/category/{category}', 'Client\ClientController@category')->name('main.product.category.get');
Route::get('/product/{category}/{item}', 'Client\ClientController@item_detail')->name('main.product.detail.get');
Route::get('/search', 'Client\ClientController@search')->name('main.search.get');

Route::group(['prefix' => 'ajax'], function () {
    Route::get('/products/sort', 'Client\ClientController@get_product_sort')->name('main.product.ajax.product.sort.get');
});