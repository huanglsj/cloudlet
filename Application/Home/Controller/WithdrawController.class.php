<?php
namespace Home\Controller;
use Think\Controller;
use Think\Model;
use Think\Log;

require_once APP_PATH."../Extend/weipay/lib/WxPay.Api.php";
require_once APP_PATH."../Extend/weipay/WxPay.NativePay.php";
require_once APP_PATH."../Extend/weipay/WxPay.JsApiPay.php";
require_once APP_PATH."../Extend/unionpay/sdk/acp_service.php";
require_once APP_PATH .'Common/Enum/PayWayEnum.php';
require_once APP_PATH .'Common/Enum/PayStateEnum.php';
require_once APP_PATH .'Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH .'Common/Enum/BalanceOpTypeEnum.php';
require_once APP_PATH .'Common/Common/token.php';


class WithdrawController extends Controller 
{
    private function userlogin()
    {
        //判断用户是否已经登录
        if (!isset($_SESSION['uid'])) {
            $this->redirect('User/login');
        }
    }

	public function doWithdraw()
	{
		$this->userlogin();
		$uid = $_SESSION['uid'];
		$redirectParam = $_POST;
		$now = time();
		$amount = $_POST['amount'];
		$payway = $_POST['payway'];
		if($payway == 'unionpay'){
            $_POST['bankCardNo'] = $_POST['bankCardNo2'];
        }
		 
		if ($amount == "") {
			$redirectParam["errMsg"] = "请输入提现金额";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}if ($amount < 1) {
			$redirectParam["errMsg"] = "提现金额必须大于1元";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		if ($payway == "") {
			$redirectParam["errMsg"] = "请选择提现方式";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
        if ($_POST['bankCardNo']== "") {
            $redirectParam["errMsg"] = "请先绑定收款账号";
            $this->redirect("User/withdraw", $redirectParam);
            return;
        }
	
		$user = M("userinfo")->where("uid=".$uid)->find();
		$account = M("accountinfo")->where("uid=".$uid)->find();
		if ($user === false || $account===false) {
			Log::dberror("failed to get account info", new Model());
			$redirectParam["errMsg"] = "系统繁忙，请稍候重试！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		 
		if ($user === null || $account === null) {
			Log::dberror("user or account not exist. uid=".$uid, new Model());
			$redirectParam["errMsg"] = "此用户不存在！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}

		if ($user['ustatus'] != 2) {
			Log::dberror("account not active. uid=".$uid, new Model());
			$redirectParam["errMsg"] = "账户未激活！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		 
		if ($amount > $account['balance']-$account['frozen']) {
			$redirectParam["errMsg"] = "提现金额不能大于账户可用余额！";
			$this->redirect("User/withdraw", $redirectParam);
			//$this->error("提现金额不能大于账户余额！");
			return;
		}

		if($account['give']){
            if ($amount > $account['balance']-$account['frozen']-$account['give']) {
                $totalOrder = M('Order')->where(array('uid'=>$uid, 'ostaus'=>1))->sum('fee');
                if($totalOrder < $account['give'] * 20){  //要交易额达到 赠送金额的20倍 才能提
                    $redirectParam["errMsg"] = "不能提现赠送金额！";
                    $this->redirect("User/withdraw", $redirectParam);
                    return;
                }
            }
        }



		
		//银联必须审核通过
		if ($payway == "unionpay") {
			if ($user['authenticationsstatus'] != 2) {
				$redirectParam["errMsg"] = "实名认证通过后方可提现";
				$this->redirect("User/withdraw", $redirectParam);
				return;
			}

			$authModel = M("authenticationinfo");
			$authInfo = $authModel->where("useruid=".$uid)->find();
			if ($authInfo === false) {
				Log::dberror("failed to get auth info. uid=".$uid, $authModel);
				$redirectParam["errMsg"] = "系统繁忙";
				$this->redirect("User/withdraw", $redirectParam);
				return false;
			}
			if ($authInfo === null) {
				Log::error("authStatus is 2 but authInfo not exist. uid=".$uid);
				$redirectParam["errMsg"] = "系统繁忙";
				$this->redirect("User/withdraw", $redirectParam);
				return false;
			}
		}
	
		$confmodel = M("sysconfig");
		$miaoti = $confmodel->where("k='tixian.common.enableMiaoti'")->getField("v");
		$userTimesLimit = $confmodel->where("k='tixian.common.maxTimesPerUserDay'")->getField("v");
		$userAmountLimit = $confmodel->where("k='tixian.common.maxAmountPerUserDay'")->getField("v");
		$sysAmountLimit = $confmodel->where("k='tixian.common.maxAmountPerDay'")->getField("v");
		if ($miaoti===false || $userTimesLimit===false
				|| $userAmountLimit===false || $sysAmountLimit===false) {
			Log::dberror("failed to get tixian config", $confmodel);
			$redirectParam["errMsg"] = "系统繁忙，请稍候重试！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		 
		if ($user['authenticationsstatus']==2 && $miaoti==1) {
			$tixianFlag = true;//提现
		}
		else {
			$tixianFlag = false;//申请提现
		}
		 
		$curDate = date("Ymd");
		$model = new Model();
		$d = $model->query("select count(1)cc, sum(amount)amount from wp_pay where uid=%d and type=2 and state<>3 and checkstate<>3 and opDate='%s'", array($uid, $curDate));
		if ($d === false) {
			Log::dberror("failed to get user balance info", $model);
			$redirectParam["errMsg"] = "系统繁忙，请稍候重试！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		 
		$d = $d[0];
		if ($d['cc'] >= $userTimesLimit) {
// 			$redirectParam["errMsg"] = "您今天的提现次数已经达到每天的最大提现次数！";
		    $redirectParam["errMsg"] = "提现失败，错误码（ZJ0101）";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		if ($d['amount'] >= $userAmountLimit) {
// 			$redirectParam["errMsg"] = "您今天的提现金额已经达到每天的最大提现额度！";
			$redirectParam["errMsg"] = "提现失败，错误码（ZJ0102）";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		 
		$d = $model->query("select sum(amount)amount from wp_pay where uid=%d and type=2 and state<>3 and checkstate<>3 and opDate='%s'", array($uid, $curDate));
		if ($d === false) {
			Log::dberror("failed to get system balance info", $model);
			$redirectParam["errMsg"] = "系统繁忙，请稍候重试！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		$d = $d[0];
		if ($d['amount'] >= $sysAmountLimit) {
// 			$redirectParam["errMsg"] = "系统今天的提现额度已满，请改天重试！";
			$redirectParam["errMsg"] = "提现失败，错误码（ZJ0103）";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		 
		$this->assign('amount', $amount);
		$this->assign('payway', $payway);
		if ($payway == "weixin") {
			$payway = \PayWayEnum::WEIXIN;
			$feeConfig = $confmodel->where("k='tixian.weixin.fee'")->getField("v");
		}
		else if ($payway == "unionpay") {
			$payway = \PayWayEnum::UNIONPAY;
			$feeConfig = $confmodel->where("k='tixian.unionpay.fee'")->getField("v");
		}else if ($payway == "alipay") {
			$payway = \PayWayEnum::ALIPAY;
			$feeConfig = $confmodel->where("k='tixian.alipay.fee'")->getField("v");
		}
		else {
			$redirectParam["errMsg"] = "请选择出金方式！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		if ($feeConfig === false) {
			Log::error("failed to get tixian config", $confmodel);
			$redirectParam["errMsg"] = "系统繁忙，请稍候重试！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}

		//计算手续费
		$feeConfig = json_decode($feeConfig);
		$fee = $this->calcCommissionFee($feeConfig, $amount);
		if ($fee >= $amount) {
// 			$redirectParam["errMsg"] = "提现金额必须大于手续费！";
		    $redirectParam["errMsg"] = "提现失败，错误码（ZJ0104）";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}

		$pay['type'] = 2;
		$pay['amount'] = $amount;
		$pay['uid'] = $uid;
		$pay['utype'] = 1;
		if ($tixianFlag) {
			$pay['state'] = \PayStateEnum::START;
		}
		else {
			$pay['state'] = \PayStateEnum::NOT_START;
		}
		$pay['beginTime'] = $now;
		$pay['orderNo'] = createOrderNo('TX');
		$pay['payWay'] = $payway;
		$pay['opDate'] = date('Ymd', $now);
		$pay['commissionFee'] = $fee;
		$pay['opUserId'] = $uid;

		if ($tixianFlag) {
			$pay['checkState'] = \PayCheckStateEnum::AUTO_SUCCESS;
			$pay['checkUserId'] = 0;
			$pay['checkTime'] = $now;
			$pay['payReqTime'] = date('YmdHis', $now);
		}
		else {
			$pay['checkState'] = \PayCheckStateEnum::SUBMIT;
		}
		if (I("post.checkName") == "1") {
			$pay['wxCheckName'] = 1;
			$pay['wxRealName'] = I("post.realName");
		}
		else {
			$pay['wxCheckName'] = 0;
		}

		if (isset($_POST['bankCardNo'])) {
			$pay['bankCardNo'] = $_POST['bankCardNo'];
		}

		if ($tixianFlag) {
			$querydata = array();
			$querydata['orderNo'] = $pay['orderNo'];
			$querydata['queriedTimes'] = 0;
			if ($payway == \PayWayEnum::WEIXIN) {
				$querydata['nextQueryTime'] = $now + C("PAY_QUERY.weixin_qiye")[0];
			}
			else if ($payway == \PayWayEnum::UNIONPAY) {
				$querydata['nextQueryTime'] = $now + C("PAY_QUERY.unionpay_df")[0];
			}else if ($payway == \PayWayEnum::ALIPAY) {
				$querydata['nextQueryTime'] = $now + C("PAY_QUERY.alipay_df")[0];
			}
			else {
				unset($querydata);
			}
		}

		$model->startTrans(false);
		
		//添加支付流水
		$orderid = M('pay')->add($pay);
		if ($orderid === false) {
			$model->rollback();
			Log::dberror("failed to add wp_pay", $model);
			$redirectParam["errMsg"] = "系统繁忙，请稍候重试！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		
		//扣减账户余额，新增余额变更历史
		$result = D("Home/Account")->updateBalance($uid, \BalanceOpTypeEnum::TXSQ, $uid,
						-$amount, $orderid, $pay['orderNo']);
		if ($result === false) {
			$model->rollback();
			$redirectParam["errMsg"] = "系统繁忙，请稍候重试！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
		
		//
		if (isset($querydata)) {
			$result = M("payquery")->add($querydata);
			if ($result === false) {
				$model->rollback();
				Log::dberror("failed to add wp_payquery", $model);
				$redirectParam["errMsg"] = "系统繁忙，请稍候重试！";
				$this->redirect("User/withdraw", $redirectParam);
				return;
			}
		}
		$model->commit();

		if ($tixianFlag) {
			$param = array('orderNo'=>$pay['orderNo'], 'amount'=>$amount-$fee);
			if ($payway == \PayWayEnum::WEIXIN) {
				$param['openid'] = $user['openid'];

				if (I("post.checkName") == "1") {
					$param['checkName'] = "FORCE_CHECK";
					$param["realUserName"] = I("post.realName");
				}
				else {
					$param['checkName'] = "NO_CHECK";
					$param["realUserName"] = "";
				}

				$msg = "OK";
				$tixian = A('Common/Withdraw', 'Event');
				$result = $tixian->weipay($param, $msg);
				if ($result == false) {
					$this->assign("success", 0);
					//$this->assign("msg", $msg);
				}
				else {
					$this->assign("success", 1);
				}
				$this->display("weipay_result");
			}
			else if ($payway == \PayWayEnum::UNIONPAY) {
				$param['bankCardNo'] = I("post.bankCardNo");
				$param['idCardNo'] = $authInfo['IDnumber'];
				$param['realName'] = $authInfo['realname'];
				$param['notifyURL'] = U('unionpay_notify', '', true, true);
				$param['reqTime'] = $pay['payReqTime'];

				$msg = "OK";
				$tixian = A('Common/Withdraw', 'Event');
				$result = $tixian->unionpay($param, $msg);
				if ($result == false) {
					$this->assign("success", 0);
					$this->assign("msg", $msg);
				}
				else {
					$this->assign("success", 1);
// 					$this->assign("msg", "银联正在处理转账请求, 请注意查看账户余额");
					$this->assign("msg", "银联正在处理转账请求, 请稍后。");
				}
				$this->display("weipay_result");
			}else if ($payway == \PayWayEnum::ALIPAY) {
				$param['bankCardNo'] = I("post.bankCardNo");
				$param['realName'] = I("post.realName");
				$param['idCardNo'] = $authInfo['IDnumber'];
				$param['realName'] = $authInfo['realname'];
				$param['notifyURL'] = U('alipay_notify', '', true, true);
				$param['reqTime'] = $pay['payReqTime'];

				$msg = "OK";
				$tixian = A('Common/Withdraw', 'Event');
				$result = $tixian->alipay($param, $msg);
				if ($result == false) {
					$this->assign("success", 0);
					$this->assign("msg", $msg);
				}
				else {
					$this->assign("success", 1);
// 					$this->assign("msg", "支付宝正在处理转账请求, 请注意查看账户余额");
					$this->assign("msg", "支付宝正在处理转账请求, 请稍后。");
				}
				$this->display("alipay_result");
			}

		}
		else {
		    //如果有赠送金额的，提取任何额度，都清空赠送金额
            if($account['give']){
                D("Home/Account")->updateBalance($uid, \BalanceOpTypeEnum::QKZS, $uid, -$account['give'], $orderid, $pay['orderNo']);
            }
			$redirectParam = array();
			$redirectParam["errMsg"] = "提现申请提交成功！";
			$this->redirect("User/withdraw", $redirectParam);
			return;
		}
	}

	public function unionpay_notify()
	{
		Log::debug("notify from unionpay df:".json_encode($_POST));

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
			Log::error("unionpay df notify sig error:".json_encode($_POST));
			header("HTTP/1.0 401 Signature Failed");
			return;
		}
		$orderNo = $_POST ['orderId'];
		$amount = $_POST ['txnAmt'];
		$reqTime = $_POST ['txnTime'];
		$respCode = $_POST ['respCode']; //判断respCode=00或A6即可认为交易成功

		$paydata = M("pay")->where("orderNo='".$orderNo. "'")->find();
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
			$withdraw = A("Common/Withdraw", "Event");
			$withdraw->onPaySuccess($param);
		}
		else {
			//原则上只有成功才会受到通知
			//此处不做处理，通过订单查询机制处理
			Log::notice("notify from unionpay df, but respCode is not success:".$respCode);
		}

		echo "success";
	}
	
	private function calcCommissionFee($feeConfig, $amount) {
		$fee = 0;
		foreach($feeConfig as $config) {
			if ($config->fromRelation == "le") {
				$result = (double)$config->fromAmount <= $amount;
			}
			else {
				$result = (double)$config->fromAmount < $amount;
			}
			if (!$result) {
				continue;
			}
	
			if ($config->toRelation == "le") {
				$result = (double)$amount <= $config->toAmount;
			}
			else {
				$result = (double)$amount < $config->toAmount;
			}
			if (!$result) {
				continue;
			}
	
			if ($config->type == "fixed") {
				$fee = (double)$config->fee;
			}
			else {
				$fee = $config->fee * $amount / 100.0;
			}
	
			break;
		}
			
		return $fee;
	}
	
	//微信企业付款（企业向个人付款）
	private function weipay($params)
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
				$this->assign("success", 0);
				$this->assign("msg", "通信异常");
			}
			else if ($result['return_code'] == "SUCCESS") {
				if (isset($result['result_code']) && $result['result_code']=="SUCCESS") {
					//做check，更新数据库
					$withdraw = A("Common/Withdraw", "Event");
					$withdraw->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>$result['payment_no']));
					$this->assign("success", 1);
				}
				else {
					$withdraw = A("Common/Withdraw", "Event");
					$withdraw->onPayFail(array('orderNo'=>$orderNo, 'errCode'=>$result['err_code'], 'errMsg'=>$result['err_code_des']));
					Log::error("weixin mchpay error.result=".json_encode($result));
					$this->assign("success", 0);
					$this->assign("msg", $result['err_code_des']);
				}
			}
			else if ($result['return_code'] == "FAIL") {
				$withdraw = A("Common/Withdraw", "Event");
				$withdraw->onPayFail(array('orderNo'=>$orderNo, 'errMsg'=>$result['return_msg']));
				Log::error("weixin mchpay error.result=".json_encode($result));
				$this->assign("success", 0);
				$this->assign("msg", $result['return_msg']);
			}
			else {
				$withdraw = A("Common/Withdraw", "Event");
				$withdraw->onPayFail(array('orderNo'=>$orderNo, 'errMsg'=>'return code不正确'));
				Log::error("weixin mchpay error.result=".json_encode($result));
				$this->assign("success", 0);
				$this->assign("msg", "通信异常");
			}
		}
		catch (\Exception $e) {
			Log::error("weixin mchpay exception. ".$e->getMessage());
			$this->assign("success", 0);
			$this->assign("msg", "通信异常");
		}
		
		$this->display("weipay_result");
	}


}
