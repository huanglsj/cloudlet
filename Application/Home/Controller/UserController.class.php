<?php
namespace Home\Controller;

use Think\Controller;
use Think\Log;
use Think\Model;

require APP_PATH . '/Common/Common/smsfunctions.php';
require_once APP_PATH . '/Common/Enum/PayWayEnum.php';
require_once APP_PATH . '/Common/Enum/PayStateEnum.php';
require_once APP_PATH . '/Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH . '/Common/Enum/KeyAccessEnum.php';
require_once APP_PATH . '/Common/Common/token.php';

class UserController extends Controller
{
    public function login()
    {
        header("Content-type: text/html; charset=utf-8");
        if(isset($_COOKIE['tokendesu'])==1&&isset($_COOKIE['usernamedesu'])==1&&isset($_COOKIE['desudesu'])==1)
        {
            $tokendesu=$_COOKIE['tokendesu'];
            $usernamedesu=$_COOKIE['usernamedesu'];
            $uid=$_COOKIE['desudesu'];
            $logintk = M('logintoken')->where(array('username'=>$usernamedesu,'token'=>$tokendesu))->find();
            if ($logintk) {
                // 存储session
                session('uid', $uid);
                session('husername', $usernamedesu);
                $this->redirect('Index/index');
            }
        }

        $keyaccess = D('Common/keyaccess');
        $keyaccessinfo['key'] = \KeyAccessEnum::UNKNOWN_LOGIN;
        $keyaccessinfo['result'] = 0;
        // 判断提交方式
        if (I('post.username') != '' && I('post.password') != '') {
            // 实例化Login对象
            $user = D('userinfo');
            $where = array();
            $where['username'] = I('post.username');
            $where['ustatus'] = array('neq', 0);
            $lockCheck = $keyaccess->isClientLock(I('post.username'));
//            if ($lockCheck['result']) {
//                $this->assign('errorMsg', $lockCheck['errorMsg']);
//                $this->display();
//                return;
//            }
            $result = $user->where($where)->field('uid,username,upwd,utime')->find();
            if ($result) {
                $keyaccessinfo['outid'] = $result['uid'];
                $keyaccessinfo['username'] = $result['username'];
            }
            // 验证用户名 对比 密码
            if ($result['upwd'] == md5(I('post.password') . $result['utime'])) {
                // 存储session
                session('uid', $result['uid']);          // 当前用户id
                session('husername', $result['username']);   // 当前用户昵称
                // 更新用户登录信息
                $dd['lastlog'] = time();
                $user->where('uid=' . $result['uid'])->save($dd);

                $keyaccessinfo['key'] = \KeyAccessEnum::CUSTOMER_LOGIN;
                $keyaccessinfo['result'] = 1;
                $keyaccessinfo['checkedslip'] = 2;

                // 2017-3-20
                $logintoken['token']=createToken();
                $logintoken['logintime']=time();
                $logintk = M('logintoken')->where(array('username'=>$result['username']))->find();
                cookie("tokendesu",$logintoken['token'],time()+1*24*60*60);
                cookie("usernamedesu",$result['username'],time()+1*24*60*60);
                cookie("desudesu",$result['uid'],time()+1*24*60*60);
                if ($logintk) {
                    M('logintoken')->where(array('username'=>$result['username']))->save($logintoken);
                } else {
                    $logintoken['username']=$result['username'];
                    M('logintoken')->add($logintoken);
                }

                $result = $keyaccess->addInfo($keyaccessinfo);
                $this->redirect('Index/index');
            } else {
                $keyaccessinfo['key'] = \KeyAccessEnum::CUSTOMER_LOGIN;
                $keyaccessinfo['result'] = 2;//客户端登陆失败
                $keyaccessinfo['checkedslip'] = 2;
                $result = $keyaccess->addInfo($keyaccessinfo);
                if ($result['risk']) {
                    $this->assign('errorMsg', $result['remark']);
                    $this->display();
                    return;
                } else if ($lockCheck['warringMsg']) {
                    $this->assign('errorMsg', $lockCheck['warringMsg']);
                    $this->display();
                    return;
                } else {
                    $this->assign('errorMsg', '登录失败,用户名或密码不正确!');
                    $this->display();
                    return;
                }
            }
        }
        $userinfo = M('userinfo');
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //var_dump(strpos($user_agent, 'MicroMessenger'));
        if (strpos($user_agent, 'MicroMessenger') === false) {
            //非微信浏览器
            //echo "<script>alert('非微信端登陆');</script>";
            $this->assign('is_weixin', 0);
            $this->display();
        } else {
            //做跳转，拿到openid,第一步跳转链接，
            // echo "<script>alert('微信端登陆');</script>";
            //echo $_GET['openid'];
            //die();

            $copenif = I('cookie.openid');
            if (!empty($copenif)) {
                //如果在Cookie中存在openid，则根据这个Openid获取用户信息
            }

            if (I('get.openid') == '') {
                $this->assign('is_weixin', 1);
                $wechat = M('wechat')->find();
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $wechat['appid'] . "&redirect_uri=http://" . $_SERVER['HTTP_HOST'] . "/Extend/weixin.php&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";
                echo "<script>window.location.href='" . $url . "'</script>";
            } else {
                // echo "<script>alert('openid有值')</script>";
                $this->assign('is_weixin', 2);
                //echo $_GET['openid'];
                //这里做一个判断，客户没有注册，则直接去注册页面，否则去登录页面。
                $openid['openid'] = I('get.openid');
                $openid['nickname'] = I('get.nickname');
                $openid['address'] = I('get.address');
                $openid['portrait'] = I('get.portrait');
                $openid['utime'] = time();//时间
                $length = M('userinfo')->count();
                $openid['username'] = "wx" . ($length + 100100).substr($openid['openid'], -1);
                //$openid['username']= substr($openid['openid'], -5).time();//登录名
                $openid['usertype'] = '1';
                $openid['ustatus'] = '1';
                $openid['wxtype'] = '1';
                $userinfoid = $userinfo->where("openid='" . $openid['openid'] . "'")->find();
                if ($userinfoid) {
                    $keyaccessinfo['outid'] = $userinfoid['uid'];
                    $keyaccessinfo['username'] = $userinfoid['username'];
                }
                //有数据在判断看是否有密码，有账号，没有的话跳转到初始页面，让输入密码，这里是修改方法。
                if ($userinfoid) {
                    if ($userinfoid['ustatus'] == 0) {
                        $keyaccessinfo['key'] = \KeyAccessEnum::CUSTOMER_LOGIN;
                        $keyaccessinfo['result'] = 3;
                        $keyaccessinfo['checkedslip'] = 2;
                        $result = $keyaccess->addInfo($keyaccessinfo);
                        die("<script>alert('您的账户出错，请联系客服！');</script>");
                    }
                    if ($userinfoid['upwd']) {
                        //echo "<script>alert('找到了')</script>";
                        //传用户名过去，做隐藏，然后直接登录
                        // $this->assign('username',$userinfoid['username']);
                        // $this->assign('userpswd',$userinfoid['upwd']);
                        $_SESSION['uid'] = $userinfoid['uid'];
                        $_SESSION['husername'] = $userinfoid['username'];// 当前用户昵称
                        $dd['lastlog'] = time();
                        $userinfo->where('uid=' . $userinfoid['uid'])->save($dd);

                        $keyaccessinfo['key'] = \KeyAccessEnum::CUSTOMER_LOGIN;
                        $keyaccessinfo['result'] = 1;
                        $keyaccessinfo['checkedslip'] = 2;

                        // 2017-3-20
                        $logintoken['token']=createToken();
                        $logintoken['logintime']=time();
                        $logintk = M('logintoken')->where(array('username'=>$userinfoid['username']))->find();
                        cookie("tokendesu",$logintoken['token'],time()+1*24*60*60);
                        cookie("usernamedesu",$userinfoid['username'],time()+1*24*60*60);
                        cookie("desudesu",$userinfoid['uid'],time()+1*24*60*60);
                        if ($logintk) {
                            M('logintoken')->where(array('username'=>$userinfoid['username']))->save($logintoken);
                        } else {
                            $logintoken['username']=$userinfoid['username'];
                            M('logintoken')->add($logintoken);
                        }

                        $result = $keyaccess->addInfo($keyaccessinfo);
                        echo "<script>window.location.href='/index.php/Home/Index/index.html';</script>";
                    } else {
                        //echo "<script>alert('没找到')</script>";
                        //注册初始密码页面
                        $this->redirect('User/reg', array('openid' => $openid['openid']), 1, '请设置初始密码...');
                    }
                } else {
                    //没查询到，就跳转到注册页面，让输入初始密码。生成一个账号。这里是添加账号方法后，赋值跳转到登录页面。
                    $result = $userinfo->add($openid);
                    if ($result) {
                        //初始密码页面
                        $this->redirect('User/reg', array('openid' => $openid['openid']), 1, '请设置初始密码...');
                    }
                }

            }
            $this->display();
        }


    }

