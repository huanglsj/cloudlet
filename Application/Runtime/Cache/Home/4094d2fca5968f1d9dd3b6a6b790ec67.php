<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<title>微云交易</title>
<meta name="keywords" content="交易" />
<meta name="description" content="微云交易，轻松获得高收益---全国领先的交易平台">

<link href="/newpublic/css/style_head.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="/Public/Home/css/cd.css" />
<link rel="stylesheet" type="text/css" href="/Public/Home/css/icons.css" />
<script language="javascript" type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/cd.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/reconnecting-websocket.min.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/common.js"></script>

<link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />
<!-- <link rel="stylesheet" href="/Public/Home/css/mobile-angular-ui-hover.min.css" /> -->
<link rel="stylesheet" href="/Public/Home/css/mobile-angular-ui-base.min.css" />
<!-- <link rel="stylesheet" href="/Public/Home/css/swiper.min.css" /> -->
<link rel="stylesheet" href="/Public/Home/css/app.css" />
<!--     <script src="/Public/Home/js/angular.min.js"></script>
    <script src="/Public/Home/js/angular-route.min.js"></script>
    <script src="/Public/Home/js/mobile-angular-ui.min.js"></script>
    <script src="/Public/Home/js/mobile-angular-ui.gestures.min.js"></script> -->
<!--     <script src="/Public/Home/js/swiper.min.js"></script>
    <script src="/Public/Home/js/all.js"></script>
    <script src="/Public/Home/js/jquery.js"></script> -->


<script>
$(document).ready(function(){

	$(".top_nav > ol > a > li").click(function(){
       if(!$(this).hasClass("show_nav")){
		 $(".top_nav > ol > a > li").removeClass("stay_nav");
		 $(this).addClass("show_nav");
	    }
	  })

	})




</script>
<style>
.bottom_ico{
    width: 20px;
    height: 20px;
    margin: 5px auto 0 auto;
}
.btn-navbar-bottom-text{
    padding: 5px 0;
}
</style>

</head>
<body>




<div class="main" style="margin-bottom:80px;">

       
    <link rel="stylesheet" href="/Public/Home/css/global.css">
    <link rel="stylesheet" href="/Public/Home/css/index.css">
    <link rel="stylesheet" href="/Public/Home/css/pwd.css">

    <div class="wrap">
        <div class="index" style="min-height: 891px;">
            <header class="list-head">
                <nav class="list-nav clearfix"><a href="javascript:history.go(-1)" class="list-back"></a>
                    <h3 class="list-title">修改登陆密码</h3>
                </nav>
            </header>
            <div class="tip"></div>
            <form id="reviseForm" class="i-form" method="post" action="<?php echo U('User/edituser');?>">
                <ul class="form-box">
                    <li class="f-line clearfix">
                        <label class="f-label">当前密码</label>
                        <input id="c-pwd" class="f-input text" type="password" maxlength="18" placeholder="请输入当前登陆密码"
                               name="upwd">
                    </li>
                    <li class="f-line clearfix">
                        <label class="f-label">新密码</label>
                        <input id="n-pwd" class="f-input text" type="password" maxlength="18" placeholder="请输入六位新密码"
                               name="newpwd">
                    </li>
                    <li class="f-line clearfix">
                        <label class="f-label">确认密码</label>
                        <input id="r-pwd" class="f-input text" type="password" maxlength="18" placeholder="再次输入登陆密码"
                               name="mypwd">
                    </li>
                </ul>
                <input type="button" value="确 认" class="f-sub register-btn" id="send" onclick="repwd()">
            </form>
        </div>
    </div>
    <script>
        function repwd() {
            $.ajax({
                cache: true,
                type: "POST",
                url: "<?php echo U('User/edituser');?>",
                data: $('#reviseForm').serialize(),// 你的formid
                async: false,
                error: function (request) {
                    alert("Connection error");
                },
                success: function (data) {
                    layer.confirm(data.info, {
                        btn: ['确定']
                    }, function (index) {
                        if (data.info == '密码修改成功') {
                            location.href = "<?php echo U('User/memberinfo');?>";
                        }
                        layer.close(index);
                    });
                }
            });
        }
    </script>

 </div>





