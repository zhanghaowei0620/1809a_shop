<?php

namespace App\Http\Controllers\Weixin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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
        $data = [
            '$js_config'=>$js_config
        ];
        return view('weixin.jsdemo',$data);
    }
}
