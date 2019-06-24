<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\AdminAction;
use App\AdminModule;
use App\AdminOperate;
use App\AdminRole;
use App\AdminRolePermission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    public function index(){
        return view('admin.admin.index');
    }

    public function lists(Request $request){
        $limit = $request->get('limit',10);
        $results = Admin::orderBy('id','desc')->paginate($limit);
        return $this->layuiData($results);
    }

    public function add(Request $request){
        $id = $request->get('id',null);
        if (empty($id)){
            $result = new Admin();
        }else{
            $result = Admin::find($id);
        }
        $roles = AdminRole::all();
        return view('admin.admin.add')->with(['result'=>$result,'roles'=>$roles]);
    }

    public function postAdd(Request $request){
        $id = $request->get('id',null);
        $username = $request->get('username',null);
        $password = $request->get('password',null);
        $role_id = $request->get('role_id',null);
        $has = Admin::where('username',$username)->first();
        if (!empty($has) && empty($id)){
            return $this->error('已经有这个管理员了');
        }
        try{
            if (empty($id)){
                $admin = new Admin();
                $admin->create_time = time();
            }else{
                $admin = Admin::find($id);
            }
            $admin->username = $username;
            $admin->password = Admin::generatePassword($password);
            $admin->role_id = $role_id;
            $admin->save();
            return $this->success('操作成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }


    public function delete(Request $request){
        $id = $request->get('id',null);
        if (empty($id)) return $this->error('参数错误');
        $admin = Admin::find($id);
        if (empty($admin)) return $this->error('无此记录');
        try{
            $admin->delete();
            return $this->success('删除成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }

    public function role(){
        return view('admin.admin.role');
    }

    public function roleList(){
        $results = AdminRole::all();
        return response()->json(['code'=>0,'msg'=>'','data'=>$results]);
    }

    public function roleAdd(Request $request){
        $id = $request->get('id',null);
        if (empty($id)){
            $result = new AdminRole();
        }else{
            $result = AdminRole::find($id);
        }
        return view('admin.admin.roleAdd')->with('result',$result);
    }

    public function postRoleAdd(Request $request){

        $id = $request->get('id', null);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => '角色名称必须填写',
        ]);

        if(empty($id)) {
            $adminRole = new AdminRole();
        }else{
            $adminRole = AdminRole::find($id);
            if($adminRole == null) {
                return redirect()->back();
            }
        }

        $adminRole->name = $request->get('name', '');
        $adminRole->is_super = $request->get('is_super', 0);

        if($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        try {
            $adminRole->save();
            return $this->success('添加成功');
        }catch (\Exception $ex){
            return $this->error($validator->errors()->first());
        }

    }

    public function postRoleDel(Request $request){
        $id = $request->get('id',null);
        if (empty($id)) return $this->error('参数错误');
        $admin_role = AdminRole::find($id);
        if (empty($admin_role)) return $this->error('无此记录');
        $has = Admin::where('role_id',$admin_role->id)->first();
        if (!empty($has)) return $this->error('该角色下有管理员，请先删除');
        try{
            $admin_role->delete();
            return $this->success('删除成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }
    }

    public function permission(Request $request){
        $role_id = $request->get('id',null);
        if (empty($role_id)) return $this->error('参数错误');
        $results = AdminRolePermission::where('role_id',$role_id)->pluck('admin_action_id')->toArray();
        $modules = AdminModule::all();
        return view('admin.admin.permission')->with(['results'=>$results,'modules'=>$modules,'role_id'=>$role_id]);
    }

    public function postPermission(Request $request){
        $role_id = $request->get('id', null);

        $role = AdminRole::find($role_id);
        if($role == null) {
            abort(404);
        }

        AdminRolePermission::where('role_id', $role_id)->delete();

        $permissions = $request->get('permission');
        if (!empty($permissions)){
            foreach( $permissions as $module => $actions)
            {
                foreach($actions as $action)
                {
                    $adminRolePermission = new AdminRolePermission();
                    $adminRolePermission->role_id = $role_id;
                    $adminRolePermission->admin_action_id = $action;
                    $adminRolePermission->save();
                }
            }
        }


        return $this->success('修改成功');
    }

    public function operate(){
        $admins = Admin::all();
        $actions = AdminAction::all();
        return view('admin.admin.operate')->with('admins',$admins)->with('actions',$actions);
    }

    public function operateList(Request $request){
        $search_admin = $request->get('search_admin',null);
        $search_action = $request->get('search_action',null);
        $search_time = $request->get('search_time',null);
        $limit = $request->get('limit',10);
        $results = new AdminOperate();
        if (!empty($search_admin)){
            $results = $results->where('admin_id',$search_admin);
        }
        if (!empty($search_action)){
            $results = $results->where('action_id',$search_action);
        }
        if (!empty($search_time)){
            $results = $results->where('create_time','>=',Carbon::createFromFormat('Y-m-d H:i:s',$search_time)->timestamp);
        }
        $results = $results->orderBy('id','desc')->paginate($limit);
        return $this->layuiData($results);
    }
}
