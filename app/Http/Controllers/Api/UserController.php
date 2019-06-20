<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function login(Request $request){
        $user_id = session('user_id');
        return redirect($request->back_url);
        return $this->success($user_id);
    }
}
