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
        if (empty($user->radar_username)){
            return $this->error('请注册雷达币账户',4001);
        }else{
            $token = Redis::connection('token')->get($user_id);
            return $this->success($token);
        }
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
