<?php

namespace App;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'setting';
    public $timestamps = false;

    public static function getValueByKey($key,$default = ''){
        $result = self::where('key',$key)->first();
        if (empty($result)){
            return $default;
        }else{
            return $result->value;
        }
    }

    public static function setValueByKey($key,$value){
        try{
            $result = self::where('key',$key)->first();
            if (empty($result)){
                $result = new self();
                $result->key = $key;
            }
            $result->value = $value;
            $result->save();
            return true;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }

    public static function sendSmsForSmsBao($mobile,$content)
    {
        try{
            $username = self::getValueByKey('smsBao_username', 'paihaosc');
            $password = self::getValueByKey('password', 'paihaoSC2019');
            if (empty($mobile)){
                throw new \Exception('请填写手机号');
            }

            if (empty($content)) {
                throw new \Exception('请填写发送内容');
            }
            $format_content = '【派好商城】';
            $api            = 'http://api.smsbao.com/sms';
            $send_url       = $api . "?u=" . $username . "&p=" . md5($password) . "&m=" . $mobile . "&c=" . urlencode($format_content.$content);
            $controller = new Controller();
            $return_message = $controller->request('GET',$send_url);
            if ($return_message == 0) {
                return true;
            } else {
                $statusStr = array(
                    "-1" => "参数不全",
                    "-2" => "服务器空间不支持,请确认支持curl或者fsocket，联系您的空间商解决或者更换空间！",
                    "30" => "密码错误",
                    "40" => "账号不存在",
                    "41" => "余额不足",
                    "42" => "帐户已过期",
                    "43" => "IP地址限制",
                    "50" => "内容含有敏感词"
                );
                throw new \Exception($statusStr[$return_message]);
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }

    }


    public static function getExpressInfo($express_code,$express_no,$mobile){
        $customer_id = self::getValueByKey('exp100_customer', 'EF72D280EB0BD252D3AD86EFDC5920A7');
        $key = self::getValueByKey('exp100_key', 'arzupcaa7670');
        $post_data = array();
        $post_data["customer"] = $customer_id;
        $post_data["param"] = '{"com":"'.$express_code.'","num":"'.$express_no.'","phone":"'.$mobile.'"}';
        $url = 'http://poll.kuaidi100.com/poll/query.do';
        $post_data["sign"] = md5($post_data["param"].$key.$post_data["customer"]);
        $post_data["sign"] = strtoupper($post_data["sign"]);
        $o = '';
        foreach ($post_data as $k=>$v)
        {
            $o .= "$k=".urlencode($v)."&";		//默认UTF-8编码格式
        }
        $post_data=substr($o,0,-1);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($ch);
        $data = str_replace("\"",'"',$result );
        $data = json_decode($data,true);
        if ($data['message'] == 'ok'){
            $result = $data['data'];
        }else{
            $result = array();
        }
        return $result;
    }



    /**
     * @param $lng1 经度1
     * @param $lat1 纬度1
     * @param $lng2 经度2
     * @param $lat2 纬度2
     *
     * @param int $len_type 输出类型(m?km)
     * @param int $decimal 保留小数位
     * @return float
     */
    public static function GetDistance($lng1,$lat1,$lng2 , $lat2,  $len_type = 1, $decimal = 2)
    {
        $radLat1 = $lat1 * M_PI / 180.0;
        $radLat2 = $lat2 * M_PI / 180.0;
        $a = $radLat1 - $radLat2;
        $b = ($lng1 * M_PI / 180.0) - ($lng2 * M_PI / 180.0);
        $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2)));
        $s = $s * 6378.137;
        $s = round($s * 1000);
        if ($len_type > 1) {
            $s /= 1000;
        }
        $result = round($s, $decimal);
        return number_format($result,$decimal,'.','');
    }




    /**
     *计算某个经纬度的周围某段距离的正方形的四个点
     *
     *@param lng float 经度
     *@param lat float 纬度
     *@param distance float 该点所在圆的半径，该圆与此正方形内切，默认值为0.5千米
     *@return array 正方形的四个点的经纬度坐标
     */
    public static function returnSquarePoint($lng, $lat,$distance = 0.5){
        //6371-地球半径
        $dlng =  2 * asin(sin($distance / (2 * 6371)) / cos(deg2rad($lat)));
        $dlng = rad2deg($dlng);

        $dlat = $distance/6371;
        $dlat = rad2deg($dlat);

        return array(
            'left-top'=>array('lat'=>$lat + $dlat,'lng'=>$lng-$dlng),
            'right-top'=>array('lat'=>$lat + $dlat, 'lng'=>$lng + $dlng),
            'left-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng - $dlng),
            'right-bottom'=>array('lat'=>$lat - $dlat, 'lng'=>$lng + $dlng)
        );
    }
}
