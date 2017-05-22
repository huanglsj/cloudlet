<?php
/**
 * Created by PhpStorm.
 * User: huangyongquan
 * Date: 2017/5/17
 * Time: 10:13
 */

function huiten_daifu_pay($parms){

    $huiten_config = $GLOBALS['huiten_config'];

    $url = 'http://121.42.248.56:8080/pay-platform-api/doQhyHtGateBusiness';//请求地址

    $merNo = $huiten_config['merchantNo'];//

    $signKey = $huiten_config['signKey'];//

    $resMap=array();

    $resMap['requestNo']=$parms['requestNo'];

    $resMap['version']='V1.0';

    $resMap['productId']='0203';

    $resMap['transId']='005';

    $resMap['merNo']=$merNo;

    $resMap['orderDate']=date('Ymd');

    $resMap['orderNo']=$parms['orderNo'];

    $resMap['notifyUrl']='http://www.euamote.me/index.php/Home/Deposit/huiten_notify.html';

    $resMap['dfType']='001';

    $resMap['transAmt']=$parms['transAmt'];

    $resMap['isCompay']='0';

    $resMap['phoneNo']=$parms['phoneNo'];

    $resMap['customerName']=$parms['customerName'];

    $resMap['accBankNo']=$parms['accBankNo'];

    $resMap['accBankName']=$parms['accBankName'];

    $resMap['acctNo']=$parms['acctNo'];

    $resMap['signature']=strtoupper(md5($resMap['requestNo'].$resMap['productId'].$resMap['merNo'].$signKey));



    $data=array();

    $data['reqJson']=json_encode($resMap,256);

    return json_decode(post($url,$data), true);
}