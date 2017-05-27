<?php

namespace Admin\Controller;

use Think\Controller;
use Think\Model;
use Think\Log;

require_once APP_PATH . '/Common/Enum/PayWayEnum.php';
require_once APP_PATH . '/Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH . '/Common/Enum/PayStateEnum.php';
require_once APP_PATH . '/Common/Enum/BalanceOpTypeEnum.php';
require_once APP_PATH . '/Common/Enum/KeyAccessEnum.php';

class UserController extends Controller
{

    function checkCode($phone, $code)
    {
        $telcode = D('telcode')->where('tel=' . $phone)->order('time desc')->find();
        if ($telcode['code'] == $code && time() - $telcode['time'] <= 300) {
            return true;
        } else {
            return false;
        }
    }

    //管理员登陆
    public function signin()
    {
        if (IS_POST) {
            header("Content-type: text/html; charset=utf-8");
            $user = D("systemuser");

            if (I('post.risk') == 1) {
                //滑块校验
                //$checkedslip = slipVerify();
                // if(!$checkedslip){
                //     $this->assign('risk',1);
                //     $this->display();
                //     return;
                //  }
            }

            $keyaccess = D('Common/keyaccess');
            $keyaccessinfo['key'] = \KeyAccessEnum::UNKNOWN_LOGIN;
            $keyaccessinfo['result'] = 0;

            //查询条件
            $where = array();
            $where['username'] = I('post.username');
            $where['state'] = '1';

            //$where['ustatus'] = "1";
            $result = $user->where($where)->field("id,password,username,createtime,type,isadmin,state,portrait,tel,deptid")->find();
            //验证用户

            if (empty($result)) {
                $result = $keyaccess->addInfo($keyaccessinfo);
                if ($result['risk']) {
                    $this->assign('risk', 1);
                    $this->display();
                } else {
                    $this->error('登录失败,用户名不存在!');
                }
            } else {
                if (false && checkCode($result['tel'], I('post.code')) == false) {
                    $result = $keyaccess->addInfo($keyaccessinfo);
                    $this->error('登录失败,请检查您的验证码');
                    return;
                }

                $keyaccessinfo['outid'] = $result['id'];
                if ($result['password'] == md5(I('post.password') . strval($result['createtime']))) {
                    $keyaccessinfo['result'] = 1;
                    //session
                    $depart = M('department')->where('id=' . $result['deptid'])->find();
                    session('cuid', $result['id']);
                    $departmentinfo = array(
                        'deptid' => $result['deptid'],
                        'code' => $depart['code'],
                        'name' => $depart['name'],
                        'type' => $depart['type']
                    );
                    session('deptinfo', $departmentinfo);
                    if ($result['type'] == 2 || $result['type'] == 3 || $result['type'] == 4) {
                        //记住用户名
                        if (isset($_POST['ckrename'])) {
                            setcookie("0729UserName", $where['username'], time() + 7 * 24 * 60 * 60);
                        }
                        session('username', $result['username']);
                        session('portrait', $result['portrait']);
                        $this->setPermission($result['id'], $result['type']);
                        $keyaccessinfo['key'] = \KeyAccessEnum::MEMBER_LOGIN;
                        $keyaccessinfo['result'] = 1;
                        $keyaccessinfo['checkedslip'] = $checkedslip;
                        $result = $keyaccess->addInfo($keyaccessinfo);
                        $this->success('登录成功,正跳转至系统首页...', U('Ucenter/Index/index'));
                    } else if ($result['type'] == 1) {
                        session('userid', $result['id']);
                        //记住用户名
                        if (isset($_POST['ckrename'])) {
                            setcookie("0729UserName", $where['username'], time() + 7 * 24 * 60 * 60);
                        }
                        session('username', $result['username']);
                        $this->setPermission($result['id'], $result['type']);
                        $keyaccessinfo['key'] = \KeyAccessEnum::SYSTEMUSER_LOGIN;
                        $keyaccessinfo['result'] = 1;
                        $keyaccessinfo['checkedslip'] = $checkedslip;
                        $result = $keyaccess->addInfo($keyaccessinfo);
                        $this->success('登录成功,正跳转至系统首页...', U('Index/index'));
                    } else {
                        $result = $keyaccess->add($keyaccessinfo);
                        if ($result['risk']) {
                            $this->assign('risk', 1);
                            $this->display();
                        } else {
                            $this->error('登录失败,用户类型不正确!');
                        }
                    }
                } else {
                    if ($result['type'] == 2 || $result['type'] == 3 || $result['type'] == 4) {
                        $keyaccessinfo['key'] = \KeyAccessEnum::MEMBER_LOGIN;
                    } else if ($result['type'] == 1) {
                        $keyaccessinfo['key'] = \KeyAccessEnum::SYSTEMUSER_LOGIN;
                    }
                    $result = $keyaccess->addInfo($keyaccessinfo);
                    if ($result['risk']) {
                        $this->assign('risk', 1);
                        $this->assign('pwderror', '密码错误！');
                        $this->display();
                    } else {
                        $this->error('登录失败,密码错误!');
                    }
                }

            }
        } else {
            if (isset($_COOKIE['0729UserName'])) {
                $UserName = $_COOKIE['0729UserName'];
                $this->assign('uname', $UserName);
            }
            $this->display();
        }
    }

    //管理员信息
    public function personalinfo()
    {
        $this->checklogin();

        $uid = $_SESSION['uid'];
        $user = D('systemuser');
        $person = $user->where('id=' . $uid)->find();

        $this->assign('person', $person);
        $this->display();
    }

    //给管理员发送验证码
    public function sendsms()
    {
        $user = D('systemuser');
//查询条件
        $where = array();
        $where['username'] = I('post.username');

        //$where['ustatus'] = "1";
        $result = $user->where($where)->field("id,password,username,createtime,type,isadmin,state,portrait,tel,deptid")->find();

        if ($result['tel'] == "") {
            echo "没有查询到相关的手机号码";
        } else {
            $tel = $result['tel'];
            echo "发送成功";
            post("http://www.euamote.me/index.php/Home/User/smsverify11.html", array("tel" => $tel));
        }
    }

