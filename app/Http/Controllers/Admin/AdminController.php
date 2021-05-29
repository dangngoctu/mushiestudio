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

	public function index(){
        try {
			$user = Auth::user();
            return view('Web.Admin.Page.setting');
        } catch (\Exception $e) {
			return redirect()->route('admin.login.view');
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
}
