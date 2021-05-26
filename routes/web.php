<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
})->name('index');

Route::get('/admin_mushie', 'Admin\AdminController@admin_login')->name('admin.login.view');
Route::post('/admin/login', 'Admin\AdminController@admin_login_action')->name('admin.login.action');
Route::get('/admin/logout', 'Admin\AdminController@logout')->name('admin.logout');
