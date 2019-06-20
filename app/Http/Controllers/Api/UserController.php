<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function login(){
        $user_id = session('user_id');
        return $this->success($user_id);
    }
}
