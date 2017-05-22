<?php
namespace Home\Controller;

use Think\Controller;
$rtn = vendor('Jugui.MarketStatusJudge');
class HomeController extends Controller
{
    public function home(){
        $this->display();
    }

    //检查产品是否休市
    public function checkprodopen(){
        $code = I('code');
        $pid = I('pid', 0, 'intval');
        if($code != '1328'){
            echo '0';
            exit();
        }

        if($pid){
            $isopen = \MarketStatusJudge::isopen($pid);
            echo $isopen;
            exit();
        }

        echo '1';
        exit();
    }

}
