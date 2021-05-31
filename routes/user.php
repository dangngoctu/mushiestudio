<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('Web.Client.home.main');
})->name('main.home.get');

Route::get('/detail',function(){
    return view('Web.Client.product-detail.main');
})->name('main.product.detail.get');