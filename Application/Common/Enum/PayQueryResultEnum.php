<?php

//交易查询状态
abstract class PayQueryResultEnum {
	//查询本身失败
	const QUERY_FAIL = 1;
	
	//查询的交易不存在
	const PAY_NOT_EXIST = 2;
	
	//支付成功
	const PAY_SUCCESS = 10;
	
	//支付失败
	const PAY_FAIL = 11;
	
	//支付状态未知，需再次查询
	const PAY_QUERY_AGAIN = 12;
}
