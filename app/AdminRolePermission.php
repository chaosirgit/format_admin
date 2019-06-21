<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminRolePermission extends Model
{
    protected $table = 'admin_role_permission';
    public $timestamps = false;
    protected $appends = ['action'];

    public function getActionAttribute(){
        return $this->hasOne('App\AdminAction','id','admin_action_id')->value('action');
    }
}
