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

//    $resMap['returnUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_return.html';

//    $resMap['notifyUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_notify.html';
    $resMap['returnUrl']='http://demo.euamote.me/index.php/Home/Deposit/huiten_return.html';

    $resMap['notifyUrl']='http://demo.euamote.me/index.php/Home/Deposit/huiten_notify.html';

    $resMap['signature']=strtoupper(md5($resMap['userOrderNo'].$resMap['payCode'].$resMap['merchantNo'].$signKey));

    $data=array();

    $data['reqJson']=json_encode($resMap,256);

    return json_decode(post($url,$data), true);
}

function huiTen_new_pay($orderNo, $amount, $type){
    $huiten_config = $GLOBALS['huiten_config'];
    $url = 'http://121.42.248.56:8080/pay-platform-api/doQhyHtGateBusiness';//请求地址
    $merNo = $huiten_config['merchantNo'];//商户号
    $signKey = $huiten_config['signKey'];//密匙
    $requestNo = $orderNo; //交易请求流水号
    $productId = $type;  //0108微信扫码 0113微信刷卡 0119支付宝扫码 0120支付宝刷卡 0111 银联
    $signData = $requestNo . $productId . $merNo . $signKey;
    $sign=md5($signData);
    $resMap=array();
    $resMap['requestNo']=$requestNo;
    $resMap['productId']=$productId;
    $resMap['transId']='001';
    $resMap['merNo']=$merNo;
    $resMap['settleType'] = '1';
    $resMap['clientIp'] = '47.93.56.86';
    $resMap['orderDate']=date('Ymd');
    $resMap['orderNo']=$orderNo;  //商户订单号
    $resMap['transAmt']=strval($amount*100);  //金额（单位分）
//    $resMap['returnUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_return.html';
//    $resMap['notifyUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_notify_new.html';
    $resMap['returnUrl']='http://demo.euamote.me/index.php/Home/Deposit/huiten_return.html';

    $resMap['notifyUrl']='http://demo.euamote.me/index.php/Home/Deposit/huiten_notify_new.html';
    $resMap['commodityName']='充值';
    $resMap['signature']=$sign;

    $reqStr='reqJson='.json_encode($resMap,256);
    $return=file_get_contents($url.'?'.$reqStr);

    return json_decode($return);
}