    function post($url, $param = array())
    {
        if (!is_array($param)) {
            throw new Exception("参数必须为array");
        }
        $httph = curl_init($url);
        curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 1);
        curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
        curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式
        curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
        curl_setopt($httph, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($httph, CURLOPT_HEADER, 1);
        $rst = curl_exec($httph);
        curl_close($httph);
        return $rst;
    }

    /**
     * 用户注销
     */
    public function signinout()
    {
        // 清楚所有session
        header("Content-type: text/html; charset=utf-8");
        session(null);
        redirect(U('User/signin'), 2, '正在退出登录...');
    }


    //会员列表
    public function ulist()
    {
        $this->checklogin();

        $tq = C('DB_PREFIX');
        $user = D('userinfo');
        $order = D('order');
        $account = D('accountinfo');
        $field = $tq . 'userinfo.username as username,'
            . $tq . 'userinfo.uid as uid,'
            . $tq . 'userinfo.utel as utel,'
            . $tq . 'userinfo.address as address,'
            . $tq . 'userinfo.portrait as portrait,'
            . $tq . 'userinfo.utime as utime,'
            . $tq . 'userinfo.oid as oid,'
            . $tq . 'userinfo.managername as managername,'
            . $tq . 'userinfo.lastlog as lastlog,'
            . $tq . 'accountinfo.balance as balance,'
            . $tq . 'userinfo.otype as otype,'
            . $tq . 'userinfo.nickname as nickname,'
            . $tq . 'userinfo.authenticationsstatus as authenticationsstatus,'
            . $tq . 'userinfo.ustatus as ustatus,'
            . $tq . 'authenticationinfo.realname as realname,'
            . "'desu' as dmjig,"
            . "'desu' as dmjinj,"
            . "'desu' as dmhuiy,"
            . "'desu' as dmname,"
            . $tq . 'userinfo.ucode as ucode';
        //分页
        if (I('username') <> '') {
            $map['username'] = array('like', '%'.I('username').'%');
        }
        if (I('nickname') <> '') {
            $map['nickname'] = array('like', '%'.I('nickname').'%');
        }
        if (I('utel') <> '') {
            $map['utel'] = array('like', I('utel').'%');
        }

        $startUtime = str_replace('_', ' ', I('utime'));
        $endUtime = str_replace('_', ' ', I('utimeEnd'));

        if ($startUtime <> '' && !$endUtime) {
            $map['utime'] = array('elt', strtotime(urldecode($startUtime)));
        }

        if (!$startUtime <> '' && $endUtime) {
            $map['utime'] = array('elt', strtotime(urldecode($endUtime)));
        }

        if ($startUtime && $endUtime) {
            if(strtotime($startUtime)>strtotime($endUtime)) {
                die("<script>alert('开始创建时间不能大于结束创建时间！');history.back(-1);</script>");
            }else{
                $map['utime'] = array(array('elt', strtotime(urldecode($endUtime))),array('egt', strtotime(urldecode($startUtime))));
           }

        }
        if (I('companyid') <> '0' && I('companyid') <> '') {
            $map['companyid'] = I('companyid');
//            var_dump($map['companyid']);
        }
        if (I('authenticationsstatus') <> '') {
            $map['authenticationsstatus'] = array('eq', I('authenticationsstatus'));
        }
        if (I('ustatus') <> '') {
            $map['ustatus'] = I('ustatus');
        } else {
            $map['ustatus'] = array('neq', 0);
        }
        if (I('ustatus') <> '') {
            $map['ustatus'] = I('ustatus');
        }
        $map['otype'] = 0;
        $count = $user->where($map)->count();
        $psize = I('psize');
        if (strlen($psize) <= 0) {
            $psize = 20;
        }
        $this->assign('psize', $psize);
        $page = new \Think\Page($count, $psize);
        foreach ($map as $key => $value) {
//            if ($key == 'utime') {
//                $page->parameter[$key] = urlencode(urldecode(I('utime')));
//            } else {
//                $page->parameter[$key] = urlencode($value);//此处的row是数组，为了传递查询条件
//            }
        }
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '&#8249;');
        $page->setConfig('next', '&#8250;');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
        $show = $page->show();


//        var_dump($map);
        //查询用户和账户信息

        if(I("sort") != 'orderSum' && I("sort")){
            $mySort = I("sort").' '.I("way");
        }else{
            $mySort = 'utime desc';
        }

        $ulist = $user->Distinct(true)
            ->join($tq . 'accountinfo on ' . $tq . 'userinfo.uid=' . $tq . 'accountinfo.uid', 'left')
            ->join($tq . 'authenticationinfo on ' . $tq . 'userinfo.uid=' . $tq . 'authenticationinfo.useruid', 'left')
            ->where($map)->field($field)->order($mySort)
            ->limit($page->firstRow . ',' . $page->listRows)->select();

        $cmap['isdelete'] = array('neq', 'Y');
        $cmap['type'] = 2;
        $companys = M('companyinfo')->where($cmap)->field('cid,ccode,comname')->select();
        //循环用户id，获取该用户的所有订单数
        $ototal = array();
        foreach ($ulist as $k => $v) {
            $department = M('department')->where("code={$ulist[$k]['ucode']}")->field('code,huiyuan_name,jigou_name,daili_name,huiyuan_code')->select()[0];
            $ototal['balance'] += $ulist[$k]['balance'];
//            $ulist[$k]['balance'] = number_format($ulist[$k]['balance'], 2);
            $ulist[$k]['dmjig'] = $department['jigou_name'];
//            $ulist[$k]['mutime'] = strtotime($ulist[$k]['utime']);
            $ulist[$k]['dmjinj'] = $department['daili_name'];
            $ulist[$k]['dmhuiy'] = $department['huiyuan_code'];
            $ulist[$k]['dmname'] = $department['huiyuan_name'];
            $ulist[$k]['orderSum'] = M('Order')->where(array('uid'=>$v['uid'], 'ostaus'=>1, 'ploss'=>array('neq',0)))->sum('fee');
            $ototal['orderSum'] += $ulist[$k]['orderSum'];
//            $ulist[$k]['orderSum'] = number_format($ulist[$k]['orderSum'], 2);
        }
//var_dump($ulist);
        $this->assign('ototal', $ototal);

