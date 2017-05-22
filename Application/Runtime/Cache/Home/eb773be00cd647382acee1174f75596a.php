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

       

<!doctype html>
<html>
<head>
<meta charset="utf-8">

<link rel="stylesheet" href="/Public/Home/css/global.css">
<link rel="stylesheet" href="/Public/Home/css/mobiscroll/mobiscroll.css">
<link rel="stylesheet" href="/Public/Home/css/mobiscroll/mobiscroll_date.css">
<script>var isDtradingP=true;</script>
<style type="text/css">
.tablabel {
	position:relative;
	background-color:#282A31;
	border-bottom:2px solid #4A4E59;
	z-index:10;
}
.tabselected {
	color:#d5b544;
	border-bottom-color:#d5b544;
}

#scrollWrapper,#scrollWrapper2 {
	position: relative;
	overflow: hidden;

	/* Prevent native touch events on Windows */
	-ms-touch-action: none;

	/* Prevent the callout on tap-hold and text selection */
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;

	/* Prevent text resize on orientation change, useful for web-apps */
	-webkit-text-size-adjust: none;
	-moz-text-size-adjust: none;
	-ms-text-size-adjust: none;
	-o-text-size-adjust: none;
	text-size-adjust: none;
}
#scroller,#scroller2 {
	position: absolute;

	/* Prevent elements to be highlighted on tap */
	-webkit-tap-highlight-color: rgba(0,0,0,0);

	/* Put the scroller into the HW Compositing layer right from the start */
	-webkit-transform: translateZ(0);
	-moz-transform: translateZ(0);
	-ms-transform: translateZ(0);
	-o-transform: translateZ(0);
	transform: translateZ(0);
}
</style>

