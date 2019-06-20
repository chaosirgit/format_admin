<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function success($data,$code = 200){
        return response()->json(['code'=>$code,'data'=>$data]);
    }

    public function error($data,$code = 400){
        return response()->json(['code'=>$code,'data'=>$data]);
    }

    public function layuiData($paginateObj){
        return response()->json(['code'=>0,'msg'=>'','count'=>$paginateObj->total(),'data'=>$paginateObj->items()]);
    }

    public function pageDate($paginateObj){
        $results = array('data'=>$paginateObj->items(),'page'=>$paginateObj->currentPage(),'pages'=>$paginateObj->lastPage(),'total'=>$paginateObj->total());
        return $this->success($results);
    }
}
