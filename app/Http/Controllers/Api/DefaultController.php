<?php

namespace App\Http\Controllers\Api;

use App\Admin;
use App\Upload;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DefaultController extends Controller
{
    public function saveUpload(Request $request){
        $user_id = $request->get('user_id',0);
        $is_admin = $request->get('is_admin',0);
        $key = $request->get('key','');
        $ext = $request->get('ext','');
        $file_size = $request->get('file_size',0);
        $file_name = $request->get('file_name','');

        $baseUrl = env('QINIU_BASE_URL');
        if (!empty($baseUrl)){
            $url = $baseUrl.'/'.$key;
            $ext = substr($ext,1);
        }else{
            $url = $key;
        }


        if (empty($key) || empty($user_id) || empty($ext)){
            return $this->error('参数错误');
        }
        try{

            $upload = new Upload();
            $upload->user_id = $user_id;
            $upload->is_admin = $is_admin;
            $upload->key = $key;
            $upload->ext = $ext;
            $upload->file_size = $file_size;
            $upload->file_name = $file_name;
            $upload->url = $url;
            $upload->create_time = time();
            $upload->save();
            return $this->success(['src'=>$url,'id'=>$upload->id]);
        }catch (\Exception $exception){
            return $this->error($exception->getMessage());
        }

    }


    public function getQiniuToken(){
        return $this->success(Admin::getUploadToken());
    }
}
