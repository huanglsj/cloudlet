<?php

use Workerman\Worker;
use Workerman\Connection\AsyncTcpConnection;
use Workerman\Lib\Timer;

require_once 'func.php';
require_once 'db.php';
require_once 'logger.php';
require_once __DIR__ . '/vendor/Workerman/Autoloader.php';


class DataReceiver
{
	private $loginTimer;
	private $loginToken;
	private $heartbeatTimer;
	private $prodInfo;
	private $receiver;
	private $openInfoURL;
	
	public function __construct()
	{	
		$this->openInfoURL = config("worker.saver.openInfoURL");
		$this->receiver = new Worker();
		$this->receiver->count = 1;
		$this->receiver->name = "receiver";
		$this->prodInfo = array();

		$this->receiver->onWorkerStart = function() {
			pdo_connect();
			$this->initProdData();
			$this->connectDataSource();
		};
	}

	private function connectDataSource()
	{
		logInfo("begin connect datasource");

		$dataserver = "ws://".config("dataserver.ip").":".config("dataserver.port");
		$connection = new AsyncTcpConnection($dataserver);
		$connection->onConnect = function($connection) use($dataserver) {
			logInfo("connected to data source");
			$this->login($connection);

			$heartbeatInterval = (int)config("worker.receiver.heartbeatInterval");
			$this->heartbeatTimer = Timer::add($heartbeatInterval, function() use ($connection) {
				if (isset($this->loginToken)) {
					logTrace("sending heartbeat to dataserver");
					$req = array('action'=>'keepalive', 'token'=>$this->loginToken);
					$connection->send(json_encode($req));
				}
			}, array(), true);

		};

		$connection->onMessage = function($connection, $msg) {
			logTrace("message received:".$msg);

			$recvtime = time();
			$data = json_decode($msg);
			if (is_object($data)) {
				if (isset($data->success)) {
					$this->onReceiveCtrlData($data);
				}
				else {
					$this->saveRTData($recvtime, $data);
				}
			}
			else {
				foreach($data as $item) {
					$this->saveRTData($recvtime, $item);
				}
			}
		};

		$connection->onError = function($connection, $code, $msg) {
			logError("error occured on connection to data source");
		};

		$connection->onClose = function($connection) {
			logError("connection to data source closed. retring...");
			if (isset($this->heartbeatTimer)) {
				Timer::del($this->heartbeatTimer);
				unset($this->heartbeatTimer);
			}
			unset($this->loginToken);

			Timer::add(5, function() use ($connection) {
				$this->connectDataSource();
            }, array(), false);
		};

		$connection->connect();	
	}

	private function onReceiveCtrlData($data) {
		if ($data->success == 1) {
			if (isset($data->token)) {
				$this->loginToken = $data->token;
				if (isset($this->loginTimer)) {
					Timer::del($this->loginTimer);
					unset($this->loginTimer);
				}
			}
			else {
				logWarn("invalid response from dataserver:".json_encode($data));
			}
		}
		else {
			logError("login failed:".json_encode($data));
		}
	}
	
	private function login($connection) {
		$login_info = ['action'=>'login', 'username'=>config("dataserver.username"), 'password'=>config("dataserver.password")];
		$connection->send(json_encode($login_info));
		
		$loginTimeout = (int)config("worker.receiver.loginTimeout");
		$this->loginTimer = Timer::add($loginTimeout, function() use($connection) {
			logError("login timeout");
			//调用destroy时，会触发onClose()事件
			$connection->destroy();
		}, array(), false);
	}

	public function initProdData()
	{
		$sql = "select pid,code,ask,displayname from wp_product_lastprice";
		$rs = pdo_fetchAll($sql);
		if ($rs === false) {
			logError("failed to get lastest price");
			return false;
		}
		if (!empty($rs)) {
			foreach($rs as $row) {
				$this->prodInfo[$row["code"]]["pid"] = $row["pid"];
				$this->prodInfo[$row["code"]]["name"] = $row["displayname"];
			}
		}
	}

	private function getOpenInfo($pid, $time)
	{
		$data = array("pid"=>$pid, "time"=>$time);
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
		return $rsp[$pid];
	}
	
