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
<link href="/Public/Admin/css/lib/select2.css" type="text/css" rel="stylesheet" />
<link rel="stylesheet" href="/Public/Admin/css/compiled/user-list.css" type="text/css" media="screen">
<div class="container-fluid">
            <div id="pad-wrapper" class="users-list">
                <div class="row-fluid header">
                    <h3>客户列表</h3>
                    <form id="searchfm" method="get" action="<?php echo U('User/ulist');?>">
                        <input name="psize" id="psize" type="hidden" value="<?php echo ($psize); ?>"/>
	                    <div class="span10 pull-right">
		                    <div class="tpsearch span3">
		                    	客户账号：<input type="text" placeholder="请输入客户账号" value="<?php echo I('username'); ?>" name="username" id="username"/>
		                    </div>
		                    <div class="tpsearch span3">
		                    	客户昵称：<input type="text"  placeholder="请输入客户昵称" value="<?php echo I('nickname'); ?>" name="nickname" id="nickname"/>
		                    </div>
		                    <div class="tpsearch span3">
		                    	客户手机：<input type="text"  placeholder="请输入客户手机" value="<?php echo I('utel'); ?>" name="utel" id="utel">
		                    </div>
		                    <div class="tpsearch span3">
		                    	创建日期晚于：<input type="text" onclick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm'})" value="<?php echo urldecode(I('utime'))?>" name="utime" id="utime">
		                    </div>
		                </div>
		                <div class="span10 pull-right">
		                    <div class="tpsearch span3">
		                    	所属会员单位：
		                    	<select name="companyid" style="width:200px" class="select2">
	                                <option value="0">请选择会员单位</option>
	                                <?php if(is_array($companys)): $i = 0; $__LIST__ = $companys;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cp): $mod = ($i % 2 );++$i;?><option value="<?php echo ($cp['cid']); ?>" <?php if(I('companyid') == $cp['cid']): ?>selected="selected"<?php endif; ?>/><?php echo ($cp['comname']); endforeach; endif; else: echo "" ;endif; ?>
	                            </select>
		                    </div>
		                    <div class="tpsearch span3">
		                    	实名认证状态：
		                    	<select name="authenticationsstatus" style="width:200px">
	                                <option value="" <?php if(I('authenticationsstatus') == ''): ?>selected="selected"<?php endif; ?>>请选择认证状态</option>
	                                <option value="0"<?php if(I('authenticationsstatus') == '0'): ?>selected="selected"<?php endif; ?>>未申请</option>
	                                <option value="1"<?php if(I('authenticationsstatus') == '1'): ?>selected="selected"<?php endif; ?>>认证中</option>
	                                <option value="2"<?php if(I('authenticationsstatus') == '2'): ?>selected="selected"<?php endif; ?>>已认证</option>
	                                <option value="3"<?php if(I('authenticationsstatus') == '3'): ?>selected="selected"<?php endif; ?>>认证失败</option>
	                            </select>
		                    </div>
		                    <div class="tpsearch span3">
		                    	激活状态：
		                    	<select name="ustatus" style="width:200px">
	                                <option value="" <?php if(I('ustatus') == ''): ?>selected="selected"<?php endif; ?>>请选择激活状态</option>
	                                <option value="1"<?php if(I('ustatus') == '1'): ?>selected="selected"<?php endif; ?>>开户</option>
	                                <option value="2"<?php if(I('ustatus') == '2'): ?>selected="selected"<?php endif; ?>>激活</option>
	                                <option value="3"<?php if(I('ustatus') == '3'): ?>selected="selected"<?php endif; ?>>冻结</option>
	                            </select>
		                    </div>
		                </div>
		                <div class="span10 pull-right" style="margin-top: 10px;">
	                    	<a href="javascript:void(0)" onclick="$('#searchfm').submit();" class="btn-flat info" id="search_begin">开始查找</a>
	                    </div>
                    </form>
                </div>
                <!-- Users table -->
                <style>
                    #pad-wrapper{padding-left: 0}
                    #ajaxback td{padding: 3px 2px;font-size: 12px;line-height: 14px;}
                </style>
                <div class="row-fluid table">
                    <table class="table table-hover ulist-desu">
                        <thead>
                            <tr>
                                <th class="span1 sortable">
                                	#
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>客户账号
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>客户姓名
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>客户昵称
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>手机号码
                                </th>
                                <!-- <th class="span1 sortable">
                                    <span class="line"></span>地区
                                </th> -->
                                <th class="span2 sortable">
                                    <span class="line"></span>微信头像
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>创建日期
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>所属机构名称
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>所属代理名称
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>所属会员编号
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>公司名称
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>最近登录时间
                                </th>	
                                <th class="span2 sortable">
                                    <span class="line"></span>账户余额
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>交易量
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>实名认证
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>激活状态
                                </th>
                                <th class="span2 sortable">
                                    <span class="line"></span>操作
                                </th>
                            </tr>
                        </thead>
                        <tbody id="ajaxback">
                        <?php if(is_array($ulist)): $i = 0; $__LIST__ = $ulist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$ult): $mod = ($i % 2 );++$i;?><!-- row -->
                        <tr class="first">
                            <td data-uid="<?php echo ($ult['uid']); ?>"><?php echo ($i); ?></td>
                            <td>
                            	<?php echo ($ult['username']); ?>
                            </td>
                            <td>
                                <?php echo ($ult['realname']); ?>
                            </td>
                            <td>
                                <?php echo ($ult['nickname']); ?>
                            </td>
                            <td>
                                <?php echo ($ult['utel']); ?>
                            </td>
                            <!-- <td>
                                <?php echo ($ult['address']); ?>
                            </td> -->
                            <td>
                            	<?php if($ult["portrait"] == ''): ?><img src="/Public/Admin/img/contact-img.png" class="img-circle avatar hidden-phone"><?php else: ?><img src="<?php echo ($ult["portrait"]); ?>" class="img-circle avatar hidden-phone"><?php endif; ?>
                            </td>
                            <td>
                                <?php echo (date('Y-m-d H:i',$ult['utime'])); ?>
                            </td>
                            <td><?php echo ($ult['dmjig']); ?></td>
                            <td><?php echo ($ult['dmjinj']); ?></td>
                            <td><?php echo ($ult['dmhuiy']); ?></td>
                            <td><?php echo ($ult['dmname']); ?></td>
                            <td>
                            	<?php if($ult["lastlog"] == ''): else: ?>
                            		<?php echo (date('Y-m-d H:i:s',$ult['lastlog'])); endif; ?>
                            </td>
                            <td>
                            	<font color="#f00" size="4">￥<?php echo ($ult['balance']); ?></font>
                            </td>
                            <td>
                                <font color="#f00" size="4">￥<?php echo ($ult['orderSum']); ?></font>
                            </td>
                            <td>
                                <?php if($ult["authenticationsstatus"] == 0): ?>未实名<?php endif; ?>
                                <?php if($ult["authenticationsstatus"] == 1): ?>实名申请中<?php endif; ?>
                                <?php if($ult["authenticationsstatus"] == 2): ?><a href="<?php echo U('User/checkauthentications',array('uid'=>$ult['uid']));?>">已实名</a><?php endif; ?>
                                <?php if($ult["authenticationsstatus"] == 3): ?>认证失败<?php endif; ?>
                            </td>
                            <td>
                            	<?php switch($ult["ustatus"]): case "1": ?>开户<?php break;?>
                            		<?php case "2": ?>激活<?php break;?>
                            		<?php case "3": ?>冻结<?php break; endswitch;?>
                            </td>
                            <td>
                            	<ul class="actions">
                            	    <?php if($_SESSION['pkey']['USER_EDIT'] == '1'): ?><li ><a href="<?php echo U('User/updateuser',array('uid'=>$ult['uid']));?>"><i class="table-edit"></i></a></li>
                                        <li class="last"><a href="<?php echo U('User/userdel',array('uid'=>$ult['uid']));?>" onclick="if(confirm('确定要删除吗?')){return true;}else{return false;}"><i class="table-delete"></i></a></li><?php endif; ?>
                                    <?php if($ult["authenticationsstatus"] == 1): if($_SESSION['pkey']['USER_CF'] == '1'): ?><li class="last"><a href="<?php echo U('User/checkauthentications',array('uid'=>$ult['uid']));?>"><i class="table-ok" ></i></a></li><?php endif; ?>
                                     <?php else: endif; ?>
                                </ul>
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>                        
                        </tbody>
                    </table>
                 <div class="pagination pull-right">
                    <ul>
                        <?php echo ($page); ?>
                    </ul>
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
                   <div class="qjcz">
					截止<script type="text/javascript">var myDate = new Date();document.writeln(myDate.getFullYear()+'年'+(myDate.getMonth()+1)+'月'+myDate.getDate()+'日');</script>，共有<font color="#f00" size="4"><?php echo ($ucount); ?></font>个会员，账户累计余额<font color="#f00" size="5"><?php echo ($anumber); ?></font>元
				</div>
                </div>
                <!-- end users table -->
            </div>
        </div>
