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
<style type="text/css">
    html{
        height: 100%;
        width: 100%;
    }
</style>
<body style="height: 100%;width: 100%;">

<div class="wrap">

		<form id="reviseForm" class="i-form" method="post" action="<?php echo U('User/findpassword');?>" >

        <div class=" main-con">

            <a class="logo" href="javascript:void(0)">

                <img src="/Public/Home/images/login-logo.png">

            </a>

            <div class="input-con">

                <span><strong>注册账号:</strong></span>

                <input class="f-input text" type="text"  maxlength="16" placeholder="请输入账号" name="username" value="<?php echo ($username); ?>">

            </div>

            <div class="input-con">

                <span><strong>注册手机号:</strong></span>

                <input type="tel" placeholder="请输入手机号" class="f-input text" maxlength="11" name="utel" value="<?php echo ($utel); ?>">

            </div>

			<input type="button" value="确定" class="register-btn" id='send2' style="font-weight:800;">

            <a class="login" href="<?php echo U('User/login');?>" style="color:#FFF;">登录</a>

            <div class="bot-font">

                <img src="/Public/Home/images/font.png">

            </div>

        </div>

        </form>

    </div>

</body>

<script type="text/javascript">

        $(function(){

            //如果是必填的，则加红星标识.

            //文本框失去焦点后

            $('form :input').blur(function(){

                var $parent = $(this).parent();

                $parent.find(".formtips").remove();

                //验证用户名

                if( $(this).is('input[name="username"]') ){

                    if( this.value=="" ){

                        $parent.append('<span class="formtips onError" style="color:red;width:5px">*</span>');

                    }else{

                        $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');

                    }

                }

                //手机号码验证

                if( $(this).is('input[name="utel"]') ){

                    if( this.value=="" || ( this.value!="" && !/^1[3|4|5|8][0-9]\d{4,8}$/.test(this.value) ) ){

                        var errorMsg = '请输入正确的手机号码.';

                        $parent.append('<span class="formtips onError" style="color:red;width:5px">*</span> ');

                    }else{

                        $parent.append('<span class="formtips onSuccess" style="display:none"></span> ');

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

                    return false;

                } else {

                	$("#reviseForm").submit();

                }

            });

        })

    </script>


</div>
</body>
</html>