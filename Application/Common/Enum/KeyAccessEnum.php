<?php

abstract class KeyAccessEnum {
	//客户登录系统
	const CUSTOMER_LOGIN = 1;
	
	//总公司系统用户登录
	const SYSTEMUSER_LOGIN = 2;
	
	//会员用户登录
	const MEMBER_LOGIN = 3;
	
	//非法访问
	const UNKNOWN_LOGIN = 4;
	
}
