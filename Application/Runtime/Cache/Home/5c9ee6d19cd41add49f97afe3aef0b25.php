<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="format-detection" content="telephone=no">
<meta name="format-detection" content="email=no">
<title>微云交易</title>
<meta name="keywords" content="微云交易，轻松获得高收益---全国领先的交易平台" />
<meta name="description" content="微云交易，轻松获得高收益---全国领先的交易平台">

<link rel="stylesheet" type="text/css" href="/Public/Home/css/cd.css" />
<link rel="stylesheet" type="text/css" href="/Public/Home/css/icons.css" />
<script language="javascript" type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
</head>
<body>
<div class="main"> 	
       
<link rel="stylesheet" href="/Public/Home/css/base.css">
<link rel="stylesheet" href="/Public/Home/css/login.css">
<script src="/Public/Home/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript">
	var winH = $(window).height();
	$("body").css('height',winH+'px');//这里的div，选择你的那个div

    //禁止滚动条
    $(document.body).css({
        "overflow-x":"hidden",
        "overflow-y":"hidden"
    });
    document.body.addEventListener('touchmove', function (event) {
        event.preventDefault();
    }, false);
</script>
<script src="/Public/Home/js/script.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Home/js/sea.js" async></script>
<body>
<div class="wrap">
	<form id="reviseForm" class="i-form" method="post" action="<?php echo U('User/login');?>">
	    <div class=" main-con">
	        <a class="logo" href="javascript:void(0)">
	            <img src="/Public/Home/images/login-logo.png">
	        </a>
	
	        <div class="input-con">
	            <span style="width: 3.5em"><strong>账号:</strong></span>
	            <input id="c-pwd" class="f-input" type="text" maxlength="20" value="<?php echo I('username'); ?>" placeholder="请输入账号" name="username">
	        </div>
	
	        <div class="input-con">
	            <span style="width: 3.5em"><strong>密码:</strong></span>
	            <input id="n-pwd" class="f-input" type="password" maxlength="20" placeholder="请输入密码" name="password">
	        </div>
			<div style="color:red">
			<span class="tips"><?php echo ($errorMsg); ?></span>
			</div>
	        <!-- <a class="register-btn" href="###">登录</a> -->
			<input type="submit" value="立即登录" class="register-btn" style="font-weight:800;">
	        <a class="login" href="<?php echo U('User/findpassword');?>"><font color="#FFFFFF">忘记密码</font></a>
	        <a class="login" href="<?php echo U('User/reg');?>"style="color:#FFF;">新手注册</a>
	
	        <div class="login-bot-font">
	          <!--  <img src="/Public/Home/images/font.png">-->
	        </div>
	    </div>
    </form>
	<script type="javascript">
	$(".register-btn").submit(function(){
	$.ajax({
                cache: true,
                type: "POST",
                url:<?php echo U('User/login');?>,
                data:$('#reviseForm').serialize(),// 你的formid
                async: false,
                error: function(request) {
                    alert("Connection error");
                },
                success: function(data) {
                    $(".tips").html(data);
                }
            });

	});
	</script>
</div>

</body>

</div>
</body>
</html>