<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head><meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
    <title>绑定手机号</title>
    <meta content="app-id=984819816" name="apple-itunes-app" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, user-scalable=no, maximum-scale=1.0" />
    <meta content="yes" name="apple-mobile-web-app-capable" />
    <meta content="black" name="apple-mobile-web-app-status-bar-style" />
    <meta content="telephone=no" name="format-detection" />
    <link href="css/comm.css" rel="stylesheet" type="text/css" />
    <link href="css/login.css" rel="stylesheet" type="text/css" />
    <link href="css/findpwd.css" rel="stylesheet" type="text/css" />
    <script src="js/jquery-1.11.2.min.js"></script>
</head>
<body>

<!--触屏版内页头部-->
<div class="m-block-header" id="div-header">
    <strong id="m-title">绑定手机号</strong>
    <a href="javascript:history.back();" class="m-back-arrow"><i class="m-public-icon"></i></a>
    <a href="/" class="m-index-icon"><i class="m-public-icon"></i></a>
</div>



<div class="wrapper">
    <div class="registerCon">
        <ul>
            <li>
                <input type="hidden" name="openid" value="{{$openid['openid']}}" id="openid">
                <s class="password"></s>
                <input type="number" id="verifcode" placeholder="请输入要绑定的手机号" value="" maxlength="26" />
                <span class="clear">x</span>
            </li>
            <li><a id="findPasswordNextBtn" href="javascript:void(0);" class="orangeBtn">确认绑定该手机号</a></li>
        </ul>
    </div>

</div>

<script src="layui/layui.js"></script>
<script>
    layui.use(['layer', 'laypage', 'element'], function(){
        var layer = layui.layer
            ,laypage = layui.laypage
            ,element = layui.element();
    })
</script>
<script>







    function resetpwd(){
        // 密码失去焦点
        $('#verifcode').blur(function(){
            reg=/^[1][3-9]\d{3,9}$/;
            var that = $(this);
            if( that.val()==""|| that.val()=="6-16位数字、字母组成") {
                layer.msg('请输入要绑定的手机号！');
            }
            // }else if(!reg.test(that.val())){
            //     layer.msg('请输入正确格式的手机号！');
            // }
        })

    }
    resetpwd();





    $('.registerCon input').bind('keydown',function(){
        var that = $(this);
        if(that.val().trim()!=""){

            that.siblings('span.clear').show();
            that.siblings('span.clear').click(function(){
                that.val("");
                $(this).hide();
            })

        }else{
            that.siblings('span.clear').hide();
        }

    })
    // function show(){
    //     if($('.registerCon input').attr('type')=='password'){
    //         $(this).prev().prev().val($("#passwd").val());
    //     }
    // }
    // function hide(){
    //     if($('.registerCon input').attr('type')=='text'){
    //         $(this).prev().prev().val($("#passwd").val());
    //     }
    // }
    // $('.registerCon s').bind({click:function(){
    //     if($(this).hasClass('eye')){
    //         $(this).removeClass('eye').addClass('eyeclose');

    //         $(this).prev().prev().prev().val($(this).prev().prev().val());
    //         $(this).prev().prev().prev().show();
    //         $(this).prev().prev().hide();



    //     }else{
    //             console.log($(this  ));
    //             $(this).removeClass('eyeclose').addClass('eye');
    //             $(this).prev().prev().val($(this).prev().prev().prev().val());
    //             $(this).prev().prev().show();
    //             $(this).prev().prev().prev().hide();

    //          }
    //      }
    //  })
</script>
</body>
</html>
<script>
    $(function(){
        layui.use('layer',function(){
            var layer=layui.layer;
            $('.orangeBtn').click(function(){
                var openid=$('#openid').val();
                var register_name=$('#verifcode').val();
                $.post(
                    "bindingopenidadd",
                    {openid:openid,register_name:register_name},
                    function(res){
                        if(res.status==1){
                            location.href='index'
                        }else{
                            layer.msg(res.msg);
                        }
                    }
                )
            })
        })
    })
</script>