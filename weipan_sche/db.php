<?php

require_once "config.php";
require_once "logger.php";

function pdo_connect($force = false) {
	static $pdo;

	if (isset($pdo) && !$force) {
		return $pdo;
	}
	if (isset($pdo)) {
		$pdo = null;
	}

	$cfg = config("db");
	$dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['database']}";
	try {
		logTrace("connecting...");
		$pdo = new PDO($dsn, $cfg['username'], $cfg['password'], array(PDO::MYSQL_ATTR_FOUND_ROWS => true));
		logTrace("connected");
	}
	catch (\Exception $e) {
		logError("Connect failed.", $e);
		return false; 
	}
	$sql = "SET NAMES '{$cfg['charset']}';";
	$pdo->exec($sql);
	$pdo->exec("SET sql_mode='';");
	return $pdo;
}

function pdo_disconnected($errorinfo) {
	$code = $errorinfo[1];
	if ($code == 2006 || $code == 2013) {
		return true;
	}
	else {
		return false;
	}
}

function pdo_prepare($sql)
{
	$pdo = pdo_connect();
	if(!$pdo) {
		return false;
	}

}

function pdo_fetchAll($sql, $params=array())
{
	$force = false;
	do {
		$pdo = pdo_connect($force);
		if (!$pdo) {
			return false;
		}

		$stmt = $pdo->prepare($sql);
		$num = count($params);
		for($i=0; $i<$num; $i++) {
			$p = $params[$i];
			if (is_array($p)) {
				$stmt->bindValue($i+1, $p[0], $p[1]);
			}
			else {
				$stmt->bindValue($i+1, $p, PDO::PARAM_STR );
			}
		}
		if ($stmt->execute() === false) {
			if (pdo_disconnected($stmt->errorInfo())) {
				logWarn("connection has gone away");
				$force = true;
				continue;	
			}
			else {
				logWarn("stmt exec error:".$stmt->errorInfo()[2]);
				return false;
			}
		}
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if ($result === false) {
			if (pdo_disconnected($stmt->errorInfo())) {
				logWarn("connection has gone away");
				$force = true;
				continue;	
			}
			else {
				logWarn("result fetch error:".$stmt->errorInfo()[2]);
				return false;
			}
		}
		$stmt->closeCursor();
		$stmt = null;
		return $result;
	} while(true);
}

function pdo_fetchSingle($sql, $params = array())
{
	$result = pdo_fetchAll($sql, $params);
	if ($result === false) {
		return false;
	}
	if (empty($result)) {
		return null;
	}
	return $result[0];
}


function pdo_exec($sql, $params=array())
{
	$force = false;
	do {
		$pdo = pdo_connect($force);
		if (!$pdo) {
			return false;
		}

		if (empty($params)) {
			$result = $pdo->exec($sql);
			if ($result === false) {
				if (pdo_disconnected($pdo->errorInfo())) {
					logWarn("connection has gone away");
					$force = true;
					continue;	
				}
				else {
					logWarn("sql exec error:".$pdo->errorInfo()[2]);
					return false;
				}
			}
			return $result;
		}
		else {
			$stmt = $pdo->prepare($sql);
			$num = count($params);
			for($i=0; $i<$num; $i++) {
				$p = $params[$i];
				if (is_array($p)) {
					$stmt->bindValue($i+1, $p[0], $p[1]);
				}
				else {
					$stmt->bindValue($i+1, $p, PDO::PARAM_STR );
				}
			}

			if ($stmt->execute() === false) {
				if (pdo_disconnected($stmt->errorInfo())) {
					logWarn("connection has gone away");
					$force = true;
					continue;	
				}
				else {
					logWarn("stmt exec error:".$stmt->errorInfo()[2]);
					return false;
				}
			}

			return $stmt->rowCount();
		}

	} while(true);
}


function pdo_beginFetch($sql, $params=array())
{
	$force = false;
	do {
		$pdo = pdo_connect($force);
		if (!$pdo) {
			return false;
		}

		$stmt = $pdo->prepare($sql);
		$num = count($params);
		for($i=0; $i<$num; $i++) {
			$p = $params[$i];
			if (is_array($p)) {
				$stmt->bindValue($i+1, $p[0], $p[1]);
			}
			else {
				$stmt->bindValue($i+1, $p, PDO::PARAM_STR );
			}
		}
		if ($stmt->execute() === false) {
			if (pdo_disconnected($stmt->errorInfo())) {
				logWarn("connection has gone away");
				$force = true;
				continue;
			}
			else {
				logWarn("stmt exec error:".$stmt->errorInfo()[2]);
				return false;
			}
		}
		return $stmt;
		
	} while(true);
}

function pdo_fetchNext(&$stmt) {
	$result = $stmt->fetch(PDO::FETCH_ASSOC);
	if ($result === false) {
		if (pdo_disconnected($stmt->errorInfo())) {
			logWarn("connection has gone away");
		}
		else if ($stmt->errorInfo()[0] != '00000') {
			logWarn("result fetch error:".$stmt->errorInfo()[2]);
		}
	}
	
	return $result;
}

function pdo_endFetch(&$stmt) {
	$stmt->closeCursor();
	$stmt = null;
}



function pdo_beginTrans()
{
	$force = false;
	do {
		$pdo = pdo_connect($force);
		if (!$pdo) {
			return false;
		}
	
		try {
			$res = $pdo->beginTransaction();
			if ($res === false) {
				if (pdo_disconnected($pdo->errorInfo())) {
					logWarn("connection has gone away");
					$force = true;
					continue;
				}
				else {
					logWarn("sql exec error:".$pdo->errorInfo()[2]);
					return false;
				}
			}
			else {
				return true;
			}
		}
		catch (\Exception $e) {
			logError("failed to begin transaction", $e);
			return false;
		}

	} while(true);
}

function pdo_commit()
{
	$pdo = pdo_connect(false);
	if (!$pdo) {
		return false;
	}
	$pdo->commit();
}

function pdo_rollback()
{
	$pdo = pdo_connect(false);
	if (!$pdo) {
		return false;
	}
	$pdo->rollBack();
}


function pdo_insert($tablename, $record)
{
	$sql1 = "insert into {$tablename} (";
	$sql2 = ")values(";
	$params = array();
	$i = 0;
	foreach($record as $field => $value) {
		if ($i > 0) {
			$sql1 .= ",";
			$sql2 .= ',';
		}
		$sql1 .= "`{$field}`";
		$sql2 .= '?';
		
		$type = gettype($value);
		if ($type == "integer") {
			$params[$i] = [$value, PDO::PARAM_INT];
		}
		else if ($type == "NULL") {
			$params[$i] = [$value, PDO::PARAM_NULL];
		}
		else if ($type == "boolean") {
			$params[$i] = [$value, PDO::PARAM_BOOL];
		}
		else {
			$params[$i] = $value;
		}
		$i++;
	}
	
	$sql2 .= ')';
	return pdo_exec($sql1 . $sql2, $params);
}

function pdo_ping() {
	$force = false;
	do {
		$pdo = pdo_connect($force);
		$attr = $pdo->getAttribute(PDO::ATTR_SERVER_INFO);
		if ($attr == null) {
			if (pdo_disconnected($pdo->errorInfo())) {
				logWarn("connection has gone away");
				$force = true;
				continue;	
			}
			return;
		}
		return;
	} while (true);
}


