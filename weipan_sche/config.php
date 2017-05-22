<?php

$config = [
	"tasks" =>[
		"closeOrderByTime"=>[
			"interval" => 1,
			"url" => "http://www.euamote.me/index.php/Scheduler/OrderClose/closeOrderByTime.html",
			"method" => "get"
		],
		"closeOrderByPrice"=>[
			"interval" => 1,
			"url" => "http://www.euamote.me/index.php/Scheduler/OrderClose/closeOrderByPrice.html",
			"method" => "get"
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

