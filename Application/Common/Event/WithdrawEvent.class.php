<?php
namespace Common\Event;
use Think\Controller;
use Think\Model;
use Think\Log;

require_once APP_PATH."../Extend/alipay/aop/AopClient.php";
require_once APP_PATH."../Extend/alipay/aop/SignData.php";
require_once APP_PATH."../Extend/alipay/aop/request/AlipayFundTransToaccountTransferRequest.php";

require_once APP_PATH."../Extend/weipay/lib/WxPay.Api.php";
require_once APP_PATH."../Extend/weipay/WxPay.NativePay.php";
require_once APP_PATH."../Extend/weipay/WxPay.JsApiPay.php";
require_once APP_PATH."../Extend/unionpay/sdk/acp_service.php";
require_once APP_PATH."../Extend/unionpay/sdk/SDKConfig.php";
require_once APP_PATH .'Common/Enum/PayWayEnum.php';
require_once APP_PATH .'Common/Enum/PayStateEnum.php';
require_once APP_PATH .'Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH .'Common/Enum/PayQueryResultEnum.php';
require_once APP_PATH .'Common/Enum/BalanceOpTypeEnum.php';
require_once APP_PATH .'Common/Common/token.php';
require_once APP_PATH .'../Extend/huiten/config.php';
require_once APP_PATH .'../Extend/huiten/daifu.php';

class WithdrawEvent extends Controller 
{

    //汇腾付款（企业向个人付款）
    public function huitenpay($params, &$msg)
    {
        $resMap = array();

        $resMap['requestNo']=time().rand(1000,9999);

        $resMap['orderNo']=$params['orderNo'];

        $resMap['transAmt']=$params['amount']*100;

        $resMap['phoneNo']=$params['bphone'];

        $resMap['customerName']=$params['busername'];

        $resMap['accBankNo']=$params['bankcode'];

        $resMap['accBankName']=$params['bankname'];

        $resMap['acctNo']=$params['banknumber'];

        $result = huiten_daifu_pay($resMap);
        if($result['respCode'] == 'P000'){
            //做check，更新数据库
            $this->onPaySuccess(array('orderNo'=>$resMap['orderNo'], 'outTradeNo'=>$resMap['requestNo']));
            return true;
        }else{
            $this->onPayFail(array('orderNo'=>$resMap['orderNo'], 'errCode'=>$result['err_code'], 'errMsg'=>$result['err_code_des']));
            Log::error("weixin mchpay error.result=".json_encode($result));
            $msg = $result['err_code_des'];
            return false;
        }
    }

