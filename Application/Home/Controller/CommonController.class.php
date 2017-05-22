<?php
namespace Home\Controller;
use Think\Controller;
class CommonController extends Controller {

        public function _initialize(){

            //判断用户是否已经登录
            if (!isset($_SESSION['uid'])) {
                if(strtolower(CONTROLLER_NAME) == 'index' && strtolower(ACTION_NAME) == 'index') {
                    $this->assign('is_guest', 1);
                }else{
                    //直接跳转页面
                    $this->redirect('User/login');
                }
            }else{
                $price=M('accountinfo')->where('uid='.$_SESSION['uid'])->find();
                $this->assign('price',$price);
                $this->assign('is_guest', 0);
            }
    }


    	




}