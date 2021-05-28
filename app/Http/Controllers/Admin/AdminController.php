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
			// try {
				DB::beginTransaction();
				$check = Models\Setting::where('key', $request->key)->first();
				if($check){
					$check->update(['value' => $request->value]);
				} else {
					return self::JsonExport(404, 'Data wrong');
				}
				
				DB::commit();
				return self::JsonExport(200, 'Update success');
			// } catch (\Exception $e) {
			// 	DB::rollback();
			// 	return self::JsonExport(500, 'Error');
			// }	
		}
	}
}
