<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<title>微云交易</title>
<meta name="keywords" content="交易" />
<meta name="description" content="微云交易，轻松获得高收益---全国领先的交易平台">

<link href="/newpublic/css/style_head.css" rel="stylesheet"
	type="text/css">
<link rel="stylesheet" type="text/css" href="/Public/Home/css/cd.css" />
<link rel="stylesheet" type="text/css" href="/Public/Home/css/icons.css" />
<script language="javascript" type="text/javascript"
	src="/Public/Home/js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/cd.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/reconnecting-websocket.min.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/common.js"></script>
<link rel="shortcut icon" href="/favicon.png" type="image/x-icon" />
<!-- <link rel="stylesheet" href="/Public/Home/css/mobile-angular-ui-hover.min.css" /> -->
<link rel="stylesheet" href="/Public/Home/css/mobile-angular-ui-base.min.css" />
<!-- <link rel="stylesheet" href="/Public/Home/css/swiper.min.css" /> -->
<link rel="stylesheet" href="/Public/Home/css/account.css">
<link rel="stylesheet" href="/Public/Home/css/app.css" />
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
.btn-navbar-bottom-text {
	font-size: 1.2rem;
	padding: 5px 0;
}
</style>
</head>
<body>
	<!--顶部开始-->
	<div class="top_div">
		<!-- <div class="cdan_div"><img src="/Public/Home/images/cdan.png" width="35" height="32"/></div>-->
		<div class="mid_div">
			<div class="top_nav txss">
				<a href="<?php echo U('User/memberinfo');?>"><div class="tx">
						<?php if($user["portrait"] == ''): ?><img
							src="/Public/Home/images/pic.gif" style="width:90%; height:90%;"> <?php else: ?> <img
							src="<?php echo ($user["portrait"]); ?>" style="width:90%; height:90%;"><?php endif; ?>
					</div></a>
				<div class="content_right">
				<!--
					<p style="margin-top:5px">总权益：<?php echo (sprintf("%.2f",$price["balance"])); ?> &nbsp;&nbsp;&nbsp;&nbsp;
					可用：<?php echo (sprintf("%.2f",$price['balance']-$price['frozen'])); ?></p>
				-->
					<p style="margin-top:15px"><span style="color:#EAEAEB;">账户余额：</span><?php echo (sprintf("%.2f",$price["balance"])); ?> 元</p>
					<ul style="padding-top:10px">
						<li><a href="<?php echo U('User/memberinfo');?>">个人中心</a></li>
						<li><a href="<?php echo U('User/deposit');?>">充值</a></li>
						<li><a href="<?php echo U('User/withdraw?type=1');?>">提现</a></li>
					</ul>
				</div>
				<ol>
					<?php if(is_array($goods)): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gd): $mod = ($i % 2 );++$i;?><a href="<?php echo U('Index/trade',array('pid'=>$gd['pid']));?>"><li><?php echo ($gd["displayname"]); ?></li></a><?php endforeach; endif; else: echo "" ;endif; ?>
				</ol>
			</div>
		</div>
	</div>
	<div class="dbjjDiv"></div>

	<!--顶部结束-->



	<div class="main" style="margin-top: 90px; margin-bottom: 80px;">

		
<link rel="stylesheet" href="/Public/Home/css/global.css">
<link rel="stylesheet" href="/Public/Home/css/index.css">
<link rel="stylesheet" href="/Public/Home/css/pwd.css">
<script language="javascript" type="text/javascript" src="/Public/Home/js/jquery.min.js"/>
<script language="javascript" type="text/javascript" src="/Public/Home/js/script.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Js/layer/layer.js"/>
<script>
$(document).ready(function(){
  $(".contents > .ul_three > li > a").click(function(){
       if(!$(this).hasClass("show")){
     $(".contents > .ul_three > li > a").removeClass(".contents > .ul_three > li > show");
     $(this).addClass("show");
      }
    });
  $(".contents > .ul_two > li > a").click(function(){
       if(!$(this).hasClass("stay")){
     $(".contents > .ul_two > li > a").removeClass(".contents > .ul_two > li > stay");
     $(this).addClass("stay");
      }
    });

  $(".contents > .ul_four > li > a").click(function(){
       if(!$(this).hasClass("shows")){
     $(".contents > .ul_four > li > a").removeClass(".contents > .ul_four > li > shows");
     $(this).addClass("shows");
      }
    })
  });
