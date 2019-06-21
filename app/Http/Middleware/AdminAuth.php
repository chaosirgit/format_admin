<?php

namespace App\Http\Middleware;

use App\Admin;
use App\AdminAction;
use App\AdminRolePermission;
use Closure;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $admin_id = session('admin_id');
        if (empty($admin_id)){
            return redirect('admin/login');
        }
        $role_id = session('role_id');
        if (empty($role_id)){
            return redirect('admin/login');
        }
        $admin = Admin::findOrFail($admin_id);
        view()->share('admin',$admin);

        $upload_type = env('UPLOAD_TYPE','LOCAL');
        if ($upload_type == 'QINIU'){
            $qiniu_token = Admin::getUploadToken();
            if (empty($qiniu_token)){
                return abort(404,'请填写七牛云配置');
            }
            view()->share('qiniu_token',$qiniu_token);
        }

        $action_name = $request->route()->getName();

        $action = AdminAction::where('action',$action_name)->first();
        if (!empty($action)){
            $action_id = $action->id;
        }else{
            $action_id = 0;
        }

        $is_super = session('is_super',0);
        if (!empty($is_super)){
            view()->share('action_arr',AdminAction::pluck('action')->toArray());
            $response = $next($request);
            if ($request->method() == 'POST'){
                $operate = new \App\AdminOperate();
                $operate->admin_id = $admin_id;
                $operate->role_id = $role_id;
                $operate->action_id = $action_id;
                $operate->result_msg = $response->original['data'];
                $operate->create_time = time();
                $operate->save();
            }

            return $response;
        }


        $permissions = AdminRolePermission::where('role_id',$role_id)->get();
        view()->share('action_arr',$permissions->pluck('action')->toArray());

        if (!in_array($action_id,$permissions->pluck('admin_action_id')->toArray())){
            if ($request->method() == 'POST'){
                return response()->json(['code'=>403,'data'=>'权限不足']);
            }else{
                abort(403);
            }

        }

        $response = $next($request);

        if ($request->method() == 'POST'){
            $operate = new \App\AdminOperate();
            $operate->admin_id = $admin_id;
            $operate->role_id = $role_id;
            $operate->action_id = $action_id;
            $operate->result_msg = $response->original['data'];
            $operate->create_time = time();
            $operate->save();
        }
        return $response;
    }
}
