<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Redis;

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

    public function request($method,$api,$data = array(),$cookie = false){
        $client      = new Client();
        if ($method == 'POST'){
            $response    = $client->request($method, $api, ['form_params' => $data]);
        }elseif($method == 'GET'){
            $response    = $client->request($method, $api);
        }
        $res_string  = $response->getBody()->getContents();

        $res_data    = json_decode($res_string, true);
        if ($cookie){
            $cookie_arr = $response->getHeader('Set-Cookie');
            $res_data['Set-Cookie'] = $cookie_arr[0];
        }
        return $res_data;
    }

    public function cookieRequest($uuid,$method,$api,$cookie_string,$data = array()){
        $cookie_jar = new CookieJar();
        $cookie_jar->setCookie(SetCookie::fromString($cookie_string));
        $client = new Client(['cookies'=>$cookie_jar]);
        if ($method == 'POST'){
            $response    = $client->request($method, $api, ['form_params' => $data]);
        }elseif($method == 'GET'){
            $response    = $client->request($method, $api);
        }
        $res_string  = $response->getBody()->getContents();

        $res_data    = json_decode($res_string, true);
        $cookie_arr = $response->getHeader('Set-Cookie');
        $res_data['Set-Cookie'] = $cookie_arr[0];
        Redis::connection('cookie')->set($uuid,$res_data['Set-Cookie']);
        if ($res_data['status'] == 'failed'){
            if ($res_data['data']['error_code'] == 'NOT_LOGGEDIN'){
                return $this->error('未登陆雷达币',999);
            }
        }
        return $res_data;

    }

    public function localUpload(Request $request){

    }
}
