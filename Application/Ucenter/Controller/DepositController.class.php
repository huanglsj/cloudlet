<?php
namespace Ucenter\Controller;
use Ucenter\Controller\CommonController;
use Think\Log;
use Think\Controller;
require_once APP_PATH."../Extend/weipay/lib/WxPay.Api.php";
require_once APP_PATH."../Extend/weipay/WxPay.NativePay.php";
require_once APP_PATH."../Extend/unionpay/sdk/acp_service.php";
require_once APP_PATH .'Common/Enum/PayWayEnum.php';
require_once APP_PATH .'Common/Enum/PayQueryResultEnum.php';
require_once APP_PATH .'Common/Enum/PayStateEnum.php';
require_once APP_PATH .'Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH .'../Extend/alipay/config.php';
require_once APP_PATH .'Common/Common/token.php';


class DepositController extends Controller 
{
    private function userlogin()
    {
        //判断用户是否已经登录
        if (!isset($_SESSION['cuid'])) {
            $this->redirect('User/login');
        }
        return $_SESSION['cuid'];
    }

	public function doDeposit()
	{
		$uid = $this->userlogin();
		
		//取得该用户的会员部门id
		$deptid = M("systemuser")->where("id=".$uid)->getField("deptid");
		if ($deptid === false) {
			Log::dberror("failed to get user's deptid. uid=".$uid, M(""));
			$this->error("系统繁忙！", 2);
		}
		
		if ($deptid == null) {
			Log::error("user's dept not exist. uid=".$uid);
			$this->error("系统繁忙！", 2);
		}
		
		$now = time();
		$amount = $_POST['amount'];
		$payway = $_POST['payway'];
		$this->assign('amount', $amount);
		$this->assign('payway', $payway);
		if ($payway == "weixin") {
			$payway = \PayWayEnum::WEIXIN_QRCODE;
		}
		else if ($payway == "alipay") {
			$payway = \PayWayEnum::ALIPAY_QRCODE;
		}
		else if ($payway == "unionpay") {
			$payway = \PayWayEnum::UNIONPAY;
		}
		else {
			$this->error("请选择支付方式！", 2);
			return;
		}
		 
		$pay['type'] = 1;
		$pay['amount'] = $amount;
		$pay['uid'] = $deptid;
		$pay['utype'] = 2;
		$pay['state'] = \PayStateEnum::START;
		$pay['beginTime'] = $now;
		$pay['orderNo'] = createOrderNo('CZ');
		$pay['payWay'] = $payway;
		$pay['checkState'] = \PayCheckStateEnum::AUTO_SUCCESS;
		$pay['checkUserId'] = 0;
		$pay['checkTime'] = $now;
		$pay['payReqTime'] = date("YmdHis", $now);
		$pay['opUserId'] = $uid;
		$pay['opDate'] = date('Ymd', $now);
		$result = M('pay')->add($pay);
		if ($result === false) {
			Log::dberror("failed to add wp_pay", M('pay'));
			$this->error("系统繁忙！", 2);
		}
	
		if ($payway == \PayWayEnum::WEIXIN_QRCODE) {
			$this->weipay_qrcode($pay['orderNo'], $amount);
		}
		else if ($payway == \PayWayEnum::ALIPAY_QRCODE) {
			$this->alipay_qrcode($pay['orderNo'], $amount);
		}
		else if ($payway == \PayWayEnum::UNIONPAY) {
			Log::debug("111");
			$param = array('orderNo'=>$pay['orderNo'], 'amount'=>$amount,
			               "reqTime"=>$pay['payReqTime'],
			               "notifyURL"=>U('unionpay_notify', '', true, true),
						   "returnURL"=>U('Index/index', '', true, true));
			$action = A("Common/Deposit", "Event");
			$html = $action->unionpay($param);
			Log::debug("html.".$html);
			header('Content-type:text/html;charset=utf-8');
			echo $html;
		}
	}
		
	public function alipay_qrcode($orderNo, $amount) {
		$param = array("orderNo"=> $orderNo, "amount"=>$amount, "notifyURL"=>U("alipay_notify", C('DOMAIN').'/index.php/ucenter/deposit/alipay_notify.html', true, true));
		$url = 'http://'.$_SERVER['SERVER_NAME'].$GLOBALS['_root']."/Extend/alipay/wappay/qrpay.php";
		$msg = post($url, $param);
		$result = json_decode($msg);
		if ($result == null) {
			Log::error("invalid response from alipay_qrcode:".$msg);
			return false;
		}
		if (isset($result->code) && $result->code=='10000') {
			$this->ajaxReturn(array('success'=>1, 'url'=>$result->qr_code));
		}
		else {
			Log::error("failed to get alipay_qrcode. errcode=%d, errmsg=%s", $result->sub_code, $result->sub_msg);
			$this->ajaxReturn(array('success'=>0, 'msg'=>'系统繁忙'));
		}
	}
	

