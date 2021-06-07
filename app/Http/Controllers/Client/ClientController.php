<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models;
use Validator;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Yajra\Datatables\Datatables;
use GrahamCampbell\Throttle\Facades\Throttle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Vmorozov\FileUploads\FilesSaver as Uploader;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class ClientController extends Controller
{
    protected $instance;

    public function __construct()
    {
    }

    public function index(Request $request){
        $latest_category = Models\Category::where('type', 1)->orderBy('id', 'desc')->first();
        $near_latest_category = Models\Category::with('menu')->where('type', 1)->where('id', '!=', $latest_category->id)->orderBy('id', 'desc')->take(2)->get();
        $third_lasted_item = Models\Item::take(3)->orderBy('id', 'desc')->get();
        $latest_album = Models\Category::with('categoryImages')->where('type', 2)->orderBy('id', 'desc')->first();

        return view('Web.Client.home.main', [
            'latest_category' => $latest_category,
            'near_latest_category' => $near_latest_category,
            'third_lasted_item' => $third_lasted_item,
            'latest_album' => $latest_album,
        ]);
    }

    public function category($category){
        $category = Models\Category::with('categoryImages', 'items.itemImages')->where('url', $category)->first();
        $material = Models\Material::all();
        $size = Models\Size::all();
        $color = Models\Color::all();
        if($category){
            if($category->type == 1){
                //Item
                return view('Web.Client.category-1.main', [
                    'category' => $category,
                    'material' => $material,
                    'size' => $size,
                    'color' => $color
                ]);
            } else {
                //Album
                return view('Web.Client.category-2.main', [
                    'category' => $category,
                    'material' => $material,
                    'size' => $size,
                    'color' => $color
                ]);
            }
        } else {
            return route('main.home.get');
        }
        return $category;
    }
}