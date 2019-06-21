<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\AdminRolePermission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function login(){
        $project_name = env('PROJECT_NAME','模因科技');
        var_dump(session(['aaa'=>1]));
        var_dump(session('aaa'));
        return view('admin.login')->with('project_name',$project_name);
    }

    public function postLogin(Request $request){
        $mobile = $request->get('mobile',null);
        $password = Admin::generatePassword($request->get('password',null));
        $user = Admin::where('username',$mobile)->first();
        if(empty($user)){
            return $this->error('无此管理员');
        }elseif($user->password != $password){
            return $this->error('密码错误');
        }else{
            session(['admin_id'=>$user->id,'is_super'=>$user->is_super,'role_id'=>$user->role_id]);
            return $this->success('登陆成功');
        }
    }

    public function index(){
        $admin = Admin::findOrFail(session('admin_id'));

        $admin_roles = AdminRolePermission::where('role_id',$admin->role_id)->get()->pluck('action')->toArray();

        return view('admin.index')->with(['admin_roles'=>$admin_roles]);
    }
}
