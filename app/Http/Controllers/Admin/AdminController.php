<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\AdminRolePermission;
use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function login(){

        return view('admin.login');
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

    public function main(){

        return view('admin.main');
    }


    public function logout(){
        session()->forget('admin_id');
        return redirect('admin/login');
    }

    public function sendMsg(){
        return view('admin.sendMsg');
    }


    public function postSendMsg(Request $request){
        $mobile = $request->get('mobile',null);
        $content = $request->get('content',null);
        if (empty($mobile) || empty($content)){
            return $this->error('参数错误');
        }
        try{
            Setting::sendSmsForSmsBao($mobile,$content);
            return $this->success('发送成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }


    }
}
