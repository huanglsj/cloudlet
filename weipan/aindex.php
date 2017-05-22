<?php
	//只处理新华大宗数据，其他数据会报错//作者QQ：2698295603 淘宝：https://zaixuasd.taobao.com/  致力于金融数据
error_reporting(0);
function getData($code){
	if($code=='conc' || $code=='autd'|| $code=='agtd'|| $code=='xau'|| $code=='xag'|| $code=='shicom'|| $code=='ndxi'|| $code=='hsi' || $code=='diniw'|| $code=='gbpusd'){
	$url = "http://m.kxt.com/hQuotes/chart?code=".$code;
	$html = _url($url);
	if(!empty($html)){
	$i=strpos($html,"codeData gr_");
	$html=substr($html,$i);
	preg_match('/<h2[^>]*>(.*?)<\/h2>/', $html, $matches);
	$prices=$matches[0];
	preg_match('/([0-9]*)\.([0-9]{2})/',$prices, $matches);
	$price= $matches[0];

	$i=strpos($html,"data-info");
	$html=substr($html,$i,$i+10);

	preg_match_all('/<li[^>]*>(.*?)<\/li>/', $html, $matches);
	$allprices=$matches[0];
	preg_match('/([0-9]*)\.([0-9]{2})/',$allprices[0], $matches);
	$allprice= $matches;

	echo $price;
	//$strpos();
	echo $html;
    	//return array("name"=>$data[2][0],"price"=>$data[4][0],"diff"=>$diff,"diffRate"=>$diffRate,"jk"=>$data[10][0],"zk"=>$data[12][0],"zg"=>$data[14][0],"zd"=>$data[16][0],"class"=>$data[3][0]);
	}}else{
			echo '参数非法';
		}
}
function _url($Date){ 
$ch = curl_init(); 
$timeout = 5; 
curl_setopt ($ch, CURLOPT_URL, "$Date"); 
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt ($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)");
curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
$contents = curl_exec($ch); 
curl_close($ch); 
return $contents; 
} 
getData("xau");
?>