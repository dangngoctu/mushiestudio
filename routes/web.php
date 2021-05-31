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
    Route::post('changepassword', 'Admin\AdminController@change_pass')->name('admin.changepassword.action');
    Route::get('index', 'Admin\AdminController@index')->name('admin.index');
    Route::get('setting','Admin\AdminController@admin_setting')->name('admin.setting');
    Route::get('size','Admin\AdminController@admin_size')->name('admin.size');
    Route::get('material','Admin\AdminController@admin_material')->name('admin.material');
    Route::get('color','Admin\AdminController@admin_color')->name('admin.color');
    Route::get('menu','Admin\AdminController@admin_menu')->name('admin.menu');
    Route::get('user','Admin\AdminController@admin_user')->name('admin.user');
    Route::get('category','Admin\AdminController@admin_category')->name('admin.category');

    Route::group(['prefix' => 'ajax'], function () {
        Route::post('fileuploader', 'Admin\AdminController@admin_post_file_uploader_ajax')->name('admin.ajax.delete_img');
        Route::get('ajax_setting', 'Admin\AdminController@admin_setting_ajax')->name('admin.setting.ajax');
        Route::post('ajax_setting', 'Admin\AdminController@admin_post_setting_ajax')->name('admin.post.setting.ajax');

        Route::get('ajax_color', 'Admin\AdminController@admin_color_ajax')->name('admin.color.ajax');
        Route::post('ajax_color', 'Admin\AdminController@admin_post_color_ajax')->name('admin.post.color.ajax');

        Route::get('ajax_size', 'Admin\AdminController@admin_size_ajax')->name('admin.size.ajax');
        Route::post('ajax_size', 'Admin\AdminController@admin_post_size_ajax')->name('admin.post.size.ajax');

        Route::get('ajax_material', 'Admin\AdminController@admin_material_ajax')->name('admin.material.ajax');
        Route::post('ajax_material', 'Admin\AdminController@admin_post_material_ajax')->name('admin.post.material.ajax');

        Route::get('ajax_user', 'Admin\AdminController@admin_user_ajax')->name('admin.user.ajax');
        Route::post('ajax_user', 'Admin\AdminController@admin_post_user_ajax')->name('admin.post.user.ajax');

        Route::get('ajax_menu', 'Admin\AdminController@admin_menu_ajax')->name('admin.menu.ajax');
        Route::post('ajax_menu', 'Admin\AdminController@admin_post_menu_ajax')->name('admin.post.menu.ajax');

        Route::get('ajax_category', 'Admin\AdminController@admin_category_ajax')->name('admin.category.ajax');
        Route::post('ajax_category', 'Admin\AdminController@admin_post_category_ajax')->name('admin.post.category.ajax');
    });

    
});