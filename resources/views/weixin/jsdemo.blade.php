<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>JSSDK</title>
</head>
<body>



<button id="btn1">选择照片</button>

<img src="" alt="" id="imgs0">
<hr>

<script src="/js/jquery.min.js"></script>
<script src="http://res2.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<script>
    wx.config({
        debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
        appId: "{{$js_config['appId']}}", // 必填，公众号的唯一标识
        timestamp: "{{$js_config['timestamp']}}", // 必填，生成签名的时间戳
        nonceStr: "{{$js_config['nonceStr']}}", // 必填，生成签名的随机串
        signature: "{{$js_config['signature']}}",// 必填，签名
        jsApiList: ['chooseImage','uploadImage'] // 必填，需要使用的JS接口列表
    });
    wx.ready(function(){
        $("#btn1").click(function(){
            wx.chooseImage({
                count: 3, // 默认9
                sizeType: ['original', 'compressed'],
                sourceType: ['album', 'camera'],
                success: function ( res) {
                    var localIds = res.localIds;
                    var img = "";
                    $.each(localIds,function(i,v){
                        img += v+',';
                        var node = "#imgs"+i;
                        $(node).attr('src',v);
                        //上传图片
                        wx.uploadImage({
                            localId: v,
                            isShowProgressTips: 1,
                            success: function (res1) {
                                var serverId = res1.serverId;
                                //alert('serverID: '+ serverId);
                                console.log(res1);
                            }
                        });
                    })
                    $.ajax({
                        url : 'getImg?img='+img,     //将上传的照片id发送给后端
                        type: 'get',
                        success:function(d){
                            console.log(d);
                        }
                    });
                    console.log(img);
                }
            });
        });
    });
</script>
</body>
</html>