<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AccountLog extends Model
{
    protected $table = 'account_log';
    public $timestamps = false;
    protected $appends = ['type_name','mobile','nickname'];



    const ADMIN_CONF_BALANCE = 1;//后台调节账户-余额
    const ADMIN_CONF_INTEGRAL = 2;//后台调节账户-积分

    const TYPE_LIST = [
        self::ADMIN_CONF_BALANCE => '后台调节账户余额',
        self::ADMIN_CONF_INTEGRAL => '后台调节账户积分',

    ];

    public function getMobileAttribute(){
        return $this->hasOne('App\User','id','user_id')->value('mobile');
    }
    public function getNicknameAttribute(){
        return $this->hasOne('App\User','id','user_id')->value('nickname');
    }

    public function getTypeNameAttribute(){
        return self::TYPE_LIST[$this->attributes['type']];
    }

    public function getCreateTimeAttribute(){
        return Carbon::createFromTimestamp($this->attributes['create_time'])->toDateTimeString();
    }

    public static function insertLog($data = array()){
        $log = new self();
        $log->user_id = $data['user_id'] ?? 0;
        $log->value = $data['value'] ?? 0;
        $log->new_value = $data['new_value'] ?? 0;
        $log->info = $data['info'] ?? '';
        $log->type = $data['type'] ?? 0;
        $log->create_time = $data['create_time'] ?? time();
        $log->save();
    }
}
