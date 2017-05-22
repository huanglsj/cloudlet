<?php

require_once 'aop/AopClient.php';
require_once 'aop/request/AlipayFundTransToaccountTransferRequest.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$params=$_POST;
}
else {
	$params=$_GET;
}
	$orderNo = $params['orderNo'];
		$amount = $params['amount'];
		$checkName = $params['checkName'];
		$realUserName = $params['realUserName'];
		$username = $params['bankCardNo'];		
		//②、统一下单
$aop = new AopClient();
$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2017040106519447';
$aop->rsaPrivateKey = 'MIICXgIBAAKBgQCgL6PVu+/XRywq76i3LofyrdHE7OW83GCMG6JPvflOotQb1eMqIFOLIrpM/aBXbC9PwR3EFkDpPn0V2GlTbFrkm80oza4CcXx1EAovyKYj/wyEqXwyt5x1yZnnz2VCw39usgA08WZbrerKod+xvIX3i+zjZOd+zDmBdVe/CTsb9wIDAQABAoGBAJVvvCUBvd8Uu6SWjcvOce84rxBKfIxy/vLWONaxgKoWh1Asek7rd2S+khSPK41DFOQPYchzhjzSGflOOfwkgql2azswwkRlNJt5SVkleY0sxBN2L0VcceUjOV93QLt4KRXT0UxquuP+NwZ3v0BHgtk9yGjLmPFOjf9pABji7/dRAkEA03sAE237XmDZYI/EfaAhr86rVZ4QbbyuT/2a552nChtH0wK85AA+E03mKbTX1jkcIh0/fuV+oyax7NUVfLLkSQJBAMHoTw6ZU7TYldYfV4nnU6tXfYKRhePk8IaRVvTIxgPg+uf0T4QhGGctblmaJoan94taFBtC75GZ2O3liwQRfj8CQCyfE1EGL7emL7eUHh1zoCf8L2KNS2IcIZt3Iywjs48KWolHVpu1AWQJV7sNEU1+F1WLXk5kFehOzdPu0e3RwSECQQCEkpbSjWPchiPTVHReNYlP4S5Pgbz/tbv41Q+VplsztbL1uWRQGux7RmVm32ytrdMiNEEA7n+qlXvqEoE8E9WzAkEAzv6RcW4D8L207jgQDI0ya+7rKMA9g/+EJDArHPabUwOYayV2A+/BzmcaZ8SSg5VBMEZcIHSnVUjTyZUA21CoJw==';
$aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCgL6PVu+/XRywq76i3LofyrdHE7OW83GCMG6JPvflOotQb1eMqIFOLIrpM/aBXbC9PwR3EFkDpPn0V2GlTbFrkm80oza4CcXx1EAovyKYj/wyEqXwyt5x1yZnnz2VCw39usgA08WZbrerKod+xvIX3i+zjZOd+zDmBdVe/CTsb9wIDAQAB';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA';
$aop->postCharset='UTF-8';
$aop->format='json';
$request = new AlipayFundTransToaccountTransferRequest ();
$request->setBizContent("{" .
"    \"out_biz_no\":\"$orderNo\"," .
"    \"payee_type\":\"ALIPAY_LOGONID\"," .
"    \"payee_account\":\"$username\"," .
"    \"amount\":\"$amount\"," .
"    \"payer_real_name\":\"西安赛雷云科技有限责任公司\"," .
"    \"payer_show_name\":\"西安赛雷云科技有限责任公司\"," .
"    \"payee_real_name\":\"$realUserName\"," .
"    \"remark\":\"转账备注\"," .
"    \"ext_param\":\"{\\\"order_title\\\":\\\"提现\\\"}\"" .
"  }");
$result = $aop->execute($request); 
$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
$resultCode = $result->$responseNode->code;
if(!empty($resultCode)&&$resultCode == 10000){
	//做check，更新数据库
echo "true";
} else {
echo "false";
}
?>