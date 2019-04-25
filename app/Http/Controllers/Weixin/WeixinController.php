<?php

namespace App\Http\Controllers\Weixin;


use App\Http\Controllers\Weixin\WXBizDataCryptController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Uri;
use Illuminate\Support\Str;
use Storage;



class WeixinController extends Controller
{
    public function accessToken()
    {
        //Cache::pull('access');exit;
        $access = Cache('access');
        if (empty($access)) {
//            $appid = "wx51db63563c238547";
//            $appkey = "35bdd2d4a7a832b6d20e4ed43017b66e";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".env('WX_APP_ID')."&secret=".env('WX_KEY')."";
            $info = file_get_contents($url);
            $arrInfo = json_decode($info, true);
            $key = "access";
            $access = $arrInfo['access_token'];
            $time = $arrInfo['expires_in'];

            cache([$key => $access], $time);
        }

        return $access;
    }
    /**微信授权*/
    public function wechat(){
        $url = urlencode("http://1809zhanghaowei.comcto.com//wechatToken");
//        $appid = "wx51db63563c238547";
        $scope = "snsapi_userinfo";
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".env('WX_APP_ID')."&redirect_uri=$url&response_type=code&scope=$scope&state=STATE#wechat_redirect";


        return view('weixin.wechat',['url'=>$url]);
    }
    public function wechatToken(Request $request){
        $access = $this->accessToken();
        $arr = $request->input();
        //var_dump($arr);exit;

        $code = $arr['code'];
        $user_id = '15';
//        $appid = "wx51db63563c238547";
//        $appkey = "35bdd2d4a7a832b6d20e4ed43017b66e";
        $accessToken = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".env('WX_APP_ID')."&secret=".env('WX_KEY')."&code=$code&grant_type=authorization_code";
        $info = file_get_contents($accessToken);
        $arr = json_decode($info,true);
        //var_dump($arr);exit;
        $openid = $arr['openid'];
        $userUrl = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access&openid=$openid&lang=zh_CN";
        $userAccessInfo = file_get_contents($userUrl);
        $userInfo = json_decode($userAccessInfo, true);
        //var_dump($userInfo);exit;
        $name = $userInfo['nickname'];
        $sex = $userInfo['sex'];
        $headimgurl = $userInfo['headimgurl'];
        $updatedata = [
            'openid'=>$openid
        ];

        $wechatdata = [
            'user_name'=>$name,
            'user_sex'=>$sex,
            'headimgurl'=>$headimgurl,
            'openid'=>$openid
        ];


        $res = DB::table('wechat')->where('openid',$openid)->first();
        if(empty($res)){
            DB::table('wechat')->insert($wechatdata);
            DB::table('user')->where('user_id',$user_id)->update($updatedata);
            echo "授权成功";
        }else{
            echo "欢迎回来";
        }
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
        $Content = $objxml->Content;

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
            if($Content == '最新商品'){
                $goodsHotInfo=DB::table('goods')->orderBy('create_time','desc')->limit(5)->get(['goods_id','goods_name','goods_img','goods_selfprice'])->toArray();
                $goods_id = $goodsHotInfo[0]->goods_id;
                $ToUserName = $FromUserName;
                $FormUserName = "gh_cf7ceceb3c6e";
                $CreateTime = time();
                $MsgType = 'news';
                $ArticleCount = 1;
                $Titkle = '最新消息';
                $Description = '最新商品信息';
                $PicUrl = 'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=4224459274,772817564&fm=27&gp=0.jpg';
                $Url = 'http://1809zhanghaowei.comcto.com/jsdemo?goods='.$goods_id;

                $response_xml = "
                <xml>
                     <ToUserName><![CDATA[$ToUserName]]></ToUserName>
                     <FromUserName><![CDATA[$FormUserName]]></FromUserName>
                     <CreateTime>$CreateTime</CreateTime>
                     <MsgType><![CDATA[$MsgType]]></MsgType>
                     <ArticleCount>$ArticleCount</ArticleCount>
                     <Articles>
                          <item>
                               <Title><![CDATA[$Titkle]]></Title>
                               <Description><![CDATA[$Description]]></Description>
                               <PicUrl><![CDATA[$PicUrl]]></PicUrl>
                               <Url><![CDATA[$Url]]></Url>
                          </item>
                     </Articles>
                </xml>
                ";
                echo $response_xml;
            }


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
    /**openid群发*/
    public function openiddo(Request $request){
        $objurl = new Client();
        $access = $this->accessToken();
        //获取测试号下所有用户的openid
        $userurl = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access";
        $info = file_get_contents($userurl);
        $arrInfo = json_decode($info, true);
        //var_dump($arrInfo);exit;
        $data = $arrInfo['data'];
        $openid = $data['openid'];
        //调用接口根据openid群发
        $msgurl = "https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=$access";
        $content = "你好";
        $arr = array(
            'touser'=>$openid,
            'msgtype'=>"text",
            'text'=>[
                'content'=>$content,
            ],
        );
        //print_r($arr);
        $strjson = json_encode($arr,JSON_UNESCAPED_UNICODE);
        $objurl = new Client();
        $response = $objurl->request('POST',$msgurl,[
            'body' => $strjson
        ]);
        $res_str = $response->getBody();
        //var_dump($res_str);
        return $res_str;
    }
    /*
     * 微信支付
     * 生成二维码*/
    public $weixin_unifiedorder_url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
    public $notify_url = 'http://1809zhanghaowei.comcto.com/notify';
    /**
     * 微信支付测试
     */
    public function wpay()
    {
        //
        $order_id = time().mt_rand(11111,99999);
        $str = md5(time());
        $order_info = [
            'appid'=>"wxd5af665b240b75d4",
            'mch_id'=>"1500086022",
            'nonce_str'=> $str,
            'sign_type'=> 'MD5',
            'body'=> '测试订单-'.mt_rand(1111,9999),
            'out_trade_no'=> $order_id,
            'total_fee'=> 1,
            'spbill_create_ip'=>$_SERVER['REMOTE_ADDR'],
            'notify_url'=>$this->notify_url,
            'trade_type'    => 'NATIVE'
        ];
        //var_dump($order_info);exit;
        $this->values = [];
        $this->values = $order_info;
        $this->SetSign();
        $xml = $this->ToXml();
        $rs = $this->postXmlCurl($xml, $this->weixin_unifiedorder_url, $useCert = false, $second = 30);
        //var_dump($rs);exit;
        $data = simplexml_load_string($rs);

        //将 code_url 返回给前端，前端生成 支付二维码
        $data = [
            'code_url'  => $data->code_url
        ];
        //var_dump($data);die;
        return view('wpaylist',$data);
    }
    protected function ToXml()
    {
        if(!is_array($this->values)
            || count($this->values) <= 0)
        {
            die("数组数据异常！");
        }
        $xml = "<xml>";
        foreach ($this->values as $key=>$val)
        {
            if (is_numeric($val)){
                $xml.="<".$key.">".$val."</".$key.">";
            }else{
                $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml.="</xml>";
        return $xml;
    }
    private  function postXmlCurl($xml, $url, $useCert = false, $second = 30)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,TRUE);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);//严格校验
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//		if($useCert == true){
//			//设置证书
//			//使用证书：cert 与 key 分别属于两个.pem文件
//			curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
//			curl_setopt($ch,CURLOPT_SSLCERT, WxPayConfig::SSLCERT_PATH);
//			curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
//			curl_setopt($ch,CURLOPT_SSLKEY, WxPayConfig::SSLKEY_PATH);
//		}
        //post提交方式
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            die("curl出错，错误码:$error");
        }
    }
    public function SetSign()
    {
        $sign = $this->MakeSign();
        $this->values['sign'] = $sign;
        return $sign;
    }
    private function MakeSign()
    {
        $key = "7c4a8d09ca3762af61e59520943AB26Q";
        //签名步骤一：按字典序排序参数
        ksort($this->values);
        $string = $this->ToUrlParams();
        //签名步骤二：在string后加入KEY
        $string = $string . "&key=$key";
        //签名步骤三：MD5加密
        $string = md5($string);
        //签名步骤四：所有字符转为大写
        $result = strtoupper($string);
        return $result;
    }
    /**
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams()
    {
        $buff = "";
        foreach ($this->values as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }
    /**
     * 微信支付回调
     */
    public function wstatus(Request $request){
        $xml = file_get_contents("php://input");
        $arr = json_decode(json_encode(simplexml_load_string($xml,'SimpleXMLElement',LIBXML_NOCDATA)),true);
        file_put_contents("/tmp/weixin.log",var_export($arr,true),FILE_APPEND);

        $sign = $arr['sign'];
        $signs = "weixin:$sign\n";
        unset($arr['sign']);
        $newsStr = $this->checksign($arr);
        $newsStr = strtoupper($newsStr);
        $newsStrs= "localhost:{$newsStr}\n";
        file_put_contents("/tmp/sign.log",$signs,FILE_APPEND);
        file_put_contents("/tmp/sign.log",$newsStrs,FILE_APPEND);


        $order_no = $arr['out_trade_no'];
        if($sign==$newsStr){
            $datas = [
                'type'=>"微信",
                'status'=>2
            ];
            DB::table('order')->where('order_no',$order_no)->update($datas);

            $orderDetailInfo=DB::table('order_detail')->where('order_no',$order_no)->get();
            foreach($orderDetailInfo as $k=>$v){
                $goodsInfo=DB::table('goods')->where('goods_id',$v->goods_id)->first();
                $goods_num=$goodsInfo->goods_num - $v->buy_number;
                $goodsUpdate=DB::table('goods')->where('goods_id',$v->goods_id)->update(['goods_num'=>$goods_num]);

                $openid = "o4rK85pje4sMiHfs9F9aFmTvDn3U";
                //print_r($arr->openid);exit;
                $objurl = new Client();
                $accessToken = $this->accessToken();
                $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=$accessToken";

                $url2 = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$accessToken&openid=$openid&lang=zh_CN";
                $bol2 = file_get_contents($url2);
                $strjson = json_decode($bol2,JSON_UNESCAPED_UNICODE);
                $nickname = $strjson['nickname'];
                $arr = array(
                    'touser'=>$openid,
                    'template_id'=>"skIxpTGsa97WKN_OjbBWpG6ViwkWujElgYlJ6NFwk18",
                    'data'=>array(
                        'name'=>array(
                            'value'=>$goodsInfo->goods_name,
                        ),
                        'age'=>array(
                            'value'=>"支付成功"
                        ),
                    ),
                );
                $strjson = json_encode($arr,JSON_UNESCAPED_UNICODE);
                $response = $objurl->request('POST',$url,[
                    'body' => $strjson
                ]);
                $res_str = $response->getBody();
                echo "支付成功";
                //var_dump($res_str);
                return $res_str;
            }
        }else{
            $datas = [
                'type'=>"微信",
                'status'=>0
            ];
            DB::table('order')->where('order_no',$order_no)->update($datas);
        }

    }
    private function checksign($arr){
        ksort($arr);
        $key = "7c4a8d09ca3762af61e59520943AB26Q";
        $strParams = urldecode(http_build_query($arr));
        $strParams.= "&key=$key";
        $endStr = md5($strParams);
        return $endStr;
    }





    public function notify()
    {
        $data = file_get_contents("php://input");
        //记录日志
        $log_str = date('Y-m-d H:i:s') . "\n" . $data . "\n<<<<<<<";
        file_put_contents('/tmp/wx_pay_notice.log',$log_str,FILE_APPEND);
        $xml = simplexml_load_string($data);
        if($xml->result_code=='SUCCESS' && $xml->return_code=='SUCCESS'){      //微信支付成功回调
            //验证签名
            $sign = true;
        }
        $response = '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';
        echo $response;
    }

}
