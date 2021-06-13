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
        try{
            $latest_category = Models\Category::where('type', 1)->orderBy('id', 'desc')->first();
            if($latest_category){
                $near_latest_category = Models\Category::with('menu')->where('type', 1)->where('id', '!=', $latest_category->id)->orderBy('id', 'desc')->take(2)->get();
            } else {
                $near_latest_category = Models\Category::with('menu')->where('type', 1)->orderBy('id', 'desc')->take(2)->get();
            }
            $third_lasted_item = Models\Item::take(3)->orderBy('id', 'desc')->get();
            $latest_album = Models\Category::with('categoryImages')->where('type', 2)->orderBy('id', 'desc')->first();
    
            return view('Web.Client.home.main', [
                'latest_category' => $latest_category,
                'near_latest_category' => $near_latest_category,
                'third_lasted_item' => $third_lasted_item,
                'latest_album' => $latest_album,
            ]);
        }catch(\Exception $e){
            abort(404);
        }
    }

    public function category($category){
        try{
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
                        'color' => $color,
                        'title' => $category->name
                    ]);
                } else {
                    //Album
                    $video_check = false;
                    if($category->video != '' && !empty($category->video)){
                        $file = 'https://www.youtube.com/embed/'.$category->video;
                        $file_headers = @get_headers($file);
                        if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found'){
                            $video_check = true;
                        }else{
                            $video_check = true;
                        }
                    }
                    
                    return view('Web.Client.category-2.main', [
                        'category' => $category,
                        'material' => $material,
                        'size' => $size,
                        'color' => $color,
                        'video_check' => $video_check,
                        'title' => $category->name
                    ]);
                }
            } else {
                abort(404);
            }
        }catch(\Exception $e){
            abort(404);
        }
    }

    public function item_detail($category, $item){
        try{
            $item = Models\Item::with('itemImages')->where('slug', $item)->first();
            $color = Models\Color::whereIn('id', explode(',',$item->color))->get();
            $size = Models\Size::whereIn('id', explode(',',$item->size))->get();
            $related_item = Models\Item::where('id', '!=', $item->id)->where('category_id', $item->category_id)->where('material', 'LIKE', '%'.$item->material.'%')->take(6)->get();
            if($item){
                return view('Web.Client.product-detail.main',[
                    'item' => $item,
                    'color' => $color,
                    'size' => $size,
                    'related_item' => $related_item
                ]);
            } else {
                return route('main.home.get');
            }
        }catch(\Exception $e){
            abort(404);
        }
    }

    public function about_us(Request $request){
        try{
            return view('Web.Client.about-us.main',[
                'title' => 'About Us'
            ]);
        }catch(\Exception $e){
            abort(404);
        }
    }
}