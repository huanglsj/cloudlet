<?php
require_once("config.php");
require_once 'wappay/service/AlipayTradeService.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$arr=$_POST;
}
else {
	$arr=$_GET;
}
$alipaySevice = new AlipayTradeService($alipay_config); 
$result = $alipaySevice->check($arr);
echo $result;
if($result) {
	//验证成功
	echo "success";		//请不要修改或删除
}
else {
    //验证失败
    echo "fail";	//请不要修改或删除
}

?>

