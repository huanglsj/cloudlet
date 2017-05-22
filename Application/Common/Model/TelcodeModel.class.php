<?php
namespace Common\Model;
use Think\Model;
class TelcodeModel extends Model
{
    protected $tableName = 'telcode';
    /**
     * 自动验证
     */
    protected $_validate = array(
        array('tel', 'require', '电话不能为空！'), //默认情况下用存在验证
        array('code', 'require', '验证码不能为空！'), //默认情况下用存在验证
        array('time', 'require', '时间不能为空！'), //默认情况下用存在验证
    );
    /**
     * 自动完成
    */
    protected $_auto = array (
    
    );
}

?>