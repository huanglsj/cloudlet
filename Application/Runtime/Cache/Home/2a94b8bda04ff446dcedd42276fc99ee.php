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

       
<meta charset="utf-8">
<link rel="stylesheet" href="/Public/Home/css/global.css">
<link rel="stylesheet" href="/Public/Home/css/account.css">
<link href="/Public/Home/css/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="/Public/Home/css/common.css" />
<link rel="stylesheet" href="/Public/Home/css/pwd.css">
<link href="/Public/Home/css/riskInfo.css" rel="stylesheet" />
<script src="/Public/Home/js/jquery-2.1.1.min.js"></script>
<script src="/Public/Home/js/reconnecting-websocket.min.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Home/js/jquerysession.js"></script>
<script>
$(document).ready(function(){
	<?php if($_GET['checkpw']== true): ?>//popupDiv('pop-div');
	$('#confirmpassword').click(function(){
		var password = $("#password").val();
		$.ajax({  
            type: "post",  
            url: "<?php echo U('User/confirmpassword');?>",
            data:"password="+password,  
            async:false,  
            success: function(data) { 
            	if(data){
            		hideDiv('pop-div');
            	}else{
            		alert('密码错误，请重新输入！');
            		$("#password").val('');
            	}
            },  
            error: function(data) {  
            	alert('error' + data);
            }  
        });
	});<?php endif; ?>
	
});
ws = new ReconnectingWebSocket("ws://"+"<?php echo (C("DATA_HOST_INFO.HOST")); ?>" + ":" + "<?php echo (C("DATA_HOST_INFO.PORT")); ?>");
ws.onopen = function() {
    //console.log("connect ok");

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
	var askselector = "[name='" + data['c'] + "']";
	$('.ask_' + data['c']).html(data['p']);
	var preVal = parseFloat($('#preval_' + data['c']).val());
	if(preVal < parseFloat(data['p'])){
		$('.back_' + data['c']).css({"background-color":"#FF0000"});
	}else if(preVal > parseFloat(data['p'])){
		$('.back_' + data['c']).css({"background-color":"#458B00"});
	}else{
		$('.back_' + data['c']).css({"background-color":"#242424"});
	}
	$('#preval_' + data['c']).val(data['p']);
	var dateStr = new Date(data['t']*1000).Format("hh:mm:ss");
	$('.time_' + data['c']).html(dateStr);
};
function popupDiv(div_id) { 
    // 获取传入的DIV 
    var $div_obj = $("#" + div_id); 
    // 计算机屏幕高度 
    var windowWidth = $(window).width(); 
    // 计算机屏幕长度 
    var windowHeight = $(window).height(); 
    // 取得传入DIV的高度 
    var popupHeight = $div_obj.height(); 
    // 取得传入DIV的长度 
    var popupWidth = $div_obj.width(); 
     
    // // 添加并显示遮罩层 
    $("<div id='bg'></div>").width(windowWidth * 0.99) 
        .height(windowHeight * 0.99).click(function() { 
          //hideDiv(div_id); 
        }).appendTo("body").fadeIn(200); 
     
    // 显示弹出的DIV 
    $div_obj.css({ 
      "position" : "absloute" 
    }).animate({ 
      left : windowWidth / 2 - popupWidth / 2, 
      top : windowHeight / 2 - popupHeight / 2, 
      opacity : "show" 
    }, "slow"); 
     
  } 
  /*隐藏弹出DIV*/ 
  function hideDiv(div_id) {
    $("#bg").remove(); 
    $("#" + div_id).animate({ 
      left : 0, 
      top : 0, 
      opacity : "hide" 
    }, "slow"); 
  } 
//需定时向服务器发送心跳
setInterval(function() {
	try{
		ws.send("");
	}catch(error){
		console.log(error);
	}
}, 2000);

