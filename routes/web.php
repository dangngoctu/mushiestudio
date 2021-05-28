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

Route::group(['prefix' => 'admin', 'middleware' => 'auth.api'], function () {
    Route::get('index', 'Admin\AdminController@index')->name('admin.index');
    Route::get('setting','Admin\AdminController@admin_setting')->name('admin.setting');
    Route::get('size','Admin\AdminController@admin_size')->name('admin.size');
    Route::get('material','Admin\AdminController@admin_material')->name('admin.material');
    Route::get('color','Admin\AdminController@admin_color')->name('admin.color');

    Route::group(['prefix' => 'ajax'], function () {
        Route::get('ajax_setting', 'Admin\AdminController@admin_setting_ajax')->name('admin.setting.ajax');
        Route::post('ajax_setting', 'Admin\AdminController@admin_post_setting_ajax')->name('admin.post.setting.ajax');

        Route::get('ajax_color', 'Admin\AdminController@admin_color_ajax')->name('admin.color.ajax');
        Route::post('ajax_color', 'Admin\AdminController@admin_post_color_ajax')->name('admin.post.color.ajax');

        Route::get('ajax_size', 'Admin\AdminController@admin_size_ajax')->name('admin.size.ajax');
        Route::post('ajax_size', 'Admin\AdminController@admin_post_size_ajax')->name('admin.post.size.ajax');
    });

    
});