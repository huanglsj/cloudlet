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
       
<link rel="stylesheet" href="/Public/Home/css/global.css">
<link rel="stylesheet" href="/Public/Home/css/index.css">
<script id="G--xyscore-load" type="text/javascript" charset="utf-8" async="" src="/Public/Home/js/xyscore_main.js"></script>

<div class="wrap">
  <div class="index" style="min-height: 1782px; height: 1752px;">
    <header class="list-head">
      <nav class="list-nav clearfix"> <a href="javascript:history.go(-1)" class="list-back"></a>
        <h3 class="list-title">订单详情</h3>
      </nav>
    </header>
    <div class="news-list2 clearfix">
      <p><span class="l_l3">流水号：</span><span class="l_l"><?php echo ($order["orderno"]); ?></span></p>
      <p><span class="l_l3">类型：</span><span class="l_l"><?php if($order[ostyle] == 1): ?>买跌<?php else: ?>买涨<?php endif; ?></span></p>
      <p><span class="l_l3">入仓价：</span><span class="l_l"><?php echo ($order["buyprice"]); ?></span></p>
      <p><span class="l_l3">平仓价：</span><span class="l_l"><?php echo ($order["sellprice"]); ?></span></p>
      <p><span class="l_l3">时间：</span><span class="l_l"><?php echo (date('Y-m-d H:i:s',$order["buytime"])); ?></span></p>
      <p><span class="l_l3">收支：</span>
        <?php if($order["ploss"] > 0): ?><span style="color: #ff7c80;"><?php echo ($order["ploss"]); ?></span>
        <?php else: ?>
          <span class="l_l2"><?php echo ($order["ploss"]); ?></span><?php endif; ?>
      </p>
    </div>
</div>
</div>
<script src="/Public/Home/js/jquery-2.1.1.min.js"></script>
<script src="/Public/Home/js/script.js"></script>
<script type="text/javascript" charset="utf-8" src="/Public/Home/js/sea.js" async=""></script>

</div>
</body>
</html>