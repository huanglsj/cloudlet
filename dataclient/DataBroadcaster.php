<?php

require_once 'func.php';
require_once 'db.php';
require_once 'logger.php';
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/vendor/Workerman/Autoloader.php';


class DataBroadcaster
{
	private $broadcaster;
	private $wsClients;
	private $priceInfo;

	public function __construct()
	{
		$this->wsClients = array();
		$this->priceInfo = array();
		$this->createBroadcaster();
	}

	private function createBroadcaster()
	{
		// 创建一个Worker监听2346端口，使用websocket协议通讯
		$port = config("worker.broadcaster.websocketPort");
		$this->broadcaster = new Worker("websocket://0.0.0.0:" . $port);
		if ($this->broadcaster == null) {
			logError("websocket start error!");
			return;
		}

		// 启动4个进程对外提供服务
		$this->broadcaster->count = config("worker.broadcaster.workerCount");
		$this->broadcaster->name = "broadcaster";

		$this->broadcaster->onWorkerStart = function() {
			pdo_connect();
			$this->initPriceInfo();

			Timer::add(0.5, function() {
				$this->broadcastPrice();
			}, array(), true);

			// 每隔一定时间，判断client是不是还活着
			$checkInterval = config("worker.broadcaster.checkClientInterval");
			Timer::add($checkInterval, function() {
				foreach($this->wsClients as $cid=>$client) {
					if ($client["isActive"] == 1) {
						$this->wsClients[$cid]["isActive"] = 0;
					}
					else {
						logDebug("client heartbeat timeout. connID=".$cid);
						$client["conn"]->close();
						unset($this->wsClients[$cid]);
					}
				}
			}, array(), true);
			
			$pingInterval = config("db.pingInterval");
			Timer::add($pingInterval, function() {
				logDebug("pinging db...");
				pdo_ping();
			}, array(), true);
		};

		$this->broadcaster->onConnect = function($connection)
		{
			logTrace("client connected. workerID=".$this->broadcaster->id.",clientIP=".$connection->getRemoteIp());
			$this->wsClients[$connection->id] = array("conn"=>$connection, "login"=>0, "isActive"=>1);
		};

		$this->broadcaster->onClose = function($connection)
		{
			logDebug("connection to client closed. connID=".$connection->id);
			unset($this->wsClients[$connection->id]);
		};

		$this->broadcaster->onMessage = function($connection, $data)
		{
			if (isset($this->wsClients[$connection->id])) {
				$this->wsClients[$connection->id]["isActive"] = 1;
			}

			if ($data == "") {
				return;
			}

			logDebug("receive message from client:".$data);
			$cmd = json_decode($data);
			if ($cmd == null) {
				return;
			}

			if (!isset($cmd->action)) {
				return;
			}

			if ($cmd->action == "login") {
				if (!isset($cmd->username) || !isset($cmd->token)) {
					return;
				}
				if ($this->doUserLogin($cmd->username, $cmd->token)) {
					logDebug("client login ok. connId=".$connection->id.",username=".$cmd->username);
					$this->wsClients[$connection->id]["login"] = 1;
				}
			}
			else {
				return;
			}

		};
	}

	private function doUserLogin($username, $token)
	{
		$sql = "select token from wp_logininfo where username=? and loginStatus=1";
		$user = pdo_fetchSingle($sql, array($username));
		if ($user === false) {
			logError("failed to get user logintoken");
			return;
		}
		if ($user === null) {
			logWarn("this user has not been login. username=".$username);
			return;
		}

		$mytoken = md5($username.date('Ymd', strtotime('-3 hours')).'Jugui');
		if ($token == $mytoken) {
			return true;
		}
		else {
			logError("incorrect login. username=".$username .", token=".$token);
			return false;
		}
	}
	
	public function broadcastPrice()
	{
		$sql = "select pid,code,ask,high,low,open,close,diff,diffrate,eidtime from wp_product_lastprice";
		$rs = pdo_fetchAll($sql);
		if ($rs === false) {
			logError("failed to get lastest price");
			return false;
		}

		foreach($rs as $prod) {
			if ($prod['ask'] == $this->priceInfo[$prod['pid']]['price']) {
				continue;
			}
			logDebug("price changed. pid=".$prod["pid"].",old=".$this->priceInfo[$prod['pid']]['price'].",new=".$prod["ask"]);
			
			//价格有变化
			$this->priceInfo[$prod["pid"]]["price"] = $prod["ask"];
			$data = array('pid'=>$prod["pid"],
			              'c'=>$prod['code'],
					      'p'=>$prod['ask'],
					      'l'=>$prod['low'],
					      'h'=>$prod['high'],
					      'o'=>$prod['open'],
					      'lc'=>$prod['close'],
					      'd'=>$prod['diff'],
					      'df'=>$prod['diffrate'],
					      't'=>$prod['eidtime']
				);
			$msg = json_encode($data);
			foreach($this->wsClients as $client) {
				if ($client["login"] == 1) {
					$client["conn"]->send($msg);
				}
			}
		}
	}
	
	public function initPriceInfo()
	{
		$sql = "select pid,ask from wp_product_lastprice";
		$rs = pdo_fetchAll($sql);
		if ($rs === false) {
			logError("failed to get lastest price");
			return false;
		}
		if (!empty($rs)) {
			foreach($rs as $row) {
				$this->priceInfo[$row["pid"]]["price"] = $row["ask"];
			}
		}
	}

}


$broadcaster = new DataBroadcaster();

// 如果不是start.php启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
	Worker::runAll();
}