</script>
<script>
ws = new ReconnectingWebSocket("ws://"+"<?php echo (C("DATA_HOST_INFO.HOST")); ?>" + ":" + "<?php echo (C("DATA_HOST_INFO.PORT")); ?>");
ws.onopen = function() {
    //alert("connect ok");
	var obj = {
		"action":"login",
		"username":"<?php echo (session('husername')); ?>",
		"token" : "<?php echo md5($_SESSION['husername'].date('Ymd', strtotime('-3 hours')).'Jugui'); ?>"
	};
    ws.send(JSON.stringify(obj));
    //alert("send string:tom");
};
ws.onmessage = function(e) {
	var data = $.parseJSON(e.data);
	if(data['c'] == '<?php echo ($curgoods["code"]); ?>'){
		$('#xianjia').html(data['p']);
        //隐藏价
        $('.yincangjia').html(data['p']);
        //昨收
        $('#yzs').html(data['lc']);
        //最高
        $('#yzg').html(data['h']);
        //今开
        $('#yjk').html(data['o']);
        //最低
        $('#yzd').html(data['l']);
        //现在
 		$('#ydangqianj').html(data['p']);
 		$('#rcj').val(data['p']);
 		  //今日差价
 		$('#ycj').html(Math.round(parseFloat(data['d'])*100)/100);
 		var percent = data['df'];
 		if(percent.indexOf("%") != -1){
 			percent = percent.substring(0, percent.indexOf("%"));
 		}
 		$('#zf').html(Math.round(parseFloat(percent)*100)/100+'%');
	}
};