	//微信企业付款（企业向个人付款）
	public function weipay($params, &$msg)
	{
		$orderNo = $params['orderNo'];
		$amount = $params['amount']*100;
		$checkName = $params['checkName'];
		$realUserName = $params['realUserName'];
		$openid = $params['openid'];
		
		//②、统一下单
		$input = new \WxMchPayOrder();
		$input->SetDesc("账户提现");
		$input->SetPartner_trade_no($orderNo);
		$input->SetAmount($amount);
		$input->SetCheck_name($checkName);
		$input->SetRe_user_name($realUserName);
		$input->SetOpenid($openid);
		try {
			$result = \WxPayApi::mchPay($input, 60);
			if ($result == null || !isset($result['return_code'])) {
				$this->onPayFail(array('orderNo'=>$orderNo, 'errMsg'=>"通信异常"));
				Log::error("weixin mchpay error.result=".json_encode($result));
				$msg = "通信异常";
				return false;
			}
			else if ($result['return_code'] == "SUCCESS") {
				if (isset($result['result_code']) && $result['result_code']=="SUCCESS") {
					//做check，更新数据库
					$this->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>$result['payment_no']));
					return true;
				}
				else {
					$this->onPayFail(array('orderNo'=>$orderNo, 'errCode'=>$result['err_code'], 'errMsg'=>$result['err_code_des']));
					Log::error("weixin mchpay error.result=".json_encode($result));
					$msg = $result['err_code_des'];
					return false;
				}
			}
			else if ($result['return_code'] == "FAIL") {
				$this->onPayFail(array('orderNo'=>$orderNo, 'errMsg'=>$result['return_msg']));
				Log::error("weixin mchpay error.result=".json_encode($result));
				$msg = $result['return_msg'];
				return false;
			}
			else {
				$this->onPayFail(array('orderNo'=>$orderNo, 'errMsg'=>'return code不正确'));
				Log::error("weixin mchpay error.result=".json_encode($result));
				$msg = "通信异常";
				return false;
			}
		}
		catch (\Exception $e) {
			Log::error("weixin mchpay exception. ".$e->getMessage());
			$msg = "通信异常";
			return false;
		}
	}

    //手动支付
    public function manualpay($params, &$msg)
    {
        $orderNo = $params['orderNo'];
        $this->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>''));
    }

	//支付宝企业付款（企业向个人付款）
	public function alipay($params, &$msg)
	{
		$orderNo = $params['orderNo'];
		$amount = $params['amount'];
		$checkName = $params['checkName'];
		$realUserName = $params['realUserName'];
		$username = $params['bankCardNo'];

		//②、统一下单
$aop = new \AopClient();
//$aop->gatewayUrl = 'https://openapi.alipaydev.com/gateway.do';
$aop->gatewayUrl = 'https://openapi.alipay.com/gateway.do';
$aop->appId = '2017050807168937';
$aop->rsaPrivateKey = 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIMfs7PjUb8D8ktv9edJICB4jaSR3DVpC4i21U+bxD5/n9dc50fXodGJkcYY/dnu9EjrOQt3zdqvHXflbJ+AkH2x4UP641Khk0wBcsizUP24DF9jYy7Ih601YWYRn3ucJJM63lGL6Vu3+frKnN42YetGlaLkW60c7AV8zIXPylF/AgMBAAECgYAKqPkjFsf+j4OTPnbvZrKF8UcSqgkNDo0xgCu3XSKHMjj8eUEURiORtW10fXOl1BdoFjd9BzBlJvduV+iMzxbwAsMyGCFsdKqs1yE7zEsI+3ZWI9/hfgGJ6jr+h9iUllzzDQwj3wLe/9iaHZrzz/UJJSz/KwQB/EQuQCX6jjOrAQJBAN1UHtfkO2DeaZNNLhxQQZTuXkBaiCmisSzikDh5/LdP5iYLe+3JyATbSLRkHLuyBNzbU1gQstWef6kLF/5zZKcCQQCXqh/62fg1mee5lCp37H5S0EgkSDNoKo/XDvFjLuRsD2tBc3aF97JR48pjn7Pi0IUOt8u0HcOuylOmMex2tc9pAkB+vAd5SggyPMkpfr1TmyUieafgo7ZqWO2pLQa2QCvUb9zylgrdq3hsR4CHQvgtBg/Aw5oiyFUO+1ZQXrjbjAnrAkA5lzppkRd1kymxCJhPzZfybnDWhiwvI+pW6a+zz/yhJAHAas3Y9UPbYLpbtisit7eu7RAHJz5FQ0McWtzF/yfxAkEAw4NqLAeRc9SJ2ZYnEFsMj3EQfNgFTFCMTdDhpZZ6JOx36Pc2Dyt0iCjGMZSiIEKv4RI7YLzGihJHuD91UPemDQ==';
$aop->alipayrsaPublicKey='MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB';
$aop->apiVersion = '1.0';
$aop->signType = 'RSA';
$aop->postCharset='UTF-8';
$aop->format='json';
$request = new \AlipayFundTransToaccountTransferRequest ();
$request->setBizContent("{" .
"    \"out_biz_no\":\"$orderNo\"," .
"    \"payee_type\":\"ALIPAY_LOGONID\"," .
"    \"payee_account\":\"$username\"," .
"    \"amount\":\"$amount\"," .
"    \"payee_real_name\":\"$realUserName\"," .
"    \"remark\":\"转账备注\"," .
"    \"ext_param\":\"{\\\"order_title\\\":\\\"提现\\\"}\"" .
"  }");
$result = $aop->execute($request);
$responseNode = str_replace(".", "_", $request->getApiMethodName()) . "_response";
$resultCode = $result->$responseNode->code;
if($resultCode == 10000){
	//做check，更新数据库
//var_dump($params);
			$this->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>$orderNo));
} else {
//var_dump($params);
			$this->onPayFail(array('orderNo'=>$orderNo, 'errCode'=>$resultCode, 'errMsg'=>$resultCode));
}
	}
	public function unionpay($params, &$msg)
	{
		Log::debug("WithdrawEvent::uninopay,parmas=".json_encode($params));

		$customerInfo = array(
				'certifTp' => '01'
		);
		if (isset($params['idCardNo'])) {
			$customerInfo['certifId'] = $params['idCardNo'];
		}
		if (isset($params['realName'])) {
			$customerInfo['customerNm'] = $params['realName'];
		}

		$orderNo = $params['orderNo'];
		$payparams = array(
				//以下信息非特殊情况不需要改动
				'version' => '5.0.0',		      //版本号
				'encoding' => 'utf-8',		      //编码方式
				'signMethod' => '01',		      //签名方法
				'txnType' => '12',		          //交易类型	
				'txnSubType' => '00',		      //交易子类
				'bizType' => '000401',		      //业务类型
				'accessType' => '0',		      //接入类型
				'channelType' => '08',		      //渠道类型
				'currencyCode' => '156',          //交易币种，境内商户勿改
				'backUrl' => $params['notifyURL'], //后台通知地址	
				'encryptCertId' => \com\unionpay\acp\sdk\AcpService::getEncryptCertId(), //验签证书序列号
				
				//TODO 以下信息需要填写
				'merId' => \com\unionpay\acp\sdk\SDK_MERCHANT_ID,		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
				'orderId' => $params["orderNo"],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
				'txnTime' => $params["reqTime"],	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
				'txnAmt' => $params["amount"]*100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
		// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据

		 		'accNo' => $params['bankCardNo'],     //卡号，旧规范请按此方式填写
		 		'customerInfo' => \com\unionpay\acp\sdk\AcpService::getCustomerInfo($customerInfo), //持卡人身份信息，旧规范请按此方式填写
		//		'accNo' =>  \com\unionpay\acp\sdk\AcpService::encryptData($params['bankCardNo']),     //卡号，新规范请按此方式填写
		//		'customerInfo' => \com\unionpay\acp\sdk\AcpService::getCustomerInfoWithEncrypt($customerInfo), //持卡人身份信息，新规范请按此方式填写
		);

		\com\unionpay\acp\sdk\AcpService::sign ( $payparams ); // 签名
		$url = \com\unionpay\acp\sdk\SDK_BACK_TRANS_URL;
		$result_arr = \com\unionpay\acp\sdk\AcpService::post ( $payparams, $url);
		Log::debug("response from unionpay df:".json_encode($result_arr));
		if(count($result_arr)<=0) { //没收到200应答的情况
			Log::error("error response from unionpay df:".json_encode($result_arr));
			$msg = "银联应答错误";
		    $this->onPayFail(array('orderNo'=>$orderNo, 'errMsg'=>$msg));
			return false;
		}

		if (!\com\unionpay\acp\sdk\AcpService::validate ($result_arr) ){
			$msg = "应答报文验签失败";
		    $this->onPayFail(array('orderNo'=>$orderNo, 'errMsg'=>$msg));
			return false;
		}

		if ($result_arr["respCode"] == "00"){
			//交易已受理，等待接收后台通知更新订单状态，如果通知长时间未收到也可发起交易状态查询
			$msg = "受理成功。";
			return true;
		}
		else if ($result_arr["respCode"] == "03"
				|| $result_arr["respCode"] == "04"
				|| $result_arr["respCode"] == "05" 
				|| $result_arr["respCode"] == "01" 
				|| $result_arr["respCode"] == "12" 
				|| $result_arr["respCode"] == "34" 
				|| $result_arr["respCode"] == "60" ){
			//后续需发起交易状态查询交易确定交易状态
			 $msg = "处理超时，请稍后查询。";
			 return true;
		}
		else {
			//其他应答码做以失败处理
			 $msg = $result_arr["respMsg"];
			 $this->onPayFail(array('orderNo'=>$orderNo, 'errCode'=>$result_arr["respCode"], 'errMsg'=>$result_arr["respMsg"]));
			 return false;
		}
	}

	public function onPaySuccess($params) 
	{
		Log::debug("WithdrawEvent::onPaySuccess");

		$orderNo = $params['orderNo'];
		$paymodel = M('pay');
		$paydata = $paymodel->where("orderNo='".$orderNo. "'")->find();
		if ($paydata === false) {
			Log::dberror("failed to select wp_pay", $paymodel);
			return false;
		}
		if ($paydata == null) {
			Log::error("order not exists! orderNo=".$orderNo);
			return false;
		}
		
		if ($paydata['state'] == \PayStateEnum::START) {
			if ($paydata['utype'] == 1) {
				return $this->onPaySuccess1($paydata, $params);
			}
			else {
				return $this->onPaySuccess2($paydata, $params);
			}
		}
		else {
		}
		return true;
	}


	//客户提现成功
	private function onPaySuccess1(&$paydata, &$params) 
	{
		Log::debug("WithdrawEvent::onPaySuccess1");

		$uid = $paydata['uid'];
		$amount = $paydata['amount'];
		
		//提现成功
		$accmodel = M("accountinfo");
		$accmodel->startTrans(false);
		
		//更新提现状态
		$paynew['state'] = \PayStateEnum::SUCCESS;
		$paynew['endTime'] = time();
		if ($paydata['outOrderNo']==null && isset($params['outTradeNo'])) {
			$paynew['outOrderNo'] = $params['outTradeNo'];
		}
		if (isset($params['bankCardNo'])) {
			$paynew['bankCardNo'] = $params['bankCardNo'];
		}

		//此处的查询条件中加上状态是为了防止状态被别的处理改了之后再次处理
		$result = M('pay')->where('id='.$paydata['id']." and state=".\PayStateEnum::START)->save($paynew);
		if ($result === false) {
			$accmodel->rollback();
			Log::dberror("failed to update wp_pay. id=".$paydata['id'], $accmodel);
			return false;
		}
		
		if ($result == 0) {
			//订单状态已经被改变
			Log::notice("order state changed by others");
			$accmodel->commit();
			return true;
		}
		
		//删除订单查询记录
		$result = M("payquery")->where("orderNo='".$orderNo."'")->delete();
		if ($result === false) {
			$accmodel->rollback();
			Log::dberror("failed to delete payquery. orderNo=".$orderNo, $accmodel);
			return false;
		}

		$accmodel->commit();

		return true;
	}

	//系统人员提现成功
	private function onPaySuccess2(&$paydata, &$params) 
	{
		Log::debug("WithdrawEvent::onPaySuccess2");

		$compmodel = M("companyinfo");
		$compmodel->startTrans(false);
		
		//更新提现状态
		$paynew['state'] = \PayStateEnum::SUCCESS;
		$paynew['endTime'] = time();
		if ($paydata['outOrderNo']==null && isset($params['outTradeNo'])) {
			$paynew['outOrderNo'] = $params['outTradeNo'];
		}
		if (isset($params['bankCardNo'])) {
			$paynew['bankCardNo'] = $params['bankCardNo'];
		}

		//此处的查询条件中加上状态是为了防止状态被别的处理改了之后再次处理
		$result = M("pay")->where('id='.$paydata['id']." and state=".\PayStateEnum::START)->save($paynew);
		if ($result === false) {
			$compmodel->rollback();
			Log::dberror("failed to update wp_pay. id=".$paydata['id'], $compmodel);
			return false;
		}
		
		if ($result == 0) {
			//订单状态已经被改变
			Log::notice("order state changed by others");
			$compmodel->commit();
			return true;
		}
			
		//更新会员权益
		//提现申请时已经扣掉了体现部分的权益，所以此处不更新
		
		//删除订单查询记录
		$result = M("payquery")->where("orderNo='".$orderNo."'")->delete();
		if ($result === false) {
			$compmodel->rollback();
			Log::dberror("failed to delete payquery. orderNo=".$orderNo, $compmodel);
			return false;
		}

		$compmodel->commit();

		return true;
	}

	public function onPayFail($params)
	{
		$orderNo = $params['orderNo'];
		$paymodel = M('pay');
		$paydata = $paymodel->where("orderNo='".$orderNo. "'")->find();
		if ($paydata === false) {
			Log::dberror("failed to get select wp_pay.", $paymodel);
			return false;
		}
		if ($paydata == null) {
			Log::error("order not exists! orderNo=".$orderNo);
			return false;
		}
		Log::debug("data=".json_encode($paydata));

		//订单支付失败
		if ($paydata['state'] == \PayStateEnum::START) {
			if ($paydata['utype'] == 1) {
				$this->onPayFail1($paydata, $params);
			}
			else {
				$this->onPayFail2($paydata, $params);
			}
		}
		
		return true;
	}
	
	private function onPayFail1(&$paydata, &$params)
	{
		$paymodel = M('pay');
		$orderNo = $paydata['orderNo'];

		//订单支付失败
		$paynew['state'] = \PayStateEnum::FAIL;
		$paynew['endTime'] = time();
		if (isset($params['errCode'])) {
			$paynew['errCode'] = $params['errCode'];
		}
		if (isset($params['errMsg'])) {
			$paynew['errMsg'] = $params['errMsg'];
		}
		if ($paydata['outOrderNo'] == null && isset($params['outTradeNo'])) {
			$paynew['outOrderNo'] = $params['outTradeNo'];
		}

		$paymodel->startTrans();
		$result = $paymodel->where('id='.$paydata['id'] ." and state=".\PayStateEnum::START)->save($paynew);
		if ($result === false) {
			Log::dberror("failed to save wp_pay to fail, id=".$paydata['id'], $paymodel);
			$paymodel->rollback();
			return false;
		}
		if ($result == 0) {
			Log::notice("order state changed by others");
			$paymodel->commit();
			return true;
		}

		//更新账户余额
		//支付失败时，将申请时扣减的余额退回，增加余额历史
		$uid = $paydata['uid'];
		$result = D("Home/Account")->updateBalance($uid, \BalanceOpTypeEnum::TXSB, $uid,
				         $paydata['amount'], $paydata['id'], $paydata['orderNo']);
		if ($result === false) {
			$paymodel->rollback();
			return false;
		}
		
		$result = M("payquery")->where("orderNo='".$orderNo."'")->delete();
		if ($result === false) {
			$paymodel->rollback();
			Log::dberror("failed to delete payquery", $paymodel);
			return false;
		}
		$paymodel->commit();
		
		return true;
	}

	private function onPayFail2(&$paydata, &$params)
	{
		$orderNo = $paydata['orderNo'];
		$amount = $paydata['amount'];
		$paymodel = M('pay');

		//订单支付失败
		$paynew['state'] = \PayStateEnum::FAIL;
		$paynew['endTime'] = time();
		if (isset($params['errCode'])) {
			$paynew['errCode'] = $params['errCode'];
		}
		if (isset($params['errMsg'])) {
			$paynew['errMsg'] = $params['errMsg'];
		}
		if ($paydata['outOrderNo'] == null && isset($params['outTradeNo'])) {
			$paynew['outOrderNo'] = $params['outTradeNo'];
		}

		$paymodel->startTrans();
		$result = $paymodel->where('id='.$paydata['id'] ." and state=".\PayStateEnum::START)->save($paynew);
		if ($result === false) {
			$paymodel->rollback();
			Log::dberror("failed to save wp_pay to fail, id=".$paydata['id'], $paymodel);
			return false;
		}
		if ($result == 0) {
			$paymodel->commit();
			Log::notice("order state changed by others");
			return true;
		}

		//更新会员权益
		//因为提现申请时，扣了该部分金额，所以失败时应该加回来
		$compmodel = M("companyinfo");
		$deptid = $paydata['uid'];
		$compdata = $compmodel->field('cid, equity')->where('deptid='.$deptid)->find();
		if ($compdata === false) {
			$paymodel->rollback();
			Log::dberror("failed to get company equity", $compmodel);
			return false;
		}
		if ($compdata === null) {
			$paymodel->rollback();
			Log::error("company with deptid=%d not exist!", array($deptid));
			return false;
		}
		$compid = $compdata['cid'];
		$result = D("Admin/Companyinfo")->updateEquity($compid, $amount, 4, $paydata['opUserId'], $paydata['id'], $deptid);
		if ($result['result']===false) {
			$paymodel->rollback();
			Log::dberror("failed to inc company equity. compid=".$compid, $compmodel);
			return false;
		}
		
		$result = M("payquery")->where("orderNo='".$orderNo."'")->delete();
		if ($result === false) {
			Log::dberror("failed to delete payquery", $paymodel);
			$paymodel->rollback();
			return false;
		}
		$paymodel->commit();
		
		return true;
	}

	/**
	 * 微信企业付款查询
	 * @param unknown $orderNo
	 */
	public function weipay_query($orderNo)
	{
		$input = new \WxMchPayOrderQuery();
		$input->SetPartner_trade_no($orderNo);
		try {
			$result = \WxPayApi::mchOrderQuery($input);
			Log::debug("weipay_query result:" . json_encode($result));
			
			if ($result == null || !isset($result['return_code'])) {
				Log::error("weixin mchpay query error.result=".json_encode($result));
				return array('result'=>\PayQueryResultEnum::QUERY_FAIL, 'errMsg'=>'系统繁忙');
			}
			else if ($result['return_code'] == "SUCCESS") {
				if (isset($result['result_code']) && $result['result_code']=="SUCCESS") {
					if ($result['status'] == 'SUCCESS') {
						Log::debug("weixin mchpay success.orderNo=".$orderNo);
						return array('result'=>\PayQueryResultEnum::PAY_SUCCESS, 'outTradeNo' => $result['detail_id']);
					}
					else if ($result['status'] == 'FAILED') {
						Log::notice("weixin mchpay failed. orderNo=%s,reason=%s", array($oderNo, $result['reason']));
						return array('result'=>\PayQueryResultEnum::PAY_FAIL,
								     'outTradeNo' => $result['detail_id'],
								     'errMsg'=>$result['reason']);
					}
					else if ($result['status'] == 'PROCESSING') {
						Log::debug("weixin mchpay order is processing. orderNo=".$orderNo);
						return array('result'=>\PayQueryResultEnum::PAY_QUERY_AGAIN, 'outTradeNo' => $result['detail_id']);
					}
					else {
						Log::error("invalid status from weixin mchpay query.orderNo=%s,status=%s", $orderNo, $result['status']);
						return array('result'=>\PayQueryResultEnum::QUERY_FAIL,
								     'outTradeNo' => $result['detail_id'],
								     'errMsg'=>'微信返回异常消息');
					}
				}
				else if ($result['err_code'] == 'NOT_FOUND') {
					Log::warn("weixin mchpay order not exist. orderNo=".$orderNo);
					return array('result'=>\PayQueryResultEnum::PAY_NOT_EXIST);
				}
				else {
					Log::error("weixin mchpay query error.result=".json_encode($result));
					return array('result'=>\PayQueryResultEnum::QUERY_FAIL,
							     'errCode'=>$result['err_code'], 'errMsg'=>$result['err_code_des']);
				}
			}
			else if ($result['return_code'] == "FAIL") {
				Log::error("weixin mchpay query error.result=".json_encode($result));
				return array('result'=>\PayQueryResultEnum::QUERY_FAIL, 'errMsg'=>$result['return_msg']);
			}
			else {
				Log::error("weixin mchpay query error.result=".json_encode($result));
				return array('result'=>\PayQueryResultEnum::QUERY_FAIL, 'errMsg'=>'系统繁忙');
			}
		}
		catch (\Exception $e) {
			Log::error("weixin mchpay query fail:".$e->getMessage());
			return array("result"=>\PayQueryResultEnum::QUERY_FAIL,
					     "errCode"=>$e->getCode(), "errMsg"=>$e->getMessage());
		}
		return false;
	}

	/**
	 * 
	 * @param array $params
	 * @return array ('result'=>查询结果, 'errCode'=>'xxx', 'errMsg'=>'xxxxx')
	 */
	public function unionpay_query($params)
	{
		$payparams = array(
			//以下信息非特殊情况不需要改动
			'version' => '5.0.0',                 //版本号
			'encoding' => 'utf-8',				  //编码方式
			'signMethod' => '01',	              //签名方法
			'txnType' => '00',				      //交易类型
			'txnSubType' => '00',				  //交易子类
			'bizType' => '000000',				  //业务类型
			'accessType' => '0',		          //接入类型
			'channelType' => '07',	              //渠道类型，07-PC，08-手机
			
			//TODO 以下信息需要填写
			'merId' => \com\unionpay\acp\sdk\SDK_MERCHANT_ID,		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
			'orderId' => $params["orderNo"],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
			'txnTime' => $params["reqTime"],	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
		);

		Log::debug("unionpay query request:".json_encode($payparams));

		\com\unionpay\acp\sdk\AcpService::sign ( $payparams );
		$url = \com\unionpay\acp\sdk\SDK_SINGLE_QUERY_URL;
		$result_arr = \com\unionpay\acp\sdk\AcpService::post ( $payparams, $url);
		Log::debug("unionpay query response:".json_encode($result_arr));

		if (!isset($result_arr['signature'])) {
			Log::error("no signature in unionpay query response!. response=".json_encode($result_arr));
			return array('result'=>\PayQueryResultEnum::QUERY_FAIL);
		}

		if (!\com\unionpay\acp\sdk\AcpService::validate ($result_arr) ){
			Log::error("signature error in unionpay query response!. response=".json_encode($result_arr));
			return array('result'=>\PayQueryResultEnum::QUERY_FAIL);
		}

		if (isset($result_arr['queryId'])) {
			$outTradeNo = $result_arr['queryId'];
		}
		else {
			$outTradeNo = null;
		}
		
		if ($result_arr["respCode"] == "00"){
			//处理被查询交易的应答码逻辑
			if ($result_arr["origRespCode"] == "00"){
				//交易成功
				return array('result'=>\PayQueryResultEnum::PAY_SUCCESS, 'outTradeNo'=>$outTradeNo);
			}
			else if ($result_arr["origRespCode"] == "03"
					|| $result_arr["origRespCode"] == "04"
					|| $result_arr["origRespCode"] == "05"){
				//后续需发起交易状态查询交易确定交易状态
				return array('result'=>\PayQueryResultEnum::PAY_QUERY_AGAIN, 'outTradeNo'=>$outTradeNo);
			}
			else {
				//其他应答码做以失败处理
				Log::error("unionpay failed. orderNo=%s, errCode=%s, errMsg=%s",
				           array($params['orderNo'], $result_arr['origRespCode'], $result_arr['origRespMsg']));
				return array('result' => \PayQueryResultEnum::PAY_FAIL,
						     'outTradeNo'=>$outTradeNo,
				             'errCode' => $result_arr["origRespCode"],
							 'errMsg' => $result_arr["origRespMsg"]);
			}
		}
		else if ($result_arr["respCode"] == "34" ){
			//订单不存在
			Log::notice("unionpay trade not exist. oid=".$params['orderNo']);
			return array('result'=>\PayQueryResultEnum::PAY_NOT_EXIST);
		}
		else {
			//其他应答码做以失败处理
			Log::error("unionpay query fail. oid=".$params['orderNo']);
			return array('result'=>\PayQueryResultEnum::QUERY_FAIL);
		}
	}
}
