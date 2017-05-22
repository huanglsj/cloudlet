<?php
return array(
    //'配置项'=>'配置值'
     /* 数据库设置 */
    'DB_TYPE'               =>  'mysqli',     // 数据库类型
    'DB_HOST'               =>  '127.0.0.1', // 服务器地址
    'DB_NAME'               =>  'wpdemo',          // 数据库名
    'DB_USER'               =>  'root',      // 用户名
    'DB_PWD'                =>  '',          // 密码
    'DB_PORT'               =>  '3306',        // 端口
    'DB_PREFIX'             =>  'wp_',    // 数据库表前缀
    'DB_FIELDTYPE_CHECK'    =>  false,       // 是否进行字段类型检查
    'DB_FIELDS_CACHE'       =>  true,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
    'DB_DEPLOY_TYPE'        =>  0, // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
    'DB_RW_SEPARATE'        =>  false,       // 数据库读写是否分离 主从式有效
    'DB_MASTER_NUM'         =>  1, // 读写分离后 主服务器数量
    'DB_SLAVE_NO'           =>  '', // 指定从服务器序号
    'DB_SQL_BUILD_CACHE'    =>  false, // 数据库查询的SQL创建缓存
    'DB_SQL_BUILD_QUEUE'    =>  'file',   // SQL缓存队列的缓存方式 支持 file xcache和apc
    'DB_SQL_BUILD_LENGTH'   =>  20, // SQL缓存的队列长度
    'DB_SQL_LOG'            =>  false, // SQL执行日志记录
    'DB_BIND_PARAM'         =>  false, // 数据库写入数据自动参数绑定
    'SHOW_PAGE_TRACE' =>false, // 显示页面Trace信息

    // /* 错误页面模板 */
    // 'TMPL_ACTION_ERROR'     =>  MODULE_PATH.'View/Public/error.html', // 默认错误跳转对应的模板文件
    // 'TMPL_ACTION_SUCCESS'   =>  MODULE_PATH.'View/Public/success.html', // 默认成功跳转对应的模板文件
    // 'TMPL_EXCEPTION_FILE'   =>  MODULE_PATH.'View/Public/exception.html',// 异常页面的模板文件

	'AUTOLOAD_NAMESPACE' => array(
		'Lib'     => APP_PATH.'Lib'
	),
    
    //阿里大于短信
    'DAYU_SMS_APPKEY' => '23808959',
    'DAYU_SMS_APPSECRET' => '22083dd9e9d3e97591bce6e1d0f9dab2',
    'DAYU_SMS_EXTEND' => '123321',
    'DAYU_SMS_SIGNNAME' => '弘界',
    'DAYU_SMS_VERIFY_TEMPLATE' => 'SMS_66605037', //不用修改
    'DAYU_SMS_SENT_PASSWORD' => 'SMS_66005077',
    'DAYU_SMS_RESET_PASSWORD' => 'SMS_65155115',
    'DAYU_SMS_PRODUCT' => '弘界',

	'LOG_RECORD' => true, // 开启日志记录
	'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR,WARN,NOTICE,INFO,DEBUG,SQL', // 只记录EMERG ALERT CRIT ERR 错误
	//'LOG_PATH' => 
    
    //交易所代码
    'BOURSE' => array(
        'TJGJS' => '津贵所',
        'WTI'  => 'WTI原油',
        'LDJS' => '伦敦金属',
    ),
    //品种
    'GOODSTYPE' => array(
        'BTC' => '比特币BTC',
        'SRM' => '白糖SRM',
        'Re' => '稀土Re',
    ),
    //交易状态
    'STATUS' => array(
        '1' => '可以交易',
        '2' => '停止交易',
    ),

	//支付查询间隔设置
	'PAY_QUERY' => array(
		//微信jsapi支付
		'wexin_jsapi' => array(1,2,4,8,16,32),
		
		//支付宝
		'alipay_wap' => array(1,2,4,8,16,32),
			
		//微信企业付款(1s、2s、4s、8s、16s)
		'weixin_qiye' => array(1,2,4,8,16,32),

		//银联wap支付(5分、10分、30分、60分、120分)
		'unionpay_wap' => array(300,600,1800,3600,7200),

		//银联代付（1s，2s, 4s, 8s, 16s, 32s, 64s）
		'unionpay_df' => array(1,2,4,8,16,32,64,128)
	),
    
    //平台归属用户名
    'PLATFORM_USERNAME' => '微云交易',
		
	'REPORT_DIR' => "/mnt/cms/report",
    
//     'ALIYUN_ACCESS_KEY' => array(
//         'ID' => 'LTAIr3f5FtT94dtn',
//         'SECRET' => 'IgSJUkcYpndCGvyptSMnHx4dicqzzd',
//         'SECURITY_APP' => 'FFFF0000000001682C2C'
        
//     )
    //Testx
    'ALIYUN_ACCESS_KEY' => array(
        'ID' => 'LTAIzs8hq1LJBBRC',
        'SECRET' => 'TzcLqkXgjriw5q2WZOsTEbRdtoEtKi',
        'SECURITY_APP' => 'FFFF00000000016874FD'
    ),
    
//     'DATA_HOST_INFO' => array(
//         'HOST' => '47.93.85.108',
//         'PORT' => '25346'
//     ),
    
    //Test
    'DATA_HOST_INFO' => array(
        'HOST' => 'www.euamote.me',
        'PORT' => '25346'
    ),

    'default_yaoqing_code'=>'520446', //默认推荐码

    'DOMAIN' => 'http://demo.euamote.me', //网站域名

    'ADMIN_PHONE' => '13539775127', //管理员手机号码
		
);
