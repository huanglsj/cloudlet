<?php
namespace Common\Event;
use Think\Controller;
use Think\Model;
use Think\Log;

require_once APP_PATH."../Extend/weipay/lib/WxPay.Api.php";
require_once APP_PATH."../Extend/weipay/WxPay.NativePay.php";
require_once APP_PATH."../Extend/weipay/WxPay.JsApiPay.php";
require_once APP_PATH."../Extend/unionpay/sdk/acp_service.php";
require_once APP_PATH."../Extend/unionpay/sdk/SDKConfig.php";
require_once APP_PATH .'Common/Enum/PayQueryResultEnum.php';
require_once APP_PATH .'Common/Enum/PayWayEnum.php';
require_once APP_PATH .'Common/Enum/PayStateEnum.php';
require_once APP_PATH .'Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH .'Common/Enum/BalanceOpTypeEnum.php';


class DepositEvent extends Controller 
{
	public function unionpay($params)
	{
		$payparams = array(
			//以下信息非特殊情况不需要改动
			'version' => '5.0.0',                 //版本号
			'encoding' => 'utf-8',				  //编码方式
			'txnType' => '01',				      //交易类型
			'txnSubType' => '01',				  //交易子类
			'bizType' => '000201',				  //业务类型
			'frontUrl' =>  $params['returnURL'],  //前台通知地址
			'backUrl' => $params['notifyURL'],	  //后台通知地址
			'signMethod' => '01',	              //签名方法
			'channelType' => '08',	              //渠道类型，07-PC，08-手机
			'accessType' => '0',		          //接入类型
			'currencyCode' => '156',	          //交易币种，境内商户固定156
			
			//TODO 以下信息需要填写
			'merId' => \com\unionpay\acp\sdk\SDK_MERCHANT_ID,		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
			'orderId' => $params["orderNo"],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
			'txnTime' => $params["reqTime"],	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
			'txnAmt' => $params["amount"]*100,	//交易金额，单位分，此处默认取demo演示页面传递的参数
	// 		'reqReserved' =>'透传信息',        //请求方保留域，透传字段，查询、通知、对账文件中均会原样出现，如有需要请启用并修改自己希望透传的数据

			//TODO 其他特殊用法请查看 special_use_purchase.php
		);

		Log::debug("unionpay request:".json_encode($payparams));

		\com\unionpay\acp\sdk\AcpService::sign ( $payparams );
		$uri = \com\unionpay\acp\sdk\SDK_FRONT_TRANS_URL;
		$html_form = \com\unionpay\acp\sdk\AcpService::createAutoFormHtml( $payparams, $uri );
		return $html_form;
	}

	public function onPaySuccess($param) {
		Log::debug("DepositEvent::onPaySuccess. param=".json_encode($param));

		$orderNo = $param['orderNo'];
		$paymodel = M('pay');
		$paydata = $paymodel->where("orderNo='".$orderNo. "'")->find();
		if ($paydata === false) {
			Log::dberror("failed to select order. orderNo=".$orderNo, $paymodel);
			return false;
		}
		if ($paydata == null) {
			Log::error('orderNo not exist. orderNo='.$orderNo);
			return false;
		}

		if ($paydata['state'] == \PayStateEnum::START) {
			$utype = $paydata['utype'];
			if ($utype == 1) {  //前端充值
				return $this->onPaySuccess1($paydata, $param);
			}
			else {
				return $this->onPaySuccess2($paydata, $param);
			}
		}
		else {
			Log::notice("renotified after successfully processed");
		}
		return true;
	}

	public function onPayFail($param) {
		Log::debug("DepositEvent::onPayFail");
		$orderNo = $param['orderNo'];

		$paymodel = M("pay");
		$paydata['state'] = \PayStateEnum::FAIL;
		$paydata['endTime'] = time();
		if (isset($param['outTradeNo'])) {
			$paydata['outOrderNo'] = $param['outTradeNo'];
		}
		if (isset($param['errCode'])) {
			$paydata['errCode'] = $param['errCode'];
		}
		if (isset($param['errMsg'])) {
			$paydata['errMsg'] = $param['errMsg'];
		}
		if (isset($param['bankCardNo'])) {
			$paydata['bankCardNo'] = $param['bankCardNo'];
		}
		$result = $paymodel->where("orderNo='".$orderNo."'")->save($paydata);
		if ($result === false) {
			Log::dberror("failed to update wp_pay to fail. id=".$paydata['id'], $paymodel);
			return false;
		}

		if ($result == 0) {
			Log::error('orderNo not exist. orderNo='.$orderNo);
			return false;
		}

		return true;
	}

