<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
<meta name="wap-font-scale" content="no">
<title>微云交易</title>
<script language="javascript" type="text/javascript" src="/Public/Home/js/jquery.min.js"></script>
<link href="/Public/Home/css/base.css" rel="stylesheet" />
<style>
	html{
		width: 100%;
		height: 100%;
	}
	body {
	  	background-color: #041a42;
	  	width: 100%;
		height: 100%;
	}
	.wrap{
		width: 100%;
		height: 100%;	
	}
	.wrap .img-bg {
	  width: 100%;
	  height: 100%
	}
	.wrap .img-bg img {

	}
	.wrap .logo-btn {
	  display: block;
	  margin: 20px auto 10px auto;
	  color: rgba(225, 225, 225, 0.9);
	  padding: 10px 0;
	  width: 72.463%;
	  text-align: center;
	  background-color: #003973;
	  border-radius: 30px;
	  font-size: 18px;
	}
</style>
<script>
$(document).ready(function(){
	setTimeout('login()',2000);
});
function login(){
	window.location.href = "<?php echo U('Index/index');?>";
}
</script>
</head>
<body>
	<div class="wrap">
		<div class="img-bg" style="background: url(/Public/Home/images/homebj.png) no-repeat center;background-size: cover;">
			
		</div>
	</div>
</body>
</html>