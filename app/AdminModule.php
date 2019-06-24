<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AdminModule extends Model
{
    protected $table = 'admin_module';
    public $timestamps = false;
    protected $appends = ['children'];

    public function getChildrenAttribute(){
        $actions = AdminAction::where('admin_module_id',$this->attributes['id'])->get();
        return $actions;
    }

    public static function getModules(){
        return self::pluck('id')->toArray();
    }
}
