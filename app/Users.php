<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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

    public static function getUserByOpenId($open_id){
        return self::where('open_id',$open_id)->first();
    }

    public static function getUserId(){
        return session('user_id',null);
    }
}