//需定时向服务器发送心跳
setInterval(function() {
	try{
		ws.send("");
	}catch(error){
		console.log(error);
	}
}, 2000);
</script>
<style>
i{
    position: absolute;
    width: 20px;
    height: 20px;
    font-size: 0;
    line-height: 0;
    left: calc(20% + 30px);
    bottom: 0;
    background: url(/Public/Home/images/sys_item_selected.png) no-repeat right bottom;
    z-index: 99999;
    display: none;
}
.money li{
	width: 33.33%;
	border-bottom: 1px solid #dedede;
	color: #000;
}
.money li.selected{
	color: #fff;
}
.profit_content ul li{
	color: #000;
}
</style>
<body style="overflow: scroll; -webkit-overflow-scrolling: touch;">
	<!--  -->
	<div class="wrap contents"
		style="height: auto; background: #1A1A1A;">
		<ul class="ul_title" style="width: 100%; margin: auto;">
   			<li style="width:100%;"><h3 style="margin: 15px 0"><?php echo ($curgoods["displayname"]); ?></h3></li>
		</ul>
		<ul class="ul_one">
			<li>高：<span id="yzg"><?php echo ($curgoods["high"]); ?></span></li>
			<li><span id="ycj"><?php echo (number_format($curgoods["diff"],"2")); ?></span>(<span id="zf"><?php echo (number_format($curgoods["diffrate"],"2")); ?>%</span>)</li>
			<li>低：<span id="yzd"><?php echo ($curgoods["low"]); ?></span></li>
		</ul>
		<div class="index"
			style="background: #191A1F; margin-top: -10px; min-height: 0px;">

			<!-- 账户有建仓订单时显示所有没有平仓的订单 -->

			<div style="background: #282A31;">
				<div class="trade-box" style="background: #282A31;">
					<ul class="buy-choose clearfix box" id="may"
						style="padding:0";>
						<input id="isopen" type="hidden" value="$isopen">
						<li>
							<a href="javascript:" class="up bounceIn"
								onClick="return false;" value="涨"> <img
									src="/Public/Home/images/rise.jpg"> 竞购涨
							</a>
						</li>
						<li>
							<a href="javascript:" class="down bounceIn" value="跌">
								<img src="/Public/Home/images/fall.jpg"> 竞购跌
							</a>
						</li>
						<li class="three" style="text-align: center;padding: 0;">
							<a href="javascript:" style="padding: 0 10px;pointer-events: none;">
								<?php if($isopen == false): ?><span><?php echo ($curgoods["ask"]); ?></span>
								<?php else: ?>
								<span id="xianjia" style="line-height: 30px;" value="1"><?php echo ($curgoods["ask"]); ?></span><?php endif; ?>
							</a>
						</li>

					</ul>
				</div>
			</div>
			<div class="box">
				<div id="dialogBg"></div>
				<div id="dialog" class="" style="display:none">
					<!--建仓确认-->
					<div class="pop-box none" id="buildBox"
						style="display: block; position: fixed;top: -580px;padding-bottom: 1em">
						<nav class="pop-nav" style="border-bottom: 1px solid #ff972f;">
							<a href="javascript:;" class="back" id="claseDialogBtn"></a>
							<div class="dialogTop">
								<a href="javascript:;" class="claseDialogBtn"
									style="position: absolute; right: 20px; top: 10px; mz-index: 111; font-size: 1.2rem; width: 40px; height: 20px; text-align: center;">关闭</a>
							</div>
							<h3>确认购买</h3>
						</nav>

						<form id="jcForm" class="build-form" method="post" action="<?php echo U('Detailed/addorder');?>">
							<div class="b-line clearfix">
								<label class="b-label" style="width: 50%;color: #000;font-size: 1.3rem;font-weight: normal;float: left;line-height: 30px;height: 30px;margin-bottom: 0;">投资品种：<?php echo ($curgoods["displayname"]); ?></label>

								<p class="price clearfix" style="float: right;width:50%;line-height: 30px;height: 30px;">
									<span style="color: #000;font-size: 1.3rem;line-height: 30px;height: 30px;">当前价格：</span> <em class="c-13" id="ydangqianj" style="color: #FF6600;font-size: 1.3rem;line-height: 30px;height: 30px;"><?php echo ($curgoods["ask"]); ?></em>
								</p>
								<p class="price clearfix" style="width: 50%;line-height: 30px;height: 30px;">
									<span style="color: #000;font-weight: 400;font-size: 1.3rem;">购买方向：</span> <em class="fx"><span id="zhd" name="myfx"
										style="font-size: 1.3rem;line-height: 30px;height: 30px;"></span></em>
								</p>
								<p style="float: right;line-height: 30px;height: 30px;font-size: 1.3rem;color: #ff972f;width:50%;"><span style="color: #000;font-size: 1.3rem;">竞购模式：</span>限时竞购</p>
							</div>
							<div class="b-profit">
								<!-- <p class="b-p-t" style="width:20%;color: #000;font-weight: 400;">模式选择</p>
								<ul class="moshi" style="width:70%;border:none;">
							  		<li value="2" id="ms_sj" style="width:100%;" class="selected">限时竞购</li>
								</ul> -->
								<div class="moshi" style="margin-top: -40px;">
									<div class="profit_content" style="display:block">
										<p class="b-p-s" style="width: 20%;color: #000;font-weight: 400;">时间选择</p>
										<ul class="toclearfix" style="width: 70%">
											<li value="<?php echo ($rule1["shijian"]); ?>" poundage-value="<?php echo ($rule1["poundage"]); ?>" id="dianshu6" class="selected" style="width: 33%"><?php echo ($rule1[shijian]*60); ?>秒</li>
											<li value="<?php echo ($rule2["shijian"]); ?>" poundage-value="<?php echo ($rule2["poundage"]); ?>" id="dianshu7" style="width: 33%"><?php echo ($rule2[shijian]*60); ?>秒</li>
											<li value="<?php echo ($rule3["shijian"]); ?>" poundage-value="<?php echo ($rule3["poundage"]); ?>" id="dianshu8" style="width: 33%"><?php echo ($rule3[shijian]*60); ?>秒</li>
<!--											<li value="<?php echo ($rule4["shijian"]); ?>" id="dianshu9" style="width: 20%"><?php echo ($rule4["shijian"]); ?></li>
											<li value="<?php echo ($rule5["shijian"]); ?>" id="dianshu10" style="width: 20%"><?php echo ($rule5["shijian"]); ?></li>-->
										</ul>
										<p class="b-p-s" style="width: 20%;color: #000;font-weight: 400;">收益比例</p>
										<ul class="myclearfix" style="width: 70%">
											<li value="<?php echo ($rule1["profit_shijian"]); ?>" poundage-value="<?php echo ($rule1["poundage"]); ?>"  id="shouyi6" class="selected" style="width: 33%"><?php echo ($rule1["profit_shijian"]); ?>%</li>
											<li value="<?php echo ($rule2["profit_shijian"]); ?>" poundage-value="<?php echo ($rule2["poundage"]); ?>" id="shouyi7" style="width: 33%"><?php echo ($rule2["profit_shijian"]); ?>%</li>
											<li value="<?php echo ($rule3["profit_shijian"]); ?>" poundage-value="<?php echo ($rule3["poundage"]); ?>" id="shouyi8" style="width: 33%"><?php echo ($rule3["profit_shijian"]); ?>%</li>
