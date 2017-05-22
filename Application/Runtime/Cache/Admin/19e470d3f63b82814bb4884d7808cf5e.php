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
    	
	<!-- this page specific styles -->
    <link rel="stylesheet" href="/Public/Admin/css/compiled/order-list.css" type="text/css" media="screen" />
    <link href="/Public/Admin/css/lib/bootstrap.datepicker.css" type="text/css" rel="stylesheet" />
    <link href="/Public/Admin/css/lib/select2.css" type="text/css" rel="stylesheet" />
    <div class="container-fluid order-screen">
		<button class="btn btn-link pull-right" full-screen="0" id="fullScreen" style="outline: none;">全屏</button>
        <div id="pad-wrapper" class="users-list">
            <div class="row-fluid header" style="margin-bottom: 30px;">
                <h3><?php if(I('ostaus') == 0): ?>持仓订单<?php else: ?>平仓订单<?php endif; ?></h3>
                <form id="searchfm" method="get" action="<?php echo U('Order/olist');?>">
                <input type="hidden" value="<?php echo ($ostaus); ?>" name="ostaus"/>
				<div class="span10 pull-right">
					<div class="tpsearch">
						<label for="username">订单号：</label>
						<input type="text" value="<?php echo ($orderno); ?>" name="orderno" id="orderno" placeholder="订单号">
					</div>
					<div class="tpsearch">
						<label for="history">结算日期：</label>
						<input type="text" value="<?php echo ($history); ?>" name="history" id="history" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})">