<!-- <div class="bottom_div">
     <ul>
       <a href="<?php echo U('Index/Index');?>"><li class="home">首页</li></a>
       <a href="<?php echo U('Detailed/dtrading');?>"><li class="trans">交易历史</li></a>
       <a href="<?php echo U('Detailed/drevenue');?>"><li class="state">收支明细</li></a>
       <a href="<?php echo U('User/memberinfo');?>"><li class="my">我的</li></a>
     </ul>
</div> -->
        <div class="navbar navbar-app navbar-absolute-bottom" style="position:fixed;bottom:0px;">
            <div class="btn-group justified">
                <a href="<?php echo U('Index/Index');?>" class="btn btn-default btn-navbar" id="index"><div class="bottom_ico" style=" background:url(/Public/Home/images/home.png) no-repeat center center;background-size:20px 20px;"></div><div class="btn-navbar-bottom-text">行情</div></a>
                <a href="<?php echo U('User/dtrading');?>" class="btn btn-default btn-navbar" id="dtrading"><div class="bottom_ico"  style=" background:url(/Public/Home/images/trans.png) no-repeat center center;background-size:20px 20px;"></div><div class="btn-navbar-bottom-text">订单</div></a>
				<a href="https://jlxc.kf5.com/kchat/29063?from=%E5%9C%A8%E7%BA%BF%E6%94%AF%E6%8C%81" class="btn btn-default btn-navbar" id="showdetailinfo"><div class="bottom_ico"  style=" background:url(/Public/Home/images/a-4.png) no-repeat center center;background-size:20px 20px;"></div><div class="btn-navbar-bottom-text">在线客服</div></a>
				<a href="<?php echo U('User/memberinfo');?>" class="btn btn-default btn-navbar" id="memberinfo"><div class="bottom_ico" style=" background:url(/Public/Home/images/my.png) no-repeat center center;background-size:20px 20px;"></div><div class="btn-navbar-bottom-text">我的</div></a>

            </div>
        </div>

<script>
if("<?php echo U('Index/Index');?>"==window.location.pathname){
document.getElementById("index").style.backgroundColor= "#ff972f";
}else if("<?php echo U('User/dtrading');?>"==window.location.pathname){
document.getElementById("dtrading").style.backgroundColor= "#ff972f";
}else if("<?php echo U('User/showdetailinfo');?>"==window.location.pathname){
document.getElementById("showdetailinfo").style.backgroundColor= "#ff972f";
}else if("<?php echo U('User/memberinfo');?>"==window.location.pathname){
document.getElementById("memberinfo").style.backgroundColor= "#ff972f";
}
</script>
<script src="/Public/Js/layer/layer.js"/>
<script>
//    $.ajax({
//        cache: true,
//        type: "GET",
//        url: "<?php echo U('Detailed/orderxq');?>",
//        error: function(data) {
//            console.log(data);
//        },
//        success: function(data) {
//            console.log('>>'+data);
//        }
//    });
</script>
<!--详情弹窗(全局)-->
<style>
    #comalterdesu{
        display: none;
        position: fixed;
        z-index: 666;
        right: 0;
        bottom: 50px;
        width: 100%;
        max-width: 640px;
        height: 290px;
        background: #272727;
    }
    #comalterdesu>.topp>h6{
        margin: 0;
        background: #272727;
        line-height: 40px;
        font-size: 1.2rem;
        color: #FFF;
        text-align: center;
    }
    #comalterdesu>.topp>a>img{
        padding: 10px;
        float: right;
        margin-top: -40px;
        margin-right: 10px;
    }

    #comalterdesu>ul{
        width: 84%;
        margin: 0 auto;
        overflow: hidden;
        padding-bottom: 20px;
    }
    #comalterdesu>ul>li{
        text-align: left;
        font-size: 1.2rem;
        line-height: 26px;
    }

    #comalterdesu > ul li {
        color: #fff;
    }
    #comalterdesu > ul > li span {
        margin-left: 20px;
    }
