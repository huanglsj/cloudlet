<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<title>微云交易管理中心</title>
    
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
    <!-- bootstrap -->
    <link href="/Public/Admin/css/bootstrap/bootstrap-responsive.css" rel="stylesheet" />
    <link href="/Public/Admin/css/bootstrap/bootstrap-overrides.css" type="text/css" rel="stylesheet" />
	<link href="/Public/Admin/css/bootstrap/bootstrap.min.css" rel="stylesheet" />
	<link href="/Public/Admin/css/bootstrap/bootstrap.css" rel="stylesheet" />

    <!-- libraries -->
    <link href="/Public/Admin/css/lib/jquery-ui-1.10.2.custom.css" rel="stylesheet" type="text/css" />
    <link href="/Public/Admin/css/lib/font-awesome.css" type="text/css" rel="stylesheet" />

    <!-- global styles -->
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/layout.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/elements.css" />
    <link rel="stylesheet" type="text/css" href="/Public/Admin/css/icons.css" />
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body>

    <!-- navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-inner">
            <button type="button" class="btn btn-navbar visible-phone" id="menu-toggler">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            
          <ul class="nav">
<h1>
    <span style="font-size: 20px; color: rgb(84, 141, 212);">·微云交易·</span>
</h1>
          </ul>

            <ul class="nav pull-right">                
                <!-- <li class="hidden-phone">
                    <input class="search" type="text" />
                </li>
                <li class="notification-dropdown hidden-phone">
                    <a href="#" class="trigger">
                        <i class="icon-warning-sign"></i>
                        <span class="count">8</span>
                    </a>
                    <div class="pop-dialog">
                        <div class="pointer right">
                            <div class="arrow"></div>
                            <div class="arrow_border"></div>
                        </div>
                        <div class="body">
                            <a href="#" class="close-icon"><i class="icon-remove-sign"></i></a>
                            <div class="notifications">
                                <h3>你有6封邮件需要查收</h3>
                                <a href="#" class="item">
                                    <i class="icon-signin"></i> 新注册用户 刘易斯
                                    <span class="time"><i class="icon-time"></i> 13 分钟前.</span>
                                </a>
                                <a href="#" class="item">
                                    <i class="icon-signin"></i> 新注册用户 张二娃
                                    <span class="time"><i class="icon-time"></i> 18 分钟前.</span>
                                </a>
                                <a href="#" class="item">
                                    <i class="icon-envelope-alt"></i> 来自用户 好啊好 的邮件，请查收
                                    <span class="time"><i class="icon-time"></i> 28 分钟前.</span>
                                </a>
                                <a href="#" class="item">
                                    <i class="icon-signin"></i> 新注册用户 无法了解
                                    <span class="time"><i class="icon-time"></i> 49 分钟前.</span>
                                </a>
                                <a href="#" class="item">
                                    <i class="icon-download-alt"></i> 我的天空 的新订单
                                    <span class="time"><i class="icon-time"></i> 1 天前.</span>
                                </a>
                                <div class="footer">
                                    <a href="#" class="logout">查看全部邮件</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <li class="notification-dropdown hidden-phone">
                    <a href="#" class="trigger">
                        <i class="icon-envelope-alt"></i>
                    </a>
                    <div class="pop-dialog">
                        <div class="pointer right">
                            <div class="arrow"></div>
                            <div class="arrow_border"></div>
                        </div>
                        <div class="body">
                            <a href="#" class="close-icon"><i class="icon-remove-sign"></i></a>
                            <div class="messages">
                                <a href="#" class="item">
                                    <img src="/Public/Admin/img/contact-img.png" class="display" />
                                    <div class="name">张二娃</div>
                                    <div class="msg">
                                        	我的钱太少了，能给我分一点前用用吗？
                                    </div>
                                    <span class="time"><i class="icon-time"></i> 13 分钟前.</span>
                                </a>
                                <a href="#" class="item">
                                    <img src="/Public/Admin/img/contact-img2.png" class="display" />
                                    <div class="name">安吉丽娜朱莉</div>
                                    <div class="msg">
                                        请问管理员，为什么周末我无法购买产品。
                                    </div>
                                    <span class="time"><i class="icon-time"></i> 26 分钟前.</span>
                                </a>
                                <a href="#" class="item last">
                                    <img src="/Public/Admin/img/contact-img.png" class="display" />
                                    <div class="name">路易斯</div>
                                    <div class="msg">
                                        提现太慢了，麻烦快一点。
                                    </div>
                                    <span class="time"><i class="icon-time"></i> 48 分钟.</span>
                                </a>
                                <div class="footer">
                                    <a href="#" class="logout">查看全部信息</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li> -->
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle hidden-phone" data-toggle="dropdown">
                        <?php echo ($_SESSION['username']); ?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                       <!--  <li><a href="<?php echo U('User/personalinfo');?>">个人信息</a></li>
                        <li><a href="<?php echo U('User/personalinfo');?>">账户设置</a></li> -->
                        <li><a href="<?php echo U('Order/olist');?>">查看订单</a></li>
                        <li><a href="<?php echo U('User/ulist');?>">查看客户</a></li>
                        <li><a href="<?php echo U('Goods/glist');?>">查看产品</a></li>
                    </ul>
                </li>
             <!--    <li class="settings hidden-phone">
                    <a href="<?php echo U('User/personalinfo');?>" role="button">
                        <i class="icon-cog"></i>
                    </a>
                </li> -->
                <li class="settings hidden-phone">
                    <a href="<?php echo U('Admin/User/signinout');?>" role="button">
                        <i class="icon-share-alt"></i>
                    </a>
                </li>
            </ul>            
        </div>
    </div>
    <!-- end navbar -->

    <!-- sidebar -->
    <div id="sidebar-nav">
        <ul id="dashboard-menu">
            <?php if($_SESSION['pkey']['HOME_PAGE_MA'] == '1'): ?><li name="系统首页">
                <div class="pointer">
                    <div class="arrow"></div>
                    <div class="arrow_border"></div>
                </div>
                <?php if($_SESSION['pkey']['HOME_PAGE'] == '1'): ?><a href="<?php echo U('Admin/Index/index');?>">
                    <i class="icon-home"></i>
                    <span>系统首页</span>
                </a><?php endif; ?>
            </li><?php endif; ?>
            <!--            
            <li>
                <a class="dropdown-toggle" href="#">
                    <i class="icon-edit"></i>
                    <span>内容管理</span>
					<i class="icon-chevron-down"></i>
                </a>
				<ul class="submenu">
                    <li><a href="<?php echo U('Admin/News/typelist');?>">栏目管理</a></li>
                    <li><a href="<?php echo U('Admin/News/newslist');?>">文章管理</a></li>-->
                    <!--<li><a href="user-profile.html">我发布的文档</a></li>-->
					<!--<li><a href="user-profile.html">内容回收站</a></li>-->					
                <!--</ul>
            </li>-->
            <?php if($_SESSION['pkey']['GOODS_MA'] == '1'): ?><li name="产品管理">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-calendar-empty"></i>
                    <span>产品管理</span>
					<i class="icon-chevron-down"></i>
                </a>
				<ul class="submenu">
                    <!--<li><a href="<?php echo U('Admin/Goods/gadd');?>">添加产品</a></li>-->
                    <?php if($_SESSION['pkey']['GOODS_LIST'] == '1'): ?><li><a href="<?php echo U('Admin/Goods/glist');?>">产品列表</a></li><?php endif; ?>
                        <?php if($_SESSION['pkey']['CALENDAR'] == '1'): ?><li><a href="<?php echo U('Super/calendar');?>">开市日历</a></li><?php endif; ?>
                    <!--<li><a href="<?php echo U('Admin/Goods/gtypeadd');?>">添加商品分类</a></li>-->
                   <!--  <li><a href="<?php echo U('Admin/Goods/gtype');?>">分类列表</a></li> -->
                    <!--<li><a href="user-profile.html">回收站</a></li>-->				
                </ul>
            </li><?php endif; ?>
            <?php if($_SESSION['pkey']['ORDER_MA'] == '1'): ?><li name="订单管理">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-th-large"></i>
                    <span>订单管理</span>
					<i class="icon-chevron-down"></i>
                </a>
				<ul class="submenu">
				    <?php if($_SESSION['pkey']['ORDER_LIST'] == '1'): ?><li><a href="<?php echo U('Admin/Order/olist',array('ostaus'=>'0', 'init'=>'1'));?>">持仓订单</a></li><?php endif; ?>
                    <!-- <li><a href="<?php echo U('Admin/Order/tlist');?>">交易流水</a></li> -->
                    <?php if($_SESSION['pkey']['ORDER_ZX'] == '1'): ?><li><a href="<?php echo U('Admin/Order/olist',array('ostaus'=>'1', 'init'=>'1'));?>">平仓订单</a></li><?php endif; ?>
                    <!--<li><a href="new-user.html">移除的订单</a></li>-->
                </ul>
            </li><?php endif; ?>
            <?php if($_SESSION['pkey']['USER_MA'] == '1'): ?><li name="客户管理">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-group"></i>
                    <span>客户管理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <?php if($_SESSION['pkey']['USER_LIST'] == '1'): ?><li><a href="<?php echo U('User/ulist');?>">客户列表</a></li><?php endif; ?>
                    <!--<li><a href="<?php echo U('Admin/User/ugroup');?>">用户组设置</a></li>-->
                    <?php if($_SESSION['pkey']['USER_DEPOSIT'] == '1'): ?><li><a href="<?php echo U('User/recharge?optype=1&opdate='.date('Y-m-d'));?>">充值列表</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['USER_WITHDRAW'] == '1'): ?><li><a href="<?php echo U('User/recharge?optype=2&opdate='.date('Y-m-d'));?>">提现列表</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['USER_BALANCELIST'] == '1'): ?><li><a href="<?php echo U('User/balancelist');?>">余额流水</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['USER_AUTHEN_LIST'] == '1'): ?><li><a href="<?php echo U('User/checkauthentications');?>">实名申请列表</a></li><?php endif; ?>
                </ul>
            </li><?php endif; ?>
            <?php if($_SESSION['pkey']['MENBER_MA'] == '1'): ?><li name="会员管理">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-star"></i>
                    <span>会员单位</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <?php if($_SESSION['pkey']['MENBER_ADD'] == '1'): ?><li><a href="<?php echo U('Menber/madd');?>">添加会员</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['MENBER_LIST'] == '1'): ?><li><a href="<?php echo U('Menber/mlist');?>">会员列表</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['MENBER_EQUITYWATER'] == '1'): ?><li><a href="<?php echo U('Menber/equitylist');?>">权益流水</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['MENBER_DEPOSIT'] == '1'): ?><li><a href="<?php echo U('Menber/recharge?optype=1&opdate='.date('Y-m-d'));?>">充值列表</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['MENBER_WITHDRAW'] == '1'): ?><li><a href="<?php echo U('Menber/recharge?optype=2&opdate='.date('Y-m-d'));?>">提现列表</a></li><?php endif; ?>
                </ul>
            </li><?php endif; ?>
            <?php if($_SESSION['pkey']['COUPONS_MA'] == '1'): ?><li name="优惠卷管理">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-code-fork"></i>
                    <span>优惠卷管理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                <?php if($_SESSION['pkey']['COUPONS_ADD'] == '1'): ?><li><a href="<?php echo U('Coupons/cpadd');?>">添加优惠卷</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['COUPONS_LIST'] == '1'): ?><li><a href="<?php echo U('Coupons/cplist',array('style'=>'list'));?>">优惠券列表</a></li><?php endif; ?>
					<!-- <li><a href="<?php echo U('Coupons/cplist',array('style'=>'oldlist'));?>">历史优惠卷</a></li>-->
					<?php if($_SESSION['pkey']['COUPONS_SEND'] == '1'): ?><li><a href="<?php echo U('Coupons/cpsend');?>">发放优惠券</a></li><?php endif; ?>
                </ul>
            </li><?php endif; ?>
            <li name="充值活动管理">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-code-fork"></i>
                    <span>充值活动管理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <?php if($_SESSION['pkey']['COUPONS_ADD'] == '1'): ?><li><a href="<?php echo U('Activity/add');?>">添加充值活动</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['COUPONS_LIST'] == '1'): ?><li><a href="<?php echo U('Activity/lists');?>">充值活动列表</a></li><?php endif; ?>
                </ul>
            </li>
            <?php if($_SESSION['pkey']['TONGJI_MA'] == '1'): ?><li name="统计数据">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-edit"></i>
                    <span>统计数据</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <?php if($_SESSION['pkey']['TONGJI_OUT'] == '1'): ?><li><a href="<?php echo U('Tongji/tongji');?>">导出报表</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['LOGIN_LOG'] == '1'): ?><li><a href="<?php echo U('Tongji/loginlist');?>">登录日志</a></li><?php endif; ?>
                </ul>
            </li><?php endif; ?>
            <!--
            <li>
                <a class="dropdown-toggle" href="#">
                    <i class="icon-group"></i>
                    <span>申购管理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                <li><a href="<?php echo U('Menber/pcorder');?>">未申请订单</a></li>
                    <li><a href="<?php echo U('Menber/nosolve');?>">未处理订单</a></li>
                    <li><a href="<?php echo U('Menber/solve');?>">已处理订单</a></li>
                    
                </ul>
            </li>-->
            <?php if($_SESSION['pkey']['SUPER_MA'] == '1'): ?><li name="系统管理员">
                <a class="dropdown-toggle" href="personal-info.html">
                    <i class="icon-code-fork"></i>
                    <span>系统管理员</span>
					<i class="icon-chevron-down"></i>
					<ul class="submenu">
					    <?php if($_SESSION['pkey']['SUPER_ADD'] == '1'): ?><li><a href="<?php echo U('Super/sadd');?>">添加管理员</a></li><?php endif; ?>
                        <?php if($_SESSION['pkey']['SUPER_LIST'] == '1'): ?><li><a href="<?php echo U('Super/slist');?>">管理员列表</a></li><?php endif; ?>
						<!--<li><a href="grids.html">管理员组</a></li>-->
					</ul>
                </a>
            </li><?php endif; ?>
            <?php if($_SESSION['pkey']['PERMISSION_MA'] == '1'): ?><li name="权限管理">
                <a class="dropdown-toggle" href="personal-info.html">
                    <i class="icon-unlock-alt"></i>
                    <span>权限管理</span>
                    <i class="icon-chevron-down"></i>
                    <ul class="submenu">
                    <?php if($_SESSION['pkey']['ROLE_ADD'] == '1'): ?><li><a href="<?php echo U('Super/radd');?>">添加角色</a></li><?php endif; ?>
                        <?php if($_SESSION['pkey']['ROLE_LIST'] == '1'): ?><li><a href="<?php echo U('Super/rlist');?>">角色列表</a></li><?php endif; ?>
                        <?php if($_SESSION['pkey']['MR_LIST'] == '1'): ?><li><a href="<?php echo U('Super/mrlist',array('type'=>99));?>">会员角色</a></li><?php endif; ?>
                        <?php if($_SESSION['pkey']['SYS_LIST'] == '1'): ?><li><a href="<?php echo U('Super/mrlist',array('type'=>1));?>">管理员角色</a></li><?php endif; ?>
                    </ul>
                </a>
            </li><?php endif; ?>
            <?php if($_SESSION['pkey']['SYS_CONFIG_MA'] == '1'): ?><li name="系统设置">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-cog"></i>
                    <span>系统设置</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <?php if($_SESSION['pkey']['SYS_E_CONFIG'] == '1'): ?><li><a href="<?php echo U('Super/esystem');?>">基本设置</a></li><?php endif; ?>
                	<?php if($_SESSION['pkey']['SYS_D_CONFIG'] == '1'): ?><li><a href="<?php echo U('Super/content');?>">公告设置</a></li><?php endif; ?>
                	<?php if($_SESSION['pkey']['SYS_D_CONFIG'] == '1'): ?><li><a href="<?php echo U('Super/withdrawConfig');?>">提现设置</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['SYS_D_CONFIG'] == '1'): ?><li><a href="<?php echo U('Super/riskConfig');?>">风控设置</a></li><?php endif; ?>
                    <!--<li><a href="grids.html">清理缓存</a></li>-->
                    <!-- <li><a href="<?php echo U('Super/backupdb');?>">数据备份</a></li> -->
				<!-- 	<li><a href="signin.html">数据还原</a></li> -->
                    <li><a href="<?php echo U('User/signinout');?>">退出系统</a></li>
                </ul>
            </li><?php endif; ?>
            <?php if($_SESSION['pkey']['WEIXIN_MA'] == '1'): ?><li name="微信管理">
                <a class="dropdown-toggle" href="#">
                    <i class="icon-envelope"></i>
                    <span>微信管理</span>
                    <i class="icon-chevron-down"></i>
                </a>
                <ul class="submenu">
                    <?php if($_SESSION['pkey']['WEIXIN_INFO'] == '1'): ?><li><a href="<?php echo U('Menber/wxinfo');?>">微信基本信息</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['WEIXIN_USER'] == '1'): ?><li><a href="<?php echo U('Menber/wxlist');?>">微信用户列表</a></li><?php endif; ?>
                    <?php if($_SESSION['pkey']['WEIXIN_UPDATE'] == '1'): ?><li><a href="<?php echo U('Menber/instruser');?>">更新微信用户</a></li><?php endif; ?>
                </ul>
            </li><?php endif; ?>
        </ul>
    </div>
    <!-- end sidebar -->


	<!-- main container -->
    <div class="content">

        <!-- settings changer -->
        <div class="skins-nav">
            <a href="#" class="skin first_nav selected">
                <span class="icon"></span><span class="text">默认颜色</span>
            </a>
            <a href="#" class="skin second_nav" data-file="/Public/Admin/css/skins/dark.css">
                <span class="icon"></span><span class="text">黑色背景</span>
            </a>
        </div>
    	
