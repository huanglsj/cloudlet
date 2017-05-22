<?php

abstract class PayCheckStateEnum {
	//提交审核
	const SUBMIT = 1;

	//成功
	const SUCCESS = 2;

	//失败
	const FAIL = 3;
	
	//系统自动审核通过
	const AUTO_SUCCESS = 100;
}
