<?php
namespace Ucenter\Controller;

use Admin\Controller\TongjiController;

class UTongjiController extends TongjiController
{
    public function tongji(){
        //判断用户是否登陆
        $user= A('Ucenter/Account');
        $user->checklogin();
        
        $this->init();
    }
    
    public function exportExcel(){
        //判断用户是否登陆
        $user= A('Ucenter/Account');
        $user->checklogin();
         
        $this->output();
    }
}

?>