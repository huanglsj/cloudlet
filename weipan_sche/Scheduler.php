<?php

require_once 'func.php';
require_once 'config.php';
require_once 'logger.php';
use Workerman\Worker;
use Workerman\Lib\Timer;
require_once __DIR__ . '/vendor/Workerman/Autoloader.php';


class Scheduler
{
	private $taskConfig;
	private $scheduler;
	public function __construct()
	{
		$this->taskConfig = array();
		$tasks = config("tasks");
		$taskId = 0;
		foreach ($tasks as $name=>$task) {
			$this->taskConfig[$taskId] = $task;
			$this->taskConfig[$taskId]['name'] = $name;
			$taskId++;
		}

		$this->createScheduler();
	}

	private function createScheduler()
	{
		$this->scheduler = new Worker();
		if ($this->scheduler == null) {
			logError("scheduler start error!");
			return;
		}

		// 只能启动1个进程对外提供服务
		$this->scheduler->count = count($this->taskConfig);
		$this->scheduler->name = "scheduler";
		$this->scheduler->onWorkerStart = function($worker) {
			$task = $this->taskConfig[$worker->id];
			Timer::add($task['interval'], function($taskId) {
				$this->execTask($taskId);
			}, $worker->id, true);
		};
	}

	public function execTask($taskId)
	{
		$task = &$this->taskConfig[$taskId];
		LogDebug("begin exec Task ".$task['name']);

		$url = $task['url'];
		$method = $task['method'];
		if (isset($task['params'])) {
			$params = $task['params'];
		}
		else {
			$params = array();
		}
		if ($method == 'get') {
			http_get($url, $params);
		}
		else if ($method == 'post') {
			http_post($url, $params);	
		}
		LogDebug("end exec Task ".$task['name']);
	}
}


$scheduler = new Scheduler();

// 如果不是main.php启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
	Worker::runAll();
}

