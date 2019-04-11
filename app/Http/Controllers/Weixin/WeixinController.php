<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class WeixinController extends Controller
{
    public function accessToken(){
        //Cache::pull('access');exit;
        $access = Cache('access');
        if(empty($access)){
            $appid = "wx51db63563c238547";
            $appkey = "35bdd2d4a7a832b6d20e4ed43017b66e";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appkey";
            $info = file_get_contents($url);
            $arrInfo = json_decode($info,true);
            $key = "access";
            $access = $arrInfo['access_token'];
            $time =$arrInfo['expires_in'];

            cache([$key=>$access],$time);
        }

        return $access;
    }
    public function xmladd(Request $request)
    {
        //echo $request->input('echostr');
        $con = mysqli_connect('127.0.0.1', 'root', '123456', 'test');
        $str = file_get_contents("php://input");
        $objxml = simplexml_load_string($str);
        var_dump($objxml);
        file_put_contents("/tmp/1809_weixin.log", $str, FILE_APPEND);

        $MsgType = $objxml->MsgType;
        //var_dump($MsgType);
        if($MsgType=='text'){
            file_put_contents("/tmp/test.log", $str, FILE_APPEND);


            $content = $objxml->Content;
            $openid = $objxml->FromUserName;
            $createtime = $objxml->CreateTime;

            $arr = [
                'content'=>$content,
                'openid'=>$openid,
                'createtime'=>$createtime
            ];

            $info =DB::table('content')->insert($arr);

        }
    }

    /**
    //获取用户的基本信息
    public function userInfo(){
    $access = $this->accessToken();
    $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access";
    $info = file_get_contents($url);
    $arrInfo = json_decode($info,true);
    $data = $arrInfo['data'];
    //var_dump($data);exit;
    $openid = $data['openid'];
    foreach($openid as $k=>$v){
    $userUrl="https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$v&lang=zh_CN";
    $userAccessInfo=file_get_contents($userUrl);
    $userInfo=json_decode($userAccessInfo,true);
    $datainfos[]=$userInfo;
    }

    //        $url2="https://api.weixin.qq.com/cgi-bin/tags/get?access_token=$access";
    //        $info2=file_get_contents($url2);
    //        $arr2=json_decode($info2,true);
    //
    //        $arr = [];
    //        foreach ($arr2 as $key => $value) {
    //            $arr = $value;
    //        }
    //var_dump($datainfos);exit;
    return view('user.userlist',['userInfo'=>$datainfos]);

    }
     */

    /**自定义菜单添加*/
    public function createadd(Request $request){
        $access = $this->accessToken();
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=$access";
        //$url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=$access";
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


        $context = stream_context_create(array(
            'http' => array(
                "method"=>"POST",
                "header"=>'Content-type:application/x-www-form-urlencoded',
                "content"=>http_build_query($arr),
                "timeout"=>20
            )
        ));
        $strJson = json_encode($context,JSON_UNESCAPED_UNICODE);
        $bol = file_get_contents($url,'POST',$context);
        var_dump($bol);
    }
}

