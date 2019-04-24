<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class JssdkController extends Controller
{
    public function jsdemo(){
        $ticket = getJsapiTicket();
        //var_dump($ticket);exit;
        $nonceStr = Str::random(10);
        $time = time();
        $current_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] .$_SERVER['REQUEST_URI'];

        $string1 = "jsapi_ticket=$ticket&noncestr=$nonceStr&timestamp=$time&url=$current_url";
        $sign = sha1($string1);
        //var_dump($sign);exit;

        $js_config = [
            'appId'=>'wx51db63563c238547',
            'timestamp'=>$time,
            'nonceStr'=>$nonceStr,
            'signature'=>$sign,
        ];
        $goodsHotInfo=DB::table('goods')->orderBy('create_time','desc')->limit(1)->get(['goods_id','goods_name','goods_img','goods_selfprice'])->toArray();
        //print_r($goodsHotInfo);exit;
        $goods_id = $goodsHotInfo[0]->goods_id;
        $update = [
            'title'=>'最新消息',
            'desc'=>'最新商品信息',
            'link'=>'http://1809zhanghaowei.comcto.com/jsdemo?goods='.$goods_id,
            'imgUrl'=>'https://ss1.bdstatic.com/70cFuXSh_Q1YnxGkpoWK1HF6hhy/it/u=4096777492,3696807149&fm=26&gp=0.jpg'
        ];
        $data = [
            'js_config'=>$js_config,
            'update'=>$update,
            'goodsdata'=>$goodsHotInfo
        ];
        return view('weixin.goodsdetail',$data);

        //return view('weixin.jsdemo',$data);
    }

    public function getImg()
    {
        echo '<pre>';print_r($_GET);echo '</pre>';
    }



//    public function demo(){
//        $city = explode('+',$objxml->Content)[0];
//        //echo 'city: '.$city;
//        $url = "https://free-api.heweather.net/s6/weather/now?key=HE1904161132041607&location=".$city;
//        $arr = json_decode(file_get_contents($url),true);
//        //echo '<pre>';print_r($arr);echo '</pre>';
//        $f1 = $arr['HeWeather6'][0]['basic']['location'];//城市
//        $wind_dir = $arr['HeWeather6'][0]['now']['wind_dir'];//风向
//        $wind_sc = $arr['HeWeather6'][0]['now']['wind_sc'];//风力
//        $tmp = $arr['HeWeather6'][0]['now']['tmp'];//温度
//        $hum = $arr['HeWeather6'][0]['now']['hum'];//湿度
//
//        $str = "城市: ".$f1."\n" . "风向: ".$wind_dir ."\n" ."风力：" .$wind_sc."\n" ."温度: ".$tmp."\n" . "湿度: " .$hum."\n";
//        $time = time();
//        $response_xml = "
//                <xml>
//                    <ToUserName><![CDATA[$FromUserName]]></ToUserName>
//                    <FromUserName><![CDATA[$ToUserName]]></FromUserName>
//                    <CreateTime>$time</CreateTime>
//                    <MsgType><![CDATA[text]]></MsgType>
//                    <Content><![CDATA[".$str."]]></Content>
//                </xml>
//                ";
//        echo $response_xml;
//
//
//
//
//
//
//
//
//        $content = $objxml->Content;
//        $openid = $objxml->FromUserName;
//        $createtime = $objxml->CreateTime;
//
//        $arr = [
//            'content'=>$content,
//            'openid'=>$openid,
//            'createtime'=>$createtime
//        ];
//
//        $info =DB::table('content')->insert($arr);
//    }
}
