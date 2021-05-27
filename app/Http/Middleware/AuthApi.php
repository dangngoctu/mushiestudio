<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Carbon\Carbon;
use App\Models\MUser;
class AuthApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		try{
            if(Auth::check()){
                return $next($request);
            } else {
                return redirect()->route('admin.login.view');
            }
		} catch (\Exception $e) {
            return redirect()->route('admin.login.view');
        }
    }

    static public function JsonExport($code, $msg)
    {
        $callback = [
            'code' => $code,
            'msg' => $msg
        ];
        return response()->json($callback, 200, [], JSON_NUMERIC_CHECK);
    }

}
