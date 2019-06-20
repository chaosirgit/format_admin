<?php

namespace App\Http\Middleware;

use App\Users;
use Closure;
use EasyWeChat\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Overtrue\LaravelWeChat\Events\WeChatUserAuthorized;

class WechatAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $account = 'default', $scopes = null)
    {

        $config = config(\sprintf('wechat.mini_program.%s', $account), []);

        $app = Factory::miniProgram($config);


        try{
            if ($request->has('code')){
                $code = $request->get('code');
                var_dump($app->auth->session($code));
                die;
            }
            return $next($request);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['code'=>400,'data'=>$exception->getMessage().$exception->getLine().$exception->getFile()]);
        }

    }
}
