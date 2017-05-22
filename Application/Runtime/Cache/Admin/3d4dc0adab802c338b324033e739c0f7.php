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
		<link rel="stylesheet" href="/Public/Admin/css/compiled/article-add.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="/Public/Admin/css/compiled/ui-elements.css" type="text/css" media="screen" />
		<link href="/Public/Admin/css/lib/bootstrap.datepicker.css" type="text/css" rel="stylesheet" />
		<link href="/Public/Admin/css/bootstrap/bootstrap.min.css" type="text/css" rel="stylesheet" />
		<link href="/Public/Admin/css/bootstrap/bootstrap-switch.min.css" type="text/css" rel="stylesheet" />
		<style type="text/css">
		  .input_error {
		  	border-color:red !important;
		  }
		</style>
	        <div id="txFeeConfigTemplate" style="display:none" class="txFeeConfigRow">
	          <label></label>
	          <input class="span2 fromAmount" type="text" style="width:80px"/>
	          <select class="span1 fromRelation" style="margin-bottom:10px;">
	            <option value="lt" selected>＜</option>
	            <option value="le">≤</option>
	          </select>
	                 提现金额
	          <select class="span1 toRelation" style="margin-bottom:10px">
	            <option value="lt" selected>＜</option>
	            <option value="le">≤</option>
	          </select>
	          <input class="span2 toAmount" type="text" style="width:80px"/>
	          时，
	          <input class="span2 fee" type="text" style="width:80px"/>
	          <select class="span2 type" style="margin-bottom:10px;width:80px">
	            <option value="fixed">元/笔</option>
	            <option value="percent">%</option>
	          </select>
	          <i class="icon-minus-sign delTXFeeConfig" style="font-size:20px;cursor:pointer" onclick="delTXFeeConfig(this);"></i>
	        </div>

        <div class="container-fluid">
            <div id="pad-wrapper" class="form-page">
                <div class="row-fluid form-wrapper">
                    <!-- left column -->
                    <div class="span9 column esystem">
                        <div class="field-box" style=";padding-left:0px">
                            <label style="width:100%"><h3><b>通用提现设置</b></h3></label>
                        </div>
                    	<div style="border-bottom: 1px solid #dee3ea;width: 96.3%; position: relative;float: left;padding-bottom:10px;padding-left:20px;display:none">
                            <label>是否允许秒提</label>
                            <input type="checkbox" id="enableMiaoti" value="0"/>
                        </div>
                        <div class="field-box" style="margin-top:10px">                        	
                            <label>每客户每天最大提现金额</label>
                            <input class="span6 money" type="text" id="maxAmountPerUserDay"/>
                        </div>
                        <div class="field-box" style="margin-top:0px">                        	
                            <label>每客户每天最大提现次数</label>
                            <input class="span6 count" type="text" id="maxTimesPerUserDay"/>
                        </div>
                        <div class="field-box" style="margin-top:0px">                        	
                            <label>系统每天最大提现金额</label>
                            <input class="span6 money" type="text" id="maxAmountPerDay"/>
                        </div>
                        <div class="field-box" style="padding-left:0px">
                            <label style="width:100%"><h3><b>微信提现设置</b></h3></label>
                        </div>
                        <div id="wxConfig">
                        <div class="field-box txFeeConfig" style="margin-top:0px">
                          <div class="txFeeConfigRow">
                            <label>手续费设置</label>
                            <input class="span2 fromAmount money" type="text" style="width:80px"/>
                            <select class="span1 fromRelation" style="margin-bottom:10px">
                              <option value="lt" selected>＜</option>
                              <option value="le">≤</option>
                            </select>
                                   提现金额
                            <select class="span1 toRelation" style="margin-bottom:10px">
                              <option value="lt" selected>＜</option>
                              <option value="le">≤</option>
                            </select>
                            <input class="span2 toAmount money" type="text" style="width:80px"/>
                            时，
                            <input class="span2 fee money" type="text" style="width:80px"/>
                            <select class="span2 type" style="margin-bottom:10px;width:80px">
                              <option value='fixed'>元/笔</option>
                              <option value="percent">%</option>
                            </select>
                            <i class="icon-plus-sign addTXFeeConfig" style="font-size:20px;cursor:pointer"></i>
                          </div>
                        </div>
                        <div class="field-box" style="margin-top:0px;margin-bottom:0px">
                          <label>提现提示</label>
                          <textarea class="span8 feeTips" rows="2" style="margin-bottom:10px;"></textarea>
                        </div>
                      </div> 

                        <div class="field-box" style="padding-left:0px">
                            <label style="width:100%"><h3><b>银联提现设置</b></h3></label>
                        </div>
                        <div id="unionpayConfig">
                        <div class="field-box txFeeConfig" style="margin-top:0px">
                          <div class="txFeeConfigRow">
                            <label>手续费设置</label>
                            <input class="span2 fromAmount money" type="text" style="width:80px"/>
                            <select class="span1 fromRelation" style="margin-bottom:10px">
                              <option value="lt" selected>＜</option>
                              <option value="le">≤</option>
                            </select>
                                   提现金额
                            <select class="span1 toRelation" style="margin-bottom:10px">
                              <option value="lt" selected>＜</option>
                              <option value="le">≤</option>
                            </select>
                            <input class="span2 toAmount money" type="text" style="width:80px"/>
                            时，
                            <input class="span2 fee money" type="text" style="width:80px"/>
                            <select class="span2 type" style="margin-bottom:10px;width:80px">
                              <option value='fixed'>元/笔</option>
                              <option value="percent">%</option>
                            </select>
                            <i class="icon-plus-sign addTXFeeConfig" style="font-size:20px;cursor:pointer"></i>
                          </div>
                        </div>
                        <div class="field-box" style="margin-top:0px;margin-bottom:0px">
                          <label>提现提示</label>
                          <textarea class="span8 feeTips" rows="2" style="margin-bottom:10px;"><?php echo ($conf["notice"]); ?></textarea>
                        </div>
                      </div>

                        <div class="field-box" style="padding-left:0px">
                            <label style="width:100%"><h3><b>支付宝提现设置</b></h3></label>
                        </div>
                        <div id="alipayConfig">
                            <div class="field-box txFeeConfig" style="margin-top:0px">
                                <div class="txFeeConfigRow">
                                    <label>手续费设置</label>
                                    <input class="span2 fromAmount money" type="text" style="width:80px"/>
                                    <select class="span1 fromRelation" style="margin-bottom:10px">
                                        <option value="lt" selected>＜</option>
                                        <option value="le">≤</option>
                                    </select>
                                    提现金额
                                    <select class="span1 toRelation" style="margin-bottom:10px">
                                        <option value="lt" selected>＜</option>
                                        <option value="le">≤</option>
                                    </select>
                                    <input class="span2 toAmount money" type="text" style="width:80px"/>
                                    时，
                                    <input class="span2 fee money" type="text" style="width:80px"/>
                                    <select class="span2 type" style="margin-bottom:10px;width:80px">
                                        <option value='fixed'>元/笔</option>
                                        <option value="percent">%</option>
                                    </select>
                                    <i class="icon-plus-sign addTXFeeConfig" style="font-size:20px;cursor:pointer"></i>
                                </div>
                            </div>
                            <div class="field-box" style="margin-top:0px;margin-bottom:0px">
                                <label>提现提示</label>
                                <textarea class="span8 feeTips" rows="2" style="margin-bottom:10px;"><?php echo ($conf["notice"]); ?></textarea>
                            </div>
                        </div>


                        <div class="span8 field-box actions" style="margin-top:0px;margin-bottom:0px;text-align:center;padding-bottom:10px;padding-top:10px">
                          <input type="button" class="btn-glow primary" value="保存" onclick="saveConfig();">
                        </div>
                    </div>
                </div>
            </div>
        </div>
	<!-- scripts for this page -->	
	<script src="/Public/Admin/js/wysihtml5-0.3.0.js"></script>
    <script src="/Public/Admin/js/jquery-latest.js"></script>
    <script src="/Public/Admin/js/bootstrap.min.js"></script>
    <script src="/Public/Admin/js/bootstrap-wysihtml5-0.0.2.js"></script>
    <script src="/Public/Admin/js/bootstrap.datepicker.js"></script>
    <script src="/Public/Admin/js/jquery.uniform.min.js"></script>
	<script src="/Public/Admin/js/bootstrap-switch.min.js"></script>
	<script src="/Public/Admin/js/theme.js"></script>
    <script language="javascript" src="/Public/Js/numeric.js"></script>
    <!-- call this page plugins -->
    <script>
	    function delTXFeeConfig (o) {
	    	$(o).parent().remove();
	    }
	    
	    function saveConfig() {
	    	if (!checkInput()) {
	    		return false;
	    	}
	    	
	    	var config = {};
	    	config['tixian.common.enableMiaoti'] = $("#enableMiaoti").val();
	    	config['tixian.common.maxAmountPerUserDay'] = $("#maxAmountPerUserDay").val();
	    	config['tixian.common.maxTimesPerUserDay'] = $("#maxTimesPerUserDay").val();
	    	config['tixian.common.maxAmountPerDay'] = $("#maxAmountPerDay").val();
	    	
	    	var tips = $("#wxConfig").find(".feeTips").val();
	    	tips = tips.replace(" ", "&nbsp;")
	    	tips = tips.replace("\n", "<br>")
	    	config['tixian.weixin.feeTips'] = tips;
	    	
	    	var fee = [];
	    	$("#wxConfig").find(".txFeeConfigRow").each(function() {
	    		var f = {};
	    		f.fromAmount = $(this).find(".fromAmount").val();
	    		if (f.fromAmount == "") {
	    			f.fromAmount = 0;
	    		}
	    		f.fromRelation = $(this).find(".fromRelation").val();
	    		f.toAmount = $(this).find(".toAmount").val();
	    		if (f.toAmount == "") {
	    			f.toAmount = 0;
	    		}
	    		f.toRelation = $(this).find(".toRelation").val();
	    		f.fee = $(this).find(".fee").val();
	    		f.type = $(this).find(".type").val();
	    		fee.push(f);
	    	});
	    	config['tixian.weixin.fee'] = JSON.stringify(fee);


	    	tips = $("#unionpayConfig").find(".feeTips").val();
	    	tips = tips.replace(" ", "&nbsp;")
	    	tips = tips.replace("\n", "<br>")
	    	config['tixian.unionpay.feeTips'] = tips;
	    	
	    	fee = [];
	    	$("#unionpayConfig").find(".txFeeConfigRow").each(function() {
	    		var f = {};
	    		f.fromAmount = $(this).find(".fromAmount").val();
	    		if (f.fromAmount == "") {
	    			f.fromAmount = 0;
	    		}
	    		f.fromRelation = $(this).find(".fromRelation").val();
	    		f.toAmount = $(this).find(".toAmount").val();
	    		if (f.toAmount == "") {
	    			f.toAmount = 0;
	    		}
	    		f.toRelation = $(this).find(".toRelation").val();
	    		f.fee = $(this).find(".fee").val();
	    		f.type = $(this).find(".type").val();
	    		fee.push(f);
	    	});
	    	config['tixian.unionpay.fee'] = JSON.stringify(fee);

            tips = $("#alipayConfig").find(".feeTips").val();
            tips = tips.replace(" ", "&nbsp;")
            tips = tips.replace("\n", "<br>")
            config['tixian.alipay.feeTips'] = tips;

            fee = [];
            $("#alipayConfig").find(".txFeeConfigRow").each(function() {
                var f = {};
                f.fromAmount = $(this).find(".fromAmount").val();
                if (f.fromAmount == "") {
                    f.fromAmount = 0;
                }
                f.fromRelation = $(this).find(".fromRelation").val();
                f.toAmount = $(this).find(".toAmount").val();
                if (f.toAmount == "") {
                    f.toAmount = 0;
                }
                f.toRelation = $(this).find(".toRelation").val();
                f.fee = $(this).find(".fee").val();
                f.type = $(this).find(".type").val();
                fee.push(f);
            });
            config['tixian.alipay.fee'] = JSON.stringify(fee);

	    	$.ajax({
	    		"url" : "saveWithdrawConfig.html",
	    		"async":false,
	    		"type" :'POST',
	    		"data" : {"config":JSON.stringify(config)},
	    		"success" :function(data) {
	    			var result = JSON.parse(data);
	    			if (result.success == 1) {
	    				alert("保存成功！");
	    			}
	    			else {
	    				alert("保存失败！");
	    			}
	    			
	    		},
	    		"error" :function(data) {
    				alert("保存失败！");
	    		}
	    	});
	    	
	    }
	    
	    function initDisplay() {
	    	var config = <?php echo ($config); ?>;
	    	var len = config.length;
	    	for (var i=0; i<len; i++) {
	    		var k = config[i].k;
	    		var v = config[i].v;
	    		
	    		if (k == "tixian.common.enableMiaoti") {
	    			if (v == 1) {
	    				$('#enableMiaoti')[0].checked = true;
	    			}
	    			else {
	    				$('#enableMiaoti')[0].checked = false;
	    			}
	    		}
	    		else if (k == "tixian.common.maxAmountPerUserDay") {
	    			$("#maxAmountPerUserDay").val(v);
	    		}
	    		else if (k == "tixian.common.maxTimesPerUserDay") {
	    			$("#maxTimesPerUserDay").val(v);
	    		}
	    		else if (k == "tixian.common.maxAmountPerDay") {
	    			$("#maxAmountPerDay").val(v);
	    		}
	    		else if (k == "tixian.weixin.feeTips") {
	    			v = v.replace("&nbsp;", " ")
	    	    	v = v.replace("<br>", "\n")
	    			$("#wxConfig").find(".feeTips").val(v);
	    		} 
	    		else if (k == "tixian.weixin.fee") {
	    			var c = JSON.parse(v);
	    			for (var j=0; j<c.length; j++) {
	    				var o;
	    				if (j == 0) {
	    					o = $("#wxConfig").find(".txFeeConfigRow").first();
	    				}
	    				else {
	    	    	    	var o = $("#txFeeConfigTemplate").clone();
	    	    	    	o.removeAttr("id");
	    	    	    	o.css("display", "block");
	    	    	    	o.find("input").addClass("money");
	    	    	    	$("#wxConfig").children(".txFeeConfig").append(o);
	    				}
    					o.children(".fromAmount").val(c[j].fromAmount);
    					o.children(".toAmount").val(c[j].toAmount);
    					o.children(".fromRelation").val(c[j].fromRelation);
    					o.children(".toRelation").val(c[j].toRelation);
    					o.children(".fee").val(c[j].fee);
    					o.children(".type").val(c[j].type);
	    			}
	    		}
	    		else if (k == "tixian.unionpay.feeTips") {
	    			v = v.replace("&nbsp;", " ")
	    	    	v = v.replace("<br>", "\n")
	    			$("#unionpayConfig").find(".feeTips").val(v);
	    		} 
	    		else if (k == "tixian.unionpay.fee") {
	    			var c = JSON.parse(v);
	    			for (var j=0; j<c.length; j++) {
	    				var o;
	    				if (j == 0) {
	    					o = $("#unionpayConfig").find(".txFeeConfigRow").first();
	    				}
	    				else {
	    	    	    	var o = $("#txFeeConfigTemplate").clone();
	    	    	    	o.removeAttr("id");
	    	    	    	o.css("display", "block");
	    	    	    	o.find("input").addClass("money");
	    	    	    	$("#unionpayConfig").children(".txFeeConfig").append(o);
	    				}
    					o.children(".fromAmount").val(c[j].fromAmount);
    					o.children(".toAmount").val(c[j].toAmount);
    					o.children(".fromRelation").val(c[j].fromRelation);
    					o.children(".toRelation").val(c[j].toRelation);
    					o.children(".fee").val(c[j].fee);
    					o.children(".type").val(c[j].type);
	    			}
	    		}
                else if (k == "tixian.alipay.feeTips") {
                    v = v.replace("&nbsp;", " ")
                    v = v.replace("<br>", "\n")
                    $("#alipayConfig").find(".feeTips").val(v);
                }
                else if (k == "tixian.alipay.fee") {
                    var c = JSON.parse(v);
                    for (var j=0; j<c.length; j++) {
                        var o;
                        if (j == 0) {
                            o = $("#alipayConfig").find(".txFeeConfigRow").first();
                        }
                        else {
                            var o = $("#txFeeConfigTemplate").clone();
                            o.removeAttr("id");
                            o.css("display", "block");
                            o.find("input").addClass("money");
                            $("#alipayConfig").children(".txFeeConfig").append(o);
                        }
                        o.children(".fromAmount").val(c[j].fromAmount);
                        o.children(".toAmount").val(c[j].toAmount);
                        o.children(".fromRelation").val(c[j].fromRelation);
                        o.children(".toRelation").val(c[j].toRelation);
                        o.children(".fee").val(c[j].fee);
                        o.children(".type").val(c[j].type);
                    }
                }
	    	}
	    }
	    
	    function checkInput() {
	    	$(".money").removeClass("input_error");
	    	$(".count").removeClass("input_error");
	    	
	    	//检查金额
	    	var ar = $(".money");
	    	for (var i=0; i<ar.length; i++) {
	    		var o = ar[i];
	    		if (o.value != '') {
		    		if (!isNumeric(o.value, 8, 2)) {
		    			$(o).addClass("input_error");
		    			alert("请输入正确的金额（最多8位整数2位小数）");
		    			$(o).focus();
		    			return false;
		    		}
	    		}
	    	}

	    	var ar = $(".count");
	    	for (var i=0; i<ar.length; i++) {
	    		var o = ar[i];
	    		if (o.value != '') {
		    		if (!isNumeric(o.value, 8, 2)) {
		    			$(o).addClass("input_error");
		    			alert("请输入正确的整数（最多8位）");
		    			$(o).focus();
		    			return false;
		    		}
	    		}
	    	}
	    	
	    	return true;
	    }
    
        $(function () {
        	setdashboardmenu("系统设置");

            // wysihtml5 plugin on textarea
            $(".wysihtml5").wysihtml5({
                "font-styles": false
            });
            
    	    initDisplay();

            $("#enableMiaoti").bootstrapSwitch({
                onText:"开启",  
                offText:"关闭",
                onSwitchChange:function(event,state){  
                    if(state==true){  
                        $(this).val("1");  
                    }
                    else{  
                        $(this).val("0");  
                    }
                },
            	onInit(event,state) {  
	                if(state==true){  
	                    $(this).val("1");  
	                }
	                else{  
	                    $(this).val("0");  
	                }
	            }
            });
            
    	    $(".addTXFeeConfig").click(function() {
    	    	var o = $("#txFeeConfigTemplate").clone();
    	    	o.removeAttr("id");
    	    	o.css("display", "block");
    	    	o.find("input").addClass("money");
    	    	$(this).parent().parent().append(o);
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