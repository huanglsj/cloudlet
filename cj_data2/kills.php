<?php

/**

 * Created by PhpStorm.

 * User: huangyongquan

 * Date: 2017/4/14

 * Time: 9:49

 */

error_reporting(0);

date_default_timezone_set('PRC');

header('Content-type:text/html;charset=utf-8');



require 'config.php';



if(PHP_SAPI != 'cli'){

    exit('no');

}





while(true){

    getBTData();

    getGameData(37);

    getGameData(38);
    
    //getExchangeData('AUDUSD');//欧元美元

    //getExchangeData('USDJPY');//美元日元

    //getExchangeData();

    deleteData();

    sleep(1);

}



//获取上海黄金交易所数据

function getGoldData(){

    $cateList = array('Au100g'=>array('id'=>32));

    $url = "http://web.juhe.cn:8080/finance/gold/shgold";

    $params = array(

        "key" => APPKEY_1,//APP Key

        "v" => "",//JSON格式版本(0或1)默认为0

    );

    $paramstring = http_build_query($params);

    $content = juhecurl($url,$paramstring);

    $result = json_decode($content,true);

    if($result){

        if($result['error_code']=='0'){

            foreach ($result['result'][0] as $key=>$val){

                if(isset($cateList[$val['variety']]) && !empty($cateList[$val['variety']])){

                    $values = array(

                        'recvtime'=>NOW_TIME, 'dealtime'=>strtotime($val['time']), 'pid'=>$cateList[$val['variety']]['id'],

                        'lastClosePrice'=>$val['yespri'], 'openPrice'=>$val['openpri'], 'highprice'=>$val['maxpri'],

                        'lowprice'=>$val['minpri'], 'newprice'=>$val['latestpri'], 'Volume'=>$val['totalvol'],

                        'BuyPrice'=>0,'SellPrice'=>0,'diff'=>0,

                        'diffrate'=>$val['limit'],'isopen'=>1,

                    );

                    $values['diff'] = $values['lowprice'] - $values['newprice'];

                    saveData($values);

                }

            }



        }else{

            echo $result['error_code'].":".$result['reason'];

        }

    }else{

        echo "请求失败";

    }

}

//获取外汇汇率

function getExchangeData($code = ''){

    $now = time();

    $cateList = array(
        'AUDUSD'=>array('id'=>34), //欧元美元
        'USDJPY'=>array('id'=>36), //美元日元

    );

    $url = "http://web.juhe.cn:8080/finance/exchange/frate";

    $params = array(

        "key" => APPKEY_1,//APP Key

        "v" => "",//JSON格式版本(0或1)默认为0

    );

    $paramstring = http_build_query($params);

    $content = juhecurl($url,$paramstring);

    $result = json_decode($content,true);

    //var_dump($result);exit;

    if($result){

        if($result['error_code']=='0'){

            foreach ($result['result'][0] as $key=>$val){

                if(isset($cateList[$val['code']]) && !empty($cateList[$val['code']]) && $code == $val['code']){

                    $values = array(

                        'recvtime'=>$now, 'dealtime'=>strtotime($val['datatime']), 'pid'=>$cateList[$val['code']]['id'],

                        'lastClosePrice'=>'', 'openPrice'=>$val['openPri'], 'highprice'=>$val['highPic'],

                        'lowprice'=>$val['lowPic'], 'newprice'=>$val['closePri'], 'Volume'=>'',

                        'BuyPrice'=>$val['buyPic'],'SellPrice'=>$val['sellPic'],'diff'=>$val['diffAmo'],

                        'diffrate'=>$val['diffPer'],'isopen'=>1,

                    );

                    //$values['diff'] = $values['lowprice'] - $values['newprice'];

                    saveData($values);

                    break;

                }

            }



        }else{

            echo $result['error_code'].":".$result['reason'];

        }

    }else{

        echo "请求失败";

    }

}



//获取测试数据
//array('pid'=>37,'pid'=>37,'pid'=>37)
function getGameData($pid){

    $isopen = getprodopen($pid);

    if(!$isopen){
        return false;
    }

    $lastData = getLastprice($pid);

    //$lastData['ask']

    //白糖 1100-1600之间 每秒波动2-10
    //稀土2200-2600之间 每秒波动30-70

    $config = array(
        '37'=>array(
            'pan'=>array(1257,1613),
            'lastClosePrice'=> 1257 + ((date('md') + 37) % 30),
            'range'=>array(2,20)
        ),
        '38'=>array(
            'pan'=>array(2109,2587),
            'lastClosePrice'=> 2109 + ((date('md') + 37) % 30),
            'range'=>array(30,70)
        ),
    );

    $die = rand(0,1);
    $rand = rand($config[$pid]['range'][0], $config[$pid]['range'][1]);
    if($die){
        $newprice = $lastData['ask'] - $rand;
    }else{
        $newprice = $lastData['ask'] + $rand;
    }

    if($config[$pid]['pan'][0] > $newprice){
        $newprice = $newprice + $rand;  //小于最小值就加上随机量
    }elseif ($config[$pid]['pan'][1] < $newprice){
        $newprice = $newprice - $rand;  //大于最大值就减去随机量
    }

    if($newprice > $lastData['high']){
        $lastData['high'] = $newprice;
    }

    if($newprice < $lastData['low']){
        $lastData['low'] = $newprice;
    }

    $now = time();

    $values = array(

        'recvtime'=>$now, 'dealtime'=>$now, 'pid'=>$pid,

        'lastClosePrice'=>$config[$pid]['lastClosePrice'], 'openPrice'=>$config[$pid]['lastClosePrice'], 'highprice'=>$lastData['high'],

        'lowprice'=>$lastData['low'], 'newprice'=>'0', 'Volume'=>'',

        'BuyPrice'=>'','SellPrice'=>'','diff'=>'',

        'diffrate'=>'','isopen'=>1,

    );

    $values['newprice'] = $newprice;

    $values['diff'] = $values['newprice'] - $lastData['ask'];

    saveData($values);

}



