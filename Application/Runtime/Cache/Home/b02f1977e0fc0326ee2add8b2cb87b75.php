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
       
<link rel="stylesheet" href="/Public/Home/css/common.css" />
<link rel="stylesheet" href="/Public/Home/css/base.css">
<link rel="stylesheet" href="/Public/Home/css/login.css">
<link href="/Public/Home/css/riskInfo.css" rel="stylesheet" />
<script src="/Public/Home/js/jquery-2.1.1.min.js"></script>
<script src="/Public/Home/js/script.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Home/js/sea.js" async></script>
<!-- <script src="/Public/Home/js/register.js"></script> -->
<body>
<div class="wrap">
		<form id="reviseForm" class="i-form" method="post" action="<?php echo U('User/setnewpassword');?>" >
        <div class=" main-con">
            <a class="logo" href="javascript:void(0)">
                <img src="/Public/Home/images/login-logo.png">
            </a>
			<input type="hidden"  name="uid" value="<?php echo ($info["uid"]); ?>">
			<div class="input-con">
                <span>用户名</span><?php echo ($info["username"]); ?>
            </div>
            <div class="input-con">
                <span>手机号</span><?php echo ($info["utel"]); ?>
                <input type="hidden"  name="utel" value="<?php echo ($info["utel"]); ?>">
            </div>
            <div class="code input-con">
                <span>验证码</span>
                <input type=text placeholder="短信验证码" maxlength="6" name="code">
                <a id="get-code" href="javascript:void(0)">获取验证码</a>
            </div>
            <div class="input-con">
                <span>重置密码</span>
                <input id="n-pwd" class="f-input text" type="password" maxlength="15" placeholder="请输入六位密码" name="upwd">
            </div>
            <div class="input-con">
                <span>确认密码</span>
                <input id="r-pwd" class="f-input text" type="password" maxlength="15" placeholder="请再次输入六位密码" name="repassword">
            </div>
			<input type="submit" value="确定" class="register-btn" id='send2'>

            <div class="bot-font">
                <img src="/Public/Home/images/font.png">
            </div>
        </div>
        </form>
    </div>
</body>
<script type="text/javascript">
var errorMsg = "";
        $(function(){
            //如果是必填的，则加红星标识.
            //文本框失去焦点后
            $('form :input').blur(function(){
                var $parent = $(this).parent();
                $parent.find(".formtips").remove();
                
                //密码验证
                if( $(this).is('input[name="upwd"]') ){
                    if( this.value=="" ){
                        errorMsg = '请输入密码。';
                        $parent.append('<span class="formtips onError" style="color:red;width:5px">*</span>');
                    }else{
                    	errorMsg='';
                        $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');
                    }
                }
                //确认密码验证
                if( $(this).is('input[name="repassword"]') ){
                	if($('#n-pwd').val() != ""){
                        if( this.value!=$('#n-pwd').val()){
                            errorMsg = '两次密码不一样。';
                            $parent.append('<span class="formtips onError" style="color:red;width:5px">*</span>');
                        }else{
                            errorMsg = '';
                            $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');
                        }
                	}
                }
            }).keyup(function(){
                $(this).triggerHandler("blur");
            }).focus(function(){
                $(this).triggerHandler("blur");
            });//end blur
            
          //提交，最终验证。
            $('#send2').click(function(){
                $("form :input.text").trigger('blur');
                var numError = $('form .onError').length;
                if(numError){
                	if(errorMsg != ""){
                		alert(errorMsg);
                	}
                    return false;
                }
                var code = $('input[name="code"]').val();
                if(code == ""){
                    alert("请输入手机验证码！");
                    return false;
                }
            });
        })

       var countdown = 60;
       $(document).ready(function() {
           $("#get-code").click(function() {
        	   if (countdown != 60){ return false};
               var tel = $('input[name="utel"]').val();
               settime(this);
               $.ajax({
                   type : 'POST',
                   url : "<?php echo U('User/smsverify11');?>",
                   data : {'tel' : tel},
                   success:function(data){
                       alert(data);
                   },
                   error:function(data){
                       alert(data);
                   }
               });
           });
       });
       function settime(obj) {
           if (countdown == 0) {
               $(obj).text("获取验证码");
               countdown = 60;
               return;
           } else {
               $(obj).text("重新发送(" + countdown + ")");
               countdown--;
           }
           setTimeout(function() {
               settime(obj)
           }, 1000)
       }
       
    </script>

</div>
</body>
</html>