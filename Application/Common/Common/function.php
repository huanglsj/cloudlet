<?php
if (!function_exists('cal_days_in_month')) 
{ 
    function cal_days_in_month($calendar, $month, $year) 
    { 
        return date('t', mktime(0, 0, 0, $month, 1, $year)); 
    } 
} 
if (!defined('CAL_GREGORIAN')) 
    define('CAL_GREGORIAN', 1); 

function post($url, $post_data = array(), $timeout = 10){
	if (!is_array($post_data)) {
		return false;
	}

	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
	$file_contents = curl_exec($ch);
	curl_close($ch);

	return $file_contents;
}


/**
 * 
 * @param string $date yyyy-mm-dd
 */
function getStartTimeOfDay($date) {
	return mktime(0,0,0, substr($date, 5, 2), substr($date,8, 2), substr($date, 0, 4));
}


function getLoginToken() {
	return $_SESSION['logintoken'];
}

function setLoginToken($token) {
	$_SESSION['logintoken'] = $token;
}
