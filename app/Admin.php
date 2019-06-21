<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    protected $table = 'admin';
    public $timestamps = false;
    protected $hidden = ['password'];
    protected $appends = ['role_name','is_super'];

    public static function generatePassword($password)
    {
        $salt          = env('PASSWORD_SALT','MEMESTECH');
        $passwordChars = str_split($password);

        foreach ($passwordChars as $char) {
            $salt .= md5($char);
        }
        return md5($salt);
    }

    public function getRoleNameAttribute(){
        return $this->hasOne('App\AdminRole','id','role_id')->value('name');
    }

    public function getIsSuperAttribute(){
        return $this->hasOne('App\AdminRole','id','role_id')->value('is_super');
    }

    public static function getUploadToken(){
        $accessKey = env('QINIU_ACCESSKEY');
        $secretKey = env('QINIU_SECRETKEY');
        $bucket = env('QINIU_BUCKET_STATIC');
        $baseUrl = env('QINIU_BASE_URL');
        if (empty($accessKey) || empty($secretKey) || empty($bucket) || empty($baseUrl)){
            return null;
        }
        /**
         * {
        "state": "SUCCESS",
        "url": "upload/demo.jpg",
        "title": "demo.jpg",
        "original": "demo.jpg"
        }
         */
        //构建鉴权对象
        $auth = new Auth($accessKey,$secretKey);
        $returnBody = '{"key":"$(key)","hash":"$(etag)","fsize":$(fsize),"bucket":"$(bucket)","ext":"$(ext)","fname":"$(fname)","state":"SUCCESS","url":"http://mall.luxiya.cn/$(key)","title":"$(fname)","original":"$(bucket)/$(key)"}';
        $policy = array(
            'returnBody' => $returnBody
        );
        //生成上传 token
        $token = $auth->uploadToken($bucket,null,3600,$policy,true);

        return $token;

    }
}