//对Date的扩展，将 Date 转化为指定格式的String
//月(M)、日(d)、小时(h)、分(m)、秒(s)、季度(q) 可以用 1-2 个占位符， 
//年(y)可以用 1-4 个占位符，毫秒(S)只能用 1 个占位符(是 1-3 位的数字) 
//例子： 
//(new Date()).Format("yyyy-MM-dd hh:mm:ss.S") ==> 2006-07-02 08:09:04.423 
//(new Date()).Format("yyyy-M-d h:m:s.S")      ==> 2006-7-2 8:9:4.18 
Date.prototype.Format = function (fmt) {  
 var o = {
     "M+": this.getMonth() + 1, //月份 
     "d+": this.getDate(), //日 
     "h+": this.getHours(), //小时 
     "m+": this.getMinutes(), //分 
     "s+": this.getSeconds(), //秒 
     "q+": Math.floor((this.getMonth() + 3) / 3), //季度 
     "S": this.getMilliseconds() //毫秒 
 };
 if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
 for (var k in o)
 if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
 return fmt;
}
</script>
<style>
.main-con#dialog .content {
  margin-bottom: 4rem;
  background:none;
  height: auto;
}
 .pop-box { 
    /*弹出窗口后，弹出的DIV采用此CSS，保证置于最上层
     z-index控制Z轴的坐标，数值越大，离用户越近
    */ 
    z-index: 9999999; /*这个数值要足够大，才能够显示在最上层*/ 
    margin-bottom: 3px; 
    display: none; 
    position: absolute; 
    background: gray; 
    border: solid1px #6e8bde;
  } 
    
  #bg { 
    width: 100%; 
    height: 100%; 
    position: absolute; 
    top: 0; 
    left: 0; 
    /*弹出窗口后，添加遮罩层，采用此CSS，将该层置于弹出DIV和页面层之间
     z-index控制Z轴的坐标，数值越大，离用户越近 rgba(72, 74, 68, 0.46)
    */ 
    z-index: 1001;  
    background-color:#8f9f8f;
    -moz-opacity: 0.7; 
    opacity: .70; 
    filter: alpha(opacity = 70); 
  }

	.aircraftbox{
		position: fixed;
		top: 120%;
		left: 0;
		padding: 1rem;
		width: 100%;
		height: 100%;
		z-index: 9999;
		background: #282A31;
		box-shadow: 0 -28px 50px rgba(0,0,0,0.16);
		transition: top 1s;
		-moz-transition: top 1s;	/* Firefox 4 */
		-webkit-transition: top 1s;	/* Safari 和 Chrome */
		-o-transition: top 1s;
		overflow-y: scroll;
	}
	.aircraftbox.tgo{
		top:0 !important;
	}
	.aircraftbox-head{
		text-align: center;
		font-size: 1.3rem;
		font-weight: bold;
	}
	.aircraftbox-body{
		padding-top:1rem;
		font-size: 1rem;
		line-height: 2.2rem;
	}
	.aircraftbox-cls{
		float: right;
		padding-right: 1rem;
		height: 16px;
		cursor: pointer;
	}
	.aircraftbox-cls>img{
		width: 16px;
	}
	.clearfix:after {
	    content: " ";
	    display: block;
	    height: 0;
	    clear: both;
	}
	.content .content_right p{
		margin-top: 10px;
	}
	.content .content_right ul li{
		margin: 10px 10px 10px 0;	
	}	
	.content .content_right ul li a{
		border-radius: 5px;
	}
.guide_arrow_ani{ 
    -webkit-animation: twinkling 2s infinite ease-in-out;
    position:relative;
} 
@keyframes twinkling{ 
	0%{ 
		opacity: 0.3; 
		top: 0;
	} 
	100%{ 
		opacity: 1; 
		top: -20px;
	} 
}
.guide{
	width: 100%;
	height: 100%;
	background: rgba(0,0,0,0.9);
	position: fixed;
    top: 0;
    left: 0;
    z-index: 99999;
    display: none;
}
.guide>div{
	width: 100%;
	height: 100%;
	display: none;
}
.guide button{
	border: 1px dashed #ff972f;
    color: #fff;
    background: transparent;
    padding: 10px;
    font-size: 1.2rem;
    display: block;
    width: 80%;
}
.guide .g1 img{
	width: 80%;
	display: block;
	margin: 20% auto 0 auto;
}
.guide .g2{
	background: url(/Public/Home/images/guide_2.jpg) no-repeat center rgba(200,200,200,0.1);
	background-size: contain;
}
.guide .g3{
	background: url(/Public/Home/images/guide_3.jpg) no-repeat center rgba(200,200,200,0.1);
	background-size: contain;
}
.guide .g4{
	background: url(/Public/Home/images/guide_4.jpg) no-repeat center rgba(200,200,200,0.1);
	background-size: contain;
}
.guide .g5{
	background: url(/Public/Home/images/guide_over.png) no-repeat center 45% rgba(200,200,200,0.1);
	background-size: contain;
}