    public function login2()
    {
        header("Content-type: text/html; charset=utf-8");

        $this->display();

    }


    //注册页面
    public function reg()
    {
        //微信登录时，必须有openid才可以注册，需要跳转一圈后才能回到注册页面，所有先把邀请码放到session中
        $invite_code = I('get.invitecode');
        if (empty($invite_code)) {
            $invite_code = $_SESSION['invitecode'];
            $_SESSION['invitecode'] = null;
        } else {
            $_SESSION['invitecode'] = $invite_code;
        }
        $openid = I('get.openid');
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        //var_dump(strpos($user_agent, 'MicroMessenger'));
        if (strpos($user_agent, 'MicroMessenger') && empty($openid)) {
            $this->redirect('User/login');
        }
        if ($openid) {
            //存在openid，获取用户信息
            $userinfo = M('userinfo')->where('openid=' . $openid)->find();
            $this->assign('user', $userinfo);
        }
        $this->assign("invitecode", $invite_code);

        $this->assign('openid', $openid);
        $this->display();

    }

    //注册
    public function register()
    {
        $tq = C('DB_PREFIX');
        //$this->userlogin();
        if (IS_POST) {// 判断提交方式 做不同处理
            // 实例化User对象
            $user = D('userinfo');
            //检查用户名
            header("Content-type: text/html; charset=utf-8");
            //检查手机验证码
            $verify = I('post.code');
            if (checkCode(I('post.utel'), $verify)) {
                $data['utel'] = I('post.utel');
                $time = time();
                $data['utime'] = $time;
                $data['upwd'] = md5(I('post.upwd') . $time);
                $data['ustatus'] = "1";
                $data['reginvitecode'] = I('post.yaoqingCode') ? I('post.yaoqingCode') : C('default_yaoqing_code');
                if (!$data['reginvitecode']) {
                    if (empty($_SESSION['invitecode'])) {
                        $defaultMember = M('companyinfo')->join($tq . 'department on ' . $tq . 'companyinfo.deptid=' . $tq . 'department.id')->
                        where($tq . 'companyinfo.isself=1')->field($tq . 'department.invite_code as invite_code')->find();
                        if ($defaultMember) {
                            $data['reginvitecode'] = $defaultMember['invite_code'];
                        } else {
                            die("<script>alert('请输入邀请码！');history.back(-1);</script>");
                        }
                    } else {
                        $data['reginvitecode'] = $_SESSION['invitecode'];
                    }
                }
                //获取邀请码所属信息
                $department = M('department')->where(array('invite_code' => $data['reginvitecode']))->find();
                if (!$department) {
                    die("<script>alert('请输入正确的6位邀请码！');history.back(-1);</script>");
                }
                $company = M('companyinfo')->where('deptid=' . $department['id'])->find();
                $data['deptid'] = $department['id'];
                $data['companyid'] = $company['cid'];
                $data['ucode'] = $department['code'];
                // 默认点差
                $webconfig = D('webconfig')->find();
                $data['diancha'] = $webconfig['defdiancha'];

                $utel = $user->where('utel=' . $data['utel'])->find();
                //var_dump($data);
                if ($utel) {
                    $user_agent = $_SERVER['HTTP_USER_AGENT'];
                    if (strpos($user_agent, 'MicroMessenger')) {
                        $map['openid'] = I('post.openid');
                        $userinfo = $user->where($map)->find();
                        if (empty($userinfo)) {
                            die("<script>alert('注册失败！');</script>");
                        }
                        $inputpwd = md5(I('post.upwd') . $utel['utime']);
                        if ($inputpwd != $utel['upwd']) {
                            die("<script>alert('密码不正确！');</script>");
                        }
                        $uid = $utel['uid'];
                        //合并信息
                        $data = array_merge(array_filter($userinfo), array_filter($utel));
                        //设置微信信息
                        $user->where('uid=' . $uid)->save($data);
                        //删除微信登录时插入的数据
                        $user->where('uid=' . $userinfo['uid'])->delete();

                        $_SESSION['uid'] = $uid;
                        $_SESSION['username'] = $utel['username'];
                        $this->redirect('Index/index');
                    } else {
                        die("<script>alert('手机号已注册！');history.back(-1);</script>");
                    }
                } else {
                    if (I('post.openid')) {

                        //微信端注册，获取已有注册信息
                        $map['openid'] = I('post.openid');
                        $userinfo = $user->where($map)->find();
                        if (empty($userinfo)) {
                            die("<script>alert('注册失败！');</script>");
                        }
                        $uid = $userinfo['uid'];
                        $data['wxtype'] = 0;
                        //更新用户信息
                        $result = $user->where('uid=' . $uid)->save($data);
                        $data['username'] = $userinfo['username'];
                        if (!result) {
                            die("<script>alert('注册失败！');history.back(-1);</script>");
                        }
                    } else {
                        $uname = $user->where('username=' . $data['username'])->find();
                        if ($uname) {
                            die("<script>alert('用户名已注册！');history.back(-1);</script>");
                            //$this->ajaxReturn(3);
                        }
                        $data['username'] = I('post.username');
                        //PC端注册时，直接插入数据库
                        $uid = $user->add($data);
                    }
                    if ($uid) {
                        //添加对应的金额表
                        $acc['uid'] = $uid;
                        $acc['balance'] = 0;
                        $acc['frozen'] = 0;
                        $aid = M('accountinfo')->add($acc);

                        //发送注册体验券
                        $webconfig = D('webconfig')->find();
                        if ($webconfig['registsendcp']) {
                            $limittime = D('experience')->where("eid=" . $webconfig['registsendcp'])->getField('limittime');
                            if (!empty($limittime)) {
                                $cpdata['eid'] = $webconfig['registsendcp'];
                                $cpdata['exgtime'] = time();
                                $cpdata['endtime'] = time() + 24 * 3600 * (intval($limittime));
                                $cpdata['getstyle'] = 0;
                                $cpdata['getway'] = "注册赠送";
                                $cpdata['uid'] = $uid;
                                $result = M('experienceinfo')->add($cpdata);
                            }
                        }
                        //注册成功
                        $_SESSION['uid'] = $uid;
                        $_SESSION['username'] = $data['username'];
                        $this->redirect('Index/index');
                    } else {
                        die("<script>alert('注册失败！');history.back(-1);</script>");
                    }
                }
            } else {
                die("<script>alert('验证码不正确，请重新获取验证码！');history.back(-1);</script>");
            }

        } else {
            $oid = I('get.oid');
            $com = M('userinfo')->field('comname,uid')->where('uid=' . $oid)->find();
            $this->assign('com', $com);
            $this->display();
        }

    }

