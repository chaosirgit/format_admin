<?php

namespace App\Http\Controllers\Api;

use App\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function login(){
        $user_id = session('user_id');
        $user = Users::find($user_id);
        if (empty($user->radar_username)){
            return $this->error('请注册雷达币账户',4001);
        }else{
            return $this->success($user->id);
        }


    }
}
