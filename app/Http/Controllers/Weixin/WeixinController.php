<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Storage;


class WeixinController extends Controller
{
    public function accessToken()
    {
        //Cache::pull('access');exit;
        $access = Cache('access');
        if (empty($access)) {
            $appid = "wx51db63563c238547";
            $appkey = "35bdd2d4a7a832b6d20e4ed43017b66e";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appkey";
            $info = file_get_contents($url);
            $arrInfo = json_decode($info, true);
            $key = "access";
            $access = $arrInfo['access_token'];
            $time = $arrInfo['expires_in'];

            cache([$key => $access], $time);
        }

        return $access;
    }
    public function xmladd(Request $request)
    {
        $client = new Client();
        //echo $request->input('echostr');
        $str = file_get_contents("php://input");
        $objxml = simplexml_load_string($str);
        //var_dump($objxml);
        file_put_contents("/tmp/1809_weixin.log", $str, FILE_APPEND);

        $Event = $objxml->Event;
        $FromUserName = $objxml->FromUserName;
        $ToUserName = $objxml->ToUserName;
        $MsgType = $objxml->MsgType;
        $MediaId = $objxml->MediaId;


        $access = $this->accessToken();
        $userUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$FromUserName&lang=zh_CN";
        $userAccessInfo = file_get_contents($userUrl);
        $userInfo = json_decode($userAccessInfo, true);
        //var_dump($userInfo);exit;
        $name = $userInfo['nickname'];
        $sex = $userInfo['sex'];
        $headimgurl = $userInfo['headimgurl'];
        $openid1 = $userInfo['openid'];
        if ($Event == 'subscribe') {
            $data = DB::table('kaoshi')->where('openid', $FromUserName)->count();
            //print_r($data);die;
            if ($data == '0') {
                $weiInfo = [
                    'name' => $name,
                    'sex' => $sex,
                    'img' => $headimgurl,
                    'openid' => $openid1,
                    'time' => time()
                ];
                DB::table('kaoshi')->insert($weiInfo);

                //回复消息
                $time = time();
                $content = "关注本公众号成功";
                $xmlStr = "
                   <xml>
                        <ToUserName><![CDATA[$FromUserName]]></ToUserName>
                        <FromUserName><![CDATA[$ToUserName]]></FromUserName>
                        <CreateTime>$time</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[$content]]></Content>
                   </xml>";
                echo $xmlStr;

            }else{
                $time = time();
                $content = "欢迎" . $name . "回来";
                $xmlStr = "
                   <xml>
                        <ToUserName><![CDATA[$FromUserName]]></ToUserName>
                        <FromUserName><![CDATA[$ToUserName]]></FromUserName>
                        <CreateTime>$time</CreateTime>
                        <MsgType><![CDATA[text]]></MsgType>
                        <Content><![CDATA[$content]]></Content>
                   </xml>";
                echo $xmlStr;
            }

        }



        if($MsgType=='text'){
            $city = explode('+',$objxml->Content)[0];
            //echo 'city: '.$city;
            $url = "https://free-api.heweather.net/s6/weather/now?key=HE1904161132041607&location=".$city;
            $arr = json_decode(file_get_contents($url),true);
            //echo '<pre>';print_r($arr);echo '</pre>';
            $f1 = $arr['HeWeather6'][0]['basic']['location'];//城市
            $wind_dir = $arr['HeWeather6'][0]['now']['wind_dir'];//风向
            $wind_sc = $arr['HeWeather6'][0]['now']['wind_sc'];//风力
            $tmp = $arr['HeWeather6'][0]['now']['tmp'];//温度
            $hum = $arr['HeWeather6'][0]['now']['hum'];//湿度

            $str = "城市: ".$f1."\n" . "风向: ".$wind_dir ."\n" ."风力：" .$wind_sc."\n" ."温度: ".$tmp."\n" . "湿度: " .$hum."\n";
            $time = time();
            $response_xml = "
                <xml>
                    <ToUserName><![CDATA[$FromUserName]]></ToUserName>
                    <FromUserName><![CDATA[$ToUserName]]></FromUserName>
                    <CreateTime>$time</CreateTime>
                    <MsgType><![CDATA[text]]></MsgType>
                    <Content><![CDATA[".$str."]]></Content>
                </xml>
                ";
            echo $response_xml;








            $content = $objxml->Content;
            $openid = $objxml->FromUserName;
            $createtime = $objxml->CreateTime;

            $arr = [
                'content'=>$content,
                'openid'=>$openid,
                'createtime'=>$createtime
            ];

            $info =DB::table('content')->insert($arr);

        }else if($MsgType=='image') {
            $access = $this->accessToken();
            $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access&media_id=$MediaId";
            $response = $client->get(new Uri($url));
            $headers = $response->getHeaders();
            $file_info = $headers['Content-disposition'][0];
            $file_name = rtrim(substr($file_info, -20), '"');
            $new_file_name = "/tmp/image/" . date("Y-m-d H:i:s") . $file_name;

            $rs = Storage::put($new_file_name, $response->getBody());
            //print_r($rs);exit;
//            $time = time();
//            $res_str = file_get_contents($url);
//
//            file_put_contents("/tmp/image/$time.jpg", $res_str, FILE_APPEND);
            if ($rs == '1') {
                //echo '1111';exit;
                $dataInfo = [
                    "nickname" => $userInfo['nickname'],
                    "openid" => $openid1,
                    "img" => $new_file_name
                ];
                //var_dump($dataInfo);exit;
                $imginfo = DB::table('image')->insert($dataInfo);

            }
        }else if ($MsgType == 'voice') {
            $access = $this->accessToken();
            $vourl = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=$access&media_id=$MediaId";
            $response = $client->get(new Uri($vourl));
            $headers = $response->getHeaders();
            $voice_info = $headers['Content-disposition'][0];
            $voice_name = rtrim(substr($voice_info, -20), '"');
            $new_voice_name = "/tmp/voice/" . date("Y-m-d H:i:s") . $voice_name;

            $vors = Storage::put($new_voice_name, $response->getBody());
            //print_r($vors);exit;
//            $time = time();
//            $res_str = file_get_contents($url);
//
//            file_put_contents("/tmp/image/$time.jpg", $res_str, FILE_APPEND);
            if ($vors == '1') {
                //echo '1111';exit;
                $dataInfo = [
                    "nickname" => $userInfo['nickname'],
                    "openid" => $openid1,
                    "voice" => $new_voice_name
                ];
                //var_dump($dataInfo);exit;
                $imginfo = DB::table('voice')->insert($dataInfo);
            }
        }
    }
    /**自定义菜单添加*/
    public function createadd(Request $request){
        $access = $this->accessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access";
        $arr = array(
            'button'=>array(
                array(
                    "name"=>"xxx",
                    "type"=>"click",
                    "key"=>"aaa",
                    "sub_button"=>array(
                        array(
                            "type"=>"pic_weixin",
                            "name"=>"发送图片",
                            "key"=>"aaa",
                        ),
                    ),
                ),
                array(
                    "name"=>"dadada",
                    "type"=>"view",
                    "url"=>"https://www.baidu.com"
                ),
            ),
        );

        $strJson = json_encode($arr,JSON_UNESCAPED_UNICODE);
        $objurl = new Client();
        $response = $objurl->request('POST',$url,[
            'body' => $strJson
        ]);
        $res_str = $response->getBody();
        //var_dump($res_str);
        return $res_str;
    }
}
