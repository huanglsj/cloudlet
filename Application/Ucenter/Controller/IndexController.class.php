<?php
// 本类由系统自动生成，仅供测试用途
namespace Ucenter\Controller;
use Ucenter\Controller\CommonController;
class IndexController extends CommonController{
    public function index()
    {
        $user= A('Ucenter/Account');
        $user->checklogin();
        $tq = C('DB_PROFIX');
		//redirect(U('Account/agentlist',array('type'=>'daili')));
        $sysuser = M("systemuser")->where("id=".$_SESSION['cuid'])->field("type")->find();
        $_SESSION['ctype']=$sysuser['type'];
		$productinfo = M('product_lastprice')->select();
		$webconf = M('webconfig')->find();
		$this->assign('productinfo',$productinfo);
		$this->assign('webconf',$webconf);
		$this->display();
    }
    
}