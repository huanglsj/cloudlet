<?php

/**

 * Created by PhpStorm.

 * User: Administrator

 * Date: 2017/5/17

 * Time: 22:40

 */



date_default_timezone_set('PRC');



$huiten_config = array (

    'merchantNo' => "820170511110300",

    'signKey' => "CEF82618F9D88EEBF3C26844A45391BD",

);



$GLOBALS['huiten_config'] = $huiten_config;



$huiten_config = $GLOBALS['huiten_config'];





$url = 'http://121.42.248.56:8080/pay-platform-api/doQhyHtGateBusiness';//请求地址

//http://118.190.114.127:8080/pay-platform-api/payGate

$merNo = $huiten_config['merchantNo'];//

$signKey = $huiten_config['signKey'];//

$resMap=array();

$resMap['requestNo']=time().rand(1000,9999);

$resMap['version']='V1.0';

$resMap['productId']='0203';

$resMap['transId']='005';

$resMap['merNo']=$merNo;

$resMap['orderDate']=date('Ymd');

$resMap['orderNo']='order'.time();

$resMap['notifyUrl']='http://www.euamote.me/ts.php';

$resMap['dfType']='001';

$resMap['transAmt']='1';

$resMap['isCompay']='0';

$resMap['phoneNo']='15992108558';

$resMap['customerName']='黄永泉';

$resMap['accBankNo']='102581001395';

$resMap['accBankName']='中国工商银行广州天河工业园支行';

$resMap['acctNo']='6212263602017835950';

$resMap['signature']=strtoupper(md5($resMap['requestNo'].$resMap['productId'].$resMap['merNo'].$signKey));



$data=array();

$data['reqJson']=json_encode($resMap,256);

echo $data['reqJson'];

//var_dump($data['reqJson']) ;exit;

return json_decode(post($url,$data), true);



function post($url, $post_data = array(), $timeout = 10){

    if (!is_array($post_data)) {

        return false;

    }



    $user_agent = $_SERVER['HTTP_USER_AGENT'];



    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

    curl_setopt($ch, CURLOPT_HEADER, false);

    curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

    $file_contents = curl_exec($ch);

    curl_close($ch);

    var_dump($file_contents);

    return $file_contents;

}