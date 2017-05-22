<?php
namespace Admin\Model;

use Think\Model;
require APP_PATH.'/Common/Common/smsfunctions.php';

class SystemuserModel extends Model
{

    /**
     * 自动验证
     */
    protected $_validate = array(
        array(
            'username',
            'require',
            '登录用户名不能为空！'
        ),
        array(
            'username',
            'unique',
            '登录用户名已经存在，请填写其他用户名！',
            '0',
            'unique'
        ),
        array(
            'password',
            'require',
            '密码不能为空！'
        ),
        array(
            'type',
            'require',
            '用户类型不能为空！'
        ),
        array(
            'displayname',
            'require',
            '用户显示名称不能为空！'
        ),
        array(
            'tel',
            '/^1[3|4|5|7|8][0-9]\d{4,8}$/',
            '用户电话格式不正确！',
            1,
            'regex',
            1
        )
    );

    /**
     * 自动完成
     */
    protected $_auto = array(
        
    );

    public function addSystemuser()
    {
        $result = array(
          'result' => false,
          'msg' => '处理异常',
        );
        $systemuser = $this->data;
        $time = time();
        if($systemuser['password']){
            $password=$systemuser['password'];
            $pattern = '#(?=^.*?[a-z])(?=^.*?[A-Z])(?=^.*?\d)^(.{8,})$#';
            if(!preg_match($pattern,$password)){
                $result['msg'] = '密码不符合规则，请输入6到20位的大小写字母和数字的组合!';
                return $result;
            }
        }else{
            $password = $this->generate_password();
        }
        $systemuser['password'] = md5(strval($password).strval($time));
        $systemuser['state'] = 1;
        $systemuser['createtime'] = $time;
        $systemuser['updatetime'] = $time;
        $systemuser['updateuserid'] = $systemuser['createuserid'];
        $userid =  $this->add($systemuser);
        //发送密码给负责人
        if($userid){
            $rrtn = D('Admin/userrole')->setdefaultrole($userid,$systemuser['type']);
            if($rrtn){
                $srtn = sentPassword($systemuser['tel'],$systemuser['displayname'],$systemuser['username'],$password);
            }
        }else{
            $result['msg'] = $this->getError();
            return $result;
        }
        $result['msg'] = $userid;
        $result['result'] = true;
        return $result;
    }
    
    public function restpassword($username,$updateuserid){
        $map = array(
            'username' => $username
        );
        $user = $this->where($map)->find();
        $password = $this->generate_password();
        $updatemap = array(
            'password' => md5(strval($password).strval($user['createtime'])),
            'updatetime' => time(),
            'updateuserid' => $updateuserid
        );
        $result = $this->where($map)->setField($updatemap);
        if($result){
            $result = resetPassword($user['tel'],$user['displayname'],$password);
        }
        
        if($result){
            return true;
        }else{
            return false;
        }
    }
    
    public function updatePassword($uid,$oldpassword,$newpassword,$updateuserid){
        $result = array(
            'result' => false,
            'msg' => '处理异常'
        );
        $map = array(
            'id' => $uid
        );
        $pattern = '#(?=^.*?[a-z])(?=^.*?[A-Z])(?=^.*?\d)^(.{8,})$#';
        if(!preg_match($pattern,$newpassword)){
            $result['msg'] = '新密码不符合规则，请输入6到20位的大小写字母和数字的组合!';
            return $result;
        }
        $user = $this->where($map)->find();
        $oldpassword = md5(strval($oldpassword).strval($user['createtime']));
        if($oldpassword != $user['password']){
            $result['msg'] = '原密码输入错误!';
            return $result;
        }
        $updatemap = array(
            'password' => md5(strval($newpassword).strval($user['createtime'])),
            'updatetime' => time(),
            'updateuserid' => $updateuserid
        );
        $urtn = $this->where($map)->setField($updatemap);
        if($urtn){
            $urtn = resetPassword($user['tel'],$user['displayname'],$newpassword);
        }else{
            $result['msg'] = $this->getError();
            return $result;
        }
        if($urtn){
            $result['result'] = true;
            return $result;
        }else{
            return $result;
        }
    }
    
    function generate_password( $length = 8 ) {
        // 密码字符集，可任意添加你需要的字符
        //$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
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
