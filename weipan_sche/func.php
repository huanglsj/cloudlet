<?php

function http_post($url, $postdata) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_POST, 1);
	if (!empty($postdata)) {
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
	}
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
	curl_exec($ch);
	curl_close($ch);
}

function http_get($url, $params) {
	if (!empty($params)) {
		$params = http_build_query($params);
		$url .= "?".$params;
	}

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
	curl_exec($ch);
	curl_close($ch);
}


