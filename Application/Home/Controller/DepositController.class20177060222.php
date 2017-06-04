<?php
namespace Home\Controller;
use Think\Controller;
use Think\Log;
use Lib\Pay\WeipayNotifyCallback;
require_once APP_PATH."../Extend/weipay/lib/WxPay.Api.php";
require_once APP_PATH."../Extend/weipay/WxPay.NativePay.php";
require_once APP_PATH."../Extend/weipay/WxPay.JsApiPay.php";
require_once APP_PATH."../Extend/unionpay/sdk/acp_service.php";
require_once APP_PATH .'Common/Enum/PayWayEnum.php';
require_once APP_PATH .'Common/Enum/PayQueryResultEnum.php';
require_once APP_PATH .'Common/Enum/PayStateEnum.php';
require_once APP_PATH .'Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH .'../Extend/alipay/config.php';
require_once APP_PATH .'Common/Common/token.php';
require_once APP_PATH .'../Extend/huiten/config.php';
require_once APP_PATH .'../Extend/huiten/scanPay.php';


class DepositController extends Controller 
{
    private function userlogin()
    {
        //判断用户是否已经登录
        if (!isset($_SESSION['uid'])) {
            $this->redirect('User/login');
        }
    }

	public function doDeposit()
	{
		$this->userlogin();
		$uid = $_SESSION['uid'];
		 
		$now = time();
		$amount = $_POST['amount'];
		$payway = $_POST['payway'];
		$this->assign('amount', $amount);
		$this->assign('payway', $payway);

        if ($amount < 100) {
            $this->error("最低充值100元起!", U("User/deposit"));
            return;
        }

        $type = '';
		if ($payway == "weixin") {
			$payway = \PayWayEnum::WEIXIN;
			$type = 'WXGZHZF';
		}
		else if ($payway == "weixin_qrcode") {
			$payway = \PayWayEnum::WEIXIN_QRCODE;
		}
		else if ($payway == "alipay") {
			$payway = \PayWayEnum::ALIPAY;
            $type = 'ZFBSMZF';
		}
		else if ($payway == "unionpay") {
			$payway = \PayWayEnum::UNIONPAY;
		}
		else {
			$this->display("User/deposit");
			return;
		}

		$pay['type'] = 1;
		$pay['amount'] = $amount;
		$pay['uid'] = $uid;
		$pay['utype'] = 1;
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
			$this->assign("errMsg", "系统繁忙，请稍候重试！");
			$this->display("User/deposit");
			return;
		}

