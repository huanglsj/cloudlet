<?php
$con = mysql_connect("127.0.0.1","root","klssklss.");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("weipan", $con);

$result = mysql_query("SELECT * FROM Persons ORDER BY age");

while($row = mysql_fetch_array($result))
  {
  echo $row['FirstName'];
  echo " " . $row['LastName'];
  echo " " . $row['Age'];
  echo "<br />";
  }


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

mysql_close($con);
?>
