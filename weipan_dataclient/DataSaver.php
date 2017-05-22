<?php

require_once 'func.php';
require_once 'db.php';
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/vendor/Workerman/Autoloader.php';


class DataSaver
{
	private $saver;
	private $prodInfo;
	private $openInfoURL;

	public function __construct()
	{
		$this->prodInfo = array();
		$this->openInfoURL = config("worker.saver.openInfoURL");
		$this->createSaver();
	}

	private function createSaver()
	{
		$this->saver = new Worker();
		if ($this->saver == null) {
			logError("DBSave worker start error!");
			return;
		}

		// 只能启动1个进程对外提供服务
		$this->saver->count = 1;
		$this->saver->name = "saver";

		$this->saver->onWorkerStart = function() {
			pdo_connect();
			$this->initProdData();

			//每秒检查一次K线数据
			Timer::add(1, function() {
				$this->checkKData();
			}, array(), true);

			$pingInterval = config("db.pingInterval");
			Timer::add($pingInterval, function() {
				logTrace("Pinging db ...");
				pdo_ping();
			}, array(), true);
		};
	}

	public function initProdData()
	{
		$sql = "select pid,ask,displayname from wp_product_lastprice";
		$rs = pdo_fetchAll($sql);
		if ($rs === false) {
			logError("failed to get lastest price");
			return false;
		}
		if (!empty($rs)) {
			foreach($rs as $row) {
				$this->prodInfo[$row["pid"]]["price"] = $row["ask"];
				$this->prodInfo[$row["pid"]]["name"] = $row["displayname"];
				$this->prodInfo[$row["pid"]]["isopen"] = 1;
				$this->prodInfo[$row["pid"]]["k1time"] = 0;
				$this->prodInfo[$row["pid"]]["k5time"] = 0;
				$this->prodInfo[$row["pid"]]["k15time"] = 0;
				$this->prodInfo[$row["pid"]]["k30time"] = 0;
				$this->prodInfo[$row["pid"]]["k60time"] = 0;
			}
		}

		$openInfo = $this->getOpenInfo();
		if ($openInfo !== false) {
			foreach($openInfo as $pid=>$isOpen) {
				if ($pid != "success") {
					$this->prodInfo[$pid]['isopen'] = $isOpen;
				}
			}
		}
	}
	
	private function getLatestPrice()
	{
		$latest = array();
		
		$sql = "select pid,ask from wp_product_lastprice";
		$rs = pdo_fetchAll($sql);
		if ($rs === false) {
			logError("failed to get lastest price");
			return false;
		}
		if (!empty($rs)) {
			foreach($rs as $row) {
				$latest[$row["pid"]] = $row["ask"];
			}
		}
		return $latest;
	}
	
	private function checkKData()
	{
		//logDebug("checking data..");

		//获取产品的最新价格
		$recvtime = time();
		$latest = $this->getLatestPrice();
		if ($latest === false) {
			return;
		}
		$openInfo = $this->getOpenInfo();
		if ($openInfo === false) {
			$openInfo = array();
		}
		
		foreach($this->prodInfo as $pid => $prod) {
			if (isset($openInfo[$pid])) {
				$isopen = $openInfo[$pid];
			}
			else {
				$isopen = $this->prodInfo[$pid]['isopen'];
			}

			if ($latest[$pid] == $prod['price']) {
				//价格没有变化，只有在各种K线数据不存在时创建
				foreach(array(1,5,15,30,60) as $kt) {
					$starttime = $recvtime - $recvtime % (60 * $kt);
					if ($prod["k{$kt}time"] != $starttime) {
						$this->updateKData2($recvtime, $starttime, $pid, $prod["price"], $kt, $isopen);
					}
				}
			}
			else {
				//价格发生了变化
LogInfo("updating KDATA. pid=".$pid.",old=".$this->prodInfo[$pid]['price'].",new=".$latest[$pid]);
				$this->prodInfo[$pid]['price'] = $latest[$pid];
				foreach(array(1,5,15,30,60) as $ktype) {
					$this->updateKData1($recvtime, $pid, $latest[$pid], $ktype, $isopen);
				}				
			}
		}
	}

