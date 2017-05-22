<?php

abstract class PayWayEnum {
	//微信支付
	const WEIXIN = 1;
	
	//支付宝支付
	const ALIPAY = 2;
	
	//支付宝支付（浏览器发起）
	//const ALIPAY_BROWSER = 3;
	
	//微信扫码
	const WEIXIN_QRCODE = 4;
	
	//银联
	const UNIONPAY = 5;
	
	//支付宝扫码
	const ALIPAY_QRCODE = 6;
}