//获取比特币数据

function getBTData(){

    $pid = 35;

    $isopen = getprodopen($pid);

    if(!$isopen){
        return false;
    }

    $now = time();

    $dataBase = json_decode(file_get_contents('https://www.okcoin.cn/api/v1/kline.do?symbol=btc_cny&type=1min&size=1'), true);

    $data = json_decode(file_get_contents('https://www.okcoin.cn/api/v1/ticker.do?symbol=btc_cny'), true);



    $values = array(

        'recvtime'=>$now, 'dealtime'=>$now, 'pid'=>$pid,

        'lastClosePrice'=>$dataBase[0][4], 'openPrice'=>$dataBase[0][1], 'highprice'=>$data['ticker']['high'],

        'lowprice'=>$data['ticker']['low'], 'newprice'=>$data['ticker']['buy'], 'Volume'=>$data['ticker']['vol'],

        'BuyPrice'=>$data['ticker']['buy'],'SellPrice'=>$data['ticker']['sell'],'diff'=>'',

        'diffrate'=>'','isopen'=>1,

    );

    $values['diff'] = $values['lowprice'] - $values['newprice'];

    saveData($values);

}



//初始化链接

function getConn(){

    static $conn;

    if(!$conn){

        $conn = new mysqli(DB_HOST, DB_USER, DB_PWD, DB_NAME);

    }

    return $conn;

}

function getLastprice($pid){
    $conn = getConn();
    $sql = "select * from weipan.wp_product_lastprice where pid='".$pid."'";
    $result = mysqli_query($conn, $sql);
    $row = $result->fetch_assoc();
    return $row;
}

//保存数据

function saveData($data=array()){

    $conn = getConn();

    $sql = "insert into weipan.wp_realtimeprice (".implode(",", array_keys($data)).") values ('".implode("','", array_values($data))."')";

    mysqli_query($conn, $sql);



    $sql = "update wp_product_lastprice set `open`='".$data['openPrice']."', `close`='".$data['lastClosePrice']."', `eidtime`='".$data['dealtime']."', `ask`='".$data['newprice']."', `high`='".$data['highprice']."', `low`='".$data['lowprice']."', `volume`='".$data['Volume']."', `buy`='".$data['BuyPrice']."', `sell`='".$data['SellPrice']."', `diff`='".$data['diff']."', `diffrate`='".$data['diffrate']."' where pid='".$data['pid']."'";

    mysqli_query($conn, $sql);

}



//删除数据

function deleteData(){

    $conn = getConn();

    mysqli_query($conn,"DELETE FROM weipan.wp_realtimeprice WHERE recvtime < ".(time() - 7200)."");

}

function getprodopen($pid=''){
    $status = file_get_contents('http://www.euamote.me/index.php/Home/Home/checkprodopen/code/1328/pid/'.$pid);
    if($status != '1'){
        return false;
    }
    return true;
}



/**

 * 请求接口返回内容

 * @param  string $url [请求的URL地址]

 * @param  string $params [请求的参数]

 * @param  int $ipost [是否采用POST形式]

 * @return  string

 */

function juhecurl($url,$params=false,$ispost=0){

    $httpInfo = array();

    $ch = curl_init();



    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_1 );

    curl_setopt( $ch, CURLOPT_USERAGENT , 'JuheData' );

    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );

    curl_setopt( $ch, CURLOPT_TIMEOUT , 60);

    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    if( $ispost )

    {

        curl_setopt( $ch , CURLOPT_POST , true );

        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );

        curl_setopt( $ch , CURLOPT_URL , $url );

    }

    else

    {

        if($params){

            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );

        }else{

            curl_setopt( $ch , CURLOPT_URL , $url);

        }

    }

    $response = curl_exec( $ch );

    if ($response === FALSE) {

        //echo "cURL Error: " . curl_error($ch);

        return false;

    }

    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );

    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );

    curl_close( $ch );

    return $response;

}