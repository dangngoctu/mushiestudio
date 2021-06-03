<?php

namespace App\Http\Controllers\Admin;

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


class AdminController extends Controller
{
    protected $instance;

    public function __construct()
    {
    }

    public function admin_login(){
        try {
			if (Auth::check()) {
				return redirect()->route('admin.index');
			} else {
				return view('Web.Admin.Page.login');
			}
		} catch (\Exception $e) {
			return redirect()->route('index');
		}
	}
	
	public function admin_login_action(Request $request){
		$rules = array(
			'username' => 'required|min:1|max:128',
			'password' => 'required|min:1|max:128',
		);
        $validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
            return self::JsonExport(403, "Vui lòng kiểm tra lại thông tin");
            
		} else {
            try {
				$throttler = Throttle::get($request, config('constant.limit_login'), config('constant.limit_time'));
				$credentials = [
					'email' => $request->username,
					'password' => $request->password
				];
				if(Auth::attempt($credentials)){
					return self::JsonExport(200, "Đăng nhập thành công");
				} else {
					$throttler->hit();
					return self::JsonExport(402, "Vui lòng kiểm tra lại thông tin");
            	}
			} catch (\Exception $e) {
                return self::JsonExport(500, "Vui lòng kiểm tra lại thông tin");
            }
        }
	}

	public function logout(){
        try {
			if(Auth::user()) {
				Auth::logout();
            }
			session()->flush();
			return redirect()->route('admin.login.view');
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function change_pass(Request $request){
		$rules = array(
			'oldPassword' => 'required|min:1|max:255',
			'newPassword' => 'required|min:6|max:255',
			'renewPassword' => 'required|min:6|max:255|same:newPassword'
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, 'Error');
		} else {
			try{
				DB::beginTransaction();
				$account = Models\User::find(Auth::user()->id);
				if($account){
					if(Hash::check($request->oldPassword, $account->password)){
						$account->update(['password' =>  Hash::make($request->newPassword)]);
						if(!$account){
							DB::rollback();
							return self::JsonExport(403, 'Error');
						}
					} else {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else {
					DB::rollback();
					return self::JsonExport(403, 'Error');
				}
				DB::commit();
				return self::JsonExport(200, 'Success');
			} catch (\Exception $e) {
				DB::rollback();
				return self::JsonExport(500, 'Error');
			}
		}
	}

	public function index(){
        try {
			$user = Auth::user();
            return view('Web.Admin.Page.setting');
        } catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_post_file_uploader_ajax(Request $request){
		$rules = array(
			'id' => 'required|digits_between:1,10',
			'action' => 'required'
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			self::JsonExport(403, $validator->errors()->first());
		} else {
			try{
				DB::begintransaction();
				if($request->action == 'deletecategory'){
					$img = Models\CategoryImage::find($request->id);
					if(!$img){
						DB::rollback();
						return self::JsonExport(404, 'Data wrong');
					}
					$img->delete();
					if(!$img){
						DB::rollback();
						return self::JsonExport(500, 'Error');
					}else{
						DB::commit();
						@unlink(public_path('img/item/'.$img->url));
						return self::JsonExport(200, 'Delete success');
					}
				}else if($request->action == 'deleteitem'){
					$img = Models\ItemImage::find($request->id);
					if(!$img){
						DB::rollback();
						return self::JsonExport(404, 'Data wrong');
					}
					$img->delete();
					if(!$img){
						DB::rollback();
						return self::JsonExport(500, 'Error');
					}else{
						DB::commit();
						@unlink(public_path('img/item/'.$img->url));
						return self::JsonExport(200, 'Delete success');
					}
				} else if($request->action == 'deletethumb'){
					$item = Models\Item::where('id', $request->id)->first();
					if(!$item){
						DB::rollback();
						return self::JsonExport(404, 'Data wrong');
					}
					$img_thumb = $item->img_thumb;
					$item->update(['img_thumb' => null]);
					if(!$item){
						DB::rollback();
						return self::JsonExport(500, 'Error');
					} else {
						DB::commit();
						@unlink(public_path($img_thumb));
						return self::JsonExport(200, 'Delete success');
					}
				}
			} catch (\Exception $e) {
				return self::JsonExport(500, 'Error');
			}
		}
	}

	//Setting
	public function admin_setting(Request $request)
	{
		try {
			if(Auth::user()) {
				return view('Web.Admin.Page.setting');
			} else {
				return redirect()->route('admin.login.view');
			}
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_setting_ajax(Request $request)
	{
		try {
			if($request->has('key') && !empty($request->key)) {
				$data = self::getSetting($request->key);
			} else {
				$data = self::getDTSetting();
			}
			if($data == false){
				return self::JsonExport(500, 'Error');
			} else {
				return $data;
			}
		} catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
		}
	}

	public function getDTSetting(){
        try {
            $data = Models\Setting::all();
			if(!$data){
				return false;
			}
			$result =  Datatables::of($data)
			->editColumn('key', function ($v) {
				if(!empty($v->key)){
					return $v->key;
				} else {
					return '';
				}
			})
			->editColumn('value', function ($v) {
				if(!empty($v->value)){
					return $v->value;
				} else {
					return '';
				}
			})
			->addColumn('action', function ($v) {
                $action = '';
                if(1==1) {
                    $action .= '<span class="btn-action table-action-edit cursor-pointer tx-success" data-id="'.$v->key.'"><i class="fa fa-edit"></i></span>';
                }
                return $action;
			})
			->addIndexColumn()
			->rawColumns(['action'])
			->make(true);
			return $result;
        } catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
        }  
	}

	public function getSetting($key){
		try{
			$data = Models\Setting::where('key', $key)->first();
			if($data){
				$result = self::JsonExport(200, trans('app.success'), $data);
			} else {
				$result = self::JsonExport(404, 'Error');
			}
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function admin_post_setting_ajax(Request $request)
	{
		$rules = array(
			'value' => 'required',
			'key' => 'required'
		);
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, $validator->errors());
		} else {
			try {
				DB::beginTransaction();
				$check = Models\Setting::where('key', $request->key)->first();
				if($check){
					$check->update(['value' => $request->value]);
				} else {
					return self::JsonExport(404, 'Data wrong');
				}
				
				DB::commit();
				return self::JsonExport(200, 'Update success');
			} catch (\Exception $e) {
				DB::rollback();
				return self::JsonExport(500, 'Error');
			}	
		}
	}

	//Color
	public function admin_color(Request $request)
	{
		try {
			if(Auth::user()) {
				return view('Web.Admin.Page.color');
			} else {
				return redirect()->route('admin.login.view');
			}
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_color_ajax(Request $request)
	{
		try {
			if($request->has('id') && !empty($request->id)) {
				$data = self::getColor($request->id);
			} else {
				$data = self::getDTColor();
			}
			if($data == false){
				return self::JsonExport(500, 'Error');
			} else {
				return $data;
			}
		} catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
		}
	}

	public function getDTColor(){
        try {
            $data = Models\Color::all();
			if(!$data){
				return false;
			}
			$result =  Datatables::of($data)
			->addColumn('color', function ($v) {
				if(!empty($v->color_code)){
					return '<span style="margin:auto;width:20px; display:block; height:20px; border-radius: 50%; background-color:'.$v->color_code.'"></span>';
				} else {
					return '';
				}
			})
			->addColumn('action', function ($v) {
                $action = '';
                if(1==1) {
                    $action .= '<span class="btn-action table-action-edit cursor-pointer tx-success" data-id="'.$v->id.'"><i class="fa fa-edit"></i></span>';
                }
				if(1==1) {
                    $action .= '<span class="btn-action table-action-delete cursor-pointer tx-danger mg-l-5" data-id="'.$v->id.'"><i class="fa fa-trash"></i></span>';
                }
                return $action;
			})
			->addIndexColumn()
			->rawColumns(['action', 'color'])
			->make(true);
			return $result;
        } catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
        }  
	}

	public function getColor($id){
		try{
			$data = Models\Color::where('id', $id)->first();
			if($data){
				$result = self::JsonExport(200, trans('app.success'), $data);
			} else {
				$result = self::JsonExport(404, 'Error');
			}
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function admin_post_color_ajax(Request $request)
	{
		$rules = array(
			'name' => 'required',
			'color_code' => 'required'
		);
		if($request->action == 'update') {
			$rules['id'] = 'required|digits_between:1,10';
		} else if($request->action == 'delete') {
			$rules = array('id' => 'required|digits_between:1,10');
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, $validator->errors());
		} else {
			try {
				DB::beginTransaction();
				if($request->action == 'update' || $request->action == 'delete') {
					$query = Models\Color::where('id', $request->id)->first();
					if(!$query) {
						DB::rollback();
						return false;
					}
				}
				$data = [];
				if($request->has('name') && !empty($request->name)) {
					$data['name'] = $request->name;
				}
				if($request->has('color_code') && !empty($request->color_code)) {
					$data['color_code'] = $request->color_code;
				}

				if($request->action == 'update') {
					$query->update($data);
					if (!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else if($request->action == 'delete') {
					$query->delete();
					if(!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else {
					$insert = Models\Color::insert($data);
					if(!$insert) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				}
				DB::commit();
				switch ($request->action) {
					case 'update':
						return self::JsonExport(200, 'Update success');
					case 'insert':
						return self::JsonExport(200, 'Insert success');
					default:
						return self::JsonExport(200, 'Delete success');
				}
			} catch (\Exception $e) {
				DB::rollback();
				return self::JsonExport(500, 'Error');
			}	
		}
	}

	//Size
	public function admin_size(Request $request)
	{
		try {
			if(Auth::user()) {
				return view('Web.Admin.Page.size');
			} else {
				return redirect()->route('admin.login.view');
			}
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_size_ajax(Request $request)
	{
		try {
			if($request->has('id') && !empty($request->id)) {
				$data = self::getSize($request->id);
			} else {
				$data = self::getDTSize();
			}
			if($data == false){
				return self::JsonExport(500, 'Error');
			} else {
				return $data;
			}
		} catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
		}
	}

	public function getDTSize(){
        try {
            $data = Models\Size::all();
			if(!$data){
				return false;
			}
			$result =  Datatables::of($data)
			->addColumn('action', function ($v) {
                $action = '';
                if(1==1) {
                    $action .= '<span class="btn-action table-action-edit cursor-pointer tx-success" data-id="'.$v->id.'"><i class="fa fa-edit"></i></span>';
                }
				if(1==1) {
                    $action .= '<span class="btn-action table-action-delete cursor-pointer tx-danger mg-l-5" data-id="'.$v->id.'"><i class="fa fa-trash"></i></span>';
                }
                return $action;
			})
			->addIndexColumn()
			->rawColumns(['action'])
			->make(true);
			return $result;
        } catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
        }  
	}

	public function getSize($id){
		try{
			$data = Models\Size::where('id', $id)->first();
			if($data){
				$result = self::JsonExport(200, trans('app.success'), $data);
			} else {
				$result = self::JsonExport(404, 'Error');
			}
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function admin_post_size_ajax(Request $request)
	{
		$rules = array(
			'name' => 'required'
		);
		if($request->action == 'update') {
			$rules['id'] = 'required|digits_between:1,10';
		} else if($request->action == 'delete') {
			$rules = array('id' => 'required|digits_between:1,10');
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, $validator->errors());
		} else {
			try {
				DB::beginTransaction();
				if($request->action == 'update' || $request->action == 'delete') {
					$query = Models\Size::where('id', $request->id)->first();
					if(!$query) {
						DB::rollback();
						return false;
					}
				}
				$data = [];
				if($request->has('name') && !empty($request->name)) {
					$data['name'] = $request->name;
				}

				if($request->action == 'update') {
					$query->update($data);
					if (!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else if($request->action == 'delete') {
					$query->delete();
					if(!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else {
					$insert = Models\Size::insert($data);
					if(!$insert) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				}
				DB::commit();
				switch ($request->action) {
					case 'update':
						return self::JsonExport(200, 'Update success');
					case 'insert':
						return self::JsonExport(200, 'Insert success');
					default:
						return self::JsonExport(200, 'Delete success');
				}
			} catch (\Exception $e) {
				DB::rollback();
				return self::JsonExport(500, 'Error');
			}	
		}
	}

	//Material
	public function admin_material(Request $request)
	{
		try {
			if(Auth::user()) {
				return view('Web.Admin.Page.material');
			} else {
				return redirect()->route('admin.login.view');
			}
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_material_ajax(Request $request)
	{
		try {
			if($request->has('id') && !empty($request->id)) {
				$data = self::getMaterial($request->id);
			} else {
				$data = self::getDTMaterial();
			}
			if($data == false){
				return self::JsonExport(500, 'Error');
			} else {
				return $data;
			}
		} catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
		}
	}

	public function getDTMaterial(){
        try {
            $data = Models\Material::all();
			if(!$data){
				return false;
			}
			$result =  Datatables::of($data)
			->addColumn('action', function ($v) {
                $action = '';
                if(1==1) {
                    $action .= '<span class="btn-action table-action-edit cursor-pointer tx-success" data-id="'.$v->id.'"><i class="fa fa-edit"></i></span>';
                }
				if(1==1) {
                    $action .= '<span class="btn-action table-action-delete cursor-pointer tx-danger mg-l-5" data-id="'.$v->id.'"><i class="fa fa-trash"></i></span>';
                }
                return $action;
			})
			->addIndexColumn()
			->rawColumns(['action'])
			->make(true);
			return $result;
        } catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
        }  
	}

	public function getMaterial($id){
		try{
			$data = Models\Material::where('id', $id)->first();
			if($data){
				$result = self::JsonExport(200, trans('app.success'), $data);
			} else {
				$result = self::JsonExport(404, 'Error');
			}
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function admin_post_material_ajax(Request $request)
	{
		$rules = array(
			'name' => 'required'
		);
		if($request->action == 'update') {
			$rules['id'] = 'required|digits_between:1,10';
		} else if($request->action == 'delete') {
			$rules = array('id' => 'required|digits_between:1,10');
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, $validator->errors());
		} else {
			try {
				DB::beginTransaction();
				if($request->action == 'update' || $request->action == 'delete') {
					$query = Models\Material::where('id', $request->id)->first();
					if(!$query) {
						DB::rollback();
						return false;
					}
				}
				$data = [];
				if($request->has('name') && !empty($request->name)) {
					$data['name'] = $request->name;
				}

				if($request->action == 'update') {
					$query->update($data);
					if (!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else if($request->action == 'delete') {
					$query->delete();
					if(!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else {
					$insert = Models\Material::insert($data);
					if(!$insert) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				}
				DB::commit();
				switch ($request->action) {
					case 'update':
						return self::JsonExport(200, 'Update success');
					case 'insert':
						return self::JsonExport(200, 'Insert success');
					default:
						return self::JsonExport(200, 'Delete success');
				}
			} catch (\Exception $e) {
				DB::rollback();
				return self::JsonExport(500, 'Error');
			}	
		}
	}

	//Menu
	public function admin_menu(Request $request)
	{
		try {
			if(Auth::user()) {
				return view('Web.Admin.Page.menu');
			} else {
				return redirect()->route('admin.login.view');
			}
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_menu_ajax(Request $request)
	{
		try {
			if($request->has('id') && !empty($request->id)) {
				$data = self::getMenu($request->id);
			} else {
				$data = self::getDTMenu();
			}
			if($data == false){
				return self::JsonExport(500, 'Error');
			} else {
				return $data;
			}
		} catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
		}
	}

	public function getDTMenu(){
        try {
            $data = Models\Menu::all();
			if(!$data){
				return false;
			}
			$result =  Datatables::of($data)
			->addColumn('action', function ($v) {
                $action = '';
                if(1==1) {
                    $action .= '<span class="btn-action table-action-edit cursor-pointer tx-success" data-id="'.$v->id.'"><i class="fa fa-edit"></i></span>';
                }
				if(1==1) {
                    $action .= '<span class="btn-action table-action-delete cursor-pointer tx-danger mg-l-5" data-id="'.$v->id.'"><i class="fa fa-trash"></i></span>';
                }
                return $action;
			})
			->addIndexColumn()
			->rawColumns(['action'])
			->make(true);
			return $result;
        } catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
        }  
	}

	public function getMenu($id){
		try{
			$data = Models\Menu::where('id', $id)->first();
			if($data){
				$result = self::JsonExport(200, trans('app.success'), $data);
			} else {
				$result = self::JsonExport(404, 'Error');
			}
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function admin_post_menu_ajax(Request $request)
	{
		$rules = array(
			'name' => 'required'
		);
		if($request->action == 'update') {
			$rules['id'] = 'required|digits_between:1,10';
		} else if($request->action == 'delete') {
			$rules = array('id' => 'required|digits_between:1,10');
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, $validator->errors());
		} else {
			try {
				DB::beginTransaction();
				if($request->action == 'update' || $request->action == 'delete') {
					$query = Models\Menu::where('id', $request->id)->first();
					if(!$query) {
						DB::rollback();
						return false;
					}
				}
				$data = [];
				if($request->has('name') && !empty($request->name)) {
					$data['name'] = $request->name;
				}

				if($request->has('url') && !empty($request->url)) {
					$data['url'] = $request->url;
				}

				if($request->action == 'update') {
					$query->update($data);
					if (!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else if($request->action == 'delete') {
					$query->delete();
					if(!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else {
					$insert = Models\Menu::insert($data);
					if(!$insert) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				}
				DB::commit();
				switch ($request->action) {
					case 'update':
						return self::JsonExport(200, 'Update success');
					case 'insert':
						return self::JsonExport(200, 'Insert success');
					default:
						return self::JsonExport(200, 'Delete success');
				}
			} catch (\Exception $e) {
				DB::rollback();
				return self::JsonExport(500, 'Error');
			}	
		}
	}

	//User
	public function admin_user(Request $request)
	{
		try {
			if(Auth::user()) {
				return view('Web.Admin.Page.user');
			} else {
				return redirect()->route('admin.login.view');
			}
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_user_ajax(Request $request)
	{
		try {
			if($request->has('id') && !empty($request->id)) {
				$data = self::getUser($request->id);
			} else {
				$data = self::getDTUser();
			}
			if($data == false){
				return self::JsonExport(500, 'Error');
			} else {
				return $data;
			}
		} catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
		}
	}

	public function getDTUser(){
        try {
            $data = Models\User::all();
			if(!$data){
				return false;
			}
			$result =  Datatables::of($data)
			->addColumn('action', function ($v) {
                $action = '';
                if(1==1) {
                    $action .= '<span class="btn-action table-action-edit cursor-pointer tx-success" data-id="'.$v->id.'"><i class="fa fa-edit"></i></span>';
                }
				if(1==1) {
                    $action .= '<span class="btn-action table-action-delete cursor-pointer tx-danger mg-l-5" data-id="'.$v->id.'"><i class="fa fa-trash"></i></span>';
                }
                return $action;
			})
			->addIndexColumn()
			->rawColumns(['action'])
			->make(true);
			return $result;
        } catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
        }  
	}

	public function getUser($id){
		try{
			$data = Models\User::where('id', $id)->first();
			if($data){
				$result = self::JsonExport(200, trans('app.success'), $data);
			} else {
				$result = self::JsonExport(404, 'Error');
			}
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function admin_post_user_ajax(Request $request)
	{
		$rules = array(
			'name' => 'required',
			'email' => 'required'
		);
		if($request->action == 'update') {
			$rules['id'] = 'required|digits_between:1,10';
		} else if($request->action == 'delete') {
			$rules = array('id' => 'required|digits_between:1,10');
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, $validator->errors());
		} else {
			try {
				DB::beginTransaction();
				if($request->action == 'update' || $request->action == 'delete') {
					$query = Models\User::where('id', $request->id)->first();
					if(!$query) {
						DB::rollback();
						return false;
					}
				}
				$data = [];
				if($request->has('name') && !empty($request->name)) {
					$data['name'] = $request->name;
				}

				if($request->action == 'update'){
					$check_user = Models\User::where('id', '!=', $request->id)->where('email', $request->email)->first();
					if($check_user){
						DB::rollback();
						return self::JsonExport(403, 'User existed');
					}

					if($request->has('email') && !empty($request->email)) {
						$data['email'] = $request->email;
					}

					if($request->has('password') && !empty($request->password)) {
						$data['password'] = Hash::make($request->password);
					}
				} else if ($request->action == 'insert'){
					$check_user = Models\User::where('email', $request->email)->first();
					if($check_user){
						DB::rollback();
						return self::JsonExport(403, 'User existed');
					}

					if($request->has('email') && !empty($request->email)) {
						$data['email'] = $request->email;
					}

					if($request->has('password') && !empty($request->password)) {
						$data['password'] = Hash::make($request->password);
					} else {
						$data['password'] = Hash::make(123456);
					}
				}

				if($request->action == 'update') {
					$query->update($data);
					if (!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else if($request->action == 'delete') {
					$query->delete();
					if(!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else {
					$insert = Models\User::insert($data);
					if(!$insert) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				}
				DB::commit();
				switch ($request->action) {
					case 'update':
						return self::JsonExport(200, 'Update success');
					case 'insert':
						return self::JsonExport(200, 'Insert success');
					default:
						return self::JsonExport(200, 'Delete success');
				}
			} catch (\Exception $e) {
				DB::rollback();
				return self::JsonExport(500, 'Error');
			}	
		}
	}

	//Category
	public function admin_category(Request $request)
	{
		try {
			if(Auth::user()) {
				$menu = Models\Menu::all();
				return view('Web.Admin.Page.category', [
					'menu' => $menu
				]);
			} else {
				return redirect()->route('admin.login.view');
			}
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_category_ajax(Request $request)
	{
		try {
			if($request->has('id') && !empty($request->id)) {
				$data = self::getCategory($request->id);
			} else {
				$data = self::getDTCategory();
			}
			if($data == false){
				return self::JsonExport(500, 'Error');
			} else {
				return $data;
			}
		} catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
		}
	}

	public function getDTCategory(){
        try {
            $data = Models\Category::all();
			if(!$data){
				return false;
			}
			$result =  Datatables::of($data)
			->addColumn('menu_id', function ($v) {
				if(!empty($v->menu_id)){
					return $v->menu->name;
				} else {
					return '';
				}
			})
			->addColumn('action', function ($v) {
                $action = '';
                if(1==1) {
                    $action .= '<span class="btn-action table-action-edit cursor-pointer tx-success" data-id="'.$v->id.'"><i class="fa fa-edit"></i></span>';
                }
				if(1==1) {
                    $action .= '<span class="btn-action table-action-delete cursor-pointer tx-danger mg-l-5" data-id="'.$v->id.'"><i class="fa fa-trash"></i></span>';
                }
                return $action;
			})
			->addIndexColumn()
			->rawColumns(['action'])
			->make(true);
			return $result;
        } catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
        }  
	}

	public function getCategory($id){
		try{
			$data = Models\Category::with('categoryImages')->where('id', $id)->first();
			if($data){
				$result = self::JsonExport(200, trans('app.success'), $data);
			} else {
				$result = self::JsonExport(404, 'Error');
			}
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function admin_post_category_ajax(Request $request)
	{
		$rules = array(
			'name' => 'required',
			'url' => 'required',
			'menu_id' => 'required',
			'type' => 'required',
			// 'video' => 'max:20000|mimes:mp4',
			// 'img' => 'max:10000|mimes:png,jpg,jpeg',
		);
		if($request->action == 'update') {
			$rules['id'] = 'required|digits_between:1,10';
		} else if($request->action == 'delete') {
			$rules = array('id' => 'required|digits_between:1,10');
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, $validator->errors());
		} else {
			try {
				DB::beginTransaction();
				if($request->action == 'update' || $request->action == 'delete') {
					$query = Models\Category::where('id', $request->id)->first();
					if(!$query) {
						DB::rollback();
						return false;
					}
				}
				$data = [];
				$array_img = [];
				if($request->has('name') && !empty($request->name)) {
					$data['name'] = $request->name;
				}

				if($request->has('url') && !empty($request->url)) {
					$data['url'] = $request->url;
				}

				if($request->has('type') && !empty($request->type)) {
					$data['type'] = $request->type;
				}

				if($request->has('menu_id') && !empty($request->menu_id)) {
					$data['menu_id'] = $request->menu_id;
				}

				if($request->type == 2){
					$data['video'] = $request->video;
				} else {
					$data['video'] = '';
				}

				if($request->action == 'update') {
					$query->update($data);
					if (!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else if($request->action == 'delete') {
					$query->delete();
					if(!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else {
					$insert = Models\Category::create($data);
					if(!$insert) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				}
				DB::commit();
				if($request->has('img') && !empty($request->img)) {
					$dir = public_path('img/item');
					if (!File::exists($dir)) {
						File::makeDirectory($dir, 0777, true, true);
					}
					
					foreach($request->img as $key => $val){
						$name_image_img = 'img_'.$key.'_'.time().'.'.$val->getClientOriginalExtension();
						if($request->action == 'update') {
							$insert_category_img = Models\CategoryImage::create([
								'category_id' => $query->id,
								'url' => 'img/item/'.$name_image_img
							]);
						} if($request->action == 'insert') {
							$insert_category_img = Models\CategoryImage::create([
								'category_id' => $insert->id,
								'url' => 'img/item/'.$name_image_img
							]);
						}
						
						Uploader::uploadFile($val, 'img/item', 'item', false, $name_image_img);
					}
				}

				switch ($request->action) {
					case 'update':
						return self::JsonExport(200, 'Update success');
					case 'insert':
						return self::JsonExport(200, 'Insert success');
					default:
						return self::JsonExport(200, 'Delete success');
				}
			} catch (\Exception $e) {
				DB::rollback();
				return self::JsonExport(500, 'Error');
			}	
		}
	}

	//Item
	public function admin_item(Request $request)
	{
		try {
			if(Auth::user()) {
				$category = Models\Category::where('type', 1)->get();
				$material = Models\Material::get();
				$size = Models\Size::get();
				$color = Models\Color::get();
				return view('Web.Admin.Page.item', [
					'category' => $category,
					'material' => $material,
					'size' => $size,
					'color' => $color
				]);
			} else {
				return redirect()->route('admin.login.view');
			}
		} catch (\Exception $e) {
			return redirect()->route('admin.login.view');
		}
	}

	public function admin_item_ajax(Request $request)
	{
		try {
			if($request->has('id') && !empty($request->id)) {
				$data = self::getItem($request->id);
			} else {
				$data = self::getDTItem();
			}
			if($data == false){
				return self::JsonExport(500, 'Error');
			} else {
				return $data;
			}
		} catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
		}
	}

	public function getDTItem(){
        try {
            $data = Models\Item::all();
			if(!$data){
				return false;
			}
			$result =  Datatables::of($data)
			->addColumn('category', function ($v) {
				if(!empty($v->category_id)){
					return $v->category->name;
				} else {
					return '';
				}
			})
			->addColumn('hot', function ($v) {
				if($v->is_hot == 1){
					return '<i class="fa fa-check-circle tx-success"></i>';
				} else {
					return '<i class="fas fa-times-circle tx-danger"></i>';
				}
			})
			->addColumn('image', function ($v) {
				if(!empty($v->img_thumb)){
					return  '<img href="'.asset($v->img_thumb).'" data-lightbox="image-'.$v->id.'" src="'.asset($v->img_thumb).'" width="60" height="60"/>';
				}
			})
			->addColumn('action', function ($v) {
                $action = '';
                if(1==1) {
                    $action .= '<span class="btn-action table-action-edit cursor-pointer tx-success" data-id="'.$v->id.'"><i class="fa fa-edit"></i></span>';
                }
				if(1==1) {
                    $action .= '<span class="btn-action table-action-delete cursor-pointer tx-danger mg-l-5" data-id="'.$v->id.'"><i class="fa fa-trash"></i></span>';
                }
                return $action;
			})
			->addIndexColumn()
			->rawColumns(['action', 'hot', 'image'])
			->make(true);
			return $result;
        } catch (\Exception $e) {
			return self::JsonExport(500, 'Error');
        }  
	}

	public function getItem($id){
		try{
			$data = Models\Item::with('itemImages')->where('id', $id)->first();
			if($data){
				$result = self::JsonExport(200, trans('app.success'), $data);
			} else {
				$result = self::JsonExport(404, 'Error');
			}
			return $result;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function admin_post_item_ajax(Request $request)
	{
		$rules = array(
			'name' => 'required',
			'sub_name' => 'required',
			'slug' => 'required',
			'category_id' => 'required',
			'slug' => 'required',
			'material' => 'required',
			'size' => 'required',
			'color' => 'required',
			// 'video' => 'max:20000|mimes:mp4',
			// 'img' => 'max:10000|mimes:png,jpg,jpeg',
		);
		if($request->action == 'update') {
			$rules['id'] = 'required|digits_between:1,10';
		} else if($request->action == 'delete') {
			$rules = array('id' => 'required|digits_between:1,10');
		}
		$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			return self::JsonExport(403, $validator->errors());
		} else {
			// try {
				DB::beginTransaction();
				if($request->action == 'update' || $request->action == 'delete') {
					$query = Models\Item::where('id', $request->id)->first();
					if(!$query) {
						DB::rollback();
						return false;
					}
				}
				$data = [];
				$array_img = [];
				if($request->has('name') && !empty($request->name)) {
					$data['name'] = $request->name;
				}

				if($request->has('sub_name') && !empty($request->sub_name)) {
					$data['sub_name'] = $request->sub_name;
				}

				if($request->has('slug') && !empty($request->slug)) {
					$data['slug'] = $request->slug;
				}

				if($request->has('price') && !empty($request->price)) {
					$data['price'] = $request->price;
				}


				if($request->has('description_save') && !empty($request->description_save)) {
					$text_description = str_replace("<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>", "", $request->description_save);
					$text_description = str_replace("</body>\r\n</html>", "", $text_description);
					$data['description'] = $text_description;
				}

				if($request->has('farbrics_save') && !empty($request->farbrics_save)) {
					$text_farbrics = str_replace("<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>", "", $request->farbrics_save);
					$text_farbrics = str_replace("</body>\r\n</html>", "", $text_farbrics);
					$data['farbrics'] = $text_farbrics;
				}

				if($request->has('detail_save') && !empty($request->detail_save)) {
					$text_save = str_replace("<!DOCTYPE html>\r\n<html>\r\n<head>\r\n</head>\r\n<body>", "", $request->detail_save);
					$text_save = str_replace("</body>\r\n</html>", "", $text_save);
					$data['detail'] = $text_save;
				}

				if($request->has('size') && !empty($request->size)) {
					$data['size']  = implode(',', $request->size);
				}

				if($request->has('material') && !empty($request->material)) {
					$data['material']  = implode(',', $request->material);
				}

				if($request->has('color') && !empty($request->color)) {
					$data['color']  = implode(',', $request->color);
				}

				if($request->has('category_id') && !empty($request->category_id)) {
					$data['category_id'] = $request->category_id;
				}

				if($request->has('img_thumb') && !empty($request->img_thumb)) {
					$name_image_thumb = 'img_thumb_'.time().'.'.$request->img_thumb->getClientOriginalExtension();
					$data['img_thumb'] = 'img/item/'.$name_image_thumb;
				}

				if($request->action == 'update') {
					$query->update($data);
					if (!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else if($request->action == 'delete') {
					$query->delete();
					if(!$query) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				} else {
					$insert = Models\Item::create($data);
					if(!$insert) {
						DB::rollback();
						return self::JsonExport(403, 'Error');
					}
				}
				DB::commit();

				if($request->has('img_thumb') && !empty($request->img_thumb)) {
					$dir = public_path('img/item');
					if (!File::exists($dir)) {
						File::makeDirectory($dir, 0777, true, true);
					}

					Uploader::uploadFile($request->img_thumb, 'img/item', 'item', false, $name_image_thumb);
				}

				if($request->has('imgs') && !empty($request->imgs)) {
					$dir = public_path('img/item');
					if (!File::exists($dir)) {
						File::makeDirectory($dir, 0777, true, true);
					}
					
					foreach($request->imgs as $key => $val){
						$name_image_img = 'img_'.$key.'_'.time().'.'.$val->getClientOriginalExtension();
						if($request->action == 'update') {
							$insert_item_img = Models\ItemImage::create([
								'item_id' => $query->id,
								'url' => 'img/item/'.$name_image_img
							]);
						} if($request->action == 'insert') {
							$insert_item_img = Models\ItemImage::create([
								'item_id' => $insert->id,
								'url' => 'img/item/'.$name_image_img
							]);
						}
						
						Uploader::uploadFile($val, 'img/item', 'item', false, $name_image_img);
					}
				}

				switch ($request->action) {
					case 'update':
						return self::JsonExport(200, 'Update success');
					case 'insert':
						return self::JsonExport(200, 'Insert success');
					default:
						return self::JsonExport(200, 'Delete success');
				}
			// } catch (\Exception $e) {
			// 	DB::rollback();
			// 	return self::JsonExport(500, 'Error');
			// }	
		}
	}
}
