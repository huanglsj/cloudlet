<?php

namespace Scheduler\Controller;
use Think\Controller;
use Think\Model;
use Think\Log;

require_once APP_PATH .'/Common/Enum/BalanceOpTypeEnum.php';
vendor("Jugui.MarketStatusJudge");

class OrderCloseController extends Controller 
{
	//平仓订单(按点位)
	public function closeOrderByPrice()
	{
		//if (!APP_DEBUG) {
		//	$remoteAddr = $_SERVER['REMOTE_ADDR'];
		//	if ($remoteAddr != "127.0.0.1") {
		//		exit("OK");
		//	}
		//}

		//获取全部产品当前的价格
		$optime = time();
		$prodList = M("product_lastprice")->field('pid,ask,displayname')->select();
		$waitmodel = M("waitcloseorder");
		foreach($prodList as $prod) {
			$pid = $prod['pid'];
			$price = $prod['ask'];
			$dispname = $prod['displayname'];

			//获取未平仓的订单 edit 不是强制要输的获取处理
			$list = $waitmodel->where('pid='.$pid .' and order_type=1 and is_lost=0 and (close_price_low>='.$price .' or close_price_high<='.$price.')')->field('id, oid')->select();
			if ($list != null) {
				foreach($list as $wo) {
					$this->closeSingleOrder($wo['oid'], $price, $optime, $dispname);	
					$waitmodel->where("id=".$wo['id'])->delete();
				}
			}
            //获取未平仓的订单 edit 是强制要输的获取处理
            $list = $waitmodel->where('pid='.$pid .' and order_type=1 and is_lost=1 and (close_price_low>='.$price .' or close_price_high<='.$price.')')->field('id, oid, is_lost, lost_ext')->select();
            if ($list != null) {
                foreach($list as $wo) {
                    $lost_ext = json_decode($wo['lost_ext'], true);
                    $price = $lost_ext['lost_point'];
                    $this->closeSingleOrder($wo['oid'], $price, $optime, $dispname);
                    $waitmodel->where("id=".$wo['id'])->delete();
                }
            }
			
			//如果现在是休市时间，强制把所有订单平仓
			if (!\MarketStatusJudge::isopen($pid)) {
				//获取未平仓的订单
				$list = $waitmodel->where('pid='.$pid .' and order_type=1')->field('id, oid')->select();
				if ($list == null) {
					continue;
				}
				foreach($list as $wo) {
					$this->closeSingleOrder($wo['oid'], $price, $optime, $dispname, 2);
					$waitmodel->where("id=".$wo['id'])->delete();
				}
			}
		}

	}

	private function getClosePrice($pid, $time)
	{
		$sql = "select newprice from wp_realtimeprice where pid=%d and recvtime= (select max(recvtime) from wp_realtimeprice where pid=%d and recvtime<=%d)";
		$model = new Model();
		$result = $model->query($sql, array($pid, $pid, $time));
		if ($result === false) {
			Log::dberror("failed to get close price. pid=".$pid.",time=".$time, $model);
			return false;
		}

		return $result[0]['newprice'];
	}

	//平仓订单(按时间)
	public function closeOrderByTime()
	{
		//if (!APP_DEBUG) {
		//	$remoteAddr = $_SERVER['REMOTE_ADDR'];
		//	if ($remoteAddr != "127.0.0.1") {
		//		exit("OK");
		//	}
		//}

		$priceCache = array();

		//获取未平仓的订单
		$optime = time();
		$waitmodel = M("waitcloseorder");
		$list = $waitmodel->field('id,pid,oid,close_time,lost_ext,is_lost')->where('order_type=2 and close_time<='.($optime-1))->select();
		if ($list == null) {
			return;
		}
		foreach($list as $wo) {
			$pid = $wo['pid'];
			$oid = $wo['oid'];
			$closetime = $wo['close_time'];

			//获取该产品平仓时的价格
			if (isset($priceCache[$pid][$closetime])) {
				$price = $priceCache[$pid][$closetime];
			}
			else {
			    if($wo['is_lost'] == 1){
			        $lost_ext = json_decode($wo['lost_ext'], true);
                    $price = $lost_ext['lost_point'];
                }else{
                    $price = $this->getClosePrice($pid, $closetime);
                    if ($price === false) {
                        continue;
                    }
                }
				$priceCache[$pid][$closetime] = $price;
			}

			if (isset($priceCache[$pid]['dispname'])) {
				$dispname = $priceCache[$pid]['dispname'];
			}
			else {
				$dispname = M("productinfo")->where('pid='.$pid)->getField('ptitle');
				if ($dispname === false) {
					Log::dberror("failed to get dispname.pid=".$pid, $waitmodel);
					continue;
				}
				$priceCache[$pid]['dispname'] = $dispname;
			}

			$this->closeSingleOrder($oid, $price, $optime, $dispname);	
			$waitmodel->where("id=".$wo['id'])->delete();
		}
	}

