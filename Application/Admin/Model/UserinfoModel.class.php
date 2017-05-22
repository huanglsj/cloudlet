<?php

namespace Admin\Model;
use Think\Model;
require APP_PATH.'/Common/Common/smsfunctions.php';
class UserinfoModel extends Model {
	
	/**
     * 自动验证
     */
	protected $_validate = array(
        array('username', 'require', '用户名不能为空！'), //默认情况下用正则进行验证
        array('utel', 'require', '联系电话地址不能为空！'), //默认情况下用正则进行验证
        array('username', '', '该用户名已被注册！', 0, 'unique', 1), // 在新增的时候验证name字段是否唯一
	    array('username', '/^[\w\_]{6,20}$/u', '请输入至少6位的英文、数字、下划线的用户名！', 0), // 
        array('email', '', '该邮箱已被占用', 0, 'unique', 1), // 新增的时候email字段是否唯一
        array('utel', '', '该手机号码已被占用', 0, 'unique', 1), // 新增的时候mobile字段是否唯一
        // 正则验证密码 [需包含字母数字以及@*#中的一种,长度为6-22位]
        array('upwd', 'require', '密码不能为空！'), //默认情况下用正则进行验证
        array('rpwd', 'upwd', '确认密码不正确', 0, 'confirm'), // 验证确认密码是否和密码一致
        array('email', 'email', '邮箱格式不正确'), // 内置正则验证邮箱格式
        array('mobile', '/^1[34578]\d{9}$/', '手机号码格式不正确', 0), // 正则表达式验证手机号码
	);
	
    /**
     * 自动完成
     */
     protected $_auto = array (
        //array('password', 'md5', 3, 'function') , // 对password字段在新增和编辑的时候使md5函数处理
        //array('regdate', 'time', 1, 'function'), // 对regdate字段在新增的时候写入当前时间戳
        //array('regip', 'get_client_ip', 1, 'function'), // 对regip字段在新增的时候写入当前注册ip地址
    );
     
     public function restpassword($uid){
         $map = array(
             'uid' => $uid
         );
         $user = $this->where($map)->find();
         $password = $this->generate_password();
         $updatemap = array(
             'upwd' => md5(strval($password).strval($user['utime']))
         );
         $result = $this->where($map)->setField($updatemap);
         if($result){
             $result = M('managerinfo')->where('uid='.$uid)->find();
             if(!empty($result['mname'])){
                 $name = $result['mname'];
             }else if(!empty($user['nickname'])){
                 $name = $result['nickname'];
             }else{
                 $name = $user['username'];
             }
             $result = resetPassword($user['utel'],$name,$password);
         }
         
         if($result){
             return true;
         }else{
             return false;
         }
     }
     
     function generate_password( $length = 6 ) {
         // 密码字符集，可任意添加你需要的字符
         //$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
         $chars = 'abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ0123456789';
     
         $password = '';
         for ( $i = 0; $i < $length; $i++ )
         {
             // 这里提供两种字符获取方式
             // 第一种是使用 substr 截取$chars中的任意一位字符；
             // 第二种是取字符数组 $chars 的任意元素
             // $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
             $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
         }
     
         return $password;
     }
}