	//前端客户充值成功
	private function onPaySuccess1(&$paydata, &$param) {
		$payid = $paydata['id'];
		$uid = $paydata['uid'];
		$amount = $paydata['amount'];

		$accmodel = M("accountinfo");
		$accmodel->startTrans(false);
		
		//更新充值状态
		$paynew['state'] = \PayStateEnum::SUCCESS;
		$paynew['endTime'] = time();
		if (isset($param['outTradeNo'])) {
			$paynew['outOrderNo'] = $param['outTradeNo'];
		}
		if (isset($param['bankCardNo'])) {
			$paynew['bankCardNo'] = $param['bankCardNo'];
		}
		$result = M('pay')->where('id='.$payid ." and state=".\PayStateEnum::START)->save($paynew);
		if ($result === false) {
			Log::dberror("failed to update wp_pay. id=".$payid, $accmodel);
			$accmodel->rollback();
			return false;
		}
		if ($result == 0) {
			Log::notice("order state changed by others. uid=%d", $uid);
			$accmodel->commit();
			return true;
		}
			
		//更新账户余额,添加余额记录
		$result = D("Home/Account")->updateBalance($uid, \BalanceOpTypeEnum::CZ, $uid,
							$amount, $paydata['id'], $paydata['orderNo']);
		if ($result == false) {
			$accmodel->rollback();
			return false;
		}

		$accmodel->commit();

        //充值赠送
        $payCount = M('Pay')->where(array('uid'=>$uid, 'state'=>2))->count();
        $aWhere = array();
        $aWhere['status'] = 1;
        if($payCount > 1){
            $aWhere['type'] = 2;
        }else{
            $aWhere['type'] = 1;
        }
        $aWhere['min_money'] = array('elt', $amount);
        $aWhere['max_money'] = array('egt', $amount);

        $aData= M('Activity')->where($aWhere)->find();
        if($aData['give_money']){
            $give_money = $amount * $aData['give_money'];
            D("Home/Account")->updateBalance($uid, \BalanceOpTypeEnum::CZZS, $uid, $give_money, $paydata['id'], $paydata['orderNo']);
        }

		return true;
	}

	//系统用户充值成功
	private function onPaySuccess2(&$paydata, &$param) {
		$payid = $paydata['id'];
		$deptid = $paydata['uid'];
		$amount = $paydata['amount'];

/*
		$dept = M("department")->field('type'where("id=".$deptid)->find();
		if ($dept->type != 2) {
			Log::error("只有会员能够充值");
			return false;
		}
*/

	/*
		$deptdata = M("department")->where('id='.$deptid)-find();
		if ($deptdata === false) {
			Log::dberror("failed to get department.", M("department"));
			return false;
		}
		if ($deptdata === null) {
			Log::error("dept not exist. deptid=".$deptid);
			return false;
		}
	*/

		$compmodel = M("companyinfo");
		$compdata = $compmodel->field('cid, equity')->where('deptid='.$deptid)->find();
		if ($compdata === false) {
			Log::dberror("failed to get company equity", $compmodel);
			return false;
		}
		if ($compdata === null) {
			Log::error("company with deptid=%d not exist!", array($deptid));
			return false;
		}
		$equity = $compdata['equity'];
		$compid = $compdata['cid'];

		$compmodel->startTrans(false);
		
		//更新充值状态
		$paynew['state'] = \PayStateEnum::SUCCESS;
		$paynew['endTime'] = time();
		if (isset($param['outTradeNo'])) {
			$paynew['outOrderNo'] = $param['outTradeNo'];
		}
		if (isset($param['bankCardNo'])) {
			$paynew['bankCardNo'] = $param['bankCardNo'];
		}
		$result = M('pay')->where('id='.$payid ." and state=".\PayStateEnum::START)->save($paynew);
		if ($result === false) {
			Log::dberror("failed to update wp_pay. id=".$payid, $compmodel);
			$compmodel->rollback();
			return false;
		}
		if ($result == 0) {
			Log::notice("order state changed by others. deptid=%d", $deptid);
			$compmodel->commit();
			return true;
		}
			
		//更新会员权益
		$result = D("Admin/Companyinfo")->updateEquity($compid, $amount, 6, $paydata['opUserId'], $payid, $deptid);
		if ($result['result']===false) {
			$compmodel->rollback();
			Log::dberror("failed to inc company equity. compid=".$compid, $compmodel);
			return false;
		}
			
		$compmodel->commit();
		return true;
	}

