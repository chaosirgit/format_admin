<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AdminOperate extends Model
{
    protected $table = 'admin_operate';
    public $timestamps = false;
    protected $appends = ['admin_name','role_name','action_name'];

    public function getCreateTimeAttribute(){
        return Carbon::createFromTimestamp($this->attributes['create_time'])->toDateTimeString();
    }

    public function getAdminNameAttribute(){
        return $this->hasOne('App\Admin','id','admin_id')->value('username');
    }

    public function getRoleNameAttribute(){
        return $this->hasOne('App\AdminRole','id','role_id')->value('name');
    }

    public function getActionNameAttribute(){
        return $this->hasOne('App\AdminAction','id','action_id')->value('name');
    }
}
