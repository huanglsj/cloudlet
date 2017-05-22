<?php
require_once 'config.php';
require_once 'logger.php';

function http_post($url, $postdata) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    if (!empty($postdata)) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
    }
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, config("datasrc.authtimeout"));
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_USERAGENT, 'curl');
    $response = curl_exec($ch);
    curl_close($ch);

	return $response;
}

