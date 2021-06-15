<?php

use Illuminate\Support\Facades\Route;

Route::get('/', 'Client\ClientController@index')->name('main.home.get');
Route::get('/about-us', 'Client\ClientController@about_us')->name('main.about_us.get');
Route::get('/category/{category}', 'Client\ClientController@category')->name('main.product.category.get');
Route::get('/product/{category}/{item}', 'Client\ClientController@item_detail')->name('main.product.detail.get');
Route::get('/search', 'Client\ClientController@search')->name('main.search.get');

// Route::get('/detail',function(){
//     return view('Web.Client.product-detail.main');
// })->name('main.product.detail.get');

// Route::get('/category-1',function(){
//     return view('Web.Client.category-1.main');
// })->name('main.product.category.get');

// Route::get('/category-2',function(){
//     return view('Web.Client.category-2.main');
// })->name('main.product.category2.get');