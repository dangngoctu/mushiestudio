<?php

namespace App\Providers;
use App\Models;
use Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        Schema::defaultStringLength(191);
        view()->composer('*', function ($view){
            $menu = Models\Menu::with(['categorys' => function($query){ $query->orderBy('id','asc'); }])->get();
            $address = Models\Setting::where('key', 'ADDRESS')->first();
            $phone = Models\Setting::where('key', 'PHONE')->first();
            $time = Models\Setting::where('key', 'TIME')->first();
            $email = Models\Setting::where('key', 'EMAIL')->first();
            $website = Models\Setting::where('key', 'WEBSITE')->first();
            $facebook = Models\Setting::where('key', 'URL_FACEBOOK')->first();
            $instagram = Models\Setting::where('key', 'URL_INSTAGRAM')->first();
            $content = Models\Setting::where('key', 'CONTENT')->first();
            $title = Models\Setting::where('key', 'TITLE')->first();
            $file = Models\Setting::where('key', 'FILE')->first();

            View::share('menu', $menu);
            View::share('address', $address);
            View::share('phone', $phone);
            View::share('time', $time);
            View::share('email', $email);
            View::share('website', $website);
            View::share('facebook', $facebook);
            View::share('instagram', $instagram);
            View::share('title_setting', $title);
            View::share('content_setting', $content);
            View::share('file_setting', $file);
        });
    }
}
