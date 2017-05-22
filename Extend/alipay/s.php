<?php
ini_set('display_errors','1');
error_reporting(E_ALL);
require_once 'aop/AopClient.php';
require_once 'aop/SignData.php';
require_once 'aop/request/AlipayFundTransToaccountTransferRequest.php';	
		//②、统一下单
$aop = new AopClient();
$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2017020705558767';
$aop->rsaPrivateKey = 'MIICXgIBAAKBgQDFNMGfpEajjBBbjpr/Ye3pH0Ir7/DBu+dvzOBibeb/biLV1ipY4qoxpZ1HmXTkSlkxhFm7iAUIugH4fG2awNtf4BRMCPAahJYOVvwQkuct3UNZxwykWq6Z4Mbbhn1tq+QN6RIKnrXLHpju7ebpJYLNQ5sGylWPs/saSrLjoxSRIwIDAQABAoGBAKVzhZ8ybDF6qZ6nHlN1De2wVAwY2p0v0RRzhy4NluhmtsNBU3YPdUsB79rKXM/LsNlieQZlMBQKOu0HN83GUEW+6BIqUqt+jNlRjhX7mbVekOt+6aOwi5/REZHlneYp4L+sr1UJBDM6+9VxcPnJ6D7PPcVfSrzUvutHfXLQivOBAkEA4uWHXNgaGOBZPM8w/cI0ABOIYfrzRLobeOf3IM/vx2Ebi2TVp1i7bMmpVYbTvRVJ/2RKpU9ZprQTkXD+6ibUWwJBAN6ATCEQj/c8d+mbkv6Xuuo7NLveUNQuAHN8P27g8yXZISkNCTL9k7PTSuWiJSZqkR/zcey0u3lG4I19EYpEsNkCQQCmNnB/0grkGE0m37zK194SBD0PUa+ttAv1+S+vCMRlMTzlU1u0OKssGVqDvb4UsKjBfy2zhbpuBVKkY7tJumIrAkAvb4LLedB3kTivYS+Rf5+l2EGB2/+3fKcz98U+Sk8MPqJHMjRH3q/ioqVRF6R78DRIb4pWQKHHVUkCYSugIszhAkEA2qprcOssiWEQgM4ol1uRz902cDgUrOCtiC36vRjP80HVZ5T7uCic7eeM3FfG2v2nWs1jsWT5OUWfGg2Z2J/ecw==';
$aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA';
$aop->postCharset='UTF-8';
$aop->format='json';
$request = new AlipayFundTransToaccountTransferRequest ();
$request->setBizContent("{" .
"    \"out_biz_no\":\"123321123321223QQ\"," .
"    \"payee_type\":\"ALIPAY_LOGONID\"," .
"    \"payee_account\":\"15555888295\"," .
"    \"amount\":\"1.00\"," .
"    \"payer_real_name\":\"西安赛雷云科技有限责任公司\"," .
"    \"payer_show_name\":\"西安赛雷云科技有限责任公司\"," .
"    \"payee_real_name\":\"西安赛雷云科技有限责任公司\"," .
"    \"remark\":\"转账备注\"," .
"    \"ext_param\":\"{\\\"order_title\\\":\\\"提现\\\"}\"" .
"  }");
$result = $aop->execute($request); 
//$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
//$resultCode = $result->$responseNode->code;
//if(!empty($resultCode)&&$resultCode == 10000){
	//做check，更新数据库
//echo "true";
//} else {
//echo "false";
//}
?>