    //设置初始密码，密码后台可以修改。这里需要创建资金表，创建详细信息表。
    public function myreg()
    {

        $userinfo = M('userinfo');
        $openid = I('post.openid');
        $user = $userinfo->where("openid='" . $openid . "'")->find();
        $data['uid'] = $user['uid'];
        $data['utime'] = date(time());
        $data['upwd'] = md5(I('post.upwd') . $data['utime']);
        $data['wxtype'] = '0';
        if ($userinfo->save($data)) {
            $brok['uid'] = $user['uid'];
            $brok['brokerid'] = I('post.brokerid');
            M('managerinfo')->add($brok);
            $accid['uid'] = $user['uid'];
            $accid['pwd'] = I('post.upwd');
            $accid['balance'] = 0;
            $accid['frozen'] = 0;
            M('accountinfo')->add($accid);
            $this->redirect('User/login');
        } else {
            $this->error('设置失败，请联系客服人员！');
        }


    }

    //生成随机六位验证码

    public function mescontent()
    {

        $CheckCode = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);
        return $CheckCode;

    }


    //会员中心
    public function memberinfo()
    {
        $this->userlogin();
        $uid = $_SESSION['uid'];
        $result = M('accountinfo')->where('uid=' . $uid)->find();
        $suer = M('userinfo')->where('uid=' . $uid)->find();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger')) {
            $this->assign('isinweixin', 1);
        }


        $this->assign('result', $result);
        $this->assign('suer', $suer);
        $this->display();
    }

    //修改密码
    public function edituser()
    {
        $this->userlogin();
        if (IS_POST) {
            if (strlen(I('post.upwd')) <= 0 || strlen(I('post.newpwd')) <= 0 || strlen(I('post.mypwd')) <= 0) {
                $this->ajaxReturn(array(
                    'info' => '密码不能为空，请填写所有项'
                ));
            } else {

                $data['uid'] = $_SESSION['uid'];
                $myuser = M('userinfo')->where('uid=' . $data['uid'])->find();
                $user = M('userinfo')->where($data)->find();
                if ($user['upwd'] === md5(I('post.upwd') . $myuser['utime'])) {
                    $edit = M('userinfo');
                    if ($edit->create()) {
                        if (I('post.newpwd') == I('post.mypwd')) {
                            $edit->uid = $_SESSION['uid'];
                            $edit->utime = date(time());
                            $edit->upwd = md5(I('post.newpwd') . date(time()));
                            $edituser = $edit->save();
                            if ($edituser) {
//                        redirect(U('User/memberinfo'), 1, '密码修改成功...');
                                $this->ajaxReturn(array(
                                    'info' => '密码修改成功'
                                ));
                            } else {
                                $this->error('密码修改失败，请重新修改');
                            }
                        } else {
                            $this->ajaxReturn(array(
                                'info' => '两次密码不一致'
                            ));
                        }
                    }
                } else {
                    $this->error('原密码不正确，请重新输入');
                }
            }
        }
        $this->display();
    }

    //修改交易密码
    public function edituserb()
    {
        $this->userlogin();

        if (IS_POST) {
            $data['uid'] = $_SESSION['uid'];
            $myuser = M('userinfo')->where('uid=' . $data['uid'])->find();
            $user = M('accountinfo')->where('uid=' . $data['uid'])->find();
            if ($user['pwd'] == md5(I('post.pwd') . $myuser['utime']) || $user['pwd'] == '' || !$user) {
                $editb = M('accountinfo');
                if (I('post.newpwdb') !== I('post.mypwdb')) {
                    die("<script>alert('密码不一致！');history.back(-1);</script>");
                }

                $data['pwd'] = md5(I('post.newpwdb') . $myuser['utime']);
                $edituserb = $editb->where('uid=' . $data['uid'])->save($data);
                if ($edituserb) {
                    redirect(U('User/memberinfo'), 1, '密码修改成功...');
                } else {
                    $this->error('密码修改失败，请重新修改');
                }

            } else {
                $this->error('原密码不正确，请重新输入');
            }
        }
        $this->display();
    }


    //退出登录
    public function logout()
    {
        // 清楚所有session
        //更新登录token
        $result = M('logininfo')->where(array('username' => $_SESSION['husername']))->setField('loginStatus', 2);
        session(null);
        cookie("tokendesu",null,0);
        cookie("usernamedesu",null,0);
        cookie("desudesu",null,0);
        $this->redirect('User/login');
    }

    //账户提现
    public function cash()
    {
        $this->userlogin();
        $account = D('accountinfo');
        $balance = D('balance');
        $bankinfo = D('bankinfo');
        $bournal = D('bournal');
        $uid = $_SESSION['uid'];
        $username = $_SESSION['husername'];
        if (IS_POST) {
            //交易密码
            $bpwd = $account->field('pwd,balance')->where('uid=' . $uid)->find();
            $myuser = M('userinfo')->where(array('uid' => $uid))->find();
            $pwd = md5(I('post.pwd') . $myuser['utime']);
            $banknumber = I('post.banknumber');
            $bankname = I('post.bankname');
            $province = I('post.province');
            $city = I('post.city');
            $branch = I('post.branch');
            $busername = I('post.busername');
            $bpprice = I('post.bpprice');
            if ($bpwd['pwd'] == $pwd) {
                if ($banknumber != '' || isset($banknumber)) {
                    $detailed = A('Home/Detailed');
                    //提现表
                    $balances['bptype'] = '提现';
                    $balances['bptime'] = date(time());
                    $balances['bpprice'] = $bpprice;
                    $balances['uid'] = $uid;
                    $balances['isverified'] = 0;
                    //提现记录
                    $bournals['btype'] = '提现';
                    $bournals['btime'] = date(time());
                    $bournals['bprice'] = $bpprice;
                    $bournals['uid'] = $uid;
                    $bournals['username'] = $username;
                    $bournals['isverified'] = 0;
                    $bournals['bno'] = $detailed->build_order_no();
                    $bournals['balance'] = $bpwd['balance'] - $bpprice;
                    //银行卡信息，添加或修改
                    $banks['bankname'] = $bankname;
                    $banks['province'] = $province;
                    $banks['city'] = $city;
                    $banks['branch'] = $branch;
                    $banks['banknumber'] = $banknumber;
                    $banks['busername'] = $busername;
                    //插入提现记录
                    $balance_result = $balance->add($balances);
                    $bournal_result = $bournal->add($bournals);
                    //查询银行卡表所有用户id数组
                    $uidcount = $bankinfo->where('uid=' . $uid)->count();
                    //判断uid是否已经存在银行卡表内，存在插入数据，不存在修改数据
                    if ($uidcount == 1) {
                        //查询用户银行卡表的bid
                        $bid = $bankinfo->field('bid')->where('uid=' . $uid)->find();
                        $bankinfo->where('bid=' . $bid['bid'])->save($banks);
                    } else {
                        $banks['uid'] = $uid;
                        $bankinfo->add($banks);
                    }
                    if ($balance_result) {
                        $accounts['balance'] = $bpwd['balance'] - $bpprice;
                        $account->where('uid=' . $uid)->save($accounts);
                        $this->ajaxReturn();
                    } else {
                        $this->ajaxReturn("0");
                    }
                } else {
                    $this->ajaxReturn("10");
                }
            } else {
                $this->ajaxReturn("-99");
            }
        } else {
            //账户余额
            $totle = $account->field('balance')->where('uid=' . $uid)->find();
            //银行信息
            $binfo = $bankinfo->where('uid=' . $uid)->find();

            $this->assign('binfo', $binfo);
            $this->assign('totle', $totle);
            $this->display();
        }
    }

    public function txcl()
    {
        if (IS_POST) {
            $wxhao = M('bankinfo')->where(array('uid' => $_SESSION['uid']))->getField('banknumber');
            if ($wxhao == '') {
                die("<script>alert('请先完善信息！');location.href='bindinginfo.html';</script>");
            }
            $data_p = I('post.');
            $uid = $data_p['nameuid'];
            $txfee = $data_p['bpprice'];
            $ye = $data_p['ye'];
            if ($txfee > $ye || $txfee < 0 || $ye < 0) {
                die('<script>alert("您的余额不足！");history.back(-1);</script>');
            }
            $balance['bptype'] = "提现";
            $balance['bptime'] = time();
            $balance['bpprice'] = $txfee;
            $balance['uid'] = $uid;
            $rs = M('accountinfo')->where(array('uid' => $uid))->setDec('balance', $txfee);
            if ($rs) {
                M('balance')->add($balance);
                die('<script>alert("提现成功！");location.href="withdraw.html";</script>');
            } else {
                die('<script>alert("提现失败！");history.back(-1);</script>');
            }
        }
    }

    //账户充值
    public function deposit()
    {
        //|| $_SESSION['uid'] != 290 $_SESSION['uid'] == 145 || 
/*        if($_SESSION['uid'] == 290){

        }else{
            redirect('http://b.xiumi.us/board/v5/2eSMt/43530856');  //充值暂时关闭，跳转到这里
            exit();
        }*/


        $this->userlogin();
        $uid = $_SESSION['uid'];

        $balance = M('accountinfo')->where('uid=' . $uid)->getField('balance');
        $this->assign('balance', $balance);
        $this->assign('amount', 0);
        $this->assign('payway', 'weixin');
        $this->display();
    }


    //账户提现
    public function withdraw()
    {
        $this->userlogin();
        $uid = $_SESSION['uid'];
        $this->assign($_GET);

        $user = M("userinfo")->where("uid=" . $uid)->field("authenticationsstatus,ustatus")->find();
        $this->assign("authStatus", $user['authenticationsstatus']);
        $this->assign("ustatus", $user['ustatus']);

        $balance = M('accountinfo')->where('uid=' . $uid)->getField('balance');
        $this->assign('balance', $balance);

        //提现配置
        $miaoti = M("sysconfig")->where("k='tixian.common.enableMiaoti'")->getField("v");
        $weixinTips = M("sysconfig")->where("k='tixian.weixin.feeTips'")->getField("v");
        $unionpayTips = M("sysconfig")->where("k='tixian.unionpay.feeTips'")->getField("v");
        $alipayTips = M("sysconfig")->where("k='tixian.alipay.feeTips'")->getField("v");

        $alipayinfo = M("alipayinfo");
        $aliusername = $alipayinfo->field("aliusername")->where("uid={$uid}")->select()[0]['aliusername'];
        $this->assign('aliusername', $aliusername);


        $this->assign("enableMiaoti", $miaoti);
        $this->assign("weixinTips", $weixinTips);
        $this->assign("unionpayTips", $unionpayTips);
        $this->assign("alipayTips", $alipayTips);
        //TODO 测试账号
        //$this->assign("bankCardNo", '6226388000000095');

        $this->display();
    }


    //获取用户收入排行
    public function ranking()
    {
        $this->userlogin();
        $order = M('order');
        //$userinfo=M('userinfo')->select();
        $tq = C('DB_PREFIX');
        // foreach ($userinfo as $k => $v) {
        $list = $order->field('sum(' . $tq . 'order.ploss) as pric,' . $tq . 'order.uid')
            ->where('buytime >' . strtotime('today'))
            ->group($tq . 'order.uid')->having('pric > 0')
            ->order('sum(' . $tq . 'order.ploss) desc')->limit(3)->select();
        $lists = array();
        foreach ($list as $k => $v) {
            $lists[$k] = $v;
            $userinfo = M('userinfo')->field('username,nickname,portrait')->where('uid=' . $v['uid'])->find();
            $lists[$k]['name'] = $userinfo['username'];
            if ($userinfo['nickname']) {
                $lists[$k]['name'] = $userinfo['nickname'];
            }
            $namelength = strlen($lists[$k]['name']);
            if ($namelength > 0) {
                $lists[$k]['name'] = str_pad(mb_substr($lists[$k]['name'], 0, 1, 'utf-8'), $namelength, '*', STR_PAD_RIGHT);
            }
            $lists[$k]['portrait'] = $userinfo['portrait'];
        }
        $this->assign('lists', $lists);
        $this->display();
    }

    //体验卷列表
    public function experiencelist()
    {
        $this->userlogin();
        $uid = $_SESSION['uid'];
        $tq = C('DB_PREFIX');
        $endtime = date(time());

        // $list=M('experience')->join($tq.'experienceinfo on'.$tq.'experienceinfo.exid=' . $tq . 'experience.eid')->select();

        $list = M('experienceinfo')->join($tq . 'experience on ' . $tq . 'experienceinfo.eid=' . $tq . 'experience.eid')->where($tq . 'experienceinfo.uid=' . $uid . ' and ' . $endtime . ' < ' . $tq . 'experienceinfo.endtime and ' . $tq . 'experienceinfo.getstyle=0')->select();


        $this->assign('list', $list);
        $this->display();
    }


    //体验卷列表
    public function alist()
    {
        $this->userlogin();
        $uid = $_SESSION['uid'];
        $tq = C('DB_PREFIX');
        $endtime = date(time());
        $alist = M('experience')->where($endtime . ' < ' . $tq . 'experience.endtime')->select();
        $this->assign('alist', $alist);
        $this->display();
    }


    //体验卷详情页
    public function experienceid()
    {
        $tq = C('DB_PREFIX');
        $this->userlogin();
        $eid = I('eid');
        $expid = D('experienceinfo')->join($tq . 'experience on ' . $tq . 'experienceinfo.eid=' . $tq . 'experience.eid')->where('exid=' . $eid)->find();
        $this->assign('expid', $expid);
        $this->display();
    }

    public function userlogin()
    {
        //判断用户是否已经登录
        if (!isset($_SESSION['uid'])) {
            $this->redirect('User/login');
        }
        return $_SESSION['uid'];
    }

    public function img()
    {
        $uid = $_SESSION['uid'];
        $suer = M('userinfo')->where('uid=' . $uid)->find();
        if ($suer['ucode']) {
            $department = M('department')->where('code=' . $suer['ucode'])->find();
            $invite_code = $department['invite_code'];
        } else {
            $invite_code = '888888';
        }
        $hostlink = $_SERVER['HTTP_HOST'];
        $url = $hostlink . U('User/reg') . "?uid=" . I('get.uid') . '&invitecode=' . $invite_code;
        $this->assign('myurl', $url);
        $this->assign('url_encode', urlencode($url));
        $this->display();
    }

    //随机生成订单编号
    function build_order_no()
    {
        return date(time()) . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 3);
    }

    function purchase()
    {
        if (IS_POST) {
            $data_p = I('post.');
            $data['uid'] = $_SESSION['uid'];
            $data['pcnumber'] = $data_p['pcnumber'];
            $data['pcfee'] = $data_p['pcfee'];
            if ($data['pcfee'] == 0) {
                die("<script>alert('购买失败！');location.back(-1);</script>");

            }
            $data['payno'] = $data_p['payno'];
            $data['time'] = time();
            if (M('pcorderinfo')->add($data)) {
                die("<script>alert('购买成功！');location.href='pcmanagement.html';</script>");
            } else {
                die("<script>alert('购买失败！');location.back(-1);</script>");
            }
        }
        $this->display();
    }

    function pcmanagement()
    {
        $uid = $_SESSION['uid'];
        $count = M('pcorderinfo')->where('uid=' . $uid)->count();
        $pagecount = 5;
        $page = new \Think\Page($count, $pagecount);
        $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '上一页');
        $page->setConfig('next', '下一页');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%  第 ' . I('p', 1) . ' 页/共 %TOTAL_PAGE% 页 ( ' . $pagecount . ' 条/页 共 %TOTAL_ROW% 条)');
        $show = $page->show();
        $list = M('pcorderinfo')->where('uid=' . $uid)->order('time desc')->limit($page->firstRow . ',' . $page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    public function revenueid()
    {
        $jno = I('jno');
        $order = M('pcorderinfo')->where('jno=' . $jno)->find();
        $this->assign('order', $order);
        $this->display();
    }

    function queryfee()
    {
        $order = I('post.order');
        $fee = M('order')->where(array('orderno' => $order))->getField('fee');
        if ($fee) {
            $fee = $fee * 0.1;
        } else {
            $fee = 0;
        }
        $this->ajaxReturn($fee);

    }

    function lipeisb()
    {
        $lipeino = I('post.lipeino');
        $pcnumber = M('pcorderinfo')->where(array('id' => $lipeino, 'uid' => $_SESSION['uid']))->find();
        if ($pcnumber) {
            M('pcorderinfo')->where(array('id' => $lipeino, 'uid' => $_SESSION['uid']))->setField('status', '1');
            $data = 1;
        } else {
            $data = 0;
        }
        $this->ajaxReturn($data);
    }

    function loding()
    {
        $this->display();
    }

    function trems()
    {
        $this->display();
    }

    function dtrading()
    {
        $this->display();
    }

    function searchChicang()
    {
        if (!isset($_SESSION['uid'])) {
            $this->ajaxReturn(array('success' => 0, 'errcode' => 'login'));
        }
        $uid = $_SESSION['uid'];

        $orders = M('order')->where(array('uid' => $uid, 'ostaus' => 0))->order('buytime DESC')->select();
        foreach ($orders as &$order) {
            if ($order['eid'] == 2) {
                $tm = $order['selltime'] - time();
                if ($tm < 0) {
                    $tm = 0;
                }
                $order['tm'] = $tm;
            }
        };
        $prices = M('product_lastprice')->getField('pid,ask');
        $data = array(
            'success' => 1,
            'orders' => $orders,
            'prices' => $prices
        );
        $this->ajaxReturn($data);
    }

    function queryCloseState()
    {
        if (!isset($_SESSION['uid'])) {
            $this->ajaxReturn(array('success' => 0, 'errcode' => 'login'));
        }
        $uid = $_SESSION['uid'];

        $oid = I("get.oid");
        $order = M('order')->where('oid=' . $oid)->field('ostaus, selltime, eid')->find();
        if ($order['eid'] == 2) {
            $tm = $order['selltime'] - time();
            if ($tm < 0) {
                $tm = 0;
            }
            $order['tm'] = $tm;
        }
        $this->ajaxReturn(array('success' => 1, 'order' => $order));
    }

    function searchtrading()
    {
        if (!isset($_SESSION['uid'])) {
            $this->ajaxReturn(array('success' => 0, 'errcode' => 'login'));
        }

        $uid = $_SESSION['uid'];
        $date = I("get.date");

        $cond = "uid=%d and ostaus=1";
        $params = array($uid);
        if (!empty($date)) {
            $start = getStartTimeOfDay($date);
            $end = $start + 24 * 3600;
            $cond .= " and buytime>=%d and buytime<%d";
            array_push($params, $start);
            array_push($params, $end);
        }

        $pagenum = I("get.page");
        if (empty($pagenum)) {
            $pagenum = 1;
        }

        $pagesize = 10;
        $orders = M('order')->where($cond, $params)->order('buytime desc')->page($pagenum, $pagesize + 1)->select();
        $result = array();
        $result['success'] = 1;
        if (count($orders) == $pagesize + 1) {
            $result['more'] = 1;
            array_pop($orders);
        } else {
            $result['more'] = 0;
        }
        $result['data'] = $orders;
        $this->ajaxReturn($result);
    }

    function jyxqcx()
    {
        if (IS_POST) {
            $oid = I('post.oid');
            $orderxx = M('order')->where(array('oid' => $oid))->find();
            $orderxx['buytime'] = date('Y-m-d H:i:s', $orderxx['buytime']);
            $orderxx['selltime'] = date('Y-m-d H:i:s', $orderxx['selltime']);
            if ($orderxx) {
                $this->ajaxReturn($orderxx);
            }
            $this->display();
        }
    }

    public function bindinginfo()
    {
        $wxhao = M('bankinfo')->where(array('uid' => $_SESSION['uid']))->getField('banknumber');
        if ($wxhao == '') {
            if (IS_POST) {
                $data_p = I('post.');
                //var_dump($data_p);
                $utel = $data_p['phone'];
                $addphone = M('userinfo')->where(array('uid' => $_SESSION['uid']))->setField('utel', $utel);
                $data['busername'] = $data_p['name'];
                $data['banknumber'] = $data_p['wxhao'];
                $data['uid'] = $_SESSION['uid'];
                $addwx = M('bankinfo')->add($data);
                if ($addphone && $addwx) {
                    die("<script>alert('绑定成功！');location.href='withdraw.html?type=1'</script>");
                } else {
                    die("<script>alert('绑定失败！');history.back(-1);</script>");
                }
            }
            $this->display();

        }
    }

    public function authentication()
    {
        $infoM = M('authenticationinfo');
        if (IS_POST) {
            $userid = $_SESSION['uid'];
            $uid = I('post.uid', "");
            $data['useruid'] = $userid;
            $data['realname'] = I('post.realname');
            $data['IDnumber'] = I('post.IDnumber');
            $data['mobile'] = I('post.mobile');
            $data['IDcardfront'] = I('post.IDcardfrontH');
            $data['IDcardback'] = I('post.IDcardbackH');
            $data['IDcardhandheld'] = I('post.IDcardhandheldH');
            $data['applytime'] = time();
            $data['status'] = '1';
            $data['wxnumber'] = I('post.wxnumber');

            if ($uid != "") {
                $mm = $infoM->where('uid=' . $uid)->save($data);
            } else {
                $mm = $infoM->add($data);
            }
            if ($mm) {
                //实名认证状态，改变状态为1,（认证中）
                $mydata['authenticationsstatus'] = 1;
                M('userinfo')->where("uid=".$userid)->save($mydata);
                //$this->success('提交成功,等待审核', U('User/memberinfo'));
                echo "提交成功,等待审核";
            } else {
                // $this->error('提交失败');
                echo "提交失败";
            }
        } else {
            $this->userlogin();
            $id = I("get.id", "");
            if ($id != "") {
                $info = $infoM->find($id);
                $this->assign("info", $info);
            }
            $this->display();
        }
    }

    public function UplodePic()
    {
        header("Content-type: text/html; charset=utf-8");
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 3145728;// 设置附件上传大小
        $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath = 'Public/Uploads/'; // 设置附件上传根目录
        $info = $upload->upload();

        if (!$info) {// 上传错误提示错误信息
            echo "<script>parent.callback('" . $upload->getError() . "',false)</script>";
            return;
        } else {// 上传成功 获取上传文件信息
            foreach ($info as $file) {
                $path = $file['savepath'] . $file['savename'];
            }
            echo "<script>parent.callback('$path',true)</script>";
            return;
        }
    }

    public function authenticationinfo()
    {
        $this->userlogin();
        $authenticationinfo = M('authenticationinfo')->where('useruid=' . $_SESSION['uid'])->find();
        if ($authenticationinfo > 0) {
            $idcard = $authenticationinfo['IDnumber'];
            $authenticationinfo['IDnumber'] = strlen($idcard) == 15 ? substr_replace($idcard, "*******", 8, 7) : (strlen($idcard) == 18 ? substr_replace($idcard, "********", 10, 8) : "********");
            $mobile = $authenticationinfo['mobile'];
            $authenticationinfo['mobile'] = strlen($mobile) == 11 ? substr_replace($mobile, "****", 3, 4) : '****';
        }
        $this->assign('info', $authenticationinfo);
        $this->display();
    }

    //短信验证
    public function smsverify11()
    {
        $tel = $_REQUEST['tel'];
        sentVerifySms($tel);
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger')) {
            //判断该电话是否是已经注册过
            $map['utel'] = $tel;
            $map['ustatus'] = array('neq', 0);
            $user = D('userinfo')->where($map)->find();
            $this->ajaxReturn('发送成功!');
        } else {
            $this->ajaxReturn('发送成功');
        }
    }

    // 判断是否已经实名认证
    public function checkUserAuthentication()
    {
        $this->userlogin();
        $user = D('userinfo');
        $where = array();
        $where['uid'] = $_SESSION['uid'];
        $result = $user->where($where)->field('authenticationsstatus')->find();
        if ($result['authenticationsstatus'] == "0") {
            $this->ajaxReturn(false);
        } else {
            $this->ajaxReturn(true);
        }
    }

    // 判断验证码
    public function checkVerificationCode()
    {
        $tel = $_REQUEST['tel'];
        $code = $_REQUEST['code'];

        $telcode = D('telcode');
        $map['tel'] = array('EQ', $tel);

        list($usec, $sec) = explode(" ", strtotime());
        $time = strtotime('-60 minute', time());
        $map['time'] = array('EGT', $time);

        $result = $telcode->field('')->where($map)->order('time desc')->limit(1)->select();
        if ($result[0]['code'] == $code) {
            $this->ajaxReturn(true);
        } else {
            $this->ajaxReturn(false);
        }
    }

    public function confirmpassword()
    {
        $password = I('post.password');
        $user = M('userinfo');
        $result = $user->where('uid=' . $_SESSION['uid'])->find();
        if (md5($password . $result['utime']) == $result['upwd']) {
            $this->ajaxReturn(true);
        } else {
            $this->ajaxReturn(false);
        }
    }

    public function dxsetting()
    {
        $this->userlogin();
        $uid = $_SESSION['uid'];
        $diancha = I('post.diancha');
        D('userinfo')->where('uid=' . $uid)->setField('diancha', $diancha);
        $this->ajaxReturn(true);
    }

    //找回密码页面
    public function findpassword()
    {
        if (IS_POST) {
            $utel = I('post.utel');
            $username = I('post.username');

            $user = M('userinfo');
            $info = $user->where("username='" . $username . "'")->find();
            if ($info == null) {
                $this->error("用户名不存在。", U('User/findpassword', array('utel' => $utel, 'username' => $username)));
            }
            if ($info['utel'] != $utel) {
                $this->error("用户名与注册手机号不一致。", U('User/findpassword', array('utel' => $utel, 'username' => $username)));
            }
            $this->redirect('User/setnewpassword', array('uid' => $info['uid']));
        } else {
            $utel = I('get.utel', "");
            $username = I('get.username', "");
            $this->assign("utel", $utel);
            $this->assign("username", $username);
            $this->display();
        }
    }

    function setnewpassword()
    {
        $user = M('userinfo');

        if (IS_POST) {
            $uid = I('post.uid');
            $card = I('post.code');
            $password = I('post.upwd');

            $info = $user->find($uid);
            $telcode = M('telcode');

            $map['tel'] = array('EQ', $info['utel']);

            list($usec, $sec) = explode(" ", strtotime());
            $time = strtotime('-60 minute', time());
            $map['time'] = array('EGT', $time);

            $result = $telcode->where($map)->order('time desc')->limit(1)->select();
            if ($result[0]['code'] != $card) {
                $this->error("手机验证码错误！", U('User/setnewpassword', array('uid' => $uid)));
            }
            $data['upwd'] = md5($password . $info['utime']);
            if ($user->where('uid=' . $uid)->save($data)) {
                $this->success("成功重置密码，请重新登录。", U('User/login'));
            } else {
                $this->error("操作失败，请联系客服人员！", U('User/login'));
            }
        } else {
            $uid = I('get.uid');
            $info = $user->find($uid);
            $this->assign("info", $info);
            $this->display();
        }
    }

    function showdetailinfo()
    {
        $this->userlogin();
        $uid = $_SESSION['uid'];
        $result = M('accountinfo')->where('uid=' . $uid)->find();
        $where['uid'] = $uid;
        $where['orderno'] = array('like', date('Ymd') . '%');

        $ploss = M('order')->where($where)->sum('ploss'); //select
        $buy_count = M('order')->where($where)->count();

        $where['closetype'] = array('EXP', 'IS NOT NULL');
        $tmp_buy_count = M('order')->where($where)->count();

        $where['commission'] = array('neq', '0.00');
        $w_count = M('order')->where($where)->count();
        $tmp_odds_value = $w_count / $tmp_buy_count;
//	$tmp_odds_value = $tmp_buy_count / $w_count;
//      var_dump($tmp_odds_value);
//	$w_odds = $tmp_odds_value;
//        $w_odds *= 100;

        if (is_nan($tmp_odds_value)) {
            $tmp_odds_value = 1;
        }

        $w_odds = $tmp_odds_value *= 100;
        $w_odds = number_format($w_odds, 2);
//        $w_odds = $w_count / $tmp_buy_count;
//        $w_odds *= 100;

        $suer = M('userinfo')->where('uid=' . $uid)->find();
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($user_agent, 'MicroMessenger')) {
            $this->assign('isinweixin', 1);
        }
        $balance = empty($result['balance']) ? '0.00' : $result['balance'];

        $this->assign('result', $result);
        $can_use = $result['balance'] - $result['frozen'];
        $this->assign('ploss', $ploss);
        $this->assign('w_odds', $w_odds);
        $this->assign('balance', $balance);

        $this->assign('buy_count', $buy_count);
        $this->assign('can_use', sprintf("%.2f", $can_use));
