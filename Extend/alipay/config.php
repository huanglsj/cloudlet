<?php
date_default_timezone_set('PRC');

$alipay_config = array (
		//应用ID,您的APPID。
		'app_id' => "2017050807168937",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => 
		"MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAIMfs7PjUb8D8ktv9edJICB4jaSR3DVpC4i21U+bxD5/n9dc50fXodGJkcYY/dnu9EjrOQt3zdqvHXflbJ+AkH2x4UP641Khk0wBcsizUP24DF9jYy7Ih601YWYRn3ucJJM63lGL6Vu3+frKnN42YetGlaLkW60c7AV8zIXPylF/AgMBAAECgYAKqPkjFsf+j4OTPnbvZrKF8UcSqgkNDo0xgCu3XSKHMjj8eUEURiORtW10fXOl1BdoFjd9BzBlJvduV+iMzxbwAsMyGCFsdKqs1yE7zEsI+3ZWI9/hfgGJ6jr+h9iUllzzDQwj3wLe/9iaHZrzz/UJJSz/KwQB/EQuQCX6jjOrAQJBAN1UHtfkO2DeaZNNLhxQQZTuXkBaiCmisSzikDh5/LdP5iYLe+3JyATbSLRkHLuyBNzbU1gQstWef6kLF/5zZKcCQQCXqh/62fg1mee5lCp37H5S0EgkSDNoKo/XDvFjLuRsD2tBc3aF97JR48pjn7Pi0IUOt8u0HcOuylOmMex2tc9pAkB+vAd5SggyPMkpfr1TmyUieafgo7ZqWO2pLQa2QCvUb9zylgrdq3hsR4CHQvgtBg/Aw5oiyFUO+1ZQXrjbjAnrAkA5lzppkRd1kymxCJhPzZfybnDWhiwvI+pW6a+zz/yhJAHAas3Y9UPbYLpbtisit7eu7RAHJz5FQ0McWtzF/yfxAkEAw4NqLAeRc9SJ2ZYnEFsMj3EQfNgFTFCMTdDhpZZ6JOx36Pc2Dyt0iCjGMZSiIEKv4RI7YLzGihJHuD91UPemDQ==",

		'charset' => "UTF-8",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",
        //'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥
		//'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306mvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB",
		//'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDGgKBkV+hiH0jADpFrw7ObuOi6nB4VQiifP2cTULrJxtnNaNTIfCGWPTffnozO+6RLnvoyCralFNJef6lzF0iOFEs3suATVdnjUHizik5CSxqn4x4JNUvT7MNVYIHY1D0lOp/SEwM9WUzosg+H2wIDAQAB"
		'alipay_public_key' => "MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDDI6d306Q8fIfCOaTXyiUeJHkrIvYISRcc73s3vF1ZT7XN8RNPwJxo8pWaJMmvyTn9N4HQ632qJBVHf8sxHi/fEsraprwCtzvzQETrNRwVxLO5jVmRGi60j8Ue1efIlzPXV9je9mkjzOmdssymZkh2QhUrCmZYI/FCEa3/cNMW0QIDAQAB"
);

$GLOBALS['alipay_config'] = $alipay_config;
