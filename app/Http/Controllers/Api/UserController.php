<?php

namespace App\Http\Controllers\Api;

use App\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
    public function login(Request $request){
        $user_id = $request->get('user_id',null);
        $user = Users::find($user_id);
        if (!empty($user)){
            $redis = Redis::connection('token');
            $token = $redis->get($user_id);
            if (empty($token)){
                $token = md5($user->uid.time());
                $redis->set($user->id,$token);
                $redis->set($token,$user->id);
            }else{
                if (!$redis->exists($token)){
                    $redis->set($token,$user->id);
                }
            }
            if (empty($user->radar_username)){
                return $this->error(['msg'=>'请注册雷达币账户','token'=>$token],4001);
            }else{
                $redis_cookie = Redis::connection('cookie');
                if (!$redis_cookie->exists($user->uid)){
                    $api = 'https://t.radarlab.org/api/user/login';
                    $post_data = array('username'=>$user->radar_username,'password'=>decrypt($user->radar_password));
                    $json_data = json_encode($post_data);
                    $res_data = $this->request('POST',$api,['json'=>$json_data],true);

                    $redis_cookie->set($user->uid,$res_data['Set-Cookie']);
                    if ($res_data['status'] == 'success'){
                        if (isset($res_data['result']['account_data']['emailNotActivated'])){
                            return $this->error(['radar_email'=>$user->radar_email,'msg'=>'需要激活','token'=>$token],4002);
                        }else{
                            if(isset($res_data['result']['account_data']['Account']) && $res_data['result']['need_unlock'] == true){
                                //需要输入支付密码
                                $api = 'https://t.radarlab.org/api/user/step_auth';
                                $post_data = ['code'=>decrypt($user->radar_pay_password)];
                                $radar_cookie = $redis_cookie->get($user->uid);
                                $res_pay_data = $this->cookieRequest($user->uid,'POST',$api,$radar_cookie,$post_data);
                                if ($res_pay_data['status'] == 'success'){
                                    return $this->success('登陆成功');
                                }else{
                                    return $this->error($res_pay_data['data']['message']);
                                }
                            }
                        }
                    }else{
                        return $this->error($res_data['data']['message']);
                    }
                }

                return $this->success($token);
            }
        }

        return $this->error('无此账户');

    }


    public function register(Request $request){
        $username = $request->get('username',null);
        $password = $request->get('password',null);
        $pay_password = $request->get('pay_password',null);
        $email = $request->get('email',null);

        if (empty($username) || empty($password) || empty($pay_password) || empty($email)){
            return $this->error('请填写完整');
        }

        $api = 'https://t.radarlab.org/api/user/register';
        $post_data = array(
            'username' => $username,
            'password' => $password,
            'pay_pwd' => $pay_password,
            'email' => $email,
        );

        $post_json = json_encode($post_data);
        $res_data = $this->request('POST',$api,['json'=>$post_json]);
        if (empty($res_data)){
            return $this->error('网络链接异常');
        }
        try{
            if ($res_data['status'] != 'success'){
                return $this->error($res_data['data']['message']);
            }else{
                DB::beginTransaction();
                $user = Users::find(Users::getUserId());
                $user->radar_username = $res_data['result']['account_data']['nick'];
                $user->radar_password = encrypt($password);
                $user->radar_pay_password = encrypt($pay_password);
                $user->radar_email = $email;
                $user->radar_validated = $res_data['result']['validated'] ? 1 : 0;
                $user->save();
                DB::commit();
                return $this->success(['message'=>'注册雷达币成功','radar_validated'=>$user->radar_validated,'radar_email'=>$user->radar_email]);
            }
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->error($exception->getMessage());
        }




    }

}