//        $this->assign('can_use', $can_use);
        $this->assign('suer', $suer);
        $this->display();
    }

    // 2017-3-16 支付宝绑定
    function alipayinfo()
    {
        $this->userlogin();

        $uid = $_SESSION['uid'];
        $authenticationinfo = M("authenticationinfo");
        $alipayinfo = M("alipayinfo");

        if (IS_POST) {
            $data = array();
            $re = false;

            $authinfo = $authenticationinfo->field("status,mobile")->where("useruid={$uid}")->select()[0];

            $aliusername = I('aliusername');
            $code = I('code');

            if (checkCode($authinfo['mobile'], $code) == false) {
                $this->ajaxReturn("验证码错误");
            } else {
                $data['aliusername'] = $aliusername;
                $isaid = $alipayinfo->where("uid={$uid}")->count();

                if ($authinfo['status'] == 2 && strlen($isaid) > 0 && $isaid == 0) {
                    $data['uid'] = $uid;
                    $re = $alipayinfo->add($data);
                    if ($re > 0) {
                        $this->ajaxReturn("绑定成功");
                    } else {
                        $this->ajaxReturn("绑定失败, 请先实名认证");
                    }
                } elseif ($authinfo['status'] == 2 && strlen($isaid) > 0 && $isaid == 1) {
                    $re = $alipayinfo->where("uid={$uid}")->save($data);
                    if ($re > 0 || $re === 0) {
                        $this->ajaxReturn("修改绑定成功");
                    } else {
                        $this->ajaxReturn("修改绑定失败");
                    }
                }
            }
        } else {
            $aliusername = $alipayinfo->field("aliusername")->where("uid={$uid}")->select()[0]['aliusername'];
        }

        $this->assign('aliusername', $aliusername);
        $this->display();
    }

    // 2017-3-17 发送验证码
    function sendcode()
    {
        if (IS_POST) {
            $uid = $_SESSION['uid'];
            $authenticationinfo = M("authenticationinfo");
            $authinfo = $authenticationinfo->field("status,mobile")->where("useruid={$uid}")->select()[0];
            if ($authinfo['status'] == 2) {
                post("http://www.euamote.me/index.php/Home/User/smsverify11.html", array("tel" => $authinfo['mobile']));
                $this->ajaxReturn("已发送");
            } else {
                $this->ajaxReturn("请先做实名认证");
            }
        }
    }

    // 验证码校验
    function checkCode($phone, $code)
    {
        $telcode = D('telcode')->where('tel=' . $phone)->order('time desc')->find();
        if ($telcode['code'] == $code && time() - $telcode['time'] <= 300) {
            return true;
        } else {
            return false;
        }
    }
}
