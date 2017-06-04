<?php
/**
 * Created by PhpStorm.
 * User: huangyongquan
 * Date: 2017/5/17
 * Time: 10:13
 */
function huiTen_new_pay($orderNo, $amount, $type){
    $url = 'http://121.42.248.56:8080 /pay-platform-api/doQhyHtGateBusiness';//请求地址
    $merNo = "820170511110300";//商户号
    $signKey = "CEF82618F9D88EEBF3C26844A45391BD";//密匙
    $requestNo = $orderNo; //交易请求流水号
    $productId = $type;  //0108微信扫码 0113微信刷卡 0119支付宝扫码 0120支付宝刷卡 0111 银联
    $signData = $requestNo . $productId . $merNo . $signKey;
    $sign=md5($signData);
    $resMap=array();
    $resMap['requestNo']=$requestNo;
    $resMap['productId']=$productId;
    $resMap['transId']='006';
    $resMap['merNo']=$merNo;
    $resMap['settleType'] = '1';
    $resMap['clientIp'] = '47.93.56.86';
    $resMap['orderDate']=date('Ymd');
//    $resMap['phoneNo'] = '18028579013';
    $resMap['orderNo']=$orderNo;  //商户订单号
    $resMap['transAmt']=strval($amount);  //金额（单位分）
    $resMap['returnUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_return.html';
    $resMap['notifyUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_notify.html';
    $resMap['commodityName']='充值';
    $resMap['signature']=$sign;

    $reqStr='reqJson='.json_encode($resMap,256);
    $return=file_get_contents($url.'?'.$reqStr);

    return json_decode($return);
}