.guide>div button{
	position: absolute;
    left: 10%;
    background: rgba(0,0,0,0.8);	
    border-radius: 5px;	
}
.guide .g1 button{
    bottom: 15%;	
}
.guide .g2 button{
	bottom: 15%;	
}
.guide .g3 button{
    bottom: 15%;
}
.guide .g4 button{
    bottom: 15%;
}
.guide .g5 button{
    bottom: 15%;
}

.guide>div div{
	position: absolute;
	text-align: center;
	width: 60%;
}
.guide .g2 div{
    top: 42%;
}
.guide .g3 div{
    top: 50%;
}
.guide .g4 div{
	bottom: 42%;
	width: 100%;
}
.guide .g4 div img{
	transform: rotate(180deg);
}
.guide .g4 div p{
	width: 80%;
	margin: 0 auto 1em auto
}
.guide div p{
	color: #ff972f;
    font-size: 1.2rem;
    border: 1px dashed #ff972f;
    width: 100%;
    padding: 10px;
    background: rgba(0,0,0,0.8);	
}
.re_guide{
	position: fixed;
	bottom:80px;
	right: 10px;
	z-index: 99999;
	width: 50px;
	height: 50px;
}
</style>
<script type="text/javascript">
	$(function(){
		var $dom = $(document),
			$guide = $('#guide'),
			$gDiv = $guide.find('>div');
		var flag = is_guest
		var isShowGuide = localStorage.getItem('isShowGuide')
		if(flag == 1 && isShowGuide != 'show'){
			$guide.show();	
			$gDiv.eq(0).show();		
		}
		$dom.on('click','#guide>div button',function(e){
			var txt = $(e.target).text();
			$gDiv.hide();
			if(txt === '完成'){
				localStorage.setItem('isShowGuide','show')
				$guide.hide();			
			}else{
				$(this).parents().next().show();			
			}	
		});	
		$dom.on('click',"#reGuide",function(){
			$guide.show();	
			$gDiv.eq(0).show();		
		});	
	})
</script>
</head>

<body style="overflow:scroll;background:#191A1F;-webkit-overflow-scrolling: touch;" >
<img class="re_guide" id="reGuide" src="/Public/Home/images/reGuide.png">
<div class="guide" id="guide">
	<div class="g1">
		<img src="/Public/Home/images/guide_1.png">
		<button>下一步</button>
	</div>
	<div class="g2">
		<div>
			<img class="guide_arrow_ani" src="/Public/Home/images/guide_arrow.png">
			<p>根据您的判断<br/>选择投资产品<br/>例 ： 比特币BTC</p>
		</div>
		<button>下一步</button>
	</div>
	<div class="g3">
		<div>
			<img class="guide_arrow_ani" src="/Public/Home/images/guide_arrow.png">
			<p>请点击,竞购涨/竞购跌</p>
		</div>
		<button>下一步</button>
	</div>
	<div class="g4">
		<div>
			<p>请选择体验券,并确定下单</p>
			<img class="guide_arrow_ani" src="/Public/Home/images/guide_arrow.png">
		</div>
		<button>下一步</button>
	</div>
	<div class="g5">
		<img style="margin-top: 15%" class="guide_arrow_ani" src="/Public/Home/images/guide_over1.png">
		<button>完成</button>
	</div>
</div>
<div class="content wrap txss clearfix">
    <a href="<?php echo U('User/memberinfo');?>">
    	<div>
    		<?php if($user["portrait"] == ''): ?><img src="/Public/Home/images/pic.gif">
    		<?php else: ?>
    			<img src="<?php echo ($user["portrait"]); ?>"><?php endif; ?>
		</div>
	</a>
    <div class="content_right">
	<!--
        <p style="margin-top:5px">总权益：<?php echo (sprintf("%.2f",$price["balance"])); ?>&nbsp;&nbsp;&nbsp;&nbsp;可用：<?php echo (sprintf("%.2f",$price['balance']-$price['frozen'])); ?></p>
	-->
        <p><span>账户余额：</span><?php echo (sprintf("%.2f",$price["balance"])); ?> 元</p>
        <ul>
        <!--<li><a href="<?php echo U('User/memberinfo');?>">个人中心</a></li>-->
           <li><a href="<?php echo U('User/deposit');?>">充值</a></li>
           <li><a href="<?php echo U('User/withdraw');?>">提现</a></li>
        </ul>
    </div>
