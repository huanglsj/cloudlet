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

<link rel="stylesheet" href="/Public/Home/css/account.css">

<link rel="stylesheet" href="/Public/Home/css/common.css" />

<style>

.cha{

    width: 60%;

    height: 33px;

    border: 1px solid #dedede;

    background: #fff;

    font-size: 1.1rem;

    line-height: 33px;

	color: #ed0000;

}

.f-sub{

    margin-top: 5%;

    display:block;

    width: 60%;

    border: 0;

    border-radius: 5px;

    background: #0d216a;

    padding: .3em 0;

    cursor: pointer;

    font-size: 1.8rem;

    text-align: center;

    color: #fff;

}

</style>



<script id="G--xyscore-load" type="text/javascript" charset="utf-8" async src="/Public/Home/js/xyscore_main.js"></script>

<script type="text/javascript">

		var w, h, className;

		function getSrceenWH() {

			w = $(window).width();

			h = $(window).height();

			$('#dialogBg').width(w).height(h);

		}

		window.onresize = function() {

			getSrceenWH();

		}

		$(window).resize();



		$(function() {



			getSrceenWH();



			//购买显示弹框

			$('.diancha').click(

					function() {

						//获取选择是涨还是跌的值

						className = $(this).attr('class');

						$('#dialogBg').fadeIn(200);

						$('#dialog').removeAttr('class').addClass(

								'animated ' + className + '').fadeIn(200);



					});

			//关闭弹窗

			$('.claseDialogBtn,#claseDialogBtn').click(function() {

				$('#dialogBg').fadeOut(200, function() {

					$('#dialog').addClass('bounceOutUp').fadeOut(200);

				});

			});

			$("#conform").click(function(){

				var diancha = $('#diancha').val();

				$.ajax({

					type : 'post',

					url : "<?php echo U('User/dxsetting');?>",

					data : {

						"diancha" : diancha,

					},

					async : false,

					success : function(data) {

						$('#dialogBg').fadeOut(200, function() {

							$('#dialog').addClass('bounceOutUp').fadeOut(200);

						});

					}

				});

			});



		});

	</script>



<body style="overflow:scroll;">

<div class="wrap">

  <!-- <div class="index" style="min-height: 1114px;"> -->

  <div class="index">

  <div class="txss" style="margin-bottom: 10px;">

      <div class="tx"><?php if($suer["portrait"] == ''): ?><img src="/Public/Home/images/pic.gif"><?php else: ?><img src="<?php echo ($suer["portrait"]); ?>"><?php endif; ?></div>

        <div class="gezh">

          <div class="grzhx"><p class="a-d">账号：<b><?php echo ($suer["username"]); ?></b></p></div>

          <div class="grzhx"><p class="a-d">余额：<?php echo ($result["balance"]); ?>&nbsp;元</p></div>

          

          <div class="cztz"><?php if(empty($isinweixin)): ?><a href="<?php echo U('User/logout');?>" class="acc-btn red">退出</a><?php endif; ?><a href="<?php echo U('User/deposit');?>" class="acc-btn red">充值</a> <a href="<?php echo U('User/withdraw');?>" class="acc-btn blue">提现</a></div>

          

        </div>

        <div class="clearfix"></div>

    </div>

    <div class="info-box clearfix"> <i class="a-3"></i>

     <div class="info-detail clearfix"> <a href="<?php echo U('User/dtrading');?>" class="acc-l">持仓</a> </div>

    </div>

    <div class="info-box clearfix"> <i class="a-3"></i>

      <div class="info-detail clearfix"> <a href="<?php echo U('Detailed/drevenue');?>" class="acc-l">收支明细</a> </div>

    </div>

    <div class="info-box clearfix"> <i class="a-1"></i>

      <div class="info-detail clearfix"> <a href="<?php echo U('User/experiencelist');?>" class="acc-l">我的体验券</a> </div>

    </div>

    <div class="info-box clearfix"> <i class="a-6"></i>

    <?php if(($suer["agenttype"] == 0) OR ($suer["agenttype"] == 1)): ?><div class="info-detail clearfix"> <a href="<?php echo U('Broker/applybroker');?>" class="acc-l">申请经纪人</a> </div>

    <?php else: ?>

         <div class="info-detail clearfix" style="display:block"> <a href="<?php echo U('Broker/brokerinfo');?>" class="acc-l">经纪人中心</a></div><?php endif; ?> 

    </div>
