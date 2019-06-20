<?php

namespace App\Http\Middleware;

use App\Users;
use Closure;
use EasyWeChat\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Overtrue\LaravelWeChat\Events\WeChatUserAuthorized;
use Webpatser\Uuid\Uuid;

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

        $user_id = Users::getUserId();
        try{
            if (empty($user_id)){
                if ($request->has('code')){
                    $code = $request->get('code');
                    $data = $app->auth->session($code);
                    if (empty($data)){
                        return response()->json(['code'=>400,'data'=>'获取openid失败']);
                    }
                    DB::beginTransaction();
                    $user = Users::getUserByOpenId($data['openid']);

                    if (empty($user)) {
                        $user              = new Users();
                        $user->uid         = Uuid::generate()->string;
                        $user->open_id     = $data['openid'];
                        $user->nickname    = $request->get('nickname', '');
                        $user->avatar      = $request->get('avatar', '');
                        $user->gender      = $request->get('gender', '');
                        $user->create_time = time();
                        $user->save();
                        DB::commit();
                    }

                    $user_id = $user->id;
                }
            }
            $request->attributes->add(['user_id'=>$user_id]);

            return $next($request);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['code'=>400,'data'=>$exception->getMessage().$exception->getLine().$exception->getFile()]);
        }

    }
}
