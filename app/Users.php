<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Request;

/**
 * App\Users
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Users newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Users newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Users query()
 * @mixin \Eloquent
 */
class Users extends Model
{
    protected $table = 'users';
    public $timestamps = false;
    protected $hidden = ['radar_password','radar_pay_password'];
    protected $appends = ['parent_mobile'];

    public static function generatePassword($password)
    {
        $salt          = env('PASSWORD_SALT','PAYBAL');
        $passwordChars = str_split($password);

        foreach ($passwordChars as $char) {
            $salt .= md5($char);
        }
        return md5($salt);
    }

    public function getParentMobileAttribute(){
        return self::find($this->attributes['parent_id'])->mobile ?? '暂无';
    }

    public function getCreateTimeAttribute(){
        return Carbon::createFromTimestamp($this->attributes['create_time'])->toDateTimeString();
    }

    public static function getUserByOpenId($open_id){
        return self::where('open_id',$open_id)->first();
    }

    public static function getUserId(){
        $token = Request::header('Authorization',null);
        if (empty($token)){
            return 0;
        }else{
            return Redis::connection('token')->get($token);
        }
    }
}
