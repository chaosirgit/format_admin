<?php

namespace App\Http\Controllers\Admin;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index(){
        return view('admin.setting.index');
    }

    public function setting(Request $request){
        $data = $request->all();
        try{
            foreach ($data as $key => $value){
                if (($key == 'banner_img') || ($key == 'banner_link')){
                    $value = rtrim($value,'|');
                }
                Setting::setValueByKey($key,$value);
            }
            return $this->success('设置成功');
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }

    }
}