	private function closeSingleOrder($oid, $sellprice, $optime, $dispname, $closetype=1) {
		$order = M("order")->where("oid=".$oid)->find();
		if ($order === false || $order == null) {
			Log::error("order not exist OR db error!, oid=".$oid);
			return false;
		}
		if ($order['ostaus'] == 1) {
			//该订单已经平仓
			return;
		}
		Log::debug("close order.orderNo=%s, sellprice=%f, optime=%d, dispname=%s", array($order['orderno'], $sellprice, $optime, $dispname));
		
		//获取平仓时的价格
		$uid = $order['uid']; 
		$buyprice = $order["buyprice"];
		$exid = $order['exid'];

		$accmodel = M("accountinfo");
		$balance = $accmodel->where("uid=".$uid)->getField('balance');
		if ($balance == null) {
			Log::error("failed to get account balance");
		}
		
		if ($order['eid'] == 2) {
			//按时间的
			if ($buyprice == $sellprice) {
				//平
				$state = 2;
			}
			else {
				if (($buyprice < $sellprice) === ($order["ostyle"]==0)) {
					//盈利
					$state = 1;
				}
				else {
					//亏损
					$state = 0;
				}
			}
		}
		else {
			//按点位
			if ($order["ostyle"] == 0) {
				//买涨
				if ($sellprice >= $buyprice+$order['endprofit']) {
					//盈利
					$state = 1;
				}
				else if ($sellprice <= $buyprice-$order['endprofit']) {
					//亏损
					$state = 0;
				}
				else {
					//平
					$state = 2;
				}
			}
			else {
				//买跌
				if ($sellprice <= $buyprice-$order['endprofit']) {
					//盈利
					$state = 1;
				}
				else if ($sellprice >= $buyprice+$order['endprofit']) {
					//亏损
					$state = 0;
				}
				else {
					//平
					$state = 2;
				}
			}
		}
		
		if ($state === 1 || $state === 2) {
			Log::debug("win");

			//客户赚了
			if ($state === 1) {
				$ploss = $order["fee"] * ($order["endloss"] / 100) - ($order["fee"] * ($order["poundage"] / 100));  //扣手续费
			}
			else {
				$ploss = 0;
			}

			if ($exid == null) {
				//未使用优惠券
				$amountdiff = $order["fee"] + $ploss;
			}
			else {
				//使用了优惠券
				$amountdiff = $ploss;
			}
				
			if ($amountdiff != 0) {
				//更新客户账户余额,新增账户余额历史
				$result = D("Home/Account")->updateBalance($uid, \BalanceOpTypeEnum::PC, $uid,
								$amountdiff, $oid, $order['orderno']);
				if ($result == false) {
					Log::dberror("failed to add balance info", M("balance"));
				}
			}
				
			//更新订单状态
			$orderupdate = array('ostaus'=>1, 'sellprice'=>$sellprice, 'ploss'=>$ploss, 'closetype'=>$closetype);
			if ($order['eid'] == 1) {
				$orderupdate['selltime'] = $optime;
				$selltime = $optime;
			}
			else {
				$selltime = $order['selltime'];
			}
			//根据平仓时间计算结算日期
			$orderupdate['settlementdate'] = $this->getSellDate($selltime);
			if ($state === 1) {
				//只有客户盈利时，才有手续费
				$orderupdate['commission'] = $order['fee'] - $ploss;
			}
			else {
				$orderupdate['commission'] = 0;
			}
			M("order")->where("oid=".$order['oid'])->save($orderupdate);
			if ($result === false) {
				Log::dberror("failed to update order status to close. oid=".$oid, $accmodel);
			}

			if ($state == 1) {
				//客户赚钱之后，这部分钱从会员的权益中扣除
				//取得客户所属部门
				$deptid = M("userinfo")->where("uid=".$uid)->getField("deptid");
				if ($deptid === false) {
					Log::dberror("Failed to get userinfo. userid=".$uid, M('userinfo'));
					return false;
				}
				if ($deptid === null) {
					Log::error("the user does not has deptid. userid=" . $uid);
					return false;
				}
					
				//取得该部门所属的会员
				$sql = "select cid,managefeerate from wp_companyinfo comp inner join wp_department dept on comp.deptid=dept.huiyuan_id where dept.id=".$deptid;
				$compinfo = M()->query($sql);
				if ($compinfo === false) {
					Log::dberror("failed to get belonging company. deptid=".$deptid, M(""));
				}
				$compinfo = $compinfo[0];
				
				//客户的亏损-管理费为会员权益
				$compmodel = new \Admin\Model\CompanyinfoModel();
				$result = $compmodel->updateEquity($compinfo['cid'], -$ploss, 2, $uid, $oid, $deptid);
				if ($result['result'] === false) {
					Log::error("failed to update company's equity, compid=%d,amount=%f", array($compinfo['cid'], -$ploss));
				}
			}

		}
		else {
			Log::debug("loss");
			//客户余额不变，不用更新余额表
			//更新订单状态
			$orderupdate = array('ostaus'=>1, 'sellprice'=>$sellprice, 'ploss'=>-$order['fee'], 'commission'=>0, 'closetype'=>$closetype);
			if ($order['eid'] == 1) {
				$orderupdate['selltime'] = $optime;
				$selltime = $optime;
			}
			else {
				$selltime = $order['selltime'];
			}
			$orderupdate['settlementdate'] = $this->getSellDate($selltime);
			$result = M("order")->where("oid=".$oid)->save($orderupdate);
			if ($result === false) {
				Log::dbrror("failed to update order when autoclose", M("order"));
			}
				
			//增加会员权益
			//取得客户所属部门
			$deptid = M("userinfo")->where("uid=".$uid)->getField("deptid");
			if ($deptid === false) {
				Log::error("Failed to get userinfo. userid=" . $uid);
				return false;
			}
			if ($deptid === null) {
				Log::error("the user does not exists. userid=" . $uid);
				return false;
			}
				
			//取得该部门所属的会员
			$sql = "select cid,managefeerate from wp_companyinfo comp inner join wp_department dept on comp.deptid=dept.huiyuan_id where dept.id=".$deptid;
			$compinfo = M()->query($sql);
			if ($compinfo === false) {
				Log::dberror("failed to get belonging company. deptid=".$deptid, M(""));
			}
			$compinfo = $compinfo[0];
			
			//客户的亏损为会员权益
			$compmodel = new \Admin\Model\CompanyinfoModel();
			$result = $compmodel->updateEquity($compinfo['cid'], $order['fee'], 2, $uid, $oid, $deptid);
			if ($result['result'] === false) {
				Log::error("failed to update company's equity, compid=%d,amount=%f", array($compinfo['cid'], $order['fee']));
			}
		}

		if ($exid != null) {
			if ($state == 2) {
				//把优惠券改为未使用
				M("experienceinfo")->where('exid='.$exid)->setField('getstyle', 0);
			}
			else {
				//把优惠券改为已使用
				M("experienceinfo")->where('exid='.$exid)->setField('getstyle', 1);
			}
		}
		
		return true;
		
	}

	private function getSellDate($selltime) {
		$settlementtime = date('Ymd', $selltime);
		return mktime(0,0,0, substr($settlementtime, 4, 2), substr($settlementtime,6, 2), substr($settlementtime, 0, 4));
	}
	
}
