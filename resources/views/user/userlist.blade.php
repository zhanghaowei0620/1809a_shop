<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>会员管理-有点</title>
    <link rel="stylesheet" type="text/css" href="css/css.css" />
    <link rel="stylesheet" type="text/css" href="css/manhuaDate.1.0.css">
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/manhuaDate.1.0.js"></script>
    <script type="text/javascript" src="js/jquery-1.7.2.min.js"></script>
    <!-- <script type="text/javascript" src="js/page.js" ></script> -->
    <script type="text/javascript">
        $(function (){
            $("input.mh_date").manhuaDate({
                Event : "click",//可选
                Left : 0,//弹出时间停靠的左边位置
                Top : -16,//弹出时间停靠的顶部边位置
                fuhao : "-",//日期连接符默认为-
                isTime : false,//是否开启时间值默认为false
                beginY : 1949,//年份的开始默认为1949
                endY :2100//年份的结束默认为2049
            });
        });
    </script>
</head>

<body>


<div class="page" style="margin:0px 30px;">
    <!-- vip 表格 显示 -->
    <div class="conShow">
        <table border="1" cellspacing="0" cellpadding="0">
            <tr>
                <td width="250px" class="tdColor">头像</td>
                <td width="188px" class="tdColor">姓名</td>
                <td width="220px" class="tdColor">所在城市</td>
                <td width="220px" class="tdColor">openid</td>
            </tr>
            @foreach($userInfo as $k=>$v)
                <tr>
                    <td>
                        <div class="onsImg onsImgv">
                            <img src="{{$v['headimgurl']}}">
                        </div>
                    </td>
                    <td>{{$v['nickname']}}</td>
                    <td>{{$v['country']}}  {{$v['province']}} {{$v['city']}}</td>
                    <td>{{$v['openid']}}</td>
                </tr>
            @endforeach
        </table>
    </div>
</div>
<!-- 删除弹出框  end-->
</body>
<script src="js/j.js"></script>
<script src="layui/layui.js"></script>
<script type="text/javascript">
/**
 * layui.use('layer', function() {
        var layer = layui.layer;
        //加入黑名单
        $('.zxc').click(function () {
//            var box = $("[type='checkbox']");
            var openid =[];
            $("[type='checkbox']:checked").each(function () {
                var _this = $(this)
                openid.push(_this.attr('openids'));

            })
            $.post(
                'batchblacklist',
                {openid:openid},
                function (res) {
                    if (res.errmsg == 'ok') {
                        layer.msg('加入黑名单成功');
                    }
                }, 'json'
            )
        })
        //批量加入标签
        $('.asd').click(function(){
            var openid =[];
            $("[type='checkbox']:checked").each(function () {
                var _this = $(this)
                openid.push(_this.attr('openids'));
            })
            var dddd = $('#dddd').val();
            $.post(
                'batchtagging',
                {openid:openid,dddd:dddd},
                function (res) {
                    if (res.errmsg == 'ok') {
                        layer.msg('加入成功');
                    }
                }, 'json'
            )
        })

    })
 */


</script>
</html>