</div>
<div class="them wrap">

   <ul class="one">
        <li class="one_01"><strong>商品种类</strong></li>
        <li class="one_02 buyUp"><a href="<?php echo U('Index/trade',array('pid'=>$source[0]['pid']));?>"><strong>买涨</strong></a></li>
        <li class="one_03 buyDown"><a href="<?php echo U('Index/trade',array('pid'=>$source[0]['pid']));?>"><strong>买跌</strong></a></li>
    </ul>
    <hr/>
    <?php if(is_array($source)): $i = 0; $__LIST__ = $source;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sc): $mod = ($i % 2 );++$i;?><ul class="two">
	   		<input type=hidden id='preval_<?php echo ($sc["code"]); ?>' value=<?php echo ($sc["ask"]); ?>/>
	        <li class="two_01"><a name="<?php echo ($sc["code"]); ?>" href="<?php echo U('Index/trade',array('pid'=>$sc['pid']));?>"><?php echo ($sc["displayname"]); ?><br/><span class="time_<?php echo ($sc["code"]); ?>"><?php echo ($sc["eidtime"]); ?></span></a></li>
	        <li class="two_02 back_<?php echo ($sc["code"]); ?>"><a class="ask_<?php echo ($sc["code"]); ?>" href="<?php echo U('Index/trade',array('pid'=>$sc['pid']));?>"><?php echo ($sc["ask"]); ?></a></li>
	        <li class="two_03 back_<?php echo ($sc["code"]); ?>"><a class="ask_<?php echo ($sc["code"]); ?>" href="<?php echo U('Index/trade',array('pid'=>$sc['pid']));?>"><?php echo ($sc["ask"]); ?></a></li>
	    </ul>
	    <hr/><?php endforeach; endif; else: echo "" ;endif; ?>
    
</div>
<div class="wrap"><img src="/Public/Home/images/banner.png.jpg" width="100%"/></div>

<!-- <div class="them wrap">

   <ul class="one">
        <li style="width:100%" align="center"><strong>官方公告</strong></li>
    </ul>
	<hr/>
    <ul class="one">
    <?php if(is_array($contents)): $i = 0; $__LIST__ = $contents;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$content): $mod = ($i % 2 );++$i;?><li style="padding: 10px 7%;" class="aircraftbox-dol">
			<?php echo ($content["title"]); ?>
			<div class="aircraftbox" data-aircraftbox="0">				<div class="aircraftbox-head"><?php echo ($content["title"]); ?></div>
				<div class="aircraftbox-body" data-aval="<?php echo ($content["content"]); ?>"></div>
			</div>
		</li>
	<hr/><?php endforeach; endif; else: echo "" ;endif; ?>
    </ul>
	<script type="text/javascript">
		var $aircraftboxbody=$('.aircraftbox-body');
        $aircraftboxbody.html($aircraftboxbody.attr('data-aval'));
        $('.aircraftbox-dol').click(function () {
            var $aircraftbox = $('.aircraftbox');
            var $aircraftboxdata =$aircraftbox.attr('data-aircraftbox');
            if ($aircraftboxdata==0) {
            	$aircraftbox.addClass('tgo');
                $aircraftbox.attr('data-aircraftbox',1);
            }else{
                $aircraftbox.removeClass('tgo');
                $aircraftbox.attr('data-aircraftbox',0);
            }
        });
	</script>
	