<!--						<select name="history" id="history" style="width: 206px;">
							<option value="tday" <?php echo ($history=='tday'?'selected':''); ?>>当日</option>
							<option value="yday" <?php echo ($history=='yday'?'selected':''); ?>>昨天</option>
							<option value="7day" <?php echo ($history=='7day'?'selected':''); ?>>7天</option>
							<option value="30dy" <?php echo ($history=='30dy'?'selected':''); ?>>近30天</option>
							<option value="tsmh" <?php echo ($history=='tsmh'?'selected':''); ?>>本月</option>
							<option value="ltmh" <?php echo ($history=='ltmh'?'selected':''); ?>>上月</option>
							<option value="qutr" <?php echo ($history=='qutr'?'selected':''); ?>>本季度</option>
							<option value="ltqr" <?php echo ($history=='ltqr'?'selected':''); ?>>上季度</option>
							<option value="year" <?php echo ($history=='year'?'selected':''); ?>>今年</option>
							<option value="ltyr" <?php echo ($history=='ltyr'?'selected':''); ?>>上年</option>
						</select>-->
					</div>
					<div class="tpsearch">
						<label for="username">登录账号：</label>
						<input type="text" value="<?php echo ($username); ?>" name="username" id="username" placeholder="登录账号">
					</div>
					<div class="tpsearch">
						<label for="realname">用户姓名：</label>
						<input type="text" value="<?php echo ($realname); ?>" name="realname" id="realname" placeholder="用户姓名">
					</div>
					<a href="javascript:void(0)" onclick="$('#searchfm').submit();" class="btn-flat info pull-right" id="search_begin" style="margin-top: 20px;">开始查找</a>
				</div>
				<div class="span10 pull-right" data-desu="0" style="display: none;">
					<div class="tpsearch" data-desu="0">
						<label for="ptitle">商品名称：</label>
						<select id="ptitle" name="ptitle" style="width: 206px;">
                            <option value="">默认</option>
                            <?php if(is_array($ptitlelist)): $i = 0; $__LIST__ = $ptitlelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option <?php echo ($ptitle==$vo['ptitle']?'selected':''); ?>><?php echo ($vo["ptitle"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                        </select>
					</div>
					<div class="tpsearch">
						<label for="endprofit">交易点数：</label>
						<input type="text" value="<?php echo ($endprofit); ?>" name="endprofit" id="endprofit" placeholder="交易点数">
					</div>
					<div class="tpsearch">
						<label for="dmjig">机构名称：</label>
						<input type="text" value="<?php echo ($dmjig); ?>" name="dmjig" id="dmjig" placeholder="机构名称">
					</div>
				</div>
				<div class="span10 pull-right" data-desu="0" style="display: none;">
					<div class="tpsearch">
						<label for="dmdail">代理名称：</label>
						<input type="text" value="<?php echo ($dmdail); ?>" name="dmdail" id="dmdail" placeholder="代理名称">
					</div>
					<div class="tpsearch">
						<label for="dmdail">会员编号：</label>
						<input type="text" value="<?php echo ($dmhuiy); ?>" name="dmhuiy" id="dmhuiy" placeholder="会员编号">
					</div>
				</div>
				<input name="psize" id="psize" type="hidden" value="<?php echo ($psize); ?>"/>
				<input name="dorder" id="dorder" type="hidden" value="<?php echo ($dorder); ?>"/>
				<input name="omodel" id="omodel" type="hidden" value="<?php echo ($omodel); ?>"/>
				</form>
				<a href="javascript:void(0);" id="desumore" style="float: right;position: absolute;top: 120px;right: 50px;">更多条件>></a>
            </div>
			<!-- 新的表格 2017年3月12日 -->
			<style>
				.ordtrd>td{border-right: 1px solid #eee;}
                .order-head{position: relative;height: 35px;background: #eee;overflow: hidden;}
                .order-head-tab{position: absolute;top:0;left: 0;}
				.order-tabth>td{font-weight: bold;}
				.order-block{height:200px;overflow: auto;border: 1px solid #ccc;}
				.order-block-tab{width: 2300px;border-bottom: 1px solid #ddd;overflow-y:auto;overflow-x:hidden;}
				.ordtablock{width: 2300px;overflow-y:auto;overflow-x:hidden;}
				.full-screen{position: fixed;top:0;left:0;width: 100%;height:100%;background-color: white;z-index: 6666;}
				.orderr{margin-left:5px;font-size:12px;font-weight:400;color: #666;cursor: pointer;opacity: .1;}
				.orderr:hover{opacity: 1;}
			</style>
			<div class="row-fluid">
				<div class="order-head">
					<table class="table order-head-tab" style="width: 2300px;margin-bottom:0;table-layout: fixed;width: 2300px;">
						<tr class="order-tabth">
							<td style="width: 220px;">订单号</td>
							<td style="width: 100px;">结算日期</td>
							<td style="width: 100px;">登录账号</td>
							<td style="width: 100px;">用户姓名</td>
							<td style="width: 100px;">交易类型</td>
							<td style="width: 100px;">商品名称</td>
							<td style="width: 100px;">交易点数</td>
							<td style="width: 100px;">交易次数</td>
							<td style="width: 100px;">盈次数</td>
							<td style="width: 100px;">亏次数</td>
							<td style="width: 100px;">平次数</td>
							<td style="width: 100px;">胜率(%)</td>
							<td style="width: 100px;">买卖类型</td>
							<td style="width: 100px;">委托金额</td>
							<td style="width: 100px;">有效委托金</td>
							<td style="width: 100px;">盈亏金额</td>
							<td style="width: 100px;">盈利金额</td>
							<td style="width: 100px;">手续费</td>
							<td style="width: 100px;">亏损金额</td>
							<td style="width: 100px;">交易管理费</td>
							<td style="width: 100px;">推荐费</td>
							<td style="width: 100px;">所属机构</td>
							<td style="width: 100px;">所属代理</td>
							<td style="width: 100px;">所属会员</td>
							<td style="width: 100px;">交易市场</td>
						</tr>
					</table>
				</div>
				<div class="order-block">
					<!-- 表格主体 -->
					<table class="table order-block-tab" style="table-layout: fixed;width: 2300px;">
						<?php if(is_array($orderlist)): $i = 0; $__LIST__ = $orderlist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr class="ordtrd">
								<td style="width: 220px;"><?php echo ($vo["orderno"]); ?></td>
								<!--结算日期-->
								<td style="width: 100px;"><?php echo ($vo["sltd"]); ?></td>
								<!--登录账号-->
								<td style="width: 100px;"><?php echo ($vo["username"]); ?></td>
								<!--用户姓名-->
								<td style="width: 100px;"><?php echo ($vo["realname"]); ?></td>
								<!--资金账户编号-->
								<?php if($vo['eid'] == 1): ?><td style="width: 100px;color: #6666CC;">点数</td>
									<?php else: ?>
									<td style="width: 100px;color: #FF6666;">时间</td><?php endif; ?>
								<!--商品名称-->
								<td style="width: 100px;"><?php echo ($vo["ptitle"]); ?></td>
								<!--交易点数-->
								<td style="width: 100px;"><?php echo (abs($vo["endprofit"])); ?></td>
								<!--交易次数-->
								<td style="width: 100px;"><?php echo ($vo["odrcount"]); ?></td>
								<!--盈次数-->
								<td style="width: 100px;"><?php echo ($vo["profitcount"]); ?></td>
								<!--亏次数-->
								<td style="width: 100px;"><?php echo ($vo["losscount"]); ?></td>
								<!--平次数-->
								<td style="width: 100px;"><?php echo ($vo["flatcount"]); ?></td>
								<!--胜率-->
								<?php if($vo['winnpro'] < 0.4): ?><td style="width: 100px;color: #FFCC00;"><?php echo ($vo['winnpro']*100); ?>%</td>
									<?php elseif($vo['winnpro'] > 0.4 and $vo['winnpro'] < 0.75): ?>
									<td style="width: 100px;color: #CC33CC;"><?php echo ($vo['winnpro']*100); ?>%</td>
									<?php else: ?>
									<td style="width: 100px;color: #00CC00;"><?php echo ($vo['winnpro']*100); ?>%</td><?php endif; ?>
								<!--买卖类型-->
								<?php if($vo['ostyle'] == 0): ?><td style="width: 100px;color: red;">买涨</td>
								<?php else: ?>
								<td style="width: 100px;color: green;">买跌</td><?php endif; ?>
								<!--委托金额-->
								<td style="width: 100px;"><?php echo ($vo["feesum"]); ?></td>
								<!--有效委托金额-->
								<td style="width: 100px;"><?php echo ($vo["tfeesum"]); ?></td>
								<!--盈亏金额-->
								<td style="width: 100px;<?php echo ($vo['plosssum']>0?'color:blue':'color:red'); ?>;"><?php echo ($vo["plosssum"]); ?></td>
								<!--盈利金额-->
								<td style="width: 100px;"><?php echo ($vo["profit"]); ?></td>
								<!--亏损金额-->
								<td style="width: 100px;"><?php if($vo[plosssum] > 0): echo round($vo[feesum] * $vo[poundage] / 100, 2); else: ?>0<?php endif; ?></td>
								<!--手续费-->
								<td style="width: 100px;"><?php echo (abs($vo["loss"])); ?></td>
								<!--交易管理费-->
								<td style="width: 100px;"><?php echo ($vo["managefeesum"]); ?></td>
								<!--推荐费-->
								<td style="width: 100px;"><?php echo ($vo["commissionsum"]); ?></td>
								<!--所属机构名称-->
								<td style="width: 100px;"><?php echo ($vo["dmjig"]); ?></td>
								<!--所属代理名称-->
								<td style="width: 100px;"><?php echo ($vo["dmjinj"]); ?></td>
								<!--所属会员编号-->
								<td style="width: 100px;"><?php echo ($vo["dmhuiy"]); ?></td>
								<!--交易市场-->
								<td style="width: 100px;"><?php echo ($vo["dmshic"]); ?></td>
							</tr><?php endforeach; endif; else: echo "" ;endif; ?>
						<?php if(!empty($orderlist)): ?><tr class="ordtrd">
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td><?php echo ($ototal['odrcount']); ?></td>
							<td><?php echo ($ototal['profitcount']); ?></td>
							<td><?php echo ($ototal['losscount']); ?></td>
							<td><?php echo ($ototal['flatcount']); ?></td>
							<td></td>
							<td></td>
							<td><?php echo ($ototal['feesum']); ?></td>
							<td><?php echo ($ototal['tfeesum']); ?></td>
							<td style="<?php echo ($ototal['plosssum']>0?'color:blue':'color:red'); ?>;"><?php echo ($ototal['plosssum']); ?></td>
							<td><?php echo ($ototal['profit']); ?></td>
							<td></td>
							<td><?php echo (abs($ototal['loss'])); ?></td>
							<td><?php echo ($ototal['managefeesum']); ?></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
						</tr><?php endif; ?>
					</table>
					<?php if(empty($orderlist)): ?><div style="padding:100px 0 0;text-align: center;font-size: 32px;">没有查询到任何数据</div><?php endif; ?>
				</div>
			</div>

            <div class="pagination pull-right">
                <ul><?php echo ($page); ?></ul>
				<div style="margin-right:15px;display: inline-block;height: 26px;line-height: 26px;" class="pull-left">每页显示:
					<select style="margin:0;width: 60px;height: 24px;" onchange="$('#psize').val(this.value);$('#searchfm').submit();">
						<option <?php echo ($psize=='5'?'selected':''); ?>>5</option>
						<option <?php echo ($psize=='10'?'selected':''); ?>>10</option>
						<option <?php echo ($psize=='20'?'selected':''); ?>>20</option>
						<option <?php echo ($psize=='50'?'selected':''); ?>>50</option>
						<option <?php echo ($psize=='100'?'selected':''); ?>>100</option>
						<option <?php echo ($psize=='200'?'selected':''); ?>>200</option>
					</select>&nbsp;条记录
				</div>
            </div>
            <!-- end users table -->
        </div>
    </div>
    <!-- end main container -->
<div id="loading" style="width: 100%;height: 105%;position: absolute;top: 0; z-index: 9999;display: none;">
	<div class="load-center" style="background: #000;position: absolute;width: 60%;height: 14%;bottom: 10%;border-radius: 10px;color: #fff;text-align: center;font-size: 24px;left: 17%;padding: 1%;">
		<img src="/Public/Admin/img/ajax-loading.jpg" alt="ajax-loading" width="40"/><br/>页面加载中...
	</div>
</div>
<!-- scripts -->
<script language="javascript" type="text/javascript" src="/Public/Admin/js/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/Admin/js/jquery-latest.js"></script>
<script src="/Public/Admin/js/bootstrap.min.js"></script>
<script src="/Public/Admin/js/bootstrap.datepicker.js"></script>
<script src="/Public/Admin/js/theme.js"></script>
<script src="/Public/Admin/js/select2.min.js"></script>
<script type="text/javascript">
    $(function () {
        $("#desumore").click(function () {
            if ($("[data-desu]").attr("data-desu")==0) {
                $("[data-desu]").attr("data-desu",1);
                $("[data-desu]").css("display","block");
                $(this).text("更多条件<<");
            }else{
                $("[data-desu]").attr("data-desu",0);
                $("[data-desu]").css("display","none");
                $(this).text("更多条件>>");
            }
        });

    	setdashboardmenu("订单管理");
        // datepicker plugin
        $('.datepicker').datepicker().on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });
//
//        $(".select2").select2({
//            placeholder: "请选择会员单位"
//        });
		//2017-3-13
        var $fullScreen = $('#fullScreen');
        var $orderScreen = $('.order-screen');
        var $bodyy=$('body');
        // 设置表格高度
        $(".order-block").height($(window).height()*0.6);
        // 全屏
        $fullScreen.click(function () {
            var $isFull=$fullScreen.attr('full-screen');
            if ($isFull==0) { // 开启全屏
                $fullScreen.attr('full-screen',1);
                $orderScreen.addClass('full-screen');
                $fullScreen.text('取消全屏');
                $bodyy.css('overflow','hidden');
                $(".order-block").height($(window).height()*0.5);
            }else if ($isFull==1) { // 取消全屏
                $fullScreen.attr('full-screen',0);
                $orderScreen.removeClass('full-screen');
                $fullScreen.text('全屏');
                $bodyy.css('overflow','inherit');
                $(".order-block").height($(window).height()*0.6);
            }
        });

        $('.order-block').on('scroll',function(){
            var myOffset = {};
            myOffset.left = $('.order-block-tab').offset().left;
            $('.order-head-tab').offset(myOffset);
        });

        $('.ordtrd').click(function () {
            $('.ordtrd').removeClass('info');
            $(this).addClass('info');
        });

        var $orderr=$('.orderr');
        $orderr.click(function () {
            var $dorder=$orderr.attr('data-order');
            if ($orderr.attr('data-omodel')=="asc") {
                $orderr.text("降序");
                $orderr.attr('data-omodel','desc');
            }else {
                $orderr.text("排序");
                $orderr.attr('data-omodel','asc');
            }
            $("#dorder").val($dorder);
            $("#omodel").val($orderr.attr('data-omodel'));
            $('#searchfm').submit();
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