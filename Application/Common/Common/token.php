<?php

function createToken()
{
	$sql = "select nextval('TOKEN') as seq";
	$model = new \Think\Model();
	$seq = $model->query($sql)[0]["seq"];
	$str = session_id() . $_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR'] . microtime() . uniqid() . $seq;
	return hash("sha256", $str, false);
}


function createOrderNo($type)
{
	$sql = "select nextval('". $type . "_ORDER_SEQ') as seq";
	$model = new \Think\Model();
	$seq = $model->query($sql)[0]["seq"];
	$seq = $seq % 1000000;

	return $type . date('YmdHis') . mt_rand(100, 999) . sprintf("%06d", $seq);
}