<script language="javascript" type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/mobiscroll/mobiscroll_date.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/mobiscroll/mobiscroll.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Home/js/iscroll/iscroll-probe.js"></script>
<script language="javascript" type="text/javascript" src="/Public/Js/layer/layer.js"></script>

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
	
	$('.price_' + data['pid']).text(data['p']);
	var preVal = parseFloat($('.preprice_' + data['pid']).first().val());
	if(preVal < parseFloat(data['p'])){
		$('.price_' + data['pid']).css({"background-color":"#FF0000"});
	}else if(preVal > parseFloat(data['p'])){
		$('.price_' + data['pid']).css({"background-color":"#458B00"});
	}else{
		$('.price__' + data['pid']).css({"background-color":"#242424"});
	}
	$('.preprice_' + data['pid']).first().val(data['p']);

	var pid = data['pid'];
	var price = data['p'];
	for (var i=0; i<chicang.length; i++) {
		var order = chicang[i];
		if (order.pid==pid && order.quering==0 && (price<=order.min || price>=order.max)) {
			var tid = mySetInterval(300, queryCloseState, order.oid);
			$("#tid_"+order.oid).val(tid);
			order.quering = 1;
		}
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

//此函数是为了防止在循环中调用setInterval时，
//回调函数参数的延迟解析问题
function mySetInterval(time, func, param) {
	return setInterval(function() {
		func(param);
	}, time);
}

</script>
</head>

<body style="overflow:scroll;-webkit-overflow-scrolling: touch;">
<div class="sheets wrap">

<ul>
    <li name="jiaoyi"><div class="tablabel tabselected">订单明细</div>
      <div class="d-content" style="display:block">
	    <div id="scrollWrapper2">
		  <div id="scroller2" style="position:absolute;width:100%;overflow:hidden;z-index:1">
         	<div class="details_4col" id="chicang_tmpl" style="display:none">
              <ul>
                <li name="ptitle">x</li>
                <li><span name="ostyle" style="padding: 2px 5px;color: #fff;">x</span></li>
                <li>投资金额</li>
                <li name="fee">x</li>
                <li>订购价</li>
                <li name="buyprice">x</li>
                <li>当前价</li>
                <li style="background:#242424;border-radius:5px;text-align:center;" name="price">x</li>
                <input type="hidden" name="preprice"/>
                <input type="hidden" name="tid"/>
				<li>类型</li>
				<li name="dianshu">限价竞购</li>
				<li name="zhiyingdian">止盈/止损点</li>
				<li name="endprofit">x</li>
				<li name="shijian">限时竞购</li>
				<li name="daojishi1">倒计时</li>
				<li name="daojishi2"><span name="t_m">00</span>分<span name="t_s">00</span>秒</li>
              </ul>
              <input type="hidden" name="oid">
           </div>
	     </div>
	  </div>
    </li>
    <li name="jiaoyi2"><div class="tablabel">历史订单</div>
         <div class="d-content">
            <div class="search" style="position: relative;background-color: #282A31;z-index:10">
                  <input class="date" type="text" placeholder="订单日期" readonly  id="searchdate" min max>
                  <input class="searched" type="button" value="搜索" onclick="search(1)">
				  &nbsp;&nbsp;
                  <input class="searched" type="button" value="清空" onclick="cleardate(1)">
            </div>
             
		   <div id="scrollWrapper" style="">
		    <div id="scroller" style="position:absolute;width:100%;overflow:hidden;z-index:1">
             <div class="detail_state">
                <a id="content_tmpl" href="javascript:;" style="display:none;overflow:hidden">
                  <div class="details">
                       <ul>
                          <li name="title"></li>
                          <li>订购价</li>
                          <li>盈亏</li>
                       </ul>
                       <ol>
                           <li><span name="style"></span></li>
                           <li name="buyprice"></li>
                           <li name="ploss" style="font-weight:bold"></li>
                       </ol>
                       <input type="hidden" name="oid">
                  </div>
                </a>
             </div>
			 <div id="loadmore" style="display:none;color:bbb">上滑加载更多</div>
			 <div style="height:50px"></div>
			</div>
		 </div>
       </div>
    </li>

</ul>
<style>
  .page a{
    margin:10px;
  }
</style>
<script>
$(function() {
    $(document).on("click touchend", '.details',
    function() {
        if (!touchMoving) {
            showdetail(this);
        }
    });

    var currYear = (new Date()).getFullYear();
    var opt = {};
    opt.date = {
        preset: 'date'
    };
    opt.datetime = {
        preset: 'datetime'
    };
    opt.time = {
        preset: 'time'
    };
    opt.
default = {
        theme: 'android-ics light',
        //皮肤样式
        display: 'modal',
        //显示方式 
        mode: 'scroller',
        //日期选择模式
        dateFormat: 'yyyy-mm-dd',
        lang: 'zh',
        showNow: true,
        nowText: "今天",
        startYear: currYear - 10,
        //开始年份
        endYear: currYear + 1 //结束年份
    };

    $("#searchdate").mobiscroll($.extend(opt['date'], opt['default']));
    $("#searchdate").attr("placeholder", "选择订单日期");
    $("#searchdate").val("");

    //$("#searchdate").val("<?php echo date('Y-m-d');?>");
    initScroll();

    searchChicang();
    showPingcang = true;
});

var showPingcang = false;
$(".tablabel").click(function() {
    if (!$(this).hasClass("tabselected")) {
        $(".tablabel").removeClass("tabselected");
        $(this).addClass("tabselected");
        $(".d-content").hide();
        $(this).next().show();

        if ($(this).parent().attr("name") == 'jiaoyi') {
            //不能刷新持仓，如果要刷新，要把所有的timer清掉
            //searchChicang();
            showPingcang = true;
        } else {
            showPingcang = false;
            search(1);
        }
    }
});

function showdetail(order) {
    var oid;
    if (typeof(order) == 'number' || typeof(order) == 'string') {
        oid = order;
    } else {
        //$(order).siblings('.d-content a').removeClass('checked');
        //$(order).addClass('checked');
        //oid=$(order).children('div').children('input').val();
        oid = $(order).find('[name=oid]').val();
    }
    //alert(oid);
    $('.tanchuang').show();
    $.ajax({
        type: "POST",
        url: "<?php echo U('User/jyxqcx');?>",
        data: {
            oid: oid
        },
        dataType: 'json',
        success: function(data) {
            //alert(data.ptitle);
            $('#xqtitle').html(data.ptitle);
            if (data.ostyle == 0) {
                var fx = "看涨";
                $('#xqfx').css('background', 'red');
            } else if (data.ostyle == 1) {
                var fx = "看跌";
                $('#xqfx').css('background', 'green');
            }
            if (data.ploss > 0) {
                $('#xqploss').css('color', 'red');
                $('#poundage').html('-' + (data.fee-0) * (data.poundage-0) /100);
            } else if (data.ploss < 0) {
                $('#xqploss').css('color', 'green');
                $('#poundage').html('0');
            }
            if (data.eid == '1') {
                $('#xqmoshi').html("限价竞购");
                $('#profittitle').html("限价竞购");
            } else {
                $('#xqmoshi').html("限时竞购");
                $('#profittitle').html("限时竞购");
            }
            $('#xqfx').html(fx);
            $('#xqorderno').html(data.orderno);
            $('#xqfee').html(data.fee);
            $('#xqendprofit').html(data.endprofit);
            $('#xqbuyprice').html(data.buyprice);
            $('#xqbuytime').html(data.buytime);
            $('#xqsellprice').html(data.sellprice);
            $('#xqselltime').html(data.selltime);
            $('#xqploss').html((data.ploss));

        }
    });
}

function cleardate() {
    $("#searchdate").val("");
}

var chicang = [];
function searchChicang() {
    $.ajax({
        url: "<?php echo U('searchChicang');?>",
        method: "get",
        dataType: "json",
        success: function(data) {
            if (data.success != 1) {
                if (data.errcode == 'login') {
                    document.location.href = "<?php echo U('login');?>";
                    return;
                }
                layer.alert("系统繁忙");
                return;
            }

            chicang = [];
            $("#chicang_tmpl").siblings(".details_4col").remove();
            if (data.orders == null) {
                return;
            }
            var prices = data.prices;
            data.orders.forEach(function(order, index, arr) {
                var obj = $("#chicang_tmpl").clone();
                obj.attr("id", "chicang_" + order.oid);
                obj.find("[name=ptitle]").text(order.ptitle);
                if (order.ostyle == 0) {
                    obj.find("[name=ostyle]").text('看涨');
                    obj.find("[name=ostyle]").css("background", "red");
                } else {
                    obj.find("[name=ostyle]").text('看跌');
                    obj.find("[name=ostyle]").css("background", "green");
                }
                obj.find("[name=fee]").text(order.fee);
                obj.find("[name=buyprice]").text(order.buyprice);
                obj.find("[name=price]").text(prices[order.pid]);
                obj.find("[name=price]").addClass('price_' + order.pid);
                obj.find("[name=preprice]").addClass('preprice_' + order.pid);
                obj.find("[name=preprice]").val(prices[order.pid]);
                if (order.eid == 1) {
                    obj.find("[name=dianshu]").show();
                    obj.find("[name=zhiyingdian]").show();
                    obj.find("[name=endprofit]").show();
                    obj.find("[name=shijian]").hide();
                    obj.find("[name=daojishi1]").hide();
                    obj.find("[name=daojishi2]").hide();
                    obj.find("[name=endprofit]").text(order.endprofit);
                } else {
                    obj.find("[name=dianshu]").hide();
                    obj.find("[name=zhiyingdian]").hide();
                    obj.find("[name=endprofit]").hide();
                    obj.find("[name=shijian]").show();
                    obj.find("[name=daojishi1]").show();
                    obj.find("[name=daojishi2]").show();
                    obj.find("[name=daojishi2]").children('[name=t_m]').attr('id', 't_m_' + order.oid);
                    obj.find("[name=daojishi2]").children('[name=t_m]').text(Math.floor(order.tm / 60));
                    obj.find("[name=daojishi2]").children('[name=t_s]').attr('id', 't_s_' + order.oid);
                    obj.find("[name=daojishi2]").children('[name=t_s]').text(order.tm % 60);
                    obj.find("[name=daojishi2]").children('[name=t_s]').data('tm', order.tm);
                }
                obj.find("[name=oid]").val(order.oid);

                if (order.ploss > 0) {
                    obj.find("[name=ploss]").css("color", "red");
                } else if (order.ploss < 0) {
                    obj.find("[name=ploss]").css("color", "green");
                }

                obj.find("[name=tid]").attr("id", 'tid_' + order.oid);
                if (order.eid == 2) {
                    var tid = mySetInterval(1000, updateDaojishi, order.oid);
                    obj.find("#tid_" + order.oid).val(tid);
                }
                obj.data('oid', order.oid);
                obj.data('eid', order.eid);

                if (order.eid == 1) {
                    chicang.push({
                        pid: order.pid,
                        oid: order.oid,
                        quering: 0,
                        min: order.buyprice - order.endprofit,
                        max: parseFloat(order.buyprice) + parseFloat(order.endprofit)
                    });
                }

                obj.show();
                $("#chicang_tmpl").parent().append(obj);
                //$("#chicang_tmpl").siblings(".content_foot").before(obj);
            });

            myScroll2.refresh();
        },
        error: function(msg) {
            layer.alert("系统繁忙");
        }

    });
}

function updateDaojishi(oid) {
    var tm = $("#t_s_" + oid).data("tm");
    tm--;
    $("#t_m_" + oid).text(Math.floor(tm / 60));
    $("#t_s_" + oid).text(tm % 60);
    $("#t_s_" + oid).data('tm', tm);

    if (tm <= 2) {
        clearInterval($("#tid_" + oid).val());
        var tid = setInterval(function() {
            queryCloseState(oid);
        },
        500);
        $("#tid_" + oid).val(tid);
    }
}

function queryCloseState(oid) {
    $.ajax({
        url: "<?php echo U('queryCloseState');?>",
        data: {
            'oid': oid
        },
        method: "get",
        dataType: "json",
        success: function(data) {
            if (data.success != 1) {
                if (data.errcode == 'login') {
                    document.location.href = "<?php echo U('login');?>";
                    return;
                }
                alert("系统繁忙");
                return;
            }

            var order = data.order;
            if (order.ostaus == 1) {
                if ($(".tanchuang").css("display") == "none" && showPingcang) {
                    clearInterval($("#tid_" + oid).val());
                    showdetail(oid);
                    $("#chicang_" + oid).remove();
                    myScroll.refresh();

                    if (order.eid == 1) {
                        for (var i = 0; i < chicang.length; i++) {
                            if (chicang[i].oid == oid) {
                                chicang.splice(i, 1);
                                break;
                            }
                        }
                    }
                }
            } else {
                $("#t_m_" + oid).text(Math.floor(order.tm / 60));
                $("#t_s_" + oid).text(order.tm % 60);
                $("#t_s_" + oid).data('tm', order.tm);
            }

        },
        error: function(msg) {
            alert("系统繁忙");
        }
    });
}

var pagenum = 1;
var hasmore = false;
function search(flag) {
    if (flag == 1) {
        pagenum = 1;
    }
    $.ajax({
        url: "<?php echo U('searchtrading');?>",
        data: {
            page: pagenum,
            date: $("#searchdate").val()
        },
        method: "get",
        dataType: "json",
        success: function(data) {
            if (data.success != 1) {
                if (data.errcode == 'login') {
                    document.location.href = "<?php echo U('login');?>";
                    return;
                }
                alert("ng");
                return;
            }

            if (pagenum == 1) {
                $("#content_tmpl").siblings().remove();
            }
            if (data.data == null) {
                hasmore = false;
                $("#loadmore").hide();
                return;
            }
            data.data.forEach(function(order, index, arr) {
                var obj = $("#content_tmpl").clone();
                obj.removeAttr("id");
                obj.find("[name=title]").text(order.ptitle);
                obj.find("[name=buyprice]").text(order.buyprice);
                obj.find("[name=sellprice]").text(order.sellprice);
                obj.find("[name=ploss]").text(order.ploss);
                obj.find("[name=oid]").val(order.oid);
                if (order.ostyle == 0) {
                    obj.find("[name=style]").text('看涨');
                    obj.find("[name=style]").css("background", "red");
                } else {
                    obj.find("[name=style]").text('看跌');
                    obj.find("[name=style]").css("background", "green");
                }

                if (order.ploss > 0) {
                    obj.find("[name=ploss]").css("color", "red");
                } else if (order.ploss < 0) {
                    obj.find("[name=ploss]").css("color", "green");
                }

                obj.show();
                $("#content_tmpl").parent().append(obj);
            });

            if (data.more == 1) {
                hasmore = true;
                pagenum++;
                $("#loadmore").html("上拉加载更多");
                $("#loadmore").show();
            } else {
                hasmore = false;
                $("#loadmore").hide();
            }

            setTimeout(function() {
                myScroll.refresh();
                loadingStep = 0;
            },
            10);
        },
        error: function(data) {
            alert("ng");
        }
    });
}

var myScroll2;
var myScroll;
var loadingStep = 0;
var touchMoving = false;
function initScroll() {
    var h = $(window).height() - 80;
    $('#scrollWrapper').css("height", h);
    myScroll = new IScroll('#scrollWrapper', {
        probeType: 2
    });

    myScroll.on("scroll",
    function() {
        touchMoving = true;
        if (loadingStep != 0 || !hasmore) {
            return;
        }

        if (this.y < (this.maxScrollY - 5)) {
            $("#loadmore").html("松手开始加载");
            loadingStep = 1;
        };
    });

    myScroll.on("scrollEnd",
    function() {
        touchMoving = false;
        if (loadingStep != 1) {
            return;
        }
        $("#loadmore").html("正在加载...");
        loadingStep = 2;
        search(0);
    });

    h = $(window).height() - 85;
    $('#scrollWrapper2').css("height", h);
    myScroll2 = new IScroll('#scrollWrapper2', {
        probeType: 1
    });

    document.addEventListener('touchmove',
    function(e) {
        e.preventDefault();
    },
    false);
}
</script>
<!--详情弹窗-->

<div class="tanchuang" style="width:100%;max-width:640px;">
   <div class="top">
       <h6>交易详情</h6>
       <a href="javascript:;" onclick="jQuery('.tanchuang').hide()"><img src="/newpublic/images/gb.png"></a>
   </div>

   <ul>
       <li><span id="xqtitle"  class="left"></span><span id="xqfx" style="padding: 2px 5px;color: #fff;"></span><span class="right" id="xqorderno"></span></li>
       <li><span class="left">投资金额</span><span class="right" id="xqfee"></span></li>
       <li><span class="left">模式</span><span class="right" id="xqmoshi"></span></li>
       <li><span id="profittitle" class="left">周期(分)</span><span class="right" id="xqendprofit"></span></li>
       <li><span class="left">订购价</span><span class="right" id="xqbuyprice"></span></li>
       <li><span class="left">订购时间</span><span class="right" id="xqbuytime"></span></li>
       <li><span class="left">出售价</span><span class="right" id="xqsellprice"></span></li>
       <li><span class="left">出售时间 </span><span class="right" id="xqselltime"></span></li>
       <li><span class="left">交易手续费</span><span class="right" id="poundage" style="font-weight:bold"></span></li>
       <li><span class="left">实际盈亏</span><span class="right" id="xqploss" style="font-weight:bold"></span></li>
   </ul>
</div>
<!--详情弹窗-->

</div>

<script>
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