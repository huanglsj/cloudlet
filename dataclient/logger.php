<?php

require_once "vendor/log4php/Logger.php";

$log4phpConfig = array(
    'rootLogger' => array(
		'level' => "trace",
        'appenders' => array('default'),
    ),
    'appenders' => array(
        'default' => array(
            'class' => 'LoggerAppenderDailyFile',
            'layout' => array(
                'class' => 'LoggerLayoutPattern',
				'params' => array(
					'conversionPattern' => '%date{Y-m-d H:i:s,u} [%level][%pid] %message%newline%ex'
				)
            ),
            'params' => array(
				'datePattern' => 'Ymd',
            	'file' => __DIR__ . '/log/dataclient-%s.log',
            	'append' => true
            )
        )
    )
);

Logger::configure($log4phpConfig);
$logger = Logger::getLogger(__FILE__);

function logTrace($message, $throwable = null) {
	global $logger;
	$logger->trace($message, $throwable);
}

function logDebug($message, $throwable = null) {
	global $logger;
	$logger->debug($message, $throwable);
}

function logInfo($message, $throwable = null) {
	global $logger;
	$logger->info($message, $throwable);
}

function logWarn($message, $throwable = null) {
	global $logger;
	$logger->warn($message, $throwable);
}

function logError($message, $throwable = null) {
	global $logger;
	$logger->error($message, $throwable);
}

function logFatal($message, $throwable = null) {
	global $logger;
	$logger->fatal($message, $throwable);
}

