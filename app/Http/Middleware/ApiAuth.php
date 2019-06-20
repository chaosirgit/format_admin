<?php

namespace App\Http\Middleware;

use App\Users;
use Closure;

class ApiAuth
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
        $user_id = Users::getUserId();
        if (empty($user_id)){
            return response()->json(['code'=>999,'data'=>'请重新登陆']);
        }
        return $next($request)->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Credentials', 'true');
    }
}
