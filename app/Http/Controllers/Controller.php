<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SetCookie;
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
            $cookie_string = $response->getHeader('Set-Cookie');
            $res_data['Set-Cookie'] = $cookie_string;
        }
        return $res_data;
    }

    public function cookieRequest($method,$api,$data = array(),$cookie_string){
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
        return $res_data;

    }
}
