<?php
/* *
 * 功能：支付宝手机网站支付接口(alipay.trade.wap.pay)接口调试入口页面
 * 版本：2.0
 * 修改日期：2016-11-01
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 请确保项目文件有可写权限，不然打印不了日志。
 */

require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'service/AlipayTradeService.php';
require_once dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'buildermodel/AlipayTradePrecreateContentBuilder.php';
require dirname ( __FILE__ ).DIRECTORY_SEPARATOR.'./../config.php';

header("Content-type: text/html; charset=utf-8");

$orderNo = $_POST['orderNo'];
$amount = $_POST['amount'];
$notifyURL = $_POST['notifyURL'];
if (!$orderNo) {
	return;
}
if (!$amount) {
	return;
}
if (!$notifyURL) {
	return;
}

$payRequestBuilder = new AlipayTradePrecreateContentBuilder();
//$payRequestBuilder->setBody("content");
$payRequestBuilder->setSubject("账户充值");
$payRequestBuilder->setOutTradeNo($orderNo);
$payRequestBuilder->setTotalAmount($amount);
$payRequestBuilder->setTimeExpress('1m');

$payResponse = new AlipayTradeService($alipay_config);
$result=$payResponse->qrPay($payRequestBuilder, $notifyURL);
//var_dump($result);
echo json_encode($result);
