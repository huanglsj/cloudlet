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

           <link rel="stylesheet" href="/Public/Home/css/global.css">    <link rel="stylesheet" href="/Public/Home/css/ticket.css">    <script id="G--xyscore-load" type="text/javascript" charset="utf-8" async src="/Public/Home/js/xyscore_main.js"></script>    <body style="overflow:scroll;">    <div class="wrap">        <div class="index">            <header class="list-head">                <nav class="list-nav clearfix"><a href="javascript:history.go(-1)" class="list-back"></a>                    <h3 class="list-title">收支明细</h3>                </nav>            </header>            <?php if($list): ?><ul class="ticket-list2" style="max-height:100%;color:#ccc;">                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Detailed/revenueid',array('orderno'=>$vo['orderno']));?>" class="clearfix">                            <?php if($vo["ploss"] > 0): ?><img src="/Public/Home/images/sz.png" class="t-icon2">                                <?php else: ?>                                <img src="/Public/Home/images/sz2.png" class="t-icon2"><?php endif; ?>                            <div class="t-left2">                                <p class="pc">                                    <?php if($vo[ostyle] == 1): ?>买跌                                        <?php else: ?>                                        买涨<?php endif; ?>                                </p>                                <p class="ye">入仓价：<?php echo ($vo["buyprice"]); ?></p>                                <p class="ye">平仓价：<?php echo ($vo["sellprice"]); ?></p>                            </div>                            <div class="t-right2">                                <?php if($vo["ploss"] > 0): ?><p class="jg"><?php echo ($vo["ploss"]); ?></p>                                    <?php else: ?>                                    <p class="jg2"><?php echo ($vo["ploss"]); ?></p><?php endif; ?>                                <p class="rq"><?php echo (date('Y-m-d',$vo["buytime"])); ?></p>                            </div>                            <div class="clearfix"></div>                        </a>                        </li><?php endforeach; endif; else: echo "" ;endif; ?>                </ul>                <?php if($payList): ?><ul class="ticket-list2" style="max-height:100%;color:#ccc;">                        <?php if(is_array($payList)): $i = 0; $__LIST__ = $payList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li class="p-li">                                <?php if($vo["type"] == 1): ?><img src="/Public/Home/images/czed.png" class="p-icon2">                                    <?php else: ?>                                    <img src="/Public/Home/images/txed.png" class="p-icon2"><?php endif; ?>                                <div class="t-left2">                                    <p class="pc">                                        <?php if($vo["type"] == 1): ?>充值                                            <?php else: ?>                                            提现<?php endif; ?>                                    </p>                                </div>                                <div class="t-right2">                                    <?php if($vo["type"] == 1): ?><p class="jg">+<?php echo ($vo["amount"]); ?></p>                                        <?php else: ?>                                        <p class="jg2">-<?php echo ($vo["amount"]); ?></p><?php endif; ?>                                    <p class="rq"><?php echo (date('Y-m-d',$vo["payReqTime"])); ?></p>                                </div>                                <div class="clearfix"></div>                            </li><?php endforeach; endif; else: echo "" ;endif; ?>                    </ul><?php endif; ?>                <div class="pagelist"><?php echo ($page); ?></div><?php endif; ?>        </div>    </div>    <style type="text/css">        .pagelist {            text-align: center;            background: #0d216a;            padding: 7px 0;            color: #FFF;        }        .pagelist a {            margin: 0 5px;            border: #6185a2 solid 1px;            display: inline-block;            padding: 2px 6px 1px;            line-height: 16px;            background: #fff;            color: #6185a2;        }        .pagelist span {            margin: 0 5px;            border: #6185a2 solid 1px;            display: inline-block;            padding: 2px 6px 1px;            line-height: 16px;            color: #6185a2;            color: #fff;            background: #6185a2;        }        .p-icon2 {            float: left;            width: 44px;            height: 44px;            margin-right: 8px;            margin-top: 8px;        }        .ticket-list2 li.p-li{          padding: 0 0.5em;        }        .p-li .t-left2{            margin-top: 20px;        }    </style>    <script src="/Public/Home/js/jquery-2.1.1.min.js"></script>    <script src="/Public/Home/js/script.js"></script>    <script src="/Public/Home/js/getJuan.js"></script>    <script type="text/javascript" charset="utf-8" src="/Public/Home/js/sea.js" async></script>    </body>
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