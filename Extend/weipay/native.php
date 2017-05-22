<?php

//america
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";

//模式二
/**
 * 流程：
 * 1、调用统一下单，取得code_url，生成二维码
 * 2、用户扫描二维码，进行支付
 * 3、支付完成之后，微信服务器会通知支付成功
 * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
 *
 */
  
$product_id = $_REQUEST['orderNo'];
$trade_no = $_REQUEST['orderNo'];
$amount = $_REQUEST['amount']*100;

$input = new WxPayUnifiedOrder();
$input->SetBody('账户充值');
//$input->SetAttach($attach);
$input->SetOut_trade_no($trade_no);
$input->SetTotal_fee($amount);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
//$input->SetGoods_tag($tag);
//$input->SetNotify_url("http://cms1.tortoisecloud.com/index.php/Home/Weipay/payNotify.html");
$input->SetNotify_url('http://'.$_SERVER['SERVER_NAME'].$GLOBALS['_root']."/index.php/Home/Weipay/payNotify.html");
$input->SetTrade_type("NATIVE");
$input->SetProduct_id($product_id);

$notify = new NativePay();
try {
	$result = $notify->GetPayUrl($input);
	if (isset($result["code_url"])) {
		$url = $result["code_url"];
		echo json_encode(array('success'=>1, 'url'=>$url));
	}
	else {
		echo json_encode(array('success'=>0, 'msg'=>'获取URL失败'));
	}
}
catch(\Exception $e) {
	echo json_encode(array('success'=>0, 'msg'=>$e->getMessage()));
}
?>