<link rel="stylesheet" href="/Public/Admin/css/compiled/new-user.css" type="text/css" media="screen" />  
   <div class="container-fluid">
            <div id="pad-wrapper" class="new-user">
                <div class="row-fluid header">
                    <h3>微信管理&nbsp;>&nbsp基本信息</h3>
                </div>

                <div class="row-fluid form-wrapper">
                    <form action="<?php echo U(Menber/wxinfo);?>" method="post" class="new_user_form">
                    <input type="hidden" name="wcid" value="<?php echo ($wx['wcid']); ?>"/>
                    <!-- left column -->
                    <div class="span6 with-sidebar">
                        <div class="span9 field-box uname">
                            <label>公众号名称:</label>
                            <input class="span9" type="text" name="wxname" value="<?php echo ($wx['wxname']); ?>" />
                        </div>
                        <div class="span9 field-box">
                            <label>微信原始账号:</label>
                            <input class="span9" type="text" name="wxlogin" value="<?php echo ($wx['wxlogin']); ?>" />
                        </div>
                       
                        <div class="span12 field-box">
                            <label>URL(服务器地址):</label>
                            <input class="span9" type="text" name="wxurl" value="<?php echo ($wx['wxurl']); ?>" />
                        </div>
                        <div class="span9 field-box">
                            <label>Token(令牌):</label>
                            <input class="span9" type="text" name="token" value="<?php echo ($wx['token']); ?>" />
                        </div>
                        <div class="span9 field-box">
                            <label>AppID:</label>
                        		<input class="span9" type="text" name="appid" value="<?php echo ($wx['appid']); ?>" />
                        </div>
                        <div class="span9 field-box">
                            <label>AppSecret:</label>
                            <input class="span9" type="text" name="appsecret" value="<?php echo ($wx['appsecret']); ?>" />
                        </div>
                        <div class="span9 field-box">
                            <label>EncodingAESKey:</label>
                            <input class="span9" type="text" name="encodingaeskey" value="<?php echo ($wx['encodingaeskey']); ?>" />
                        </div>
                        <div class="span9 field-box">
                            <label>Partnerid:</label>
                            <input class="span9" type="text" name="parterid" value="<?php echo ($wx['parterid']); ?>" />
                        </div>
                        <div class="span9 field-box">
                            <label>PartnerKey:</label>
                            <input class="span9" type="text" name="parterkey" value="<?php echo ($wx['parterkey']); ?>" />
                        </div>
                         <div class="span8 field-box actions" style="padding-bottom: 20px;">
                            <input type="submit" class="btn-glow primary" <?php if($wx['wcid'] == ''): ?>value="添加"<?php else: ?>value="修改"<?php endif; ?> >
                        </div>
                    </div>
					</form>
                        
                </div>
            </div>
        </div>
	<!-- scripts -->
    <script src="/Public/Admin/js/jquery-latest.js"></script>
    <script src="/Public/Admin/js/bootstrap.min.js"></script>
    <script src="/Public/Admin/js/theme.js"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			setdashboardmenu("微信管理");
		});
	 </script>	
	<script type="text/javascript">
        $(function () {

            // toggle form between inline and normal inputs
            var $buttons = $(".toggle-inputs button");
            var $form = $("form.new_user_form");

            $buttons.click(function () {
                var mode = $(this).data("input");
                $buttons.removeClass("active");
                $(this).addClass("active");

                if (mode === "inline") {
                    $form.addClass("inline-input");
                } else {
                    $form.removeClass("inline-input");
                }
            });
        });
    </script>


    </div>
    <script type="text/javascript">
    	var wid = $(window).height();
    	document.writeln('<div id="popupLayer" style="position:absolute;z-index:2;width:100%;height:'+wid+'px;left:0;top:0;opacity:0.3;filter:Alpha(opacity=30);background:#000;display: none;"></div>');
    </script>
</body>
</html>