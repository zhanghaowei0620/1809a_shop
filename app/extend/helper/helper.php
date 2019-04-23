<?php
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

function getaccessToken()
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
/**
 * 获取jsapi ticket
 */
function getJsapiTicket()
{
    $key = 'wx_jsapi_ticket';
    $ticket = Redis::get($key);
    if($ticket){
        return $ticket;
    }else{
        $access_token = getaccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
        $ticket_info = json_decode(file_get_contents($url),true);
        if(isset($ticket_info['ticket'])){
            Redis::set($key,$ticket_info['ticket']);
            Redis::expire($key,3600);
            return $ticket_info['ticket'];
        }else{
            return false;
        }
    }
}