<!--
    <div class="info-box clearfix"> <i class="a-5"></i>

      <div class="info-detail clearfix"> <a href="<?php echo U('User/purchase');?>" class="acc-l">风险申购</a> </div>

    </div>

    <div class="info-box clearfix"> <i class="a-1"></i>

      <div class="info-detail clearfix"> <a href="<?php echo U('User/pcmanagement');?>" class="acc-l">申购管理</a> </div>

    </div>
-->

    <div class="info-box clearfix"> <i class="a-11"></i>

      <div class="info-detail clearfix"> <a href="<?php echo U('User/img',array('uid'=>$_SESSION['uid']));?>" class="acc-l">分享好友</a> </div>

    </div>

<!--    <div class="info-box clearfix"> <i class="a-12"></i>

      <div class="info-detail clearfix"> <a href="javascript:" class="acc-l diancha">默认允许偏离点差</a> </div>

    </div>-->

<!--	<div class="info-box clearfix"> <i class="a-12"></i>

  	  <div class="info-detail clearfix"> <a href="<?php echo U('User/ranking');?>" class="acc-l">排行榜</a> </div>

	</div>-->

     <div class="info-box clearfix marginBox"> <i class="a-13"></i>

	    <?php if(($suer["authenticationsstatus"] == 0)): ?><div class="info-detail clearfix"> <a href="<?php echo U('User/authentication');?>" class="acc-l">实名认证</a> </div>

	    <?php else: ?>

	         <div class="info-detail clearfix"> <a href="<?php echo U('User/authenticationinfo');?>" class="acc-l">实名认证</a> </div><?php endif; ?>

    </div>

    <!--

    <div class="info-box clearfix"> <i class="a-14"></i>

      <div class="info-detail clearfix"> <a href="<?php echo U('User/bankcard');?>" class="acc-l">签约银行</a> </div>

    </div>

    -->

    <div class="info-box clearfix"> <i class="a-0"></i>

      <div class="info-detail clearfix" style="border-bottom:1px solid #4A4E59;"> <a href="<?php echo U('User/edituser');?>" class="acc-l">修改登陆密码</a> </div>

    </div>

	  <div class="info-box clearfix"> <i class="a-6"></i>

		  <div class="info-detail clearfix" style="border-bottom:1px solid #4A4E59;"><a href="<?php echo U('User/alipayinfo');?>" class="acc-l">我的支付宝账号</a> </div>

	  </div>


      <div class="info-box clearfix"> <i class="a-2"></i>

        <div class="info-detail clearfix" style="border-bottom:1px solid #4A4E59;"> <a href="<?php echo U('User/edituserb');?>" class="acc-l">修改交易密码</a> </div>

      </div>
<!--

      <div class="info-box clearfix"> <i class="a-2"></i>

        <div class="info-detail clearfix" style="border-bottom:1px solid #4A4E59;"> <a href="<?php echo U('User/showdetailinfo');?>" class="acc-l">账户信息</a> </div>
-->

      </div>

  </div>

  <div class="box">

		<div id="dialogBg"></div>

		<div id="dialog" class="">

			<!--建仓确认

			<div class="pop-box none" 

				style="display: block; position: fixed; height: 250px;">

				<nav class="pop-nav ">

					<a href="javascript:;" class="back" id="claseDialogBtn"></a>

					<div class="dialogTop">

						<a href="javascript:;" class="claseDialogBtn"

							style="position: absolute; right: 20px;  mz-index: 111; font-size: 1rem; width: 40px; height: 20px; text-align: center;">关闭</a>

					</div>

					<h3>默认允许偏离点差</h3>

				</nav>
-->
			

				<div class="b-line clearfix" align="center"  style="margin:20px 0px 5px 0px;">

						<p >

							<input class="cha" type="text" name="diancha" id="diancha" value="<?php echo ($suer["diancha"]); ?>"/>

						</p>

					<p>

						<input type="button" class="f-sub" id="conform"  value="确 认" >

					</p>

				</div>

			</div>

		</div>

	</div>

</div>

<script src="/Public/Home/js/jquery-2.1.1.min.js"></script>

<script src="/Public/Home/js/script.js"></script>

<script type="text/javascript" charset="utf-8" src="/Public/Home/js/sea.js" async></script>



</body>


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