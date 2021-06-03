<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Web.Client.home.main');
})->name('main.home.get');

Route::get('/detail',function(){
    return view('Web.Client.product-detail.main');
})->name('main.product.detail.get');

Route::get('/category-1',function(){
    return view('Web.Client.category-1.main');
})->name('main.product.category.get');