<!-- scripts -->
<script language="javascript" type="text/javascript" src="/Public/Admin/js/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/Admin/js/wysihtml5-0.3.0.js"></script>
<script src="/Public/Admin/js/jquery-latest.js"></script>
<script src="/Public/Admin/js/bootstrap.min.js"></script>
<script src="/Public/Admin/js/bootstrap-wysihtml5-0.0.2.js"></script>
<script src="/Public/Admin/js/bootstrap.datepicker.js"></script>
<script src="/Public/Admin/js/jquery.uniform.min.js"></script>
<script src="/Public/Admin/js/select2.min.js"></script>
<script src="/Public/Admin/js/theme.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		setdashboardmenu("客户管理");
		
		// select2 plugin for select elements
        $(".select2").select2({
            placeholder: "请选择会员单位"
        });

        // datepicker plugin
        $('.datepicker').datepicker().on('changeDate', function (ev) {
            $(this).datepicker('hide');
        });
	});
	
	$('#top_search').keyup(top_serch);
	$('#sxsearch').click(top_serch);
	//搜索结果，ajax返回搜索框搜索结果
	function top_serch(){
		//获取点击参数
		var urlkey = $(this).attr("urlkey");
		//获取文本框值
		var keywords = $("#top_search").val(),
		    sxkey = $("#sxkey  option:selected").val(),
			formula = $("#formula  option:selected").val(),
			sxvalue = $("#sxvalue").val();
		//重新定义提交url
		var newurl = "";
		if(urlkey == "search"){
			newurl = "<?php echo U('User/ulist?step=search');?>"
		}
		if(urlkey == "sxsearch"){
			newurl = "<?php echo U('User/ulist?step=sxsearch');?>"
		}
		$.ajax({  
		    type: "post",  
		    url: newurl,    
        	data:{"keywords":keywords,"sxkey":sxkey,"formula":formula,"sxvalue":sxvalue},
		    success: function(data) {
		    	//console.log(data);
		    	if(data=="null"){
	            	//$("#loading").hide();
	            	$("#ajaxback").html('<volist name="ulist" id="ult"><tr class="first"><td colspan="13">没有找到结果，请重新输入！</tr></td>');
	            }else{
			    	$ulist = "";
		            $.each(data,function(no,items){
		            	$ulist += '<volist name="ulist" id="ult"><tr class="first">';
		            	$ulist += '<td>'+items.uid+'</td>';
		            	if(items.username != null && items.username != "" && items.username != "null"){
		            		$ulist += '<td><a href="<?php echo U('User/updateuser');?>?uid='+items.uid+'" >'+items.username+'</a></td>';
		            	} else {
		            		$ulist += '<td></td>';
		            	}
		            	if(items.nickname != null && items.nickname != "" && items.nickname != "null"){
		            	    $ulist += '<td>'+items.nickname+'</td>';
		            	} else {
                            $ulist += '<td></td>';
                        }
		            	if(items.utel == null || items.utel == ""){
		            		$ulist += '<td></td>';
		            	} else {
		            		$ulist += '<td>'+items.utel+'</td>';
                        }
//		            	if(items.address != null && items.address != "" && items.address != "null"){
//		            	    $ulist += '<td>'+items.address+'</td>';
//		            	} else {
//                            $ulist += '<td></td>';
//                      }
		            	if(items.portrait == null || items.portrait == ""){
		            		$ulist += '<td><img src="/Public/Admin/img/contact-img.png" class="img-circle avatar hidden-phone"></td>';
		            	} else {
		            		$ulist += '<td><img src="'+items.portrait+'" class="img-circle avatar hidden-phone"></td>';
		            	}
		            	$ulist += '<td>'+items.utime+'</td>';
		            	if(items.managername != null && items.managername != 'null' && items.managername != ""){
		            		$ulist += '<td><a href="<?php echo U('User/updateuser');?>?uid='+items.oid+'" >'+items.managername+'</a></td>';
		            	} else {
                            $ulist += '<td></td>';
                        }
		            	$ulist += '<td>'+items.lastlog+'</td>';
		            	if(items.ocount=='0'){
		            		$ulist += '<td>0</td>';
		            	}else{
		            		$ulist += '<td><a href="">'+items.ocount+'</a></td>';	
		            	}
		            	$ulist += '<td><font color="#f00" size="4">￥'+items.balance+'<font></td>';
		            	$ulist += '<td>';
		            	if(items.otype == '0'){
		            		$ulist += '客户';
		            	}
		            	if(items.otype == '1'){
		            		$ulist += '代理商';
		            	}
		            	if(items.otype == '2'){
		            		$ulist += '会员单位';
		            	}
		            	if(items.otype == '3'){
                            $ulist += '超级管理员';
                        }
		            	$ulist += '</td><td>';
		            	if(items.authenticationsstatus == '0'){
		            		$ulist += '未实名';
                        }
                        if(items.authenticationsstatus == '1'){
                            $ulist += '实名申请中';
                        }
                        if(items.authenticationsstatus == '2'){
                        	$ulist += '<a href="<?php echo U('User/checkauthentications');?>?uid='+items.uid+'">已实名</a>';
                        }
                        $ulist += '</td><td>';
                        if(items.ustatus == '0'){
                            $ulist += '激活';
                        }
                        if(items.ustatus == '1'){
                            $ulist += '冻结';
                        }
                        $ulist += '</td><td>';
		            	$ulist += '<ul class="actions">';
		            	$ulist += '<li><a href="<?php echo U('User/updateuser');?>?uid='+items.uid+'"><i class="table-edit"></i></a></li>';
						$ulist += '<li class="last"><a href="<?php echo U('User/userdel');?>?uid='+items.uid+'" onclick="if(confirm(\'确定要删除吗?\')){return true;}else{return false;}"><i class="table-delete"></i></a></li>';
						if(items.authenticationsstatus == '1'){
                            $ulist += '<li class="last"><a href="<?php echo U('User/checkauthentications');?>?uid='+items.uid+'"><i class="table-ok"></i></a></li>';
                        }
		            	$ulist += '</ul></td></tr>';
		            })
		            $("#ajaxback").html($ulist);
		            $(".pagination").html("");
	            }
		    },
		    error: function(data) {  
	            console.log(data);
	        }
		  })
	}
	
	$("#sxkey").bind("change",function(){
		var sxkey = $(this).val();
		switch(sxkey){
			case "uid":
				$("#sxvalue").attr("placeholder","格式：不允许汉字");
				break;
			case "username":
				$("#sxvalue").attr("placeholder","格式：雁过留痕");
				break;
			case "utel":
				$("#sxvalue").attr("placeholder","格式：15022220000");
				break;
			case "otype":
				$("#sxvalue").attr("placeholder","格式：客户/代理商");
				break;
			case "utime":
				$("#sxvalue").attr("placeholder","格式：1970-10-01");
				break;
			case "balance":
				$("#sxvalue").attr("placeholder","格式：不允许汉字");
				break;
            case "authenticationsstatus":
                $("#sxvalue").attr("placeholder","格式：已实名/未实名/认证中");
                break;
            case "ustatus":
                $("#sxvalue").attr("placeholder","格式：激活/冻结");
                break;
			default:
				$("#sxvalue").text("输入内容");
		}
		
	})
</script>

    </div>
    <script type="text/javascript">
    	var wid = $(window).height();
    	document.writeln('<div id="popupLayer" style="position:absolute;z-index:2;width:100%;height:'+wid+'px;left:0;top:0;opacity:0.3;filter:Alpha(opacity=30);background:#000;display: none;"></div>');
    </script>
</body>
</html>