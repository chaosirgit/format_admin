<?php

namespace App\Http\Controllers\Admin;

use App\AccountLog;
use App\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(){
        return view('admin.user.index');
    }


    public function lists(Request $request){
        $limit = $request->get('limit',10);
        $search_value = $request->get('search_value','');
        $results = new Users();
        if (!empty($search_value)){
            $results = $results->where('mobile','like','%'.$search_value.'%');
        }
        $results = $results->orderBy('id','desc')->paginate($limit);

        return $this->layuiData($results);
    }

    public function add(Request $request){
        $id = $request->get('id',null);
        $result = Users::findOrNew($id);
        return view('admin.user.add')->with('result',$result);
    }


    public function postAdd(Request $request){
        $id = $request->get('id',null);
        $mobile = $request->get('mobile',null);
        $password = $request->get('password',null);
        $nickname = $request->get('nickname',null);
        $avatar = $request->get('avatar',null);
        $parent_mobile = $request->get('parent_mobile',null);
        $user = Users::find($id);
        if (empty($user)){
            return $this->error('无此用户');
        }
        try{
            if (!empty($mobile)){
                $user->mobile = $mobile;
            }
            if (!empty($password)){
                $user->password = User::generatePassword($password);
            }
            if (!empty($nickname)){
                $user->nickname = $nickname;
            }
            if (!empty($avatar)){
                $user->avatar = $avatar;
            }
            if (!empty($parent_mobile) && $parent_mobile != '暂无'){
                $parent = Users::where('mobile',$parent_mobile)->first();
                if (empty($parent)){
                    return $this->error('推荐人不存在');
                }
                $user->parent_id = $parent->id;
            }
            $user->save();
            return $this->success('修改成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }



    }


    public function conf(Request $request){
        $id = $request->get('id',null);
        if (empty($id)){
            abort(401,'参数错误');
        }
        $user = Users::find($id);
        if (empty($user)){
            abort(401,'账户不存在');
        }
        return view('admin.user.conf')->with('result',$user);
    }


    public function postConf(Request $request){
        $id = $request->get('id',null);
        $type = $request->get('type','0');
        $way = $request->get('way','increment');
        $conf_value = $request->get('conf_value',0);
        $info = $request->get('info',null);
        if (empty($id) || !in_array($type,[1,3]) || !in_array($way,['increment','decrement']) || empty($conf_value)){
            return $this->error('参数错误');
        }



        try{
            DB::beginTransaction();
            $user = Users::lockForUpdate()->find($id);
            if (empty($user)){
                DB::rollBack();
                return $this->error('无此账户');
            }
            if ($type == 1){
                $column = 'balance';
                $info = '后台调节余额:'.$info;
                $log_type = AccountLog::ADMIN_CONF_BALANCE;
            }elseif($type == 3){
                $column = 'integral';
                $info = '后台调节积分:'.$info;
                $log_type = AccountLog::ADMIN_CONF_INTEGRAL;
            }

            $user->$way($column,$conf_value);
            if ($way == 'decrement'){
                $conf_value = -$conf_value;
            }
            AccountLog::insertLog(['user_id'=>$user->id,'value'=>$conf_value,'new_value'=>$user->$column,'info'=>$info,'type'=>$log_type]);
            DB::commit();
            return $this->success('操作成功');
        }catch (\Exception $exception){
            DB::rollBack();
            return $this->error($exception->getMessage());
        }

    }
}
