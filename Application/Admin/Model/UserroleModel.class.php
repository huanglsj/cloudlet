<?php

namespace Admin\Model;
use Think\Model;
class UserroleModel extends Model {
	
	/**
     * 自动验证
     */
	protected $_validate = array(
	);
	
    /**
     * 自动完成
     */
     protected $_auto = array (
    );
     
     public function setdefaultrole($uid, $rtype){
         $role = M('role');
         $this->where('uid=' . $uid)->delete();
         $defaultrole = $role->where('rtype='.$rtype.' and isdefault = "1" ')->find();
         if ($defaultrole['id'] != '') {
             $data['uid'] = $uid;
             $data['rid'] = $defaultrole['id'];
             $data['type'] = $rtype;
             return $this->add($data);
         }else{
             return false;
         }
     }
}