</style>
<div id="comalterdesu">
    <div class="topp">
        <h6>交易详情</h6>
        <a href="javascript:;" onclick="jQuery('#comalterdesu').hide()"><img src="/newpublic/images/gb.png"></a>
    </div>
    <ul>
        <li><span id="xqtitle2"  class="left"></span><span id="xqfx2"></span><span class="right" id="xqorderno2"></span></li>
        <li><span class="left">投资金额</span><span class="right" id="xqfee2">投资金额</span></li>
        <li><span class="left">模式</span><span class="right" id="xqmoshi2">模式</span></li>
        <li><span id="profittitle" class="left">周期(分)</span><span class="right" id="xqendprofit2">周期</span></li>
        <li><span class="left">建仓价</span><span class="right" id="xqbuyprice2">建仓价</span></li>
        <li><span class="left">建仓时间</span><span class="right" id="xqbuytime2">建仓时间</span></li>
        <li><span class="left">平仓价</span><span class="right" id="xqsellprice2">平仓价</span></li>
        <li><span class="left">平仓时间</span><span class="right" id="xqselltime2">平仓时间</span></li>
        <li><span class="left">实际盈亏</span><span class="right" id="xqploss2" style="font-weight:bold">实际盈亏</span></li>
    </ul>
</div>

<script>
var is_guest = '<?php echo ($is_guest); ?>';  //是否游客
// 显示详情弹窗(全局)
function showdetaildesu(oid) {
    $('#comalterdesu').show();
    $.ajax({
        type: "POST",
        url: "<?php echo U('User/jyxqcx');?>",
        data: {
            oid: oid
        },
        dataType: 'json',
        success: function(data) {
            //alert(data.ptitle);
            $('#xqtitle2').html(data.ptitle);
            if (data.ostyle == 0) {
                var fx = "买涨";
                $('#xqfx2').css('background', 'red');
            } else if (data.ostyle == 1) {
                var fx = "买跌";
                $('#xqfx2').css('background', 'green');
            }
            if (data.ploss > 0) {
                $('#xqploss2').css('color', 'red');
            } else if (data.ploss < 0) {
                $('#xqploss2').css('color', 'green');
            }
            if (data.eid == '1') {
                $('#xqmoshi2').html("限价竞购");
                $('#profittitle2').html("限价竞购");
            } else {
                $('#xqmoshi2').html("限时竞购");
                $('#profittitle2').html("限时竞购");
            }
            $('#xqfx2').html(fx);
            $('#xqorderno2').html(data.orderno);
            $('#xqfee2').html(data.fee);
            $('#xqendprofit2').html(data.endprofit);
            $('#xqbuyprice2').html(data.buyprice);
            $('#xqbuytime2').html(data.buytime);
            $('#xqsellprice2').html(data.sellprice);
            $('#xqselltime2').html(data.selltime);
            $('#xqploss2').html(data.ploss);
        }
    });
}

    function queryCloseState2(oid){
        $.ajax({
            cache: true,
            type: "get",
            url: appendUrl2("<?php echo U('Detailed/checkOrderIs');?>","/oid/"+oid),
            error: function(request) {
                layer.msg('服务器异常', {icon: 2});
            },
            success: function(data) {
                if (data[0].ostaus==1) {
                    showdetaildesu(oid);
                }else {
                    setTimeout(function(){queryCloseState2(oid);}, 3 * 1000 );
                }
            }
        });
    }

    function appendUrl2(origin,appender){
        //如果结尾是.html，则取出后再添加
        origin = origin.substring(0,origin.length - 5);
        return origin + encodeURI(appender) + ".html";
    }

<?php if($is_guest != 1): ?>if (typeof isDtradingP=="undefined") {
        $.ajax({
            cache: true,
            type: "get",
            url: "<?php echo U('Detailed/newOrder');?>",
            error: function(request) {
                layer.msg('服务器异常', {icon: 2});
            },
            success: function(data) {
                if (data) {
                    for(var i=0;i<data.length;i++){
                        queryCloseState2(data[i].oid);
                    }
                }
            }
        });
    }<?php endif; ?>
</script>
<!--详情弹窗(全局)-->
</body>
</html>