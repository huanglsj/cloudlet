<?php

namespace Admin\Controller;
use Think\Controller;
class DepartmentController extends Controller {
	//会员某会员下的代理列表
    public function getDailiList(){
        $parentid = I('post.parentid');
        
        $dalilist = D('department')->where('type=3 and parent_id='.$parentid)->field('daili_id,daili_name')->select();
        
        $this->ajaxReturn($dalilist);
    }
    
   
    
}