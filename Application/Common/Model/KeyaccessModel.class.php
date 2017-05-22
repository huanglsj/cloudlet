<?php
namespace Common\Model;
use Think\Model;

/**
 * 行为扩展：关键信息访问行为
 */
class KeyaccessModel extends Model {
    /**
     * 自动验证
     */
    protected $_validate = array(
        array(
            'key',
            'require',
            '日志操作的关键动作不能为空！'
        ),
    );
    /**
     * 自动完成
    */
    protected $_auto = array (
    
    );
    /**
     * @param array $params 
     *                 key:关键动作(KeyAccessEnum的值)
     *                 outid:操作主体的Key（客户ID，系统用户ID等）
     *                 result：执行结果
     *                 remark:注释（option)
     */
    public function addInfo($params) {
        $params['ip'] = $this->getIPInfo();
        $ipExtendInfo = $this->getIpExtendInfo($params['ip']);
        if(is_array($ipExtendInfo)){
            $params = array_merge($params,$ipExtendInfo);
        }
        $params['accesstime'] = time();
        $params['risk'] = 0;
        if($this->checkRemoteLogin($params)){
            //属于异地登录
            if($params['checkedslip'] == false){
                //没有通过滑块校验，判断为不允许登录
                $params['risk'] = 1;
                //修改为不成功
                $params['result'] = 0;
            }
            if($params['result'] == 2){
                //异地登录，且密码不对
                $params['remark'] = '异地登录且密码不对。';
            }else{
                $params['remark'] = '异地登录';
                $this->add($params);
                $this->updatelogininfo($params);
                return $params;
            }
        }
        if($params['result'] == 0){
            //处理失败
            $errorcount = S('errorip.'.$params['ip']);
            $errorcount = $errorcount + 1;
            //缓存1分钟，如果1分钟内失败超过3次，则判断为存在风险
            S('errorip.'.$params['ip'],$errorcount,60);
            if($errorcount >= 3){
                $params['risk'] = 1;
                $params['remark'] = '该IP在1分钟内超过三次尝试登陆失败';
            }else if($params['outid']){
                //判断用户，如果1分钟内失败超过3次，则判断为存在风险
                $errorcount = S('erroruser.'.$params['key'].'.'.$params['outid']);
                $errorcount = $errorcount + 1;
                S('erroruser.'.$params['key'].'.'.$params['outid'],$errorcount,60);
                if($errorcount >= 3){
                    $params['risk'] = 1;
                    $params['remark'] = '该用户在1分钟内超过三次尝试登陆失败';
                }
            }
        }else if($params['result'] == 2){
            //客户端登陆失败
            //转换为统一的结果值
            $params['result'] == 0;
            
            //处理失败
            $errorcount = S('errorip.'.$params['ip']);
            $errorcount = $errorcount + 1;
            //缓存24小时，如果24小时失败超过5次，则判断为存在风险
            S('errorip.'.$params['ip'],$errorcount,86400);
            if($errorcount >= 5){
                $params['risk'] = 1;
                $params['remark'] = '该IP24小时超过5次尝试登陆失败,被锁定24小时！';
            }else if($params['outid']){
                //判断用户，如果24小时失败超过5次，则判断为存在风险
                $errorcount = S('erroruser.'.$params['key'].'.'.$params['username']);
                $errorcount = $errorcount + 1;
                S('erroruser.'.$params['key'].'.'.$params['username'],$errorcount,86400);
                if($errorcount >= 5){
                    $params['risk'] = 1;
                    $params['remark'] = '该用户24小时失败超过5次尝试登陆失败,被锁定24小时！';
                }
            }
        }else if($params['result'] == 3){
            //转换为统一的结果值
            $params['result'] == 0;
            $params['remark'] = '禁止登录用户';
        }else{
            S('errorip.'.$params['ip'],null);
            if($params['username']){
                S('erroruser.'.$params['key'].'.'.$params['username'],null);
            }
            if($params['outid']){
                S('erroruser.'.$params['key'].'.'.$params['outid'],null);
            }
            $this->updatelogininfo($params);
        }
        $this->add($params);
        return $params;
    }
    public function updatelogininfo($params){
        if($params['key'] == \KeyAccessEnum::CUSTOMER_LOGIN){
            //客户端成功登录，插入token
            $logininfo['token'] = session_id();
            $logininfo['loginTime'] = time();
            $logininfo['loginStatus'] = 1;
            $login = M('logininfo')->where(array('username'=>$params['username']))->find();
            if($login){
                M('logininfo')->where(array('username'=>$params['username']))->save($logininfo);
            }else{
                $logininfo['username'] = $params['username'];
                M('logininfo')->add($logininfo);
            }
        }
    }
    public function isClientLock($username){
        $result = array(
            'result' => true,//被锁定
            'errorMsg' => ''
        );
        $errorcount1 = S('errorip.'. $this->getIPInfo());
        if($errorcount1 >= 5){
            $result['errorMsg'] = '该IP在24小时内连续超过5次尝试登陆失败,被锁定24小时！';
            return $result;
        }else{
            $errorcount2 = S('erroruser.'.\KeyAccessEnum::CUSTOMER_LOGIN.'.'.$username);
            if($errorcount2 >= 5){
                $result['errorMsg'] = '该用户在24小时内连续超过5次尝试登陆失败,被锁定24小时！';
                return '该用户在24小时内连续超过5次尝试登陆失败,被锁定24小时！';
            }
        }
        
        //警告消息
        $result['result'] = false;
        if($errorcount1 == 2 || $errorcount2 == 2){
            //警告(再错一次后，就只有2次机会)
            $result['warringMsg'] = '还有2次尝试机会，失败后将被锁定24小时！';
        }
        if($errorcount1 == 3 || $errorcount2 == 3){
            //警告(再错一次后，就只有1次机会)
            $result['warringMsg'] = '还有1次尝试机会，失败后将被锁定24小时！';
        }
        
        return $result;
    }
    
