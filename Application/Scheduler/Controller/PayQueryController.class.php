<?php

namespace Scheduler\Controller;
use Think\Controller;
use Think\Model;
use Think\Log;

require_once APP_PATH .'/Common/Enum/PayWayEnum.php';
require_once APP_PATH .'/Common/Enum/PayStateEnum.php';
require_once APP_PATH .'Common/Enum/PayQueryResultEnum.php';


class PayQueryController extends Controller 
{
	//查询处理中的支付的支付结果
	public function query()
	{
		if (!APP_DEBUG) {
			$remoteAddr = $_SERVER['REMOTE_ADDR'];
			if ($remoteAddr != "127.0.0.1") {
				exit("OK");
			}
		}

		Log::debug("pay query start");

		//查询未完结的支付交易
		$now = time();
		$pqmodel = M("payquery");
		$list = $pqmodel->where("nextQueryTime<=".$now)->select();
		if ($list === false) {
			Log::dberror("failed to select pay query table", $pqmodel);
			return;
		}

		if (empty($list)) {
			return;
		}

		foreach($list as $q) {
			$orderNo = $q['orderNo'];
			$paymodel = M('pay');
			$paydata = $paymodel->where("orderNo='".$orderNo."'")->find();
			if ($paydata === false) {
				Log::dberror("failed to get balance order", $paymodel);
				return;
			}
			if ($paydata === null) {
				Log::error("order not exist. orderNo=".$orderNo);
				continue;
			}
			if ($paydata['state'] != \PayStateEnum::START) {
				M('payquery')->where('qid='.$q['qid'])->delete();
				continue;
			}

			if ($paydata['type'] == 1) {
			}
			else {
				//提现
				$tixian = A("Common/Withdraw", "Event");
				if ($paydata['payWay'] == \PayWayEnum::WEIXIN) {
					$config = C("PAY_QUERY.weixin_qiye");
					$queryresult = $tixian->weipay_query($paydata['orderNo']);
				}
				else if ($paydata['payWay'] == \PayWayEnum::UNIONPAY) {
					$config = C("PAY_QUERY.unionpay_df");
					$queryresult = $tixian->unionpay_query(array('orderNo'=>$paydata['orderNo'], 'reqTime'=>$paydata['payReqTime']));
				}
				Log::debug("payquery config:".json_encode($config));

				if (isset($queryresult['outTradeNo'])) {
					$outTradeNo = $queryresult['outTradeNo'];
				}
				else {
					$outTradeNo = null;
				}
				
				$payresult = $queryresult['result'];
				if ($payresult == \PayQueryResultEnum::QUERY_FAIL) {
					//查询失败，等待下次查询
				}
				else if ($payresult == \PayQueryResultEnum::PAY_NOT_EXIST 
					|| $payresult == \PayQueryResultEnum::PAY_QUERY_AGAIN) {
					//处理进行中，需要再次查询
					$q['queriedTimes'] = $q['queriedTimes'] + 1;
					if ($q['queriedTimes'] >= count($config)) {
						$errMsg = "超过最长查询时间仍未查询到结果";
						$tixian->onPayFail(array('orderNo'=>$orderNo, 'outTradeNo'=>$outTradeNo, 'errMsg'=>$outInfo['errMsg']));
					}
					else {
						$q['prevQueryTime'] = $now;
						$q['nextQueryTime'] = $now + $config[$q['queriedTimes']];
						$result = $pqmodel->where('qid='.$q['qid'])->save($q);
						if ($result === false) {
							Log::dberror("failed to update payquery", $pqmodel);
						}
					}
				}
				else if ($payresult == \PayQueryResultEnum::PAY_FAIL) {
					$tixian->onPayFail(array('orderNo'=>$orderNo, 'outTradeNo'=>$outTradeNo, 
							                 'errCode'=>$queryresult['errCode'], 'errMsg'=>$queryresult['errMsg']));
				}
				else if ($payresult == \PayQueryResultEnum::PAY_SUCCESS) {
					$tixian->onPaySuccess(array('orderNo'=>$orderNo, 'outTradeNo'=>$outTradeNo, ));
				}
			}
		}
		Log::debug("pay query end");
	}
	
}