<!--											<li value="<?php echo ($rule4["profit_shijian"]); ?>" id="shouyi9" style="width: 20%"><?php echo ($rule4["profit_shijian"]); ?>%</li>
											<li value="<?php echo ($rule5["profit_shijian"]); ?>" id="shouyi10" style="width: 20%"><?php echo ($rule5["profit_shijian"]); ?>%</li>-->
										</ul>
									</div>
								</div>
							</div>
							<div class="b-line clearfix  b-profit" style="margin: 140px 0px 5px 0px">
								<p class="b-p-s" style="width: 20%;color: #000;font-weight: 400;">投资金额</p>

								<ul class="money" style="width: 70%">
									<li value="100" id="touzi1" class="selected">100元</li>
									<li value="500" id="touzi2">500元</li>
									<li value="1000" id="touzi3">1000元</li>
									<li value="2000" id="touzi4">2000元</li>
									<li value="" id="touzi" style="width: 66.66%;border:none;"><input placeholder="其它金额" id="pay" name="pay" value="100" style="width: 100%;font-size: 1.2rem;background:transparent;padding: 0 5px;text-align: center;" /></li>
								</ul>
							</div>
							<?php if(!empty($user["exper"])): ?><div style="position: relative;">
									<p class="b-p-s" style="width: 20%;color: #000;font-weight: 400;">优惠券</p>
									<a href="javascript:;" id="quanimg">
										<img src="/Public/Home/images/ticket-big.png" width='40' height='30' class="t-icon">
									</a>
									<i id="checkimg"></i>
								</div><?php endif; ?>
							<p>
								<input style="margin-top: -10px" type="button" class="pwd-btn  conform" id="conform1" value="确 认" >
		                	</p>

							<input type="hidden" name="pid" value="<?php echo ($curgoods["pid"]); ?>"  id="pid">
							<input type="hidden" name="ptitle" value="<?php echo ($curgoods["displayname"]); ?>">
							<input type="hidden" name="ordernumber" value="">
							<input type="hidden" name="product" value="6" id="product">
							<input type="hidden" name="eprice" value="<?php echo ($user["exper"]["eprice"]); ?>" id="eprice">
							<input type="hidden" name="exid" value="<?php echo ($user["exper"]["exid"]); ?>" id="exid">
							<input type="hidden" name="isquan" value="0" id="isquan">
							<input type="hidden" name="dianshu" value="<?php echo ($rule1["shijian"]); ?>" id="dianshu">
							<input type="hidden" name="sybl" value="<?php echo ($rule1["profit_shijian"]); ?>" id="sybl">
							<input type="hidden" name="poundage" value="<?php echo ($rule1["poundage"]); ?>" id="poundage">
							<input type="hidden" name="rcj" value="<?php echo ($curgoods["ask"]); ?>" id="rcj">
							<input type="hidden" name="fx" value="" id="fx">
							<input type="hidden" name="moshi" value="2" id="moshi">
						</form>
					<script>
						$('.up_3').click(function() {
							if($('#isquan').val() == '0'){
								var payMoney = $('#pay').val();
								$('#pay').val(payMoney * 3);
							}
						});

						$('.down_3').click(function() {
							if($('#isquan').val() == '0'){
								var payMoney = $('#pay').val();
								if (payMoney / 3 < 100) {
									$('#pay').val(100);
								} else {
									$('#pay').val(Math.ceil(payMoney / 3));
								}
							}
						});
						$('#ms_sj').click(function(){
							  $('#ms_sj').attr('class','selected');
							  $('#ms_ds').attr('class','');
							  if($('#moshi').val() == '1'){
								  $('#moshi').val('2');
								  var dianshu = $('#dianshu6').attr('value');
								  $('#dianshu').val(dianshu);
								  var sybl = $('#shouyi6').attr('value');
								  $('#sybl').val(sybl);
								  $('.toclearfix li').attr('class', '');
								  $('.myclearfix li').attr('class', '');
								  $('#dianshu6').attr('class', 'selected');
								  $('#shouyi6').attr('class', 'selected');
							  }
						});
						$('#ms_ds').click(function(){
							  $('#ms_ds').attr('class','selected');
							  $('#ms_sj').attr('class','');
							  if($('#moshi').val() == '2'){
								  $('#moshi').val('1');
								  var dianshu = $('#dianshu1').attr('value');
								  $('#dianshu').val(dianshu);
								  var sybl = $('#shouyi1').attr('value');
								  $('#sybl').val(sybl);
								  $('.toclearfix li').attr('class', '');
								  $('.myclearfix li').attr('class', '');
								  $('#dianshu1').attr('class', 'selected');
								  $('#shouyi1').attr('class', 'selected');
							  }
						});

						$('#touzi1').click(function(){
								$('#touzi4').removeClass('selected');
								$('#touzi2').removeClass('selected');
								$('#touzi3').removeClass('selected');
								$(this).attr('class', 'selected');
								var pay=$(this).val();
								$("#pay").val(pay);
						});

						$('#touzi2').click(function(){
								$('#touzi1').removeClass('selected');
								$('#touzi4').removeClass('selected');
								$('#touzi3').removeClass('selected');
								$(this).attr('class', 'selected');
								var pay=$(this).val();
								$("#pay").val(pay);
						});

						$('#touzi3').click(function(){
								$('#touzi1').removeClass('selected');
								$('#touzi2').removeClass('selected');
								$('#touzi4').removeClass('selected');
								$(this).attr('class', 'selected');
								var pay=$(this).val();
								$("#pay").val(pay);
						});

						$('#touzi4').click(function(){
								$('#touzi1').removeClass('selected');
								$('#touzi2').removeClass('selected');
								$('#touzi3').removeClass('selected');
								$(this).attr('class', 'selected');
								var pay=$(this).val();
								$("#pay").val(pay);
						});

						$(".b-profit > .moshi > li").click(function() {
							var n = $(".b-profit > .moshi > li").index(this);
							$(".profit_content").hide();
							$(".profit_content").eq(n).show();
						});
						<?php $__FOR_START_18064__=1;$__FOR_END_18064__=11;for($i=$__FOR_START_18064__;$i < $__FOR_END_18064__;$i+=1){ ?>$('#dianshu<?php echo ($i); ?>,#shouyi<?php echo ($i); ?>').click(function() {
								var dianshu = $('#dianshu<?php echo ($i); ?>').attr('value');
								$('#dianshu').val(dianshu);
								var sybl = $('#shouyi<?php echo ($i); ?>').attr('value');
								$('#sybl').val(sybl);
								$('.toclearfix li').attr('class', '');
								$('.myclearfix li').attr('class', '');
								$('#dianshu<?php echo ($i); ?>').attr('class', 'selected');
								$('#shouyi<?php echo ($i); ?>').attr('class', 'selected');
								$('#poundage').val($(this).attr('poundage-value'))
							});<?php } ?>
					</script>
				</div>
		</div>
	</div>

	<div class="info-box" style="background: #191A1F;">
		<div class="info-d">
			<div class="trend-box">
				<div style="width: 100%; height: 350px;">
					<iframe src="<?php echo U('Detailed/klinedata','pid='.$curgoods['pid']);?>"
						scrolling="no" style="overflow: hidden; border: none; width: 100%; height: 100%;"></iframe>
				</div>
			</div>
			<style>
				#kxt {
					width: 100%;
					height: 800px;
				}
			</style>
		</div>
	</div>
	</div>
	</div>

	<!--弹窗开始-->
	<link rel="stylesheet" href="/Public/Home/css/common.css" />
	<script type="text/javascript">
		var w, h, className;
		function getSrceenWH() {
			w = $(window).width();
			h = $(window).height();
			$('#dialogBg').width(w).height(h);
			$('#dialogBg2').width(w).height(h);
			$('#dialogBg3').width(w).height(h);
		}
		window.onresize = function() {
			getSrceenWH();
		}
		$(window).resize();

		$(function() {

			getSrceenWH();

			//购买显示弹框
			$('#may a').click(
					function() {
						//获取选择是涨还是跌的值
						but = $(this).attr('value');
                        var color = "#1ebb30";
                        if (but=="涨") {
                            color = "red";
                        }
						$('#zhd').html("<span style='font-size:1.3rem;color:"+color+"'>"+'买'+but+"</span>");
						$('#fx').val(but);
						//商品id
						var mypid = $('#pid').val();
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
			var doubleclickcheck = false;
			var minOrderAmount = <?php echo ($webconfig['minorderamount']); ?>;
			$("#conform1").click(function(){
				var mypid = $('#pid').val();
				var pay = $('#pay').val();
				var rcj = $('#rcj').val();
				if(doubleclickcheck == false){
					if(pay < minOrderAmount){
						alert('最小下单金额为' + minOrderAmount);
						$('#pay').val(minOrderAmount);
						return;
					}else{
						doubleclickcheck = true;
					}
				}else{
					return;
				}

				$.ajax({
					type : 'post',
					url : "<?php echo U('Detailed/checkMoney');?>",
					data : {
						"mypid" : mypid,
						"pay" : pay,
						"rcj" : rcj
					},
					async : false,
					success : function(data) {
						if (data == '') {
                            $.ajax({
                                cache: true,
                                type: "POST",
                                url: "<?php echo U('Detailed/addorder');?>",
                                data: $('#jcForm').serialize(),// 你的formid
                                async: false,
                                error: function(request) {
                                    layer.msg('服务器异常', {icon: 2});
                                },
                                success: function(data) {
                                    layer.msg(data.info, {icon: data.icon});
                                    if (data.reload==1) {
                                        setTimeout(function(){
                                        location.href = '/index.php/Home/User/dtrading.html';
                                        }, 15 * 100 );
                                    }
                                    if (data.href) {
                                        setTimeout(function(){location.href = data.href;}, 15 * 100 );
                                    }
                                }
                            });
						}else{
							doubleclickcheck = false;
							alert(data);
						}
					},
					error : function(data){
						doubleclickcheck = false;
					}
				});
			});

			$("#quanimg").click(function(){
				$("#checkimg").css("display","block");
				$("#pay").val($("#eprice").val());
				$("#pay").attr("disabled",true);
				$("#isquan").val("1");
			});
			$("#checkimg").click(function(){
				$("#checkimg").css("display","none");
				$("#pay").val(100);
				$("#pay").attr("disabled",false);
				$("#isquan").val("0");
			});
		});
	</script>
	<!--查看交易end  -->
	<!-- <script type="text/javascript" src="/Public/Home/js/echarts-plain.js"></script> -->
	<script src="/Public/Home/js/idangerous.swiper.min.js"></script>
	<script src="/Public/Home/js/fastclick.js"></script>
	<script>

		$("#shuoming").click(function() {
			$("#sm").show();
			$(".mask1").show();
		});
		$(".back1").click(function() {
			$("#sm").hide();
			$(".mask1").hide();

		});
	</script>
	<script>
		//关闭弹窗
		$('#claseDialogBtn2').click(function() {
			$('#dialogBg2').fadeOut(200, function() {
				$('#dialog2').addClass('bounceOutUp').fadeOut(200);
			});
		});
		//关闭弹窗
		$('#claseDialogBtn3').click(function() {
			$('#dialogBg3').fadeOut(200, function() {
				$('#dialog3').addClass('bounceOutUp').fadeOut(200);
			});
		});
		$('.payout').click(function() {
			$('#dialogBg3').fadeOut(200, function() {
				$('#dialog3').addClass('bounceOutUp').fadeOut(200);
			});
		});
	</script>
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
	<div class="navbar navbar-app navbar-absolute-bottom"
		style="position: fixed; bottom: 0px;">
		<div class="btn-group justified">
			<a href="<?php echo U('Index/Index');?>" class="btn btn-default btn-navbar"><div
					class="bottom_ico" style="background: url(/Public/Home/images/home.png) no-repeat 100% 100%;background-size:90% 90%;"></div>
				<div class="btn-navbar-bottom-text">行情</div>
			</a>
			<a href="<?php echo U('User/dtrading');?> " class="btn btn-default btn-navbar"><div
					class="bottom_ico" style="background: url(/Public/Home/images/trans.png) no-repeat 100% 100%;background-size:90% 90%;"></div>
				<div class="btn-navbar-bottom-text">订单</div>
			</a>
			<a href="<?php echo U('User/showdetailinfo');?>" class="btn btn-default btn-navbar"><div class="bottom_ico"  style=" background:url(/Public/Home/images/a-4.png) no-repeat 100% 100%;background-size:90%;"></div><div class="btn-navbar-bottom-text">在线客服</div></a>
			<a href="<?php echo U('User/memberinfo');?>" class="btn btn-default btn-navbar"><div
					class="bottom_ico" style="background: url(/Public/Home/images/my.png) no-repeat 100% 100%;background-size:90% 90%;"></div>
				<div class="btn-navbar-bottom-text">我的</div>
			</a>

		</div>
	</div>

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
                        $('#xqmoshi2').html("限时竞购");
                        $('#profittitle2').html("限时竞购");
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
//                        console.log("打开"+oid);
                        showdetaildesu(oid);
                    }else {
//                        console.log("关闭"+oid);
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
        if (typeof isDtradingP=="undefined") {
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
        }
	</script>
	<!--详情弹窗(全局)-->
</body>
</html>