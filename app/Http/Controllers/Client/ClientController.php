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
use Image;

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
                $near_latest_category = Models\Category::with('menu')
                ->where('type', 1)
                ->where('id', '!=', $latest_category->id)
                ->orderBy('id', 'desc')
                ->take(2)
                ->get();
            } else {
                $near_latest_category = Models\Category::with('menu')
                ->where('type', 1)
                ->orderBy('id', 'desc')
                ->take(2)
                ->get();
            }

            foreach($near_latest_category as $value){
                // $link = ;
               if($value->img != ''){
                   $exp_file = explode('.',$value->img);
                   $size_thumnail_home = config('config-size.home.category_img');
                   $name_file = $exp_file[0] .'-'.$size_thumnail_home['width'].'-'.$size_thumnail_home['height'].'.'.$exp_file[1];
                   if(!file_exists(public_path($name_file))){
                     
                       $img = Image::make(public_path($value->img));
                       $img->resize($size_thumnail_home['width'], $size_thumnail_home['height']);
                       $img->save(public_path($name_file));
                   }
                   $value->img = $name_file;
               }
           }
           
            $third_lasted_item = Models\Item::take(3)->whereHas('category')
            ->orderBy('id', 'desc')
            ->get();

            foreach($third_lasted_item as $value){
                // $link = ;
               if($value->img_thumb != ''){
                   $exp_file = explode('.',$value->img_thumb);
                   $size_thumnail_home = config('config-size.home.item-thumnail');
                   $name_file = $exp_file[0] .'-'.$size_thumnail_home['width'].'-'.$size_thumnail_home['height'].'.'.$exp_file[1];
                   if(!file_exists(public_path($name_file))){
                     
                       $img = Image::make(public_path($value->img_thumb));
                       $img->resize($size_thumnail_home['width'], $size_thumnail_home['height']);
                       $img->save(public_path($name_file));
                   }
                   $value->img_thumb = $name_file;
               }
           }
          

            $latest_album = Models\Category::with('categoryImages')
            ->where('type', 2)
            ->orderBy('id', 'desc')
            ->first();
    
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
                if($category->img != ''){
                    $exp_file = explode('.',$category->img);
                    $size_thumnail_home = config('config-size.category.category_img');
                    $name_file = $exp_file[0] .'-'.$size_thumnail_home['width'].'-'.$size_thumnail_home['height'].'.'.$exp_file[1];
                    if(!file_exists(public_path($name_file))){
                        $img = Image::make(public_path($category->img));
                        $img->resize($size_thumnail_home['width'], $size_thumnail_home['height']);
                        $img->save(public_path($name_file));
                    }
              
                    $category->img = $name_file;
                
                }


                if(count($category->items) > 0){
                    foreach($category->items as $values){
                        foreach($values->itemImages as $value){
                            if($value->url != ''){
                                $exp_file = explode('.',$value->url);
                                $size_thumnail_home = config('config-size.category.item-image');
                                $name_file = $exp_file[0] .'-'.$size_thumnail_home['width'].'-'.$size_thumnail_home['height'].'.'.$exp_file[1];
                                if(!file_exists(public_path($name_file))){
                                    $img = Image::make(public_path($value->url));
                                    $img->resize($size_thumnail_home['width'], $size_thumnail_home['height']);
                                    $img->save(public_path($name_file));
                                }
                          
                                $value->url = $name_file;
                            
                            }
                        }
                    }
                }
               
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
                    if(count($category->categoryImages) > 0){
                        foreach($category->categoryImages as $value){
                            if($value->url != ''){
                                $exp_file = explode('.',$value->url);
                                $size_thumnail_home = config('config-size.category.category-image');
                                $name_file = $exp_file[0] .'-'.$size_thumnail_home['width'].'-'.$size_thumnail_home['height'].'.'.$exp_file[1];
                                if(!file_exists(public_path($name_file))){
                                    $img = Image::make(public_path($value->url));
                                    $img->resize($size_thumnail_home['width'], $size_thumnail_home['height']);
                                    $img->save(public_path($name_file));
                                }
                            
                                $value->url = $name_file;
                            }
                        }
                    }

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
           
           
            if(count($item->itemImages) > 0){
                foreach($item->itemImages as $value){
                    if($value->url != ''){
                        $exp_file = explode('.',$value->url);
                        $size_thumnail_home = config('config-size.detail.item-images');
                        $name_file = $exp_file[0] .'-'.$size_thumnail_home['width'].'-'.$size_thumnail_home['height'].'.'.$exp_file[1];
                        if(!file_exists(public_path($name_file))){
                            $img = Image::make(public_path($value->url));
                            $img->resize($size_thumnail_home['width'], $size_thumnail_home['height']);
                            $img->save(public_path($name_file));
                        }
                        $value->url_view = $value->url;
                        $value->url = $name_file;
                    
                    }
                }
            }

            if(count($related_item) > 0){
                foreach($related_item as $values){
                    foreach($values->itemImages as $value){
                        if($value->url != ''){
                            $exp_file = explode('.',$value->url);
                            $size_thumnail_home = config('config-size.detail.item-related');
                            $name_file = $exp_file[0] .'-'.$size_thumnail_home['width'].'-'.$size_thumnail_home['height'].'.'.$exp_file[1];
                            if(!file_exists(public_path($name_file))){
                                $img = Image::make(public_path($value->url));
                                $img->resize($size_thumnail_home['width'], $size_thumnail_home['height']);
                                $img->save(public_path($name_file));
                            }
                            $value->url = $name_file;
                        }
                    }
                }
            }
           
            
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
                'title' => 'About Us',
                'images'     => [
                    asset('public\assets\app\page\user\images\about-us\about-1-1240-827.jpg'),
                    asset('public\assets\app\page\user\images\about-us\about-2-1240-1858.jpg'),
                    asset('public\assets\app\page\user\images\about-us\about-3-1240-1858.jpg')
                ]
            ]);
        }catch(\Exception $e){
            abort(404);
        }
    }

    public function search(Request $request){
        try{
            $keyword = '';
            if($request->has('keyword') && $request->keyword != ''){
                $keyword = $request->keyword;
            }
            $category_search = Models\Category::where('name','like','%'.$keyword.'%')->get();
            $item_search = Models\Item::where('name','like','%'.$keyword.'%')->get();
            
            $data =  [
                'categorys' => $category_search,
                'items'     => $item_search
            ];
         
            return view('Web.Client.search.main',$data);
        }catch(\Exception $e){
            abort(404);
        }
    }

    public function get_product_sort(Request $request){
        try{
            $items = Models\Item::with('category','itemImages')
            ->whereCategoryId($request->id);
            if($request->has('material') && $request->material != '' && !empty($request->material)){
                $items = $items->where('material','like','%'.$request->material.'%');
            }
            if($request->has('size') && $request->size != '' && !empty($request->size)){
                $items = $items->where('size','like','%'.$request->size.'%');
            }
            if($request->has('type') && $request->type != ''){
                if($request->type == 1){
                    $items = $items->orderBy('updated_at','desc');
                }else if($request->type == 2){
                    $items = $items->orderBy('price','asc');
                }else{
                    $items = $items->orderBy('price','desc');
                }
            }
         
            $items = $items->get();
            foreach($items as $values){
                foreach($values->itemImages as $value){
                    if($value->url != ''){
                        $exp_file = explode('.',$value->url);
                        $size_thumnail_home = config('config-size.category.item-image');
                        $name_file = $exp_file[0] .'-'.$size_thumnail_home['width'].'-'.$size_thumnail_home['height'].'.'.$exp_file[1];
                        if(!file_exists(public_path($name_file))){
                            $img = Image::make(public_path($value->url));
                            $img->resize($size_thumnail_home['width'], $size_thumnail_home['height']);
                            $img->save(public_path($name_file));
                        }
                  
                        $value->url = $name_file;
                    
                    }
                }
            }
            return $items;
        }catch(\Exception $e){
            return [];
        }
    }
}