<?php
use jaq\Request\V20161123 as Jaq;

require 'ThinkPHP/Library/Vendor/alidayu/TopSdk.php';
vendor('aliyun-php-sdk-core.Config');
require_once APP_PATH .'/Common/Jaq/Request/V20161123/AfsCheckRequest.php';

function sentVerifySms($phone){
    $code = generate_code();
    $resp = sentAlidayuSms($phone, array('number'=>$code), C('DAYU_SMS_VERIFY_TEMPLATE'));
    //$resp = sendSMS($phone,"【华泰云商城】验证码：$code");
    if($resp){
        insert_verify_code($phone,$code);
        return true;
    }else{
        return false;
    }
    
}

function sentVerifySmsByAdmin($phone){
    $code = generate_code();
    $resp = sentAlidayuSms($phone, array('number'=>$code), C('DAYU_SMS_VERIFY_TEMPLATE'));
    //$resp = sendSMS($phone,"【华泰云商城】验证码：$code");
    if($resp){
        insert_verify_code($phone,$code);
        return true;
    }else{
        return false;
    }

}

function sendSMS($phone,$msg){
$post_data = array();
$post_data['userid'] =6048;
$post_data['account'] = 'new8888';
$post_data['password'] = 'zzzz1111';
$post_data['content'] =$msg; 
$post_data['mobile'] =$phone;
$post_data['sendtime'] = ''; //不定时发送，值为0，定时发送，输入格式YYYYMMDDHHmmss的日期值
$url='http://121.43.192.197:8888/sms.aspx?action=send';
$o='';
foreach ($post_data as $k=>$v)
{
   $o.="$k=".urlencode($v).'&';
}
$post_data=substr($o,0,-1);
$ch = curl_init();
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_HEADER, "Content-Type:text/html;charset=utf-8");
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //如果需要将结果直接返回到变量里，那加上这句。
$result = curl_exec($ch);
return $result ;
}

function sentPassword($phone,$displayname,$username,$password){
    //$code = generate_code();
    $resp = sentAlidayuSms($phone, array('name'=>$displayname, 'username'=>$username, 'password'=>$password), C('DAYU_SMS_SENT_PASSWORD'));
    //$resp =sendSMS($phone,"【华泰云商城】尊敬的$displayname,已经为您开通了账户($username),请使用账户和密码 $password 登录");
    if($resp){
        return true;
    }else{
        return false;
    }
}

function resetPassword($phone,$displayname,$password){
    $resp = sentAlidayuSms($phone, array('password'=>$password), C('DAYU_SMS_RESET_PASSWORD'));
    //$resp =sendSMS($phone,"【华泰云商城】 重置密码成功!您当前的密码为：$password");
    if($resp){
        return true;
    }else{
        return false;
    }
}

function sentAlidayuSms($phone,$params,$template){
    if(!isTelNumber($phone)){
        return false;
    }
    $c = new TopClient;
    $c->appkey = C('DAYU_SMS_APPKEY');
    $c->secretKey = C('DAYU_SMS_APPSECRET');
    $req = new AlibabaAliqinFcSmsNumSendRequest;
    $req->setExtend(C('DAYU_SMS_EXTEND'));
    $req->setSmsType("normal");
    $req->setSmsFreeSignName(C('DAYU_SMS_SIGNNAME'));
    $params = json_encode($params);
    $req->setSmsParam($params);
    $req->setRecNum($phone);
    $req->setSmsTemplateCode($template);
    $resp = $c->execute($req);
    if($resp){
        return true;
    }else{
        return false;
    }

}

function isTelNumber($phone) {
    if (strlen ( $phone ) != 11 || ! preg_match ( '/^1[3|4|5|7|8][0-9]\d{4,8}$/', $phone )) {
        return false;
    } else {
        return true;
    }
}

function generate_code($length = 6) {
   return strval(rand(pow(10,($length-1)), pow(10,$length)-1));
}

function insert_verify_code($tel = null,$code = null){
    //流水号
    $telcode = M('telcode');
    $data['tel'] = $tel;
    $data['code'] = $code;
    $data['time'] = time();
    $result = $telcode->data($data)->add();
}

function checkCode($phone,$code){
    $telcode = D('telcode')->where('tel='.$phone)->order('time desc')->find();
    if($telcode['code'] == $code && time() - $telcode['time'] <= 300){
        return true;
    }else{
        return false;
    }
}

function slipVerify(){
    //滑块动作验证
    $aliyunAccess = C('ALIYUN_ACCESS_KEY');
    $iClientProfile = \DefaultProfile::getProfile("cn-hangzhou", $aliyunAccess['ID'], $aliyunAccess['SECRET']);
    $client = new \DefaultAcsClient($iClientProfile);
    $request = new Jaq\AfsCheckRequest();
    if(I('post.csessionid')<>''){
        $request->setSession(I('post.csessionid'));// 必填参数，从前端获取，不可更改
    }else{
        return false;
    }
    if(I('post.sig')<>''){
        $request->setSig(I('post.sig'));// 必填参数，从前端获取，不可更改
    }else{
        return false;
    }
    if(I('post.token')<>''){
        $request->setToken(I('post.token'));// 必填参数，从前端获取，不可更改
    }else{
        return false;
    }
    if(I('post.scene')<>''){
        $request->setScene(I('post.scene'));// 必填参数，从前端获取，不可更改
    }else{
        return false;
    }
    $request->setPlatform(3);//必填参数，请求来源： 1：Android端； 2：iOS端； 3：PC端及其他
    $response = $client->doAction($request);
    $response = json_decode($response->getBody(),true);
    if($response['Data'] && $response['ErrorCode'] == 0){
        return true;
    }else{
        return false;
    }
}
?>