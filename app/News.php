<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';
    public $timestamps = false;
    protected $appends = ['category_name'];


    public function getCategoryNameAttribute(){
        return $this->hasOne('App\NewsCategory','id','category_id')->value('name');
    }

    public function getCreateTimeAttribute(){
        return Carbon::createFromTimestamp($this->attributes['create_time'])->toDateTimeString();
    }
}
