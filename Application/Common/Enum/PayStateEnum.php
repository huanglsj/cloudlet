<?php

abstract class PayStateEnum {
	//未开始(还未提交银行/支付宝/微信）
	const NOT_START = 0;

	//开始
	const START = 1;

	//成功
	const SUCCESS = 2;

	//失败
	const FAIL = 3;
}
