<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'users';
    public $timestamps = false;

    public static function getUserByOpenId($open_id){
        return self::where('open_id',$open_id)->first();
    }
}
