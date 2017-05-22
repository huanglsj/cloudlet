<?php
/**
 * Created by PhpStorm.
 * User: huangyongquan
 * Date: 2017/5/17
 * Time: 10:13
 */

function huiten_scan_pay($orderNo, $amount, $type){

    $huiten_config = $GLOBALS['huiten_config'];

    $url = 'http://121.42.248.56:8080/pay-platform-api/doHuaFuGateBusiness';//请求地址

    $merNo = $huiten_config['merchantNo'];//

    $signKey = $huiten_config['signKey'];//

    $resMap=array();

    $resMap['merchantNo']=$merNo;

    if($type == 'WXGZHZF'){
        $resMap['transId']='005';
        $resMap['returnType']='02';
    }else{
        $resMap['transId']='001';
    }

    $resMap['settleType']='1';

    $resMap['userOrderNo']=$orderNo;

    $resMap['payCode']=$type; //ZFBSMZF	支付宝扫码支付 WXSMZF	微信扫码支付 WXGZHZF	微信公众号支付

    $resMap['orderAmt']=$amount;  //交易金额

    $resMap['orderTitle']='平台充值';

    $resMap['returnUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_return.html';

    $resMap['notifyUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_notify.html';

    $resMap['signature']=strtoupper(md5($resMap['userOrderNo'].$resMap['payCode'].$resMap['merchantNo'].$signKey));

    $data=array();

    $data['reqJson']=json_encode($resMap,256);

    return json_decode(post($url,$data), true);
}