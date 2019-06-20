<?php

namespace App\Http\Middleware;

use App\Users;
use Closure;
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
        // $account 与 $scopes 写反的情况
        if (is_array($scopes) || (\is_string($account) && Str::is('snsapi_*', $account))) {
            list($account, $scopes) = [$scopes, $account];
            $account || $account = 'default';
        }

        $isNewSession = false;
        $sessionKey = \sprintf('user_id', $account);
        $config = config(\sprintf('wechat.mini_program.%s', $account), []);
        $officialAccount = app(\sprintf('wechat.mini_program.%s', $account));
        $scopes = $scopes ?: Arr::get($config, 'oauth.scopes', ['snsapi_base']);

        if (is_string($scopes)) {
            $scopes = array_map('trim', explode(',', $scopes));
        }

        $session = session($sessionKey, []);
        try{
            if (!$session) {
                if ($request->has('code')) {
//                session([$sessionKey => $officialAccount->oauth->user() ?? []]);
                    $wechat_user = $officialAccount->oauth->user();
                    $data['openid'] = $wechat_user->getId() ?? '';          //取得openid
                    $has = Users::getUserByOpenId($data['openid']);
                    if(!empty($has)){              //openid 在库里跳转到下一步
                        $user_id = $has->id;
                        session([$sessionKey => $user_id ?? '']);
                        return $next($request);
                    }else{                                              //不再库里
                        $data['nickname'] = $wechat_user->getNickname() ?? '';
                        if(empty($data['nickname'])){                   //取不到nickname 使用授权登陆
                            session([$sessionKey => '']);
                            return $officialAccount->oauth->scopes(['snsapi_userinfo'])->redirect($request->fullUrl());
                        }else{                                          //取到nickname 存库
                            $data['avatar'] = $wechat_user->getAvatar() ?? '';
                            //存入相关信息
                            DB::beginTransaction();
                            $user = new Users();
                            $user->uid = UUID::generate()->string;
                            $user->open_id = $data['openid'];
                            $user->nickname = $data['nickname'];
                            $user->avatar = $data['avatar'];
                            $user->create_time = time();
                            $user->save();
                            DB::commit();
                            session([$sessionKey => $user->id ?? '']);
                        }
                    }
                    $isNewSession = true;

//                event(new WeChatUserAuthorized(session($sessionKey), $isNewSession, $account));

                    return redirect()->to($this->getTargetUrl($request));
                }

                session()->forget($sessionKey);

                return $officialAccount->oauth->scopes($scopes)->redirect($request->fullUrl());
            }

//        event(new WeChatUserAuthorized(session($sessionKey), $isNewSession, $account));

            return $next($request);
        }catch (\Exception $exception){
            DB::rollBack();
            return response()->json(['code'=>400,'data'=>$exception->getMessage()]);
        }

    }
}
