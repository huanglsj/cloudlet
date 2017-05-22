<?php
namespace Ucenter\Controller;

use Think\Controller;
use Think\Model;
use Think\Log;

require_once APP_PATH . "../Extend/weipay/lib/WxPay.Api.php";
require_once APP_PATH . "../Extend/weipay/WxPay.NativePay.php";
require_once APP_PATH . "../Extend/weipay/WxPay.JsApiPay.php";
require_once APP_PATH . "../Extend/unionpay/sdk/acp_service.php";
require_once APP_PATH . 'Common/Enum/PayWayEnum.php';
require_once APP_PATH . 'Common/Enum/PayStateEnum.php';
require_once APP_PATH . 'Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH . 'Common/Common/token.php';


class WithdrawController extends Controller
{
    private function userlogin()
    {
        //判断用户是否已经登录
        if (!isset($_SESSION['cuid'])) {
            $this->redirect('Admin/User/signin');
        }
        return $_SESSION['cuid'];
    }

    public function doWithdraw()
    {
        $cuid = $this->userlogin();
        $now = time();
        $amount = $_POST['amount'];
        $payway = $_POST['payway'];

        if ($amount == "") {
            $this->error("请输入提现金额！");
        }
        if ($payway == "") {
            $this->error("请选择提现方式！");
        }

        $deptid = M("systemuser")->where("id=" . $cuid)->getField("deptid");
        if ($deptid === false) {
            Log::dberror("failed to get systemuser's deptid.uid=" . $cuid, M("systemuser"));
            $this->error("系统繁忙，请稍候重试！");
        }
        if ($deptid === null) {
            Log::error("this sysuser not exist. uid=" . $cuid);
            $this->error("系统繁忙，请稍候重试！");
        }

        //取得该deptid对应的companyid
        $compmodel = M("companyinfo");
        $compinfo = $compmodel->field("cid, equity, banknum, bankusername")->where("deptid=" . $deptid)->find();
        if ($compinfo === false) {
            Log::dberror("failed to get systemuser's companyinfo.deptid=" . $deptid, $compmodel);
            $this->error("系统繁忙，请稍候重试！");
        }
        if ($compinfo === null) {
            Log::error("this companyinfo not exist. deptid=" . $deptid);
            $this->error("系统繁忙，请稍候重试！");
        }
        if (!$compinfo['banknum'] || !$compinfo['bankusername']) {
            $this->error("你尚未设定银行卡号和开户人，无法提现！");
        }

        if ($amount > $compinfo['equity']) {
            $this->error("提现金额不能大于账户可用余额！");
        }

        if ($compinfo['equity'] - $amount < 50000) {
            $this->error("剩余权益不能小于50000.00！");
        }

        $confmodel = M("sysconfig");
        $miaoti = $confmodel->where("k='tixian.common.enableMiaoti'")->getField("v");

        if ($payway == "weixin") {
            $payway = \PayWayEnum::WEIXIN;
            $feeConfig = $confmodel->where("k='tixian.weixin.fee'")->getField("v");
        } else if ($payway == "unionpay") {
            $payway = \PayWayEnum::UNIONPAY;
            $feeConfig = $confmodel->where("k='tixian.unionpay.fee'")->getField("v");
        } else if ($payway == "alipay") {
            $payway = \PayWayEnum::ALIPAY;
            $feeConfig = $confmodel->where("k='tixian.alipay.fee'")->getField("v");
        } else {
            $this->error("请选择出金方式！");
        }

        if ($feeConfig === false) {
            Log::error("failed to get tixian config", $confmodel);
            $this->error("系统繁忙，请稍候重试！");
        }

        //计算手续费
        $feeConfig = json_decode($feeConfig);
        $fee = $this->calcCommissionFee($feeConfig, $amount);
        if ($fee >= $amount) {
            $this->error("提现金额必须大于手续费！");
        }

        $pay['type'] = 2;
        $pay['amount'] = $amount;
        $pay['uid'] = $deptid;
        $pay['utype'] = 2;
        if ($miaoti == 1) {
            $pay['state'] = \PayStateEnum::START;
        } else {
            $pay['state'] = \PayStateEnum::NOT_START;
        }
        $pay['beginTime'] = $now;
        $pay['orderNo'] = createOrderNo('TX');
        $pay['payWay'] = $payway;
        $pay['opDate'] = date('Ymd', $now);
        $pay['commissionFee'] = $fee;
        $pay['opUserId'] = $cuid;

        if ($miaoti == 1) {
            $pay['checkState'] = \PayCheckStateEnum::AUTO_SUCCESS;
            $pay['checkUserId'] = 0;
            $pay['checkTime'] = $now;
            $pay['payReqTime'] = date('YmdHis', $now);
        } else {
            $pay['checkState'] = \PayCheckStateEnum::SUBMIT;
        }

        if (I("post.checkName") == "1") {
            $pay['wxCheckName'] = 1;
            $pay['wxRealName'] = I("post.realName");
        } else {
            $pay['wxCheckName'] = 0;
        }

        if (isset($_POST['bankCardNo'])) {
            $pay['bankCardNo'] = $_POST['bankCardNo'];
        } else {
            $pay['bankCardNo'] = $compinfo['banknum'];
        }
        if ($payway == \PayWayEnum::ALIPAY) {
            $pay['bankCardNo'] = M("alipay")->where("cid=" . $compinfo['cid'])->getField("aliusername");
        }
        if ($miaoti == 1) {
            $querydata = array();
            $querydata['orderNo'] = $pay['orderNo'];
            $querydata['queriedTimes'] = 0;
            if ($payway == \PayWayEnum::WEIXIN) {
                $querydata['nextQueryTime'] = $now + C("PAY_QUERY.weixin_qiye")[0];
            } else if ($payway == \PayWayEnum::UNIONPAY) {
                $querydata['nextQueryTime'] = $now + C("PAY_QUERY.unionpay_df")[0];
            } else if ($payway == \PayWayEnum::ALIPAY) {
                $querydata['nextQueryTime'] = $now + C("PAY_QUERY.alipay_df")[0];
            } else {
                unset($querydata);
            }
        }

        $compmodel->startTrans(false);

        //添加流水
        $payid = M('pay')->add($pay);
        if ($payid === false) {
            $compmodel->rollback();
            Log::dberror("failed to add wp_pay", M('pay'));
            $this->error("系统繁忙，请稍候重试！");
        }

        //更新会员权益
        $result = D("Admin/Companyinfo")->updateEquity($compinfo['cid'], -$amount, 3, $cuid, $payid, $deptid);
        if ($result['result'] === false) {
            $compmodel->rollback();
            Log::dberror("failed to dec company equity. compid=" . $compid, $compmodel);
            $this->error("系统繁忙，请稍候重试！");
        }

        if (isset($querydata)) {
            $result = M("payquery")->add($querydata);
            if ($result === false) {
                $compmodel->rollback();
                Log::dberror("failed to add wp_payquery", $model);
                $this->error("系统繁忙");
            }
        }
        $compmodel->commit();

        if ($miaoti == 1) {
            $param = array('orderNo' => $pay['orderNo'], 'amount' => $amount - $fee);
            if ($payway == \PayWayEnum::WEIXIN) {
                $param['openid'] = $user['openid'];

                if (I("post.checkName") == "1") {
                    $param['checkName'] = "FORCE_CHECK";
                    $param["realUserName"] = I("post.realName");
                } else {
                    $param['checkName'] = "NO_CHECK";
                    $param["realUserName"] = "";
                }

                $msg = "OK";
                $tixian = A('Common/Withdraw', 'Event');
                $result = $tixian->weipay($param, $msg);
                if ($result == false) {
                    $this->error("微信转账失败");
                } else {
                    $this->success("微信转账成功");
                }
            } else if ($payway == \PayWayEnum::UNIONPAY) {
                $param['bankCardNo'] = $pay['bankCardNo'];
                //$param['idCardNo'] = $compinfo['banknum'];
                $param['idCardNo'] = '510265790128303';    //TODO
                $param['realName'] = $compinfo['bankusername'];
                $param['notifyURL'] = U('unionpay_notify', '', true, true);
                $param['reqTime'] = $pay['payReqTime'];

                $msg = "OK";
                $tixian = A('Common/Withdraw', 'Event');
                $result = $tixian->unionpay($param, $msg);
                if ($result == false) {
                    $this->error("银联转账失败");
                } else {
                    $this->success("银联正在处理转账请求, 请注意查看账户余额");
                }
            } else if ($payway == \PayWayEnum::ALIPAY) {
                $params['orderNo'] = $pay['orderNo'];
                $param['bankCardNo'] = M("alipay")->where("cid=" . $compinfo['cid'])->getField("aliusername");
                $param['realName'] = M("alipay")->where("cid=" . $compinfo['cid'])->getField("alirealname");
                $param['notifyURL'] = U('alipay_notify', '', true, true);
                $param['reqTime'] = $pay['payReqTime'];
                $params['amount'] = $amount - $fee;
                $msg = "OK";
                $tixian = A('Common/Withdraw', 'Event');
                $result = $tixian->alipay($param, $msg);
                if ($result == false) {
                    $this->error("支付宝转账失败");
                } else {
                    $this->success("支付宝正在处理转账请求, 请注意查看账户余额");
                }
            }

        } else {
            $this->success("提现申请提交成功！");
            return;
        }
    }