	private function getOpenInfo()
	{
		$pids = "";
		foreach($this->prodInfo as $pid=>$prod) {
			if ($pids == "") {
				$pids = $pid;
			}
			else {
				$pids .= ",".$pid;
			}
		}

		$data = array("pid"=>$pids, "time"=>time());
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->openInfoURL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 2);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
		$output = curl_exec($ch);
		curl_close($ch);

		//logDebug("openInfo=".$output);

		$rsp = json_decode($output, true);
		if ($rsp == null || !is_array($rsp) || !isset($rsp['success'])) {
			LogError("error response from webserver:".$output);
			return false;
		}
		return $rsp;
	}

	/**
	 * 价格有变化时的处理
	 */
	private function updateKData1($recvtime, $pid, $price, $ktype, $isopen)
	{
		$starttime = $recvtime - $recvtime % (60 * $ktype);
		$tablename = "wp_k" . $ktype . "price";
	
		$sql = "select id,high,low,isopen from {$tablename} where starttime=? and pid=?";
		$rs = pdo_fetchAll($sql, array([$starttime, PDO::PARAM_INT], [$pid, PDO::PARAM_INT]));
		if ($rs === false) {
			logError("failed to select K data");
			return false;
		}
		if (empty($rs)) {
			$sql = "insert into {$tablename} (pid,starttime,open,close,high,low,updatetime,isopen)
			values(?, ?, ?, ?, ?, ?, ?, ?)";
			if (!pdo_exec($sql, array([$pid, PDO::PARAM_INT], [$starttime, PDO::PARAM_INT], $price,
					$price, $price, $price, [$recvtime, PDO::PARAM_INT], $isopen))) {
				logError("faied to insert K data");
				return false;
			}
			$this->prodInfo[$pid]["k{$ktype}time"] = $starttime;
		}
		else {
			$row = $rs[0];
			$id = $row["id"];

			if ($row['isopen'] == $isopen) {
				//一直是开市或者一直是休市
				if ($row["high"] > $price) {
					$high = $row["high"];
				}
				else {
					$high = $price;
				}
				if ($row["low"] < $price) {
					$low = $row["low"];
				}
				else {
					$low = $price;
				}
			}
			else if ($row['isopen']==0 && $isopen==1) {
				//从休市变为开市
				$high = $price;
				$low = $price;
			}
			else {
				//从开市变为休市不更新数据库
				return;
			}

LogInfo("updating. pid=".$pid.",price=".$price.",high=".$high.",low=".$low);
			$sql = "update {$tablename} set close=?, high=?, low=?, updatetime=?, isopen=? where id=?";
			if (!pdo_exec($sql, array($price, $high, $low, [$recvtime,PDO::PARAM_INT], $isopen, [$id, PDO::PARAM_INT]))) {
				logError("faied to update K data");
				return false;
			}
			$this->prodInfo[$pid]["k{$ktype}time"] = $starttime;
		}
		return true;
	}
	
	/**
	 * 价格没有变化时的处理
	 */
	private function updateKData2($recvtime, $starttime, $pid, $price, $ktype, $isopen)
	{
		$tablename = "wp_k" . $ktype . "price";
		$sql = "select id from {$tablename} where starttime=? and pid=?";
		$rs = pdo_fetchAll($sql, array([$starttime, PDO::PARAM_INT], [$pid, PDO::PARAM_INT]));
		if ($rs === false) {
			logError("failed to select K data");
			return false;
		}
		if (empty($rs)) {
			$sql = "insert into {$tablename} (pid,starttime,open,close,high,low,updatetime,isopen)
			                              values(?, ?, ?, ?, ?, ?, ?, ?)";
			if (!pdo_exec($sql, array([$pid, PDO::PARAM_INT], [$starttime, PDO::PARAM_INT], $price,
				                    $price, $price, $price, [$recvtime, PDO::PARAM_INT], $isopen))) {
				logError("failed to insert K data");
				return false;
			}

			$this->prodInfo[$pid]["k{$ktype}time"] = $starttime;
		}
		else {
			$this->prodInfo[$pid]["k{$ktype}time"] = $starttime;
		}

		return true;
	}
}


$saver = new DataSaver();

// 如果不是start.php启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
	Worker::runAll();
}