</div> -->
	<!-- 协议弹窗start -->
	<div class="xieyicontent">
	<div id="express_contract" class="order_layer_iframe1 clearfix">
		<div class="container clearfix">
			<i class="close" onclick="closeAgreeTerm()">close</i>
			<div class="content_qytk clearfix">
			    <h3 class="title">风险告知书</h3>
				<p>尊敬的投资者：</p>
				<p>欢迎您使用微云交易平台进行交易活动，为了更好的防范风险，在您进行交易之前请您务必认真阅读以下内容：</p>
				<p>一、重要提示</p>
				<p>1、微云交易与现有的国内其他交易市场相比，在交易模式、交易规则等方面存在着一定的差别，请您在参与投资之前务必详尽的了解本市场投资的基本知识和相关风险以及微云交易有关的业务规则等，依法合规地从事该项交易业务；</p>
				<p>2、微云交易为了确保市场“公开、公平、公正”和健康稳定地发展，将采取更加严格的措施，强化市场的监管。请您务必密切地关注微云交易的公告、风险提醒等信息，及时了解市场风险状况，做到理性投资，切忌盲目跟风；</p>
				<p>3、微云交易的交易业务不适合利用养老基金、债务资金(如银行贷款) 等进行投资的投资者。只适合于满足以下全部条件的投资者：</p>
				<p>(A) 年满18至60周岁并具有完全民事行为能力的中国公民或依法在中华人民共和国境内注册成立的企业法人或其他经济组织；</p>
				<p>(B) 能够充分理解有关于此交易的一切风险，并且具有风险承受能力；</p>
				<p>(C) 因投资失误而造成账户资金部分或全部损失、仍不会改变其正常的生活方式或影响其正常生产经营状况的；</p>
				<p>(D) 不具有聚力星创开户管制相关制度规定的禁止开户情形。</p>
				<p>4、您在开通交易之前，请配合会员开展的适当性管理工作，完整、如实地提供开户所需要的信息，不得采取弄虚作假等手段规避有关的要求，否则，聚力星创有权拒绝为您开通交易服务；</p>
				<p>5、本风险揭示书风险揭示事项仅为列举性质，未能详尽的列明有关该市场的所有风险因素，您在参与该市场投资之前，还应认真的对其他可能存在的风险因素有所了解和掌握。</p>
				<p>二、相关的风险揭示</p>
				<p>(一) 政策风险</p>
				<p>国家法律、法规、政策以及规章的变化，紧急措施的出台，相关监管部门监管措施的实施，微云交易交易规则的修改等原因，均可能会对您的投资产生影响，您必须承担由此导致的损失。</p>
				<p>(二) 价格波动风险</p>
				<p>本市场的交易品种的价格受多种因素的影响(并且影响机制非常复杂，您在实际操作中存在出现投资失误的可能性，如果不能有效控制风险，则可能遭受较大的损失，您必须独自承担由此导致的一切损失。</p>
				<p>(三) 技术风险</p>
				<p>1、此业务通过电子通讯技术和互联网技术来实现。由通讯或网络故障导致的某些服务中断或延时可能会对您的投资产生影响。</p>
				<p>2、您的电脑、操作系统等软硬件环境有可能由于自身原因或被病毒、网络黑客攻击等原因，从而导致您的交易系统连接受到影响，使您的投资决策无法正确或及时执行。</p>
				<p>对于上述不确定因素带来的风险，有可能会对您的投资产生影响，您应该充分了解并承担由此造成的全部损失。</p>
				<p>(四) 交易风险</p>
				<p>1、您需要了解微云交易的交易业务具有低保证金的投资特点，可能导致快速的盈利或亏损。若建仓的方向与行情的波动相反，会造成较大的亏损，根据亏损的程度，您必须有条件满足随时追加保证金的要求，否则其持仓将会被强行平仓，您必须承担由此造成的全部损失。</p>
				<p>2、本交易市场的价格可能会与其他途径的报价存在微弱的差距，并不能保证上述交易价格与其他市场保持完全的一致性。</p>
				<p>3、您在交易系统内，通过网上终端(含手机移动终端) 所提交的市价单一经成交，即不可撤销，您必须接受这种方式可能带来的风险。</p>
				<p>4、微云交易提供的交易系统中的指价建仓和指价平仓功能可能由于各种原因导致交易无法在达到触发条件时成交，如果您无法理解或承受上述风险，我们建议您不要使用该功能进行交易；如果您坚持使用该功能，我们将认为您已经完全理解并愿意承担使用该功能的全部风险，并愿意承担使用该功能所带来的一切后果。</p>
				<p>5、微云交易城禁止所有会员(及其分支机构) 、会员居间人及前述主体的工作人员以任何方式与您分享收益或共担风险，或利用您的资金从事任何代客理财业务。</p>
				<p>6、您的成交单据必须建立在自主决定的基础之上。微云交易、会员(及其分支机构) 、会员居间人及前述主体的工作人员提供的任何关于市场的分析和信息，仅供投资者参考，不构成任何要约或承诺。由此而造成的交易风险由您自行承担。</p>
				<p>(五) 不可抗力风险</p>
				<p>任何因微云交易不能够控制的原因，包括但不限于地震、水灾、火灾、暴动、罢工、战争、政府管制、国际或国内的禁止或限制以及停电、技术故障、电子故障等其他无法预测或防范的不可抗力事件，都有可能会对您的交易产生影响，您应该充分了解并承担由此造成的全部损失。</p>
			</div>
		</div>
		<a class="btn read-btn" href="###">同意</a>
	</div>
	</div>
	<!-- 协议弹窗end -->
	<!-- <div class="box">
			<div id="dialogBg"></div>
			<div id="dialog" class="main-con">
				<div class="content">
					<h4>风险告知书</h4>
					<a href="javascript:;" class="claseDialogBtn"
				style="position: absolute; right: 20px; top: 10px; mz-index: 111; font-size: 1.5rem; width: 40px; height: 20px; text-align: center;">关闭</a>
				<p>尊敬的投资者：</p>
				<p>欢迎您使用微云交易平台进行交易活动，为了更好的防范风险，在您进行交易之前请您务必认真阅读以下内容：</p>
				<p>一、重要提示</p>
				<p>1、微云交易与现有的国内其他交易市场相比，在交易模式、交易规则等方面存在着一定的差别，请您在参与投资之前务必详尽的了解本市场投资的基本知识和相关风险以及微云交易有关的业务规则等，依法合规地从事该项交易业务；</p>
				<p>2、微云交易为了确保市场“公开、公平、公正”和健康稳定地发展，将采取更加严格的措施，强化市场的监管。请您务必密切地关注微云交易的公告、风险提醒等信息，及时了解市场风险状况，做到理性投资，切忌盲目跟风；</p>
				<p>3、微云交易的交易业务不适合利用养老基金、债务资金(如银行贷款) 等进行投资的投资者。只适合于满足以下全部条件的投资者：</p>
				<p>(A) 年满18至60周岁并具有完全民事行为能力的中国公民或依法在中华人民共和国境内注册成立的企业法人或其他经济组织；</p>
				<p>(B) 能够充分理解有关于此交易的一切风险，并且具有风险承受能力；</p>
				<p>(C) 因投资失误而造成账户资金部分或全部损失、仍不会改变其正常的生活方式或影响其正常生产经营状况的；</p>
				<p>(D) 不具有微云交易开户管制相关制度规定的禁止开户情形。</p>
				<p>4、您在开通交易之前，请配合会员开展的适当性管理工作，完整、如实地提供开户所需要的信息，不得采取弄虚作假等手段规避有关的要求，否则，微云交易有权拒绝为您开通交易服务；</p>
				<p>5、本风险揭示书风险揭示事项仅为列举性质，未能详尽的列明有关该市场的所有风险因素，您在参与该市场投资之前，还应认真的对其他可能存在的风险因素有所了解和掌握。</p>
				<p>二、相关的风险揭示</p>
				<p>(一) 政策风险</p>
				<p>国家法律、法规、政策以及规章的变化，紧急措施的出台，相关监管部门监管措施的实施，微云交易交易规则的修改等原因，均可能会对您的投资产生影响，您必须承担由此导致的损失。</p>
				<p>(二) 价格波动风险</p>
				<p>本市场的交易品种的价格受多种因素的影响(并且影响机制非常复杂，您在实际操作中存在出现投资失误的可能性，如果不能有效控制风险，则可能遭受较大的损失，您必须独自承担由此导致的一切损失。</p>
				<p>(三) 技术风险</p>
				<p>1、此业务通过电子通讯技术和互联网技术来实现。由通讯或网络故障导致的某些服务中断或延时可能会对您的投资产生影响。/p>
				<p>2、您的电脑、操作系统等软硬件环境有可能由于自身原因或被病毒、网络黑客攻击等原因，从而导致您的交易系统连接受到影响，使您的投资决策无法正确或及时执行。</p>
				<p>对于上述不确定因素带来的风险，有可能会对您的投资产生影响，您应该充分了解并承担由此造成的全部损失。</p>
				<p>(四) 交易风险</p>
				<p>1、您需要了解微云交易的交易业务具有低保证金投资特点，可能导致快速的盈利或亏损。若建仓的方向与行情的波动相反，会造成较大的亏损，根据亏损的程度，您必须有条件满足随时追加保证金的要求，否则其持仓将会被强行平仓，您必须承担由此造成的全部损失。</p>
				<p>2、本交易市场的价格可能会与其他途径的报价存在微弱的差距，并不能保证上述交易价格与其他市场保持完全的一致性。</p>
				<p>3、您在交易系统内，通过网上终端(含手机移动终端) 所提交的市价单一经成交，即不可撤销，您必须接受这种方式可能带来的风险。</p>
				<p>4、微云交易提供的交易系统中的指价建仓和指价平仓功能可能由于各种原因导致交易无法在达到触发条件时成交，如果您无法理解或承受上述风险，我们建议您不要使用该功能进行交易；如果您坚持使用该功能，我们将认为您已经完全理解并愿意承担使用该功能的全部风险，并愿意承担使用该功能所带来的一切后果。</p>
				<p>5、微云交易禁止所有会员(及其分支机构) 、会员居间人及前述主体的工作人员以任何方式与您分享收益或共担风险，或利用您的资金从事任何代客理财业务。</p>
				<p>6、您的成交单据必须建立在自主决定的基础之上。微云交易、会员(及其分支机构) 、会员居间人及前述主体的工作人员提供的任何关于市场的分析和信息，仅供投资者参考，不构成任何要约或承诺。由此而造成的交易风险由您自行承担。</p>
				<p>(五) 不可抗力风险</p>
				<p>任何因微云交易不能够控制的原因，包括但不限于地震、水灾、火灾、暴动、罢工、战争、政府管制、国际或国内的禁止或限制以及停电、技术故障、电子故障等其他无法预测或防范的不可抗力事件，都有可能会对您的交易产生影响，您应该充分了解并承担由此造成的全部损失。</p>
				</div>
			</div>
			<div class="read-con">
				<a class="read-btn" href="javascript:void(0)">我已阅读以上协议并同意遵守</a>
			</div>
		</div> -->
		<div id='pop-div' style="width: 300px;height:220px; " class="pop-box form-group">
			 <h4 >请输入登录密码：</h4>
			 <div class="pop-box-body">
			 <ul class="form-box">
				 <li class="f-line clearfix">
					 <input class="f-input" type="password" id="password" />
				 </li>
			 </ul>
			 <input class="f-sub" type="button" value="确认" id="confirmpassword"/>
		</div>
</body>
<script type="text/javascript">
    var w,h,className;
    function getSrceenWH(){
      w = $(window).width();
      h = $(window).height();
      $('#dialogBg').width(w).height(h);
      $('#dialog').height(h);
      $('.box').height(h);
    }
    window.onresize = function(){  
      getSrceenWH();
    } 
    $(window).resize();  
        $(document).ready(function(){
        	getSrceenWH();
          //关闭弹窗
            $('.claseDialogBtn,#claseDialogBtn').click(function(){
              $('#dialogBg').fadeOut(200,function(){
                $('#dialog').addClass('bounceOutUp').fadeOut(200);
              });
            });
           
            $(".read-btn").click(function(){
                //$(".box").hide();
                //$("#dialogBg").hide();
                //$("#dialog").hide();
                $(".xieyicontent").hide();
                
                $.ajax({  
                    type: "post",  
                    url: "<?php echo U('Index/updateriskread');?>",
                    async:false,  
                    success: function(data) { 
                    },  
                    error: function(data) {  
                    	alert('error' + data);
                    }  
                });
            });
			/* $(".claseDialogBtn").click(function(){
                $(".box").hide();
                $("#dialogBg").hide();
                $("#dialog").hide();
            }); */

			$('.buyUp').click(function() {
				document.location.href=$('.two_01').first().children('a').attr("href");
			});
			$('.buyDown').click(function() {
				document.location.href=$('.two_01').first().children('a').attr("href");
			});
        });
        <?php if($user["readrisk"] == 0): ?>var dialogopen = $.session.get('dialogopen');
	       if(dialogopen == '' || dialogopen == undefined){
	        	//$(".box").show();
	            //$("#dialogBg").show();
	            //$("#dialog").show();
	            $(".xieyicontent").css("display","inline-flex");
	            $.session.set('dialogopen','dialogopen');
	      }<?php endif; ?>
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