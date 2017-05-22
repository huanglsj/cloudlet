<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>微云交易</title>
    
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
    <!-- bootstrap -->
    <link href="/Public/Admin/css/bootstrap/bootstrap.css" rel="stylesheet" />
    <link href="/Public/Admin/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="/Public/Admin/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/icons.css" />

    <!-- libraries -->
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/lib/font-awesome.css" />
    
    <!-- this page specific styles -->
    <link rel="stylesheet" href="/Public/Admin/css/compiled/signin.css" type="text/css" media="screen" />
    <!-- 此段必须要引入 t为小时级别的时间戳 -->
	<link type="text/css" href="//g.alicdn.com/sd/ncpc/nc.css?t=<?php echo strtotime(date('Y-m-d H:i')) ?>" rel="stylesheet"/>
	<script type="text/javascript" src="//g.alicdn.com/sd/ncpc/nc.js?t=<?php echo strtotime(date('Y-m-d H:i')) ?>"></script>
	<!-- 引入结束 -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<!-- 样式示例，请替换成自己的样式 -->
<style>
		html{
			height: 100%;
		}
		body {
			background: #f5f5f5;
			font-size: 14px;
			line-height: 20px;
			margin: 0;
			padding: 0;
		}
		.container {
			background: #fff;
			padding: 20px;
			margin: 20px;
			width: 400px;
		}
		.ln {
			padding: 5px 5px 5px 5px;
			text-align:center;
		}
		.ln .h {
			display: inline-block;
			width: 4em;
		}
		.ln input {
			border: solid 1px #999;
			padding: 5px 8px;
		}
	</style>
<!-- 样式示例结束 -->
<body  class="login-bg">
	<!-- 此段必须要引入 -->
	<div id="_umfp" style="display:inline;width:1px;height:1px;overflow:hidden"></div>
	<!-- 引入结束 -->

    <div class="row-fluid login-wrapper">
        <a href="index.html">
           
        </a>
		
		<form method="post" action="<?php echo U('User/signin');?>" style="width: 400px;margin: 0 auto;">
	        <div class="span4 box">
	            <div class="content-wrap">
	                <h6>微云交易</h6>
	                <input class="span12" type="text" placeholder="账号" id="username" name="username" value="<?php if(I('username')){echo I('username');}else{echo I('uname');} ?>"/>
	                <input class="span12" type="password" placeholder="密码" name="password" value="<?php echo I('password'); ?>"/>
			<input class="span12" type="text" placeholder="短信验证码" name="code" value="<?php echo I('code'); ?>"/>
			<!-- <a href="#" class="forgot">忘记密码？</a> -->
		                <div style="margin:10px">
		                </div>
	                <div class="ln">
						<div id="dom_id"></div>
					</div>
	                <div class="remember">
	                    <input id="remember-me" type="checkbox" name="ckrename" />
	                    <label for="remember-me">记住帐号</label> 
	                </div>
	                
	                <input type='hidden' id='csessionid' name='csessionid'/>
					<input type='hidden' id='sig' name='sig'/>
					<input type='hidden' id='token' name='token'/>
					<input type='hidden' id='scene' name='scene'/>
					<input type='hidden' id='risk' name='risk' value="1"/>
	                <input type="submit" value="登陆" class="btn-glow primary login" style="margin-left: 220px;"/>
	                	                
	            </div>
	        </div>
		</form>
    </div>

	<!-- scripts -->
    <script src="/Public/Admin/js/jquery-latest.js"></script>
    <script src="/Public/Admin/js/bootstrap.min.js"></script>
    <script src="/Public/Admin/js/theme.js"></script>

    <!-- pre load bg imgs -->
    <script type="text/javascript">
        $(function () {
            // bg switcher
            var $btns = $(".bg-switch .bg");
            $btns.click(function (e) {
                e.preventDefault();
                $btns.removeClass("active");
                $(this).addClass("active");
                var bg = $(this).data("img");

                $("html").css("background-image", "url('/Public/Admin/img/bgs/" + bg + "')");
            });
        });
        <?php if(!empty($pwderror)): ?>alert("<?php echo ($pwderror); ?>");<?php endif; ?>
    </script>
	<!-- 此段必须要引入 -->
	<script>
		var nc = new noCaptcha();
		var nc_appkey = '<?php echo (C("ALIYUN_ACCESS_KEY.SECURITY_APP")); ?>';  // 应用标识,不可更改
	    var nc_scene = 'login';  //场景,不可更改
		var nc_token = [nc_appkey, (new Date()).getTime(), Math.random()].join(':');
		var nc_option = {
			renderTo: '#dom_id',//渲染到该DOM ID指定的Div位置
			appkey: nc_appkey,
	        scene: nc_scene,
			token: nc_token,
	        //trans: '{"name1":"code100"}',//测试用，特殊nc_appkey时才生效，正式上线时请务必要删除；code0:通过;code100:点击验证码;code200:图形验证码;code300:恶意请求拦截处理
			callback: function (data){
				console.log(data.csessionid);
				console.log(data.sig);
				console.log(nc_token);
var user=document.getElementById('username').value;
$.post("/index.php/Admin/User/sendsms.html",
  {
    username:user
  },
  function(data,status){
    alert(data);
  });


				document.getElementById('csessionid').value = data.csessionid;
				document.getElementById('sig').value = data.sig;
				document.getElementById('token').value = nc_token;
	            document.getElementById('scene').value = nc_scene;
			}
		};
		nc.init(nc_option);

	</script>
	<!-- 引入结束 -->
</body>
</html>