		if(!empty($type)){
            $this->huiten_qrcode($pay['orderNo'], $amount, $type);
        }elseif ($payway == \PayWayEnum::WEIXIN) {
			$openid = M('userinfo')->where('uid='.$uid)->getField('openid');
			if ($openid==null || $openid==false) {
				Log::dberror("failed to get openid. userid=".$uid, M('userinfo'));
				$this->assign("errMsg", "系统繁忙，请稍候重试！");
				$this->display("User/deposit");
				return;
			}
		
			$this->weipay_jsapi($openid, $pay['orderNo'], $amount);
		}
		else if ($payway == \PayWayEnum::WEIXIN_QRCODE) {
			$this->weipay_qrcode($pay['orderNo'], $amount);
		}
		else if ($payway == \PayWayEnum::ALIPAY) {
			//$this->alipay_wap($balance['orderNo'], $amount);
			$param = array('orderNo'=>$pay['orderNo'], 'amount'=>$amount);
			$this->redirect("alipay_wap", $param);
		}
		else if ($payway == \PayWayEnum::UNIONPAY) {
			$param = array('orderNo'=>$pay['orderNo'], 'amount'=>$amount,
			               "reqTime"=>$pay['payReqTime'],
			               "notifyURL"=>U('unionpay_notify', '', true, true),
						   "returnURL"=>U('Index/index', '', true, true));
			$action = A("Common/Deposit", "Event");
			$html = $action->unionpay($param);
			header('Content-type:text/html;charset=utf-8');
			echo $html;
		}
	}
		
	public function alipay_wap() {
		$this->assign('orderNo', I("get.orderNo"));
		$this->assign('amount', I("get.amount"));
		$this->assign('notifyURL', U("alipay_notify", '', true, true));
		$this->assign('returnURL', U("alipay_return", '', true, true));
		$this->display("alipay_wap");		
	}
	
	
	private function weipay_jsapi($openid, $orderNo, $amount)
	{
		$this->assign('orderNo', $orderNo);
		$this->assign('amount', $amount);
		$this->assign('openid', $openid);
		
		$tools = new \JsApiPay();
		//$str =  http_build_query($_REQUEST);
		//$openid = $tools->GetOpenid($str);

		//统一下单
		$input = new \WxPayUnifiedOrder();
		$input->SetBody("账户充值");
		//$input->SetAttach("test");
		$input->SetOut_trade_no($orderNo);
		$input->SetTotal_fee($amount*100);
		$input->SetTime_start(date("YmdHis"));
		$input->SetTime_expire(date("YmdHis", time() + 600));
		//$input->SetGoods_tag("test");
		$input->SetNotify_url(U('weipay_notify', '', true, true));
		$input->SetTrade_type("JSAPI");
		$input->SetOpenid($openid);
		try {
			$order = \WxPayApi::unifiedOrder($input);
		}
		catch (\Exception $e) {
			Log::error("jsapiPay exception:".$e->getMessage());
			$deposit = A("Common/Deposit", "Event");
			$deposit->onPayFail(array("orderNo"=>$orderNo, 'errMsg'=>$e->getMessage()));
			$this->display("weipay_fail");
			return;
		}
		$jsApiParameters = $tools->GetJsApiParameters($order);
		$this->assign("jsApiParameters", $jsApiParameters);
		$this->display("weipay_jsapi");
	}

    private function huiten_qrcode($orderNo, $amount, $type)
    {
        $result = huiten_scan_pay($orderNo, $amount, $type);
        if($result['payStr']){
            $url = $result["payStr"];
            $this->assign('url', urlencode($url));
            $this->assign('type', $type);
            if($type == 'ZFBSMZF'){
                $this->display("huiten_alipay_qrcode");
            }else{
                $this->display("huiten_weipay_qrcode");
            }
        }else {
            Log::error("qrcodePay error:getpayurl failed");
            $deposit = A("Common/Deposit", "Event");
            $deposit->onPayFail(array("orderNo"=>$orderNo, 'errMsg'=>'getPayURL failed'));
            if($type == 'ZFBSMZF'){
                $this->display("alipay_fail");
            }else{
                $this->display("weipay_fail");
            }

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
		//$input->SetGoods_tag($tag);
		//$input->SetNotify_url('http://'.$_SERVER['SERVER_NAME'].$GLOBALS['_root']."/index.php/Home/Weipay/payNotify.html");
		$input->SetNotify_url(U('weipay_notify', '', true, true));
		$input->SetTrade_type("NATIVE");
		$input->SetProduct_id($orderNo);

		$notify = new \NativePay();
		try {
			$result = $notify->GetPayUrl($input);
			if (isset($result["code_url"])) {
				$url = $result["code_url"];
				$this->assign('url', urlencode($url));
				$this->display('weipay_qrcode');
			}
			else {
				Log::error("qrcodePay error:getpayurl failed");
				$deposit = A("Common/Deposit", "Event");
				$deposit->onPayFail(array("orderNo"=>$orderNo, 'errMsg'=>'getPayURL failed'));
				$this->display("weipay_fail");
			}
		}
		catch(\Exception $e) {
			Log::error("qrcodePay exception:".$e->getMessage());
			$deposit = A("Common/Deposit", "Event");
			$deposit->onPayFail(array("orderNo"=>$orderNo, 'errMsg'=>$e->getMessage()));
			$this->display("weipay_fail");
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
		$notify = new \WxPayNotifyReply();

		//获取通知的数据
		$xml = $GLOBALS['HTTP_RAW_POST_DATA'];

		//如果返回成功则验证签名
		$msg = "OK";
		try {
			$data = \WxPayResults::Init($xml);
			$result = $this->process_weipay_notify($data, $msg);
		}
		catch (\WxPayException $e){
			$msg = $e->errorMessage();
			$result = false;
		}

		if($result == false){
			$notify->SetReturn_code("FAIL");
			$notify->SetReturn_msg($msg);
		}
		else {
			$notify->SetReturn_code("SUCCESS");
			$notify->SetReturn_msg("OK");
		}
		\WxpayApi::replyNotify($notify->ToXml());
	}

	public function weipay_return()
	{
		$orderNo = I("post.orderNo");
		//查询本地订单状态
		$paydata = M("pay")->where("orderNo='%s'", $orderNo)->find();
		if ($paydata === false) {
			Log::dberror("failed to select wp_pay", M("pay"));
			$this->assign("result", "3");
			$this->display("weipay_result");
			return;
		}
		else if ($paydata == null) {
			$this->assign("result", "4");
			$this->display("weipay_result");
			return;
		}
		else {
			if ($paydata['state'] == \PayStateEnum::SUCCESS) {
				$this->assign("result", "1");
				$this->display("weipay_result");
				return;
			}
			else if ($paydata['state'] == \PayStateEnum::FAIL) {
				$this->assign("result", "2");
				$this->display("weipay_result");
				return;
			}
		}
		
		//查询订单状态
		$deposit = A("Common/Deposit", "Event");
		$queryresult = $deposit->weipay_query($orderNo);
		Log::debug("queryresult=".json_encode($queryresult));
		$payresult = $queryresult['result'];
		if ($payresult == \PayQueryResultEnum::PAY_SUCCESS) {
			$deposit->onPaySuccess(array("orderNo"=>$orderNo, "outTradeNo"=>$queryresult['outTradeNo']));
			$this->assign("result", "1");
		}
		else if ($payresult == \PayQueryResultEnum::PAY_FAIL) {
			$params = array("orderNo"=>$orderNo);
			if (isset($queryresult['outTradeNo'])) {
				$params['outTradeNo'] = $queryresult['outTradeNo'];
			}
			if (isset($queryresult['errCode'])) {
				$params['errCode'] = $queryresult['errCode'];
			}
			if (isset($queryresult['errMsg'])) {
				$params['errMsg'] = $queryresult['errMsg'];
			}
			$deposit->onPayFail($params);
			$this->assign("result", "2");
		}
		else {
			$this->assign("result", "3");
		}
		$this->display("weipay_result");
	}
	
	
	public function alipay_return()
	{
		$arr=$_GET;
		//$checkURL = 'http://'.$_SERVER['SERVER_NAME'].$GLOBALS['_root']."/Extend/alipay/check.php";
		$checkURL = 'http://'.$_SERVER['SERVER_NAME'].$GLOBALS['_root']."/Extend/alipay/check.php";
		$result = rtrim(post($checkURL, $arr));
		if ($result == "success") {
			Log::error("alipay check error:".json_encode($arr));
			echo '验证失败';
			$this->JsDumpLocal('验证失败');
			return;
		}
	
		/* 实际验证过程建议商户添加以下校验。
			1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
			2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
			3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
			4、验证app_id是否为该商户本身。
			*/
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//请在这里加上商户的业务逻辑程序代码
	
		//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
	
		//商户订单号
		$orderNo = htmlspecialchars($_GET['out_trade_no']);
	
		//支付宝交易号
		$tradeNo = htmlspecialchars($_GET['trade_no']);
	
		//支付宝交易号
		$amount = htmlspecialchars($_GET['total_amount']);
	
		$paydata = M('pay')->where("orderNo='".$orderNo. "'")->find();
		if (!$paydata) {
			echo "订单不存在";
			$this->JsDumpLocal('订单不存在');
			return;
		}
	
		if ($paydata['amount'] != $amount) {
			echo '订单金额错误';
			$this->JsDumpLocal('订单不存在');
			return;
		}
	
		$deposit = A("Common/Deposit", "Event");
		if ($deposit->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>$tradeNo)) === false) {
			echo '系统繁忙';
			return $this->JsDumpLocal('系统繁忙');
			return;
		}
	
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if (stripos($user_agent, 'MicroMessenger') === false) {
			$this->display("alipay_result");
		}
		else {
			header("refresh:1;url=".C('DOMAIN'));
			$this->assign("result", 1);
			$this->display("weipay_result");
		}
	}

	public function JsDumpLocal($tip){
		//echo '<script>alert('.$tip.')</script>';
		//Header("Location: http://wx.shxbrme.net"); 
		header("refresh:5;url=".C('DOMAIN'));
		echo '<br />五秒后跳转至首页';
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

    public function huiten_return()
    {
        //$huiten_config = $GLOBALS['huiten_config'];
        //$huiten_config = $GLOBALS['huiten_config'];
        $arr=$_GET;
        Log::error("huiten data2:".json_encode($arr));
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (stripos($user_agent, 'MicroMessenger') === false) {
            $this->display("alipay_result");
        }
        else {
            header("refresh:1;url=".C('DOMAIN'));
            $this->assign("result", 1);
            $this->display("weipay_result");
        }
    }

    public function huiten_notify()
    {
        $huiten_config = $GLOBALS['huiten_config'];
        $arr=$_GET;
        if($arr['signature'] != strtoupper(md5($arr['orderNo'].$arr['userOrderNo'].$huiten_config['signKey']))){
            Log::error("huiten check error:".json_encode($arr));
            echo '0000';
            return;
        }

        //商户订单号
        $orderNo = $arr['userOrderNo'];

        //交易号
        $tradeNo = $arr['orderNo'];

        //交易状态
        $trade_status = $arr['status'];

        //金额
        $amount = $arr['orderAmt'];

        $paydata = M('pay')->where("orderNo='".$orderNo. "'")->find();
        if (!$paydata) {
            Log::error('orderNo not exist. orderNo='.$orderNo);
            echo '0000';
            return;
        }

        if ($paydata['amount'] != $amount) {
            Log::error('amount mismatch');
            echo '0000';
            return;
        }

        if($paydata['state'] != 1){
            Log::error('amount is finish');
            echo '0000';
            return;
        }

        if($trade_status == '02') {
            $deposit = A("Common/Deposit", "Event");
            if ($deposit->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>$tradeNo)) === false) {
                echo '0000';
                return;
            }
        }
        else if ($trade_status == '03') {
            //未付款交易超时关闭，或支付完成后全额退款
            $deposit = A("Common/Deposit", "Event");
            if ($deposit->onPayFail(array('orderNo'=>$orderNo, 'outTradeNo'=>$tradeNo,
                    'errCode'=>'TRADE_CLOSED', 'errMsg'=>'未付款交易超时关闭')) === false) {
                echo '0000';
                return;
            }
        }
        else {
            Log::notice("renotify from huiten received after successfully processed. orderNo=".$orderNo);
        }
        //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

        echo "0000";		//请不要修改或删除
    }

}

