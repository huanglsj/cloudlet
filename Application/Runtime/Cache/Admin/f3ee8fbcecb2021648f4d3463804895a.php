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
<link rel="stylesheet" href="/Public/Admin/css/compiled/user-list.css" type="text/css" media="screen" />
<link href="/Public/Admin/css/lib/bootstrap.datepicker.css" type="text/css" rel="stylesheet" />
<style type="text/css">
input.search {
	background:none !important;
	width:150px !important;
}
select {
	width:150px !important;
}
</style>
    
<div class="container-fluid">
  <form method="get" action="<?php echo U('recharge');?>" name="search">
    <div id="pad-wrapper" class="users-list">
      <div class="row-fluid header">
          <h3>
		    <?php if($optype == 1): ?>充值列表
			<?php elseif($optype == 2): ?>提现列表<?php endif; ?>
		  </h3>
          <div class="span10 pull-right">
		     <input type="hidden" name="optype" value="<?php echo ($optype); ?>"/>
              <div class="tpsearch">
              	客户账号
              	<input type="text" class="span6 search" name="username" value="<?php echo ($username); ?>"/>
              </div>
              <div class="tpsearch">
              	操作日期
              	<input type="text" data-date-format="yyyy-mm-dd" class="input-large datepicker" style="margin-bottom: 0;" name="opdate" value="<?php echo ($opdate); ?>">
              </div>
              <div class="tpsearch">
              	操作状态
              	<select id="ostyle" class="span6" name="state">
                    <option value="">全部</option>
                    <option value="1" <?php if($state==1)echo 'selected' ?>>开始</option>
                    <option value="2" <?php if($state==2)echo 'selected' ?>>成功</option>
                    <option value="3" <?php if($state==3)echo 'selected' ?>>失败</option>
              	</select>
              </div>
          </div>
		  <?php if($optype == 2): ?><div class="span10 pull-right" style="margin-top:10px">
              <div class="tpsearch">
              	审核状态
              	<select id="ostyle" class="span6" name="checkstate">
                    <option value="">全部</option>
                    <option value="1" <?php if($checkstate==1)echo 'selected' ?>>待审核</option>
                    <option value="2" <?php if($checkstate==2)echo 'selected' ?>>审核通过</option>
                    <option value="3" <?php if($checkstate==3)echo 'selected' ?>>审核拒绝</option>
                    <option value="100" <?php if($checkstate==100)echo 'selected' ?>>自动审核</option>
              	</select>
              </div>
              <div class="tpsearch">
              	审核人&nbsp;
              	<input type="text" class="span6 search" name="checkuser" value="<?php echo ($checkuser); ?>"/>
              </div>
              <div class="tpsearch">
              	审核日期
              	<input type="text" data-date-format="yyyy-mm-dd" class="input-large datepicker" style="margin-bottom: 0;" name="checkdate" id="checkdate" value="<?php echo ($checkdate); ?>">
              </div>
          </div><?php endif; ?>
          <div class="span10 pull-right" style="margin-top:10px;margin-bottom:10px">
              <div class="tpsearch">
              	途径&nbsp;&nbsp;
              	<select id="ostyle" class="span6" name="payway">
                    <option value="">全部</option>
                    <option value="weixin" <?php if($payway=='weixin')echo 'selected' ?>>微信</option>
                    <option value="alipay" <?php if($payway=='alipay')echo 'selected' ?>>支付宝</option>
                    <option value="unionpay" <?php if($payway=='unionpay')echo 'selected' ?>>银联</option>
              	</select>
              </div>
	          <div class="tpsearch">
	          	<a href="javascript:void(0)" class="btn-flat info" onclick="document.forms['search'].submit();">开始查找</a>
	          </div>
          </div>
    
        <!-- Users table -->
        <div class="row-fluid table">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th class="span2">
                            编号
                        </th>
                        <th class="span2">
                            <span class="line"></span>客户
                        </th>
                        <th class="span2">
                            <span class="line"></span>姓名
                        </th>
                        <th class="span2">
                            <span class="line"></span>时间
                        </th>
						<th class="span2">
                            <span class="line"></span>状态
                        </th>
						<th class="span2">
                            <span class="line"></span>途径
                        </th>
						<th class="span2 ">
                            <span class="line"></span>金额
                        </th>
						<?php if($optype == 2): ?><th class="span2 ">
                            <span class="line"></span>手续费
                        </th>
                        <th class="span2 ">
                            <span class="line"></span>审核状态
                        </th>
						<th class="span2 ">
                            <span class="line"></span>审核人
                        </th>
						<th class="span2 ">
                            <span class="line"></span>审核时间
                        </th><?php endif; ?>
                    </tr>
                </thead>
                <tbody id="ajaxback">
				<?php if(is_array($rechargelist)): $i = 0; $__LIST__ = $rechargelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$relist): $mod = ($i % 2 );++$i;?><!-- row -->
                <tr class="first">
					<td>
                        <a href="<?php echo U('rechargedetail', array('id'=>$relist['id']));?>"><?php echo ($relist["id"]); ?></a>
                    </td>
                    <td>
                        <a href="<?php echo U('User/updateuser',array('uid'=>$relist['uid']));?>"><?php echo ($relist["customer"]); ?></a>
                    </td>
                    <td><?php echo ($relist["realname"]); ?></td>
                    <td>
                        <?php echo (date('Y-m-d H:i',$relist["begintime"])); ?>
                    </td>
                    <td data-state="<?php echo ($relist["state"]); ?>">
                    	<?php if($relist["state"] == '1' ): ?>开始
                        <?php elseif($relist["state"] == '2' ): ?>
                        成功
                        <?php elseif($relist["state"] == '3' ): ?>
                        失败<?php endif; ?>
                    </td>
                    <td>
                    	<?php if(($relist["payway"] == '1') or ($relist["payway"] == '4') ): ?>微信
                        <?php elseif(($relist["payway"] == '2') or ($relist["payway"] == '6') ): ?>
                        支付宝
                        <?php elseif($relist["payway"] == '5' ): ?>
                        银联<?php endif; ?>
                    </td>
                    <td>
                        <?php echo ($relist["amount"]); ?>
                    </td>
					<?php if($optype == 2): ?><td>
						<?php echo ($relist["fee"]); ?>
                    </td>
                    <td>
                    	<?php if($relist["checkstate"] == '1' ): ?>待审
                        <?php elseif(($relist["checkstate"] == '2') or ($relist["checkstate"] == '100')): ?>
                        通过
                        <?php elseif($relist["checkstate"] == '3' ): ?>
                        拒绝<?php endif; ?>
                    </td>
                    <td>
					 <?php if($relist["checkstate"] != '1' ): if($relist["checkuserid"] == 0): ?>系统自动
						<?php else: ?>
						<?php echo ($relist["checkuser"]); endif; endif; ?>
                    </td>
                    <td>
					  <?php if($relist["checkstate"] != '1' ): echo (date('Y-m-d H:i',$relist["checktime"])); endif; ?>
                    </td><?php endif; ?>
                </tr>
                <!-- row --><?php endforeach; endif; else: echo "" ;endif; ?>
                </tbody>
            </table>
        </div>
		<div class="qjcz">
		统计：（次数：<font color="#f00" size="4"><?php echo ($totalcount); ?></font>次&nbsp;&nbsp;金额：<font color="#f00" size="4"><?php echo ($totalamount); ?></font>元）
		</div>

        <div class="pagination pull-right">
            <ul>
                <?php echo ($page); ?>
            </ul>
        </div>
        <!-- end users table -->
    </div>
  </form>
</div>

<!-- scripts -->
<script src="/Public/Admin/js/jquery-latest.js"></script>
<script src="/Public/Admin/js/bootstrap.min.js"></script>
<script src="/Public/Admin/js/theme.js"></script>
<script src="/Public/Admin/js/popup_layer.js"></script>
<script src="/Public/Admin/js/bootstrap.datepicker.js"></script>

<script type="text/javascript">
	$(document).ready(function(){
		setdashboardmenu("客户管理");

        // datepicker plugin
        $('.datepicker').datepicker().on('changeDate', function (ev) {
            $(this).datepicker('hide');
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