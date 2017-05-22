<?php

$config = [
	"db" => [
		"host" => "127.0.0.1",
		"port" => 3306,
		"database" => "weipan",
		"username" => "weipan",
		"password" => "cd8AaRaWzj",
		"charset" => "utf8",
		"pingInterval" => 120
	],
	
	"dataserver" => [
		"ip" => "data.tortoisecloud.com",
		"port" => "15347",
		"username" => "testuser1",
		"password" => "TestUSER213",
	],
		
	"worker" => [
		"receiver" => [
			"loginTimeout" => 60,
			"heartbeatInterval" => 100
		],
		"broadcaster" => [
			"workerCount" => 2,
			"websocketPort" => 25346,
			"checkClientInterval" => 300 
		],
		"saver" => [
			"openInfoURL"=>"www.euamote.me/index.php/Admin/Goods/isOpen.html"
		]
	]
];


function config($key)
{
    global $config;

    $keys = explode(".", $key);
    $cnt = count($keys);
    if ($cnt == 1) {
        return $config[$keys[0]];
    }
    else if($cnt == 2) {
        return $config[$keys[0]][$keys[1]];
    }
    else if ($cnt == 3) {
        return $config[$keys[0]][$keys[1]][$keys[2]];
    }
    else if ($cnt == 4) {
        return $config[$keys[0]][$keys[1]][$keys[2]][$keys[3]];
    }
    else {
        return null;
    }
}