        //排序
        if(I("sort")=='orderSum'){
            $way = I("way");
            if($way=='desc'){
                $way = SORT_DESC;
            }else{
                $way = SORT_ASC;
            }
            $ulist = $this->multi_array_sort($ulist,I("sort"),$way);
        }

//        var_dump($ulist);


        $this->assign('page', $show);
        $this->assign('ulist', $ulist);
        $this->assign('companys', $companys);



        //统计
        //用户数量
        $userCount = $user->where('otype=0 and ustatus<>0')->count();
        //所有用户账户余额统计
        $acc = $account->field('balance')->select();
        $anumber = 0;
        foreach ($acc as $k => $v) {
            $anumber += $acc[$k]['balance'];
        }
        $anumber = number_format($anumber, 2);
        $this->assign('anumber', $anumber);
        $this->assign('ucount', $userCount);
        $this->display();
    }

     //排序函数
    function multi_array_sort($multi_array,$sort_key,$sort=SORT_ASC){
        if(is_array($multi_array)){
            foreach ($multi_array as $row_array){
                if(is_array($row_array)){
                    $key_array[] = $row_array[$sort_key];
                }else{
                    return false;
                }
            }
        }else{
            return false;
        }
        array_multisort($key_array,$sort,$multi_array);
        return $multi_array;
    }

    //代理商申请列表
    public function agentlist()
    {
        $tq = C('DB_PREFIX');
        $user = D('userinfo');
        $managerinfo = D('managerinfo');
        $list = $user->join($tq . 'managerinfo on ' . $tq . 'userinfo.uid=' . $tq . 'managerinfo.uid')->where($tq . 'userinfo.agenttype=1')->order($tq . 'userinfo.uid desc')->select();

        $this->assign('list', $list);
        $this->display();
    }

    //处理代理申请是否通过
    public function edituser()
    {
        $user = D('userinfo');
        $uid = I('get.uid');
        $otype = I('get.otype');

        if ($otype == 0) {
            //拒绝
            $date['uid'] = $uid;
            $date['agenttype'] = 0;
            if ($user->save($date)) {
                M('managerinfo')->where('uid=' . $uid)->delete();
            }

        } else {
            //通过
            $date['uid'] = $uid;
            $date['agenttype'] = 2;
            $date['otype'] = 1;
            $user->save($date);
        }
        $this->redirect('User/agentlist');
    }

    //修改会员
    public function updateuser()
    {
        //检测用户是否登陆
        $sysuserid = $this->checklogin();

        //实例化数据表
        $tq = C('DB_PREFIX');
        $user = D('userinfo');
        $manager = D('managerinfo');
        $bank = D('bankinfo');
        $acinfo = D('accountinfo');
        $order = D('order');
        //判断如果是post，执行修改用户方法，否则显示视图
        if (IS_POST) {
/*            $verify = I('post.vcode');
            if (!checkCode(C('ADMIN_PHONE'), $verify)) {
                $this->error('请输入正确验证码');
            }*/

            $uid = I('post.uid');                //用户id
            $username = I('post.username');        //用户名
            $mname = I('post.mname');            //真实姓名
            $utel = I('post.utel');                //手机号码
            $brokerid = I('post.brokerid');        //身份证号码
            $banknumber = I('post.banknumber');    //银行卡号
            $branch = I('post.branch');            //开户行地址
            $bankname = I('post.bankname');        //所属银行
            $busername = I('post.busername');    //持卡人
            $balance = I('post.balance');        //账户余额
            $companyid = I('post.companyid');        //所属会员单位
            //取值，如果没有做修改，保存原有值
            $users = $user->where('uid=' . $uid)->find();
            $mginfo = $manager->where('uid=' . $uid)->find();
            $banks = $bank->where('uid=' . $uid)->find();
            $accinfo = $acinfo->where('uid=' . $uid)->find();

            //判断电话是否为空
            if (!empty($utel)) {
                $users['utel'] = $utel;
            }
            //判断真实姓名是否为空
            if (!empty($mname)) {
                $mginfo['mname'] = $mname;
            }
            //判断身份证号码是否为空
            if (!empty($brokerid)) {
                $mginfo['brokerid'] = $brokerid;
            }
            //判断银行卡号是否为空
            if (!empty($banknumber)) {
                $banks['banknumber'] = $banknumber;
            }
            //判断开户行地址是否为空
            if (!empty($branch)) {
                $banks['branch'] = $branch;
            }
            //判断所属银行是否为空
            if (!empty($bankname)) {
                $banks['bankname'] = $bankname;
            }
            //判断持卡人是否为空
            if (!empty($busername)) {
                $banks['busername'] = $busername;
            }
            //判断账户余额
            if (!empty($balance)) {
                $accinfo['balance'] = $balance;
            }

            if($users['companyid'] != $companyid){
                $company = M('companyinfo')->where(array('cid'=>$companyid))->find();
                $department = M('department')->where(array('id' => $company['deptid']))->find();
                $users['companyid'] = $companyid;
                $users['deptid'] = $department['id'];
                $users['ucode'] = $department['code'];
            }

            //修改用户基本信息
            $resultUser = $user->where('uid=' . $uid)->save($users);
            //修改用户真实信息
            $resultManager = $manager->where('uid=' . $uid)->save($mginfo);
            //修改账户余额
            if ($balance != I('post.oldBalance')) {
                $resultAcinfo = D("Home/Account")->setBalance($uid, $sysuserid, $balance);
            }
            //判断用户是否存在银行卡信息
            if ($banks['uid'] == $uid) {
                //修改银行卡信息
                $resultBank = $bank->where('uid=' . $uid)->save($banks);
            } else {
                $banks['uid'] = $uid;
                //添加银行卡信息
                $resultBank = $bank->add($banks);
            }

            if ($resultUser || $resultManager || $resultBank || $resultAcinfo) {
                $this->success('修改成功');
            } else if ($resultUser == 0 || $resultManager == 0 || $resultBank == 0 || $resultAcinfo == 0) {
                $this->error('未做任何修改');
            } else {
                $this->error('修改失败');
            }

        } else {
            //根据获取的用户id查询该用户的信息，展示视图
            $uid = I('get.uid');
            //需要查询的字段
            $field = $tq . 'userinfo.uid as uid,' .
                $tq . 'userinfo.username as username,' .
                $tq . 'userinfo.companyid as companyid,' .
                $tq . 'userinfo.oid as oid,' .
                $tq . 'userinfo.managername as managername,' .
                $tq . 'userinfo.otype as otype,' .
                $tq . 'userinfo.utel as utel,' .
                $tq . 'managerinfo.mname as mname,' .
                $tq . 'managerinfo.brokerid as brokerid,' .
                $tq . 'bankinfo.bankname as bankname,' .
                $tq . 'bankinfo.province as province,' .
                $tq . 'bankinfo.city as city,' .
                $tq . 'bankinfo.branch as branch,' .
                $tq . 'bankinfo.banknumber as banknumber,' .
                $tq . 'bankinfo.bankname as bankname,' .
                $tq . 'bankinfo.busername as busername,' .
                $tq . 'accountinfo.balance as balance,' .
                $tq . 'userinfo.utime as utime,' .
                $tq . 'userinfo.ustatus as ustatus,' .
                $tq . 'department.type as dept_type,' .
                $tq . 'department.huiyuan_name as huiyuan_name,' .
                $tq . 'department.daili_name as daili_name,' .
                $tq . 'department.jigou_name as jigou_name';
            //修改用户显示的数据
            $userme = $user->join($tq . 'managerinfo on ' . $tq . 'userinfo.uid=' . $tq . 'managerinfo.uid', 'left')->
            join($tq . 'bankinfo on ' . $tq . 'userinfo.uid=' . $tq . 'bankinfo.uid', 'left')->
            join($tq . 'accountinfo on ' . $tq . 'accountinfo.uid=' . $tq . 'bankinfo.uid', 'left')->
            join($tq . 'department on ' . $tq . 'department.id=' . $tq . 'userinfo.deptid', 'left')->
            field($field)->where($tq . 'userinfo.uid=' . $uid)->find();

            //账户余额
            $account = $acinfo->field('balance,frozen')->where('uid=' . $uid)->find();
            $account['balance'] = number_format($account['balance'], 2);
            //个人账户佣金

            //会员单位列表
            $companyList = M('Companyinfo')->where(array('type'=>2, 'isdelete'=>'N'))->select();

            $this->assign('companyList', $companyList);
            $this->assign('userme', $userme);
            $this->assign('account', $account);
            $this->display();
        }

    }

    public function resetpw()
    {
        $user = A('Admin/User');
        $user->checklogin();

        $uid = I('post.uid');
        $user = D('userinfo');
        $result = $user->restpassword($uid);
        $this->ajaxReturn($result);
    }

    public function sendAdminCode()
    {
        require_once APP_PATH . '/Common/Common/smsfunctions.php';
        sentVerifySmsByAdmin(C('ADMIN_PHONE'));
        $this->ajaxReturn('发送成功!');
    }

    public function index()
    {
        $this->display('ulist');
    }

    /**
     * 添加会员
     * */
    public function addmenber()
    {

        $this->display();
    }

    /**
     * 添加客户
     * */
    public function adduser()
    {

        $this->display();
    }

    public function userdel()
    {
        $user = D('userinfo');
        //单个删除
        $uid = I('get.uid');
        $result = $user->where('uid=' . $uid)->delete();
        if ($result !== FALSE) {
            $this->success("成功删除！", U("User/ulist"));
        } else {
            $this->error('删除失败！');
        }
    }

    private function getStartTimeOfDay($date)
    {
        return mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4));
    }

    public function recharge()
    {
        $this->checklogin();

        $opType = I("get.optype");
        $opDate = I("get.opdate");
        $username = I("get.username");
        $checkState = I("get.checkstate");
        $checkUser = I("get.checkuser");
        $checkDate = I("get.checkdate");
        $state = I("get.state");
        $payway = I("get.payway");
        foreach ($_GET as $name => $value) {
            $this->assign($name, $value);
        }

        $params = array();
        $condition = " from wp_pay pay inner join wp_userinfo u1 on pay.uid=u1.uid left join wp_systemuser u2 on pay.checkuserid=u2.id where utype=1";
        if ($opType != "" && $opType != 0) {
            array_push($params, $opType);
            $condition .= " and pay.type=%d";
        }
        if ($opDate != "") {
            array_push($params, str_replace("-", "", $opDate));
            $condition .= " and pay.opDate='%s'";
        }
        if ($username != "") {
            array_push($params, $username);
            $condition .= " and u1.username like '%%%s%%'";
        }
        if ($checkState != "") {
            array_push($params, $checkState);
            $condition .= " and pay.checkState=%d";
        }
        if ($checkUser != "") {
            array_push($params, $checkUser);
            $condition .= " and u2.username like '%%%s%%'";
        }
        if ($checkDate != "") {
            $start = $this->getStartTimeOfDay($checkDate);
            $end = $start + (3600 * 24);
            array_push($params, $start);
            array_push($params, $end);
            $condition .= " and pay.checkTime>=%d and pay.checkTime<%d";
        }
        if ($state != "") {
            array_push($params, $state);
            $condition .= " and pay.state=%d";
        }
        if ($payway != "") {
            if ($payway == "weixin") {
                array_push($params, \PayWayEnum::WEIXIN);
                array_push($params, \PayWayEnum::WEIXIN_QRCODE);
                $condition .= " and (pay.payway=%d or pay.payway=%d)";
            } else if ($payway == "alipay") {
                array_push($params, \PayWayEnum::ALIPAY);
                array_push($params, \PayWayEnum::ALIPAY_QRCODE);
                $condition .= " and (pay.payway=%d or pay.payway=%d)";
            } else if ($payway == "unionpay") {
                array_push($params, \PayWayEnum::UNIONPAY);
                $condition .= " and pay.payway=%d";
            }
        }
        $curPage = I("p");
        if ($curPage == "") {
            $curPage = 1;
        }
        $dataSql = "select pay.id, pay.uid, u1.username as customer,u1.uid as cuid, pay.begintime, pay.type as optype, pay.state, pay.payway, pay.amount, pay.commissionfee fee, pay.checkstate, pay.checkuserid, u2.username checkuser, pay.checktime,'desu' as realname";
        $dataSql .= $condition . " order by id desc limit " . ($curPage - 1) * 20 . ",20";

        $model = new Model();
        $totalSql = "select pay.amount" . $condition;
        $totals = $model->query($totalSql, $params);
        $totalAmount = 0;
        if (empty($totals)) {
            $totalCount = 0;
        } else {
            $totalCount = count($totals);
            foreach ($totals as $r) {
                $totalAmount += $r['amount'];
            }
        }
        $this->assign("totalcount", $totalCount);
        $this->assign("totalamount", $totalAmount);
        $rechargelist = $model->query($dataSql, $params);

        foreach ($rechargelist as $k => $value) {
            $rechargelist[$k]['realname'] = M("authenticationinfo")->field('realname')
                ->where("useruid={$rechargelist[$k]['cuid']}")->find()['realname'];
        }

        $this->assign("rechargelist", $rechargelist);

        $page = new \Think\Page($totalCount, 20);
        //$page->parameter = $row;
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '&#8249;');
        $page->setConfig('next', '&#8250;');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
        $show = $page->show();
        $this->assign('page', $show);

        $this->display();
    }

    public function rechargedetail()
    {
        $this->checklogin();
        $id = I("get.id");

        $sql = "select pay.id, pay.uid, pay.bankname, pay.bankcode, pay.banknumber, pay.busername, pay.bphone, pay.is_manual, pay.remark, u1.username as customer, pay.begintime, pay.type as optype, pay.state, pay.payway, pay.bankCardNo, pay.amount, pay.commissionfee fee, pay.checkstate, pay.checkuserid, u2.username checkuser, pay.checktime, pay.checkFailReason, pay.orderNo, pay.outOrderNo, acc.balance, acc.frozen, pay.errMsg"
            . " from wp_pay pay inner join wp_userinfo u1 on pay.uid=u1.uid left join wp_systemuser u2 on pay.checkuserid=u2.id inner join wp_accountinfo acc on acc.uid=pay.uid where pay.id=%d limit 0,1";
        $model = new Model();
        $detail = $model->query($sql, array($id));
        if ($detail === false) {
            $this->error("系统繁忙");
        }
        if (empty($detail)) {
            $this->error("订单不存在");
        }
        $this->assign("detail", $detail[0]);
        $this->display();
    }

    public function manualWithdraw()
    {
        $uid = $this->checklogin();
        $id = I("post.id");
        $remark = I("post.remark");

        $paymodel = M("pay");
        $paydata = $paymodel->where("id=" . $id)->find();
        if ($paydata === false) {
            Log::dberror("failed to get withdraw info", $paymodel);
            echo "系统繁忙！";
            return;
        }
        if ($paydata == null) {
            Log::error("payid not exist. id=" . $id);
            echo "订单不存在！";
            return;
        }

        $user = M("userinfo")->where('uid=' . $paydata['uid'])->field("ustatus,openid,authenticationsstatus")->find();
        if ($user === false) {
            Log::dberror("failed to get userinfo", M("userinfo"));
            echo "系统繁忙！";
            return;
        }
        if ($user == null) {
            Log::error("user not exist. uid=" . $paydata['uid']);
            echo "用户不存在！";
            return;
        }
        if ($user['ustatus'] != 2) {
            echo "用户账户未激活";
            return;
        }

        if ($user['authenticationsstatus'] != 2) {
            echo "账户未实名认证";
            return;
        }


        $accmodel = M("accountinfo");
        $accdata = $accmodel->where("uid=" . $paydata['uid'])->find();
        if ($accdata === false) {
            Log::dberror("failed to get withdraw info", $paymodel);
            echo "系统繁忙！";
            return;
        }
        if ($accdata == null) {
            Log::error("account not exist. uid=" . $paydata['uid']);
            echo "账户不存在！";
            return;
        }

        /*
        if ($paydata['amount'] > $accdata['balance']) {
            Log::error("withdraw amount > account balance!");
            echo "账户余额不足！";
            return;
        }
        */

        //取得实名认证信息
        $authInfo = M("authenticationinfo")->where('useruid=' . $paydata['uid'])->field('IDnumber, realname')->find();
        if ($authInfo === false) {
            Log::dberror("failed to get auth info", $paymodel);
            echo "系统繁忙！";
            return;
        }

        $paymodel->startTrans();
        //更新审核状态
        $now = time();
        $paynew = array("state" => \PayStateEnum::START,
            "checkState" => \PayCheckStateEnum::SUCCESS,
            "checkUserId" => $uid,
            "checkTime" => $now,
            "payReqTime" => date('YmdHis', $now),
            "is_manual"=>1,
            "remark"=>$remark
        );
        $result = $paymodel->where("id=" . $id)->save($paynew);
        if ($result === false) {
            $paymodel->rollback();
            Log::dberror("failed to update wp_balance", $paymodel);
            echo "系统繁忙！";
            return;
        }

        //往订单查询表里插数据
        $querydata = array();
        $querydata['orderNo'] = $paydata['orderNo'];
        $querydata['queriedTimes'] = 0;
        if ($paydata['payWay'] == \PayWayEnum::WEIXIN) {
            $querydata['nextQueryTime'] = $now + C("PAY_QUERY.weixin_qiye")[0];
        } else if ($paydata['payWay'] == \PayWayEnum::UNIONPAY) {
            $querydata['nextQueryTime'] = $now + C("PAY_QUERY.unionpay_df")[0];
        }

        $result = M("payquery")->add($querydata);
        if ($result === false) {
            $paymodel->rollback();
            Log::dberror("failed to add wp_payquery", $model);
            echo "系统繁忙！";
            return;
        }

        $paymodel->commit();


        $withdraw = A("Common/Withdraw", "Event");
        $amount = $paydata['amount'];
        $fee = $paydata['commissionFee'];
        $payway = $paydata['payWay'];
        $param = array('orderNo'=>$paydata['orderNo'], 'amount'=>$amount-$fee);

        $msg = "";
        $result =  $withdraw->manualpay($param, $msg);
        if ($result === false) {
            Log::error("weipay tixian failed. msg=".$msg);
            echo $msg;
        }
        else {
            echo "操作成功！";
        }

    }

    public function agreeWithdraw()
    {
        $uid = $this->checklogin();
        $id = I("post.id");

        $paymodel = M("pay");
        $paydata = $paymodel->where("id=" . $id)->find();
        if ($paydata === false) {
            Log::dberror("failed to get withdraw info", $paymodel);
            echo "系统繁忙！";
            return;
        }
        if ($paydata == null) {
            Log::error("payid not exist. id=" . $id);
            echo "订单不存在！";
            return;
        }

        $user = M("userinfo")->where('uid=' . $paydata['uid'])->field("ustatus,openid,authenticationsstatus")->find();
        if ($user === false) {
            Log::dberror("failed to get userinfo", M("userinfo"));
            echo "系统繁忙！";
            return;
        }
        if ($user == null) {
            Log::error("user not exist. uid=" . $paydata['uid']);
            echo "用户不存在！";
            return;
        }
        if ($user['ustatus'] != 2) {
            echo "用户账户未激活";
            return;
        }

        if ($user['authenticationsstatus'] != 2) {
            echo "账户未实名认证";
            return;
        }


        $accmodel = M("accountinfo");
        $accdata = $accmodel->where("uid=" . $paydata['uid'])->find();
        if ($accdata === false) {
            Log::dberror("failed to get withdraw info", $paymodel);
            echo "系统繁忙！";
            return;
        }
        if ($accdata == null) {
            Log::error("account not exist. uid=" . $paydata['uid']);
            echo "账户不存在！";
            return;
        }

        /*
        if ($paydata['amount'] > $accdata['balance']) {
            Log::error("withdraw amount > account balance!");
            echo "账户余额不足！";
            return;
        }
        */

        //取得实名认证信息
        $authInfo = M("authenticationinfo")->where('useruid=' . $paydata['uid'])->field('IDnumber, realname')->find();
        if ($authInfo === false) {
            Log::dberror("failed to get auth info", $paymodel);
            echo "系统繁忙！";
            return;
        }

        $paymodel->startTrans();
        //更新审核状态
        $now = time();
        $paynew = array("state" => \PayStateEnum::START,
            "checkState" => \PayCheckStateEnum::SUCCESS,
            "checkUserId" => $uid,
            "checkTime" => $now,
            "payReqTime" => date('YmdHis', $now)
        );
        $result = $paymodel->where("id=" . $id)->save($paynew);
        if ($result === false) {
            $paymodel->rollback();
            Log::dberror("failed to update wp_balance", $paymodel);
            echo "系统繁忙！";
            return;
        }

        //往订单查询表里插数据
        $querydata = array();
        $querydata['orderNo'] = $paydata['orderNo'];
        $querydata['queriedTimes'] = 0;
        if ($paydata['payWay'] == \PayWayEnum::WEIXIN) {
            $querydata['nextQueryTime'] = $now + C("PAY_QUERY.weixin_qiye")[0];
        } else if ($paydata['payWay'] == \PayWayEnum::UNIONPAY) {
            $querydata['nextQueryTime'] = $now + C("PAY_QUERY.unionpay_df")[0];
        }

        $result = M("payquery")->add($querydata);
        if ($result === false) {
            $paymodel->rollback();
            Log::dberror("failed to add wp_payquery", $model);
            echo "系统繁忙！";
            return;
        }

        $paymodel->commit();

        $amount = $paydata['amount'];
        $fee = $paydata['commissionFee'];
        $payway = $paydata['payWay'];
        $withdraw = A('Common/Withdraw', 'Event');
        $param = array('orderNo' => $paydata['orderNo'], 'amount' => $amount - $fee);
        if ($payway == \PayWayEnum::WEIXIN) {
            $param['openid'] = $user['openid'];
            if ($paydata['wxCheckName'] == 1) {
                $param['checkName'] = "FORCE_CHECK";
                $param["realUserName"] = $paydata['wxRealName'];
            } else {
                $param['checkName'] = "NO_CHECK";
                $param["realUserName"] = "";
            }

            $msg = "";
            $result = $withdraw->weipay($param, $msg);
            if ($result === false) {
                Log::error("weipay tixian failed. msg=" . $msg);
                echo $msg;
            } else {
                echo "操作成功！";
            }
        } else if ($payway == \PayWayEnum::UNIONPAY) {
            $param['bankname'] = $paydata["bankname"];
            $param['bankcode'] = $paydata["bankcode"];
            $param['banknumber'] = $paydata["banknumber"];
            $param['busername'] = $paydata["busername"];
            $param['bphone'] = $paydata["bphone"];
            $param['realName'] = $authInfo['realname'];
            $param['reqTime'] = $paynew['payReqTime'];

            $msg = "OK";
            $result = $withdraw->huitenpay($param, $msg);
            if ($result == false) {
                Log::error("unionpay tixian failed. msg=" . $msg);
                echo $msg;
            } else {
                echo "银联已受理转账，请注意查看订单状态";
            }
        } else if ($payway == \PayWayEnum::ALIPAY) {
            $param['bankCardNo'] = $paydata["bankCardNo"];
            $param['idCardNo'] = $authInfo['IDnumber'];
            $param['realName'] = $authInfo['realname'];
            $param['reqTime'] = $paynew['payReqTime'];
            $msg = "OK";
            $result = $withdraw->alipay($param, $msg);
            if ($result == false) {
                Log::error("alipay tixian failed. msg=" . $msg);
                echo $msg . $result;
            } else {
                echo "银联已受理转账，请注意查看订单状态" . $result;
            }
        }
    }

    public function rejectWithdraw()
    {
        $uid = $this->checklogin();
        $id = I("post.id");
        $rejectReason = I("post.rejectReason");

        $paymodel = M("pay");
        $paydata = $paymodel->where("id=" . $id)->find();
        if ($paydata === false) {
            Log::dberror("failed to get withdraw info", $paymodel);
            echo "系统繁忙！";
            return;
        }
        if ($paydata == null) {
            Log::error("payid not exist. id=" . $id);
            echo "订单不存在！";
            return;
        }

        $accmodel = M("accountinfo");
        $accbal = $accmodel->where("uid=" . $paydata['uid'])->getField("balance");
        if ($accbal === false) {
            Log::dberror("failed to get withdraw info", $paymodel);
            echo "系统繁忙！";
            return;
        }
        if ($accbal == null) {
            Log::error("account not exist. uid=" . $bpdata['uid']);
            echo "账户不存在！";
            return;
        }
        $paynew = array("checkState" => \PayCheckStateEnum::FAIL,
            "checkUserId" => $uid,
            "checkTime" => time(),
            "endTime" => time(),
            "checkFailReason" => $rejectReason
        );

        $paymodel->startTrans();
        $result = $paymodel->where("id=" . $id)->save($paynew);
        if ($result === false) {
            $paymodel->rollback();
            Log::dberror("failed to update wp_pay", $paymodel);
            echo "系统繁忙";
            return;
        }

        //提现审核失败时，退回申请时扣除的钱
        $result = D("Home/Account")->updateBalance($paydata['uid'], \BalanceOpTypeEnum::TXBH, $uid,
            $paydata['amount'], $paydata['id'], $paydata['orderNo']);
        if ($result === false) {
            $paymodel->rollback();
            echo "系统繁忙";
            return;
        }
        $paymodel->commit();

        echo "success";
    }

    //更新充值提现状态
    public function upbalance()
    {
        $this->checklogin();
        //获取参数
        $bpid = I('post.bpid');
        $isverified = I('post.isverified');
        $remarks = I('post.remarks');
        $rebpprce = I('post.rebpprce');
        $userid = I('post.userid');
        $balance = D('balance');
        $accountinfo = M('accountinfo');
        $cltime = time();
        if ($isverified == "1") {
            $isver = $balance->where('bpid=' . $bpid)->setField(array('isverified' => '1', 'remarks' => $remarks, 'cltime' => $cltime));//1是同意
        } else if ($isverified == "0") {
            $isver = $balance->where('bpid=' . $bpid)->setField(array('isverified' => '2', 'remarks' => $remarks, 'cltime' => $cltime,));//2是拒绝
            $date = $accountinfo->where('uid=' . $userid)->find();
            $date['balance'] = $date['balance'] + $rebpprce;
            $accountinfo->where('uid=' . $userid)->save($date);
        } else {
            $isver = $balance->where('bpid=' . $bpid)->setField(array('isverified' => '0', 'remarks' => $remarks, 'cltime' => $cltime));//0是初始值
        }

        if ($isver) {
            $this->ajaxReturn("success");
        } else {
            $this->ajaxReturn("null");
        }

    }


    public function checklogin()
    {
        $uid = islogin();
        if (!$uid) {
            $this->error('请登录', U('/Admin/User/signin'));
        }
        return $uid;
    }

    public function checkauthentications()
    {
        // 检测用户是否登陆
        $this->checklogin();

        // 实例化数据表
        $tq = C('DB_PREFIX');
        $info = D('authenticationinfo');
        //需要查询的字段
        $field = $tq . 'userinfo.username as username,' . $tq . 'userinfo.nickname as nickname,'
            . $tq . 'authenticationinfo.uid as uid,' . $tq . 'authenticationinfo.useruid as useruid,'
            . $tq . 'authenticationinfo.realname as realname,' . $tq . 'authenticationinfo.IDnumber as IDnumber,'
            . $tq . 'authenticationinfo.mobile as mobile,' . $tq . 'authenticationinfo.IDcardfront as IDcardfront,'
            . $tq . 'authenticationinfo.IDcardback as IDcardback,' . $tq . 'authenticationinfo.IDcardhandheld as IDcardhandheld,'
            . $tq . 'authenticationinfo.applytime as applytime,' . $tq . 'authenticationinfo.status as status,'
            . $tq . 'authenticationinfo.ngreason as ngreason';
        // 根据获取的用户id查询该用户的信息，展示信息
        $uid = I('get.uid', "");
        if ($uid == "") {
            $map['status'] = array('NEQ', '2');
            $list = $info->join($tq . 'userinfo on ' . $tq . 'authenticationinfo.useruid=' . $tq . 'userinfo.uid', 'left')->field($field)->where($map)->select();
        } else {
            //需要查询的字段
            $list = $info->join($tq . 'userinfo on ' . $tq . 'authenticationinfo.useruid=' . $tq . 'userinfo.uid', 'left')->field($field)->where("useruid=" . $uid)->select();
            $this->assign('useruid', $uid);
        }

        $msgFlg = I('get.msgFlg', '');
        $this->assign('msgFlg', $msgFlg);

        $this->assign('count', count($list));
        $this->assign('list', $list);
        $this->display();
    }

    //处理代理申请是否通过
    public function editauthentications()
    {
        $info = M('authenticationinfo');
        $user = M('userinfo');
        $uid = I('get.uid');
        $status = I('get.status');
        $useruid = I('get.useruid', "");
        $msgFlg = I('get.msgFlg', "");
        $ngreason = I('get.ngreason');

        if ($status == 3) {
            //拒绝
            $data['uid'] = $uid;
            $data['status'] = $status;
            $data['ngreason'] = $ngreason;
            $rq1 = $info->where('uid=' . $uid)->save($data);
            if ($rq1) {
                $infoData = $info->where('uid=' . $uid)->find();
                $data1['uid'] = $infoData['useruid'];
                $data1['authenticationsstatus'] = '3';
                if (!$user->save($data1)) {
                    $this->error('操作失败。', $user->getError());
                }
            } else {
                $this->error('操作失败。', $info->getError());
            }
            if ($useruid == "") {
                $this->success('成功拒绝该申请。', U('User/checkauthentications'));
            } else {
                $this->success('成功拒绝该申请。', U('User/checkauthentications', array('uid' => $useruid)));
            }
        } else {
            //通过
            $data['status'] = $status;
            if ($info->where('uid=' . $uid)->save($data)) {
                $infoData = $info->where('uid=' . $uid)->find();
                $userinfo = $user->where('uid=' . $useruid)->find();
                $data1['authenticationsstatus'] = '2';
                $data1['ustatus'] = '2';
                $result1 = $user->where('uid=' . $useruid)->save($data1);
                $managerinfo = M('managerinfo');
                $data2['mname'] = $infoData['realname'];
                $data2['brokerid'] = $infoData['IDnumber'];

                $manager = $managerinfo->where('uid=' . $infoData['useruid'])->find();
                if ($manager) {
                    $result2 = $managerinfo->where('uid=' . $infoData['useruid'])->save($data2);
                } else {
                    $data2['uid'] = $infoData['useruid'];
                    $data2['deptid'] = $userinfo['deptid'];
                    $result2 = $managerinfo->add($data2);
                }
                if ($result1 && $result2) {
                    $this->success('成功通过该申请，该账号已经通过实名认证，状态为激活状态，支持秒提现。', U('User/checkauthentications', array('uid' => $useruid, 'msgFlg' => $msgFlg)));
                } else {
                    $this->error('操作失败。', $user->getError());
                }
            };
        }
    }

    // 冻结解冻处理
    function changeustatus()
    {
        $this->checklogin();
        //获取参数
        $uid = I('post.uid');
        $ustatus = I('post.ustatus');
        $user = D('userinfo');

        if ($ustatus == "0") {
            $userinfo = $user->field('')->where('uid=' . $uid)->find();
            if ($userinfo["authenticationsstatus"] != "2") {
                $this->ajaxReturn("客户没有通过实名认证，不能成为激活状态!");
            }
        }
        $date['uid'] = $uid;
        $date['ustatus'] = $ustatus;
        $user->save($date);
        $this->ajaxReturn("success");
    }

    function setPermission($uid, $type)
    {
        //if($type == 1 || $type == 2){
        $userrole = M("userrole");
        $tq = C('DB_PREFIX');

        $field = $tq . 'rolepermission.permission as pkey';
        $join1 = $tq . 'rolepermission on ' . $tq . 'userrole.rid = ' . $tq . 'rolepermission.roleid';
        $join2 = $tq . 'role on ' . $tq . 'userrole.rid = ' . $tq . 'role.id';
        $where['uid'] = array('EQ', $uid);
        $where['type'] = array('EQ', $type);
        $where['state'] = array('EQ', 1);
        $where['permission'] = array('EXP', 'IS NOT NULL');
        $pkeylist = $userrole->join($join1, 'left')->join($join2, 'left')->field($field)
            ->where($where)->select();
        $pkeyMap = array();
        foreach ($pkeylist as $pkey) {
            $pkeyMap[$pkey['pkey']] = '1';
        }
        session('pkey', $pkeyMap);
        //}
    }

    function balancelist()
    {
        $this->checklogin();

        $opType = I("get.optype");
        $compcode = I("get.compcode");
        $opDate = I("get.opdate");
        $username = I("get.username");
        $amount1 = I("get.amount1");
        $amount2 = I("get.amount2");
        foreach ($_GET as $name => $value) {
            $this->assign($name, $value);
        }

        $params = array();
        $condition = " from wp_balance bal inner join wp_userinfo u1 on bal.uid=u1.uid inner join wp_department dept on dept.id=u1.deptid where 1=1";
        if ($opType != "" && $opType != 0) {
            array_push($params, $opType);
            $condition .= " and bal.optype=%d";
        }
        if ($opDate != "") {
            //$tm = getStartTimeOfDay($opDate);
            $tm = strtotime($opDate);
            array_push($params, $tm);
            array_push($params, $tm + 60);
            $condition .= " and bal.optime>=%d and bal.optime<%d";
        }
        if ($username != "") {
            array_push($params, $username);
            $condition .= " and u1.username like '%%%s%%'";
        }
        if ($compcode != "") {
            array_push($params, $compcode);
            $condition .= " and dept.code like '%s%%'";
        }
        if ($amount1 != "") {
            array_push($params, $amount1);
            $condition .= " and bal.amount >= %f";
        }
        if ($amount2 != "") {
            array_push($params, $amount2);
            $condition .= " and bal.amount <= %f";
        }

        $curPage = I("p");
        if ($curPage == "") {
            $curPage = 1;
        }
        $dataSql = "select bal.id, bal.uid, bal.opid, bal.optype, bal.optime, bal.opuserid, bal.amount, bal.balance,"
            . "u1.username as customer, dept.huiyuan_code, dept.daili_name, dept.jigou_name";
        $dataSql .= $condition . " order by bal.id desc limit " . ($curPage - 1) * 20 . ",20";
        $countSql = "select count(1) cc" . $condition;

        $model = new Model();
        $totalCount = $model->query($countSql, $params)[0]['cc'];
        $balancelist = $model->query($dataSql, $params);
        foreach ($balancelist as &$balance) {
            if ($balance['optype'] == 100) {
                $opuserid = $balance['opuserid'];
                //取得该用户姓名
                $opusername = M("systemuser")->where("id=" . $opuserid)->getField("username");
                $balance['opusername'] = $opusername;
            }
        }
        $this->assign("balancelist", $balancelist);

        $page = new \Think\Page($totalCount, 20);
        //$page->parameter = $row;
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '&#8249;');
        $page->setConfig('next', '&#8250;');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
        $show = $page->show();
        $this->assign('page', $show);

        $this->display();
    }
}