	public function weipay_query($orderNo)
	{
		Log::debug("DepositEvent::weipay_query");

		$input = new \WxPayOrderQuery();
		$input->SetOut_trade_no($orderNo);
		try {
			$result = \WxPayApi::orderQuery($input);
			Log::debug("weipay_query result:" . json_encode($result));
			
			if ($result == null || !isset($result['return_code'])) {
				Log::error("weipay query error.result=".json_encode($result));
				return array('result'=>\PayQueryResultEnum::QUERY_FAIL, 'errMsg'=>'系统繁忙');
			}
			else if ($result['return_code'] == "SUCCESS") {
				if (isset($result['result_code']) && $result['result_code']=="SUCCESS") {
					if ($result['trade_state'] == 'SUCCESS') {
						Log::debug("weipay success.orderNo=".$orderNo);
						return array('result'=>\PayQueryResultEnum::PAY_SUCCESS, 'outTradeNo' => $result['transaction_id']);
					}
					else if ($result['trade_state'] == "USERPAYING") {
						//用户支付中
						Log::debug("weipay order is processing. orderNo=".$orderNo);
						return array('result'=>\PayQueryResultEnum::PAY_QUERY_AGAIN, 'outTradeNo' => $result['transaction_id']);
					}
					else {
						Log::notice("weipay failed. orderNo=%s,trade_state=%s", array($orderNo, $result['trade_state']));
						return array('result'=>\PayQueryResultEnum::PAY_FAIL,
								     'outTradeNo' => isset($result['transaction_id'])?$result['transaction_id']:null,
								     'errCode'=>$result['trade_state'],
								     'errMsg'=>$result['trade_state_desc']);
					}
				}
				else if ($result['err_code'] == 'NOT_FOUND') {
					Log::warn("weipay order not exist. orderNo=".$orderNo);
					return array('result'=>\PayQueryResultEnum::PAY_NOT_EXIST);
				}
				else {
					Log::error("weipay query error.result=".json_encode($result));
					return array('result'=>\PayQueryResultEnum::QUERY_FAIL,
							'errCode'=>$result['err_code'], 'errMsg'=>$result['err_code_des']);
				}
			}
			else if ($result['return_code'] == "FAIL") {
				Log::error("weipay query error.result=".json_encode($result));
				return array('result'=>\PayQueryResultEnum::QUERY_FAIL, 'errMsg'=>$result['return_msg']);
			}
			else {
				Log::error("weipay query error.result=".json_encode($result));
				return array('result'=>\PayQueryResultEnum::QUERY_FAIL, 'errMsg'=>'系统繁忙');
			}
		}
		catch (\Exception $e) {
			Log::error("weipay query fail:".$e->getMessage());
			return array("result"=>\PayQueryResultEnum::QUERY_FAIL,
					"errCode"=>$e->getCode(), "errMsg"=>$e->getMessage());
		}
		return false;
	}
	
	public function unionpay_query($params, &$errCode, &$errMsg)
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
			'channelType' => '08',	              //渠道类型，07-PC，08-手机
			
			//TODO 以下信息需要填写
			'merId' => \com\unionpay\acp\sdk\SDK_MERCHANT_ID,		//商户代码，请改自己的测试商户号，此处默认取demo演示页面传递的参数
			'orderId' => $params["orderNo"],	//商户订单号，8-32位数字字母，不能含“-”或“_”，此处默认取demo演示页面传递的参数，可以自行定制规则
			'txnTime' => $params["reqTime"],	//订单发送时间，格式为YYYYMMDDhhmmss，取北京时间，此处默认取demo演示页面传递的参数
		);

		Log::debug("unionpay query request:".json_encode($payparams));

		\com\unionpay\acp\sdk\AcpService::sign ( $payparams );
		$url = \com\unionpay\acp\sdk\SDK_SINGLE_QUERY_URL;
		$result_arr = \com\unionpay\acp\sdk\AcpService::post ( $payparams, $url);
		Log::debug("unionpay query response:".json_decode($result_arr));

		if (!isset($result_arr['signature'])) {
			Log::error("no signature in unionpay query response!. response=".json_decode($result_arr));
			return PayQueryResultEnum::QUERY_FAIL;
		}

		if (!\com\unionpay\acp\sdk\AcpService::validate ($result_arr) ){
			Log::error("signature error in unionpay query response!. response=".json_decode($result_arr));
			return PayQueryResultEnum::QUERY_FAIL;
		}

		if ($result_arr["respCode"] == "00"){
			//处理被查询交易的应答码逻辑
			if ($result_arr["origRespCode"] == "00"){
				//交易成功
				return PayQueryResultEnum::PAY_SUCCESS;
			}
			else if ($result_arr["origRespCode"] == "03"
					|| $result_arr["origRespCode"] == "04"
					|| $result_arr["origRespCode"] == "05"){
				//后续需发起交易状态查询交易确定交易状态
				return PayQueryResultEnum::PAY_QUERY_AGAIN;
			}
			else {
				//其他应答码做以失败处理
				Log::error("unionpay failed. orderNo=%s, errCode=%s, errMsg=%s",
				           $result_arr['orderId'], $result_arr['origRespCode'], $result_arr['origRespMsg']);

				$errCode = $result_arr["origRespCode"];
				$errMsg = $result_arr["origRespMsg"];
				return PayQueryResultEnum::PAY_FAIL;
			}
		}
		else if ($result_arr["respCode"] == "34" ){
			//订单不存在
			return PayQueryResultEnum::PAY_NOT_EXIST;
		}
		else {
			//其他应答码做以失败处理
			echo "失败：" . $result_arr["respMsg"] . "。<br>\n";
			return PayQueryResultEnum::QUERY_FAIL;
		}
	}

}