    public function getIPInfo()
    {
        $IPaddress='';
        if (isset($_SERVER)){
    
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
    
                $IPaddress = $_SERVER["HTTP_X_FORWARDED_FOR"];
    
            } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
    
                $IPaddress = $_SERVER["HTTP_CLIENT_IP"];
    
            } else {
    
                $IPaddress = $_SERVER["REMOTE_ADDR"];
    
            }
    
        } else {
    
            if (getenv("HTTP_X_FORWARDED_FOR")){
    
                $IPaddress = getenv("HTTP_X_FORWARDED_FOR");
    
            } else if (getenv("HTTP_CLIENT_IP")) {
    
                $IPaddress = getenv("HTTP_CLIENT_IP");
    
            } else {
    
                $IPaddress = getenv("REMOTE_ADDR");
    
            }
    
        }
        return $IPaddress;
    }
    
    function getIpExtendInfo($IPaddress){
        if($IPaddress == '' || $IPaddress =='127.0.0.1' || $IPaddress=='::1'){
            return false;
        }
        $get_ip_info_url = "http://ip.taobao.com/service/getIpInfo.php?ip=".$IPaddress;
        $IpExtendInfo = $this->getArray($get_ip_info_url);
        return $IpExtendInfo['data'];
    }
    
    /**
     * 检查是否是异地登录
     * @param Array $param
     * @return boolean
     */
    function checkRemoteLogin($param){
        if($param['outid']){
            //查询DB，检验有效的登录是否趁机成功登陆过
            $map['outid'] = $param['outid'];
            $map['key'] = $param['key'];
            $map['country_id'] = $param['country_id'];
            $map['city_id'] = $param['city_id'];
            $map['result'] = 1;
            $result = $this->where($map)->count();
            if($result){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }
    function getArray($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output, true);
    }
}