    public function unionpay_notify()
    {
        Log::debug("notify from unionpay df:" . json_encode($_POST));

        if (!isset($_POST['signature'])
            || !isset($_POST['orderId'])
            || !isset($_POST['txnAmt'])
            || !isset($_POST['txnTime'])
            || !isset($_POST['respCode'])
        ) {
            Log::error("Invalid unionpay notify:" . json_encode($_POST));
            header("HTTP/1.0 400 Invalid Request");
            return;
        }

        $result = \com\unionpay\acp\sdk\AcpService::validate($_POST);
        if (!$result) {
            //签名验证失败
            Log::error("unionpay df notify sig error:" . json_encode($_POST));
            header("HTTP/1.0 401 Signature Failed");
            return;
        }
        $orderNo = $_POST ['orderId'];
        $amount = $_POST ['txnAmt'];
        $reqTime = $_POST ['txnTime'];
        $respCode = $_POST ['respCode']; //判断respCode=00或A6即可认为交易成功

        $paydata = M("pay")->where("orderNo='" . $orderNo . "'")->find();
        if (!$paydata) {
            Log::error('orderNo not exist. orderNo=' . $orderNo);
            header("HTTP/1.0 400 Invalid Request");
            return;
        }

        if ($paydata['amount'] * 100 != $amount) {
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
            if (isset($_POST['accNo'])) {
                $param['bankCardNo'] = $_POST["accNo"];
            }
            $withdraw = A("Common/Withdraw", "Event");
            $withdraw->onPaySuccess($param);
        } else {
            //原则上只有成功才会受到通知
            //此处不做处理，通过订单查询机制处理
            Log::notice("notify from unionpay df, but respCode is not success:" . $respCode);
        }

        echo "success";
    }

    private function calcCommissionFee($feeConfig, $amount)
    {
        $fee = 0;
        foreach ($feeConfig as $config) {
            if ($config->fromRelation == "le") {
                $result = (double)$config->fromAmount <= $amount;
            } else {
                $result = (double)$config->fromAmount < $amount;
            }
            if (!$result) {
                continue;
            }

            if ($config->toRelation == "le") {
                $result = (double)$amount <= $config->toAmount;
            } else {
                $result = (double)$amount < $config->toAmount;
            }
            if (!$result) {
                continue;
            }

            if ($config->type == "fixed") {
                $fee = (double)$config->fee;
            } else {
                $fee = $config->fee * $amount / 100.0;
            }

            break;
        }

        return $fee;
    }

}