	public function saveRTData($recvtime, $data)
	{
		$pid = $this->prodInfo[$data->c]["pid"];
		$isopen = $this->getOpenInfo($pid, $recvtime);

		$sql = "select patx,patj from wp_productinfo where pid=?";
		$rs = pdo_fetchSingle($sql, array([$pid, PDO::PARAM_INT]));
		if ($rs === false) {
			logError("failed to get patx,patj. dberror");
			return false;
		}
		if ($rs === null) {
			logError("failed to get patx,patj.pid not exist, pid=".$pid);
			return false;
		}
		$patx = $rs['patx'];
		$patj = $rs['patj'];

		$lc = $data->lc * $patx + $patj;
		$o = $data->o * $patx + $patj;
		$h = $data->h * $patx + $patj;
		$l = $data->l * $patx + $patj;
		$p = $data->p * $patx + $patj;
		$b = $data->b * $patx + $patj;
		$s = $data->s * $patx + $patj;
		$d = $data->d * $patx;

		$sql = "select lpid from wp_product_lastprice where pid=?";
		$rs = pdo_fetchAll($sql, array([$pid, PDO::PARAM_INT]));
		if ($rs === false) {
			logError("faied to get latest price");
			return false;
		}
		if (empty($rs)) {
			/*
				$sql = "insert into wp_product_lastprice(recvtime,dealtime,pid,lastClosePrice,openPrice,highprice,lowprice,newprice,Volume,BuyPrice,SellPrice,diff,diffrate)
				values(?,?,?,?,?,?,?,?,?,?,?,?,?)";
				if (!pdo_exec($sql, array([$recvtime,PDO::PARAM_INT], [$data->t, PDO::PARAM_INT],[$pid, PDO::PARAM_INT], $data->lc, $data->o,
				$data->h, $data->l, $data->p, $data->v, $data->b, $data->s, $data->d, $data->df))) {
				logError("faied to insert latest price");
				return false;
				}
				*/
		}
		else {
			$id = $rs[0]["lpid"];
			$sql = "update wp_product_lastprice set eidtime=?,close=?,open=?,high=?,low=?,ask=?,
				    volume=?,buy=?,sell=?,diff=?,diffrate=? where lpid=?";
			if (!pdo_exec($sql, array([$recvtime,PDO::PARAM_INT], $lc, $o, $h, $l, $p, $data->v, $b, $s, $d, $data->df, [$id, PDO::PARAM_INT]))) {
				logError("faied to update latest price");
				return false;
			}
		}
	
		$sql = "select id from wp_realtimeprice where recvtime=? and pid=?";
		$rs = pdo_fetchAll($sql, array([$recvtime, PDO::PARAM_INT], [$pid, PDO::PARAM_INT]));
		if ($rs === false) {
			logError("faied to select relatimeprice");
			return false;
		}
		if (empty($rs)) {
			$sql = "insert into wp_realtimeprice(recvtime,dealtime,pid,lastClosePrice,openPrice,highprice,lowprice,newprice,Volume,BuyPrice,SellPrice,diff,diffrate,isopen)
			       values(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
			if (!pdo_exec($sql, array([$recvtime, PDO::PARAM_INT], [$data->t, PDO::PARAM_INT], [$pid, PDO::PARAM_INT],
				    $lc, $o, $h, $l, $p, $data->v, $b, $s, $d, $data->df, $isopen))) {
				logError("faied to insert relatimeprice");
				return false;
			}
		}
		else {
			$id = $rs[0]["id"];
			$sql = "update wp_realtimeprice set dealtime=?, lastClosePrice=?, openPrice=?, highprice=?, lowprice=?, newprice=?,
				   Volume=?, BuyPrice=?, SellPrice=?, diff=?, diffrate=? where id=?";
			if (!pdo_exec($sql, array([$data->t, PDO::PARAM_INT], $lc, $o, $h, $l, $p, $data->v, $b, $s, $d, $data->df, [$id, PDO::PARAM_INT]))) {
				logError("faied to update relatimeprice");
				return false;
			}
		}
		return true;
	}

}

$receiver = new DataReceiver();

// 如果不是start.php启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
	Worker::runAll();
}