	private function weipay_qrcode($orderNo, $amount)
	{
		$input = new \WxPayUnifiedOrder();
		$input->SetBody('账户充值');
		//$input->SetAttach($attach);
		$input->SetOut_trade_no($orderNo);
		$input->SetTotal_fee($amount*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		$input->SetNotify_url(U('weipay_notify', '', true, true));
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($orderNo);

		$notify = new \NativePay();
		try {
			$result = $notify->GetPayUrl($input);
			if (isset($result["code_url"])) {
				$url = $result["code_url"];
				$this->ajaxReturn(array('success'=>1, 'url'=>$url));
			}
			else {
				Log::error("qrcodePay error:getpayurl failed");
				$deposit = A("Common/Deposit", "Event");
				$deposit->onPayFail(array("orderNo"=>$orderNo, 'errMsg'=>'getPayURL failed'));
				$this->ajaxReturn(array('success'=>0));
			}
		}
		catch(\Exception $e) {
			Log::error("qrcodePay exception:".$e->getMessage());
			$deposit = A("Common/Deposit", "Event");
			$deposit->onPayFail(array("orderNo"=>$orderNo, 'errMsg'=>$e->getMessage()));
			$this->ajaxReturn(array('success'=>0, 'msg'=>$e->getMessage()));
		}
	}

	private function process_weipay_notify($data, &$msg)
	{
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			Log::error("输入参数不正确");
			return false;
		}

		$orderNo = $data['out_trade_no'];
		$outOrderNo = $data['transaction_id'];
		$amount = $data['total_fee'] / 100;

		if ($data['result_code'] == 'SUCCESS') {
			//付款成功
			$paydata = M('pay')->where("orderNo='".$orderNo. "'")->find();
			if ($paydata === false) {
				Log::dberror("failed to select order. orderNo=".$orderNo, M('pay'));
				$msg = "系统繁忙";
				return false;
			}
			if ($paydata == null) {
				Log::error('orderNo not exist. orderNo='.$orderNo);
				$msg = "订单不存在";
				return false;
			}
			if ($paydata['amount'] != $amount) {
				Log::error('amount mismatch. orderNo=%s, paydata.amount=%f, amount=%f',
							array($orderNo, $paydata['amount'], $amount));
				$msg = "金额不正确";
				return false;
			}
			
            if ($paydata['state'] == \PayStateEnum::START) {
            	$deposit = A("Common/Deposit", "Event");
            	return $deposit->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>$outOrderNo));
            }
			else {
			}
		}
		else if ($data['result_code'] == 'FAIL') {
			Log::error("weipay failed");
			
			//付款失败
			$deposit = A("Common/Deposit", "Event");
			return $deposit->onPayFail(array('orderNo'=>$orderNo, 'outTradeNo'=>$outOrderNo,
				                             "errCode"=>$data['err_code'], "errMsg"=>$data['err_code_des']));
		}
		else {
			Log::error("weipay resultcode=".$data['result_code']);
		}

		return true;

	}

	public function weipay_notify()
	{
		//获取通知的数据
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
		Log::debug("weipay notify msg:".$xml);

		//如果返回成功则验证签名
		$msg = "OK";
		try {
			$data = \WxPayResults::Init($xml);
			$result = $this->process_weipay_notify($data, $msg);
		}
		catch (\WxPayException $e){
			Log::error("WxPayException:".$e->errorMessage());
			$msg = $e->errorMessage();
			$result = false;
		}

		$notify = new \WxPayNotifyReply();
		if($result == false){
			Log::notice("wxpay notify failed.");
			$notify->SetReturn_code("FAIL");
			$notify->SetReturn_msg($msg);
		}
		else {
			$notify->SetReturn_code("SUCCESS");
			$notify->SetReturn_msg("OK");
		}
		\WxpayApi::replyNotify($notify->ToXml());
	}

	public function alipay_notify()
	{
		$alipay_config = $GLOBALS['alipay_config'];
	
		$arr=$_POST;
		$checkURL = 'http://'.$_SERVER['SERVER_NAME'].$GLOBALS['_root']."/Extend/alipay/check.php";
		$result = rtrim(post($checkURL, $arr));
		if ($result == "success") {
			Log::error("alipay check error:".json_encode($arr));
			echo 'fail';
			return;
		}
	
		/* 实际验证过程建议商户添加以下校验。
			1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
			2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
			3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
			4、验证app_id是否为该商户本身。
			*/
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//请在这里加上商户的业务逻辑程序代
	
	
		//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
	
		//获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
	
		//商户订单号
		$orderNo = $_POST['out_trade_no'];
	
		//支付宝交易号
		$tradeNo = $_POST['trade_no'];
	
		//交易状态
		$trade_status = $_POST['trade_status'];
	
		//金额
		$amount = $_POST['total_amount'];
	
		//appid
		$appid = $_POST['app_id'];
	
		if ($appid != $alipay_config['app_id']) {
			Log::error("appid mismatch:notify=".$appid.",config=".$alipay_config['app_id']);
			echo 'fail';
			return;
		}
	
		$paydata = M('pay')->where("orderNo='".$orderNo. "'")->find();
		if (!$paydata) {
			Log::error('orderNo not exist. orderNo='.$orderNo);
			echo 'fail';
			return;
		}
	
		if ($paydata['amount'] != $amount) {
			Log::error('amount mismatch');
			echo 'fail';
			return;
		}
	
		if($_POST['trade_status'] == 'TRADE_FINISHED' || $_POST['trade_status'] == 'TRADE_SUCCESS') {
			//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序
			//注意：
			//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
				
			$deposit = A("Common/Deposit", "Event");
			if ($deposit->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>$tradeNo)) === false) {
				echo 'fail';
				return;
			}
		}
		else if ($_POST['trade_status'] == 'TRADE_CLOSED') {
			//未付款交易超时关闭，或支付完成后全额退款
			$deposit = A("Common/Deposit", "Event");
			if ($deposit->onPayFail(array('orderNo'=>$orderNo, 'outTradeNo'=>$tradeNo,
					                      'errCode'=>'TRADE_CLOSED', 'errMsg'=>'未付款交易超时关闭')) === false) {
				echo 'fail';
				return;
			}
		}
		else {
			Log::notice("renotify from alipay received after successfully processed. orderNo=".$orderNo);
		}
		//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
			
		echo "success";		//请不要修改或删除
	}

	public function unionpay_notify()
	{
		Log::debug("notify from unionpay:".json_encode($_POST));

		if (!isset($_POST['signature'])
			|| !isset($_POST['orderId'])		
			|| !isset($_POST['txnAmt'])	
			|| !isset($_POST['txnTime'])		
			|| !isset($_POST['respCode'])) {
			Log::error("Invalid unionpay notify:".json_encode($_POST));
			header("HTTP/1.0 400 Invalid Request");
			return;
		}

		$result = \com\unionpay\acp\sdk\AcpService::validate($_POST);
		if (!$result) {
			//签名验证失败
			Log::error("unionpay notify sig error:".json_encode($_POST));
			header("HTTP/1.0 401 Signature Failed");
			return;
		}

		$orderNo = $_POST ['orderId'];
		$amount = $_POST ['txnAmt'];
		$reqTime = $_POST ['txnTime'];
		$respCode = $_POST ['respCode']; //判断respCode=00或A6即可认为交易成功

		$paydata = M('pay')->where("orderNo='".$orderNo. "'")->find();
		if (!$paydata) {
			Log::error('orderNo not exist. orderNo='.$orderNo);
			header("HTTP/1.0 400 Invalid Request");
			return;
		}
	
		if ($paydata['amount']*100 != $amount) {
			Log::error('amount mismatch');
			header("HTTP/1.0 400 Invalid Request");
			return;
		}
		if ($paydata['payReqTime'] != $reqTime) {
			Log::error('reqTime mismatch');
			header("HTTP/1.0 400 Invalid Request");
			return;
		}

		//原则上只有成功才会受到通知
		if ($respCode == "00" || $respCode == "A6") {
			//交易成功
			$param = array();
			$param['orderNo'] = $orderNo;
			if (isset($_POST['queryId'])) {
				$param['outTradeNo'] = $_POST ['queryId']; //消费交易的流水号，供后续查询用
			}
			if (isset($_POST['accNo'])){
				$param['bankCardNo'] = $_POST["accNo"];
			}
			$event = A("Common/Deposit", "Event");
			$event->onPaySuccess($param);
		}
		else {
			//原则上只有成功才会受到通知
			//此处不做处理，通过订单查询机制处理
			Log::notice("notify from unionpay, but respCode is not success:".$respCode);
		}

		echo "success";
	}
	
}

