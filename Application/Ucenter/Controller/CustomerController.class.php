<?php
// 本类由系统自动生成，仅供测试用途
namespace Ucenter\Controller;
use Ucenter\Controller\CommonController;
class CustomerController extends CommonController{
     //会员列表
	public function customerlist()
    {

    	$oid=$_SESSION['cuid'];

    	$tq=C('DB_PREFIX');
    	$userinfo=M('userinfo');
        
    	$deptCond = array('code'=>array('LIKE',$_SESSION['deptinfo']['code'].'%'));
    	$department = M('department')->field('code,name')->where($deptCond)->select();
    	
        $map = array();//查询条件
        
        $map['otype'] = 0;
        $map['ustatus'] = array('neq',0);
        $username = I('post.username');
        if(!empty($username)){
            $map['username'] = array('LIKE','%'.I('post.username').'%');
        }
        $utel = I('post.utel');
        if(!empty($utel)){
            $map['utel'] = $utel;
        }
        
        $username = I('post.dcode');
        if(!empty($username)){
            $map['ucode'] = array('LIKE',I('post.dcode')."%");
        }else{
            $map['ucode'] = array('LIKE',$_SESSION['deptinfo']['code']."%");
        }
        $count =$userinfo->where($map)->count();
        $psize = I('psize');
        if (strlen($psize) <= 0) {
            $psize = 10;
        }
        $this->assign('psize',$psize);
        $page = new \Think\Page($count , $psize);
        foreach($map as $key=>$value){
            $page->parameter[$key] = urlencode($value);//此处的row是数组，为了传递查询条件
        }
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $show = $page->show();
    	$list=$userinfo->Distinct(true)
            ->join($tq.'department on '.$tq.'department.id = '.$tq.'userinfo.deptid',"left")
            ->join($tq.'authenticationinfo on '.$tq.'userinfo.uid='.$tq.'authenticationinfo.useruid','left')
            ->where($map)->limit($page->firstRow.','.$page->listRows)->select();
    	$sum=array();
    	$accoun=array();
    	foreach ($list as $k => $v) {
    		$sum[]=M('order')->where('uid='.$v['uid'])->count();
    		$accoun[]=M('accountinfo')->field('balance')->where('uid='.$v['uid'])->find();
    	}
    	foreach ($list as $key => $value) {
    		foreach ($sum as $k => $v) {
    			if($key==$k){
    				$list[$key]['sum'] = $sum[$k];	
    			}
    		}
    		foreach ($accoun as $ky => $va) {
    			if($key==$ky){
    				$list[$key]['balance'] = $accoun[$ky]['balance'];
    			}
    		}
    	}
	    $this->assign('ulist',$list);
        $this->assign('page',$show);
        $this->assign('deptlist',$department);
		$this->display();
    }
	public function customeradd()
    {
    	header("Content-type: text/html; charset=utf-8");
        //获取登录代理商的id
        $myuid=$_SESSION['cuid'];
    	if (IS_POST) {
	    	$uid=I('post.uid');
	    	$mid=I('post.mid');
	    	$userinfo=D('userinfo');
	    	$managerinfo=M('managerinfo');
            // 自动验证 创建数据集
            if ($userinfo->create()) {
               //验证身份证正确性
               $broker= A('Ucenter/Account');
               $broker->checkIdCard(I('post.brokerid'));
               if($uid!=''&&$mid!=''){
                    //修改
                    $data['uid']=$uid;
                    $data['utel']=I('post.utel');
                    $data['address']=I('post.address');
                    $userinfo->save($data);
                    $mana['mid']=$mid;
                    $mana['brokerid']=I('post.brokerid');
                    $managerinfo->save($mana); 
                    redirect(U('Customer/customerlist'),1, '修改成功...');
                }else{
                    //添加
                    $user=$userinfo->field('username')->where('uid='.$myuid)->find();
                    $data['managername']=$user['username'];
                    $data['username']=I('post.username');
                    $data['utel']=I('post.utel');
                    $data['utime']=date(time());
                    $data['upwd']=md5(I('post.username').date(time()));
                    $data['address']=I('post.address');
                    $data['otype']=0;
                    $data['ustatus'] = 1;
                    //设置部门信息及客户关系
                    $data['deptid'] = $_SESSION['deptinfo']['deptid'];
                    $data['ucode'] = $_SESSION['deptinfo']['code'];
                    $company = M('companyinfo')->where('deptid='.$data['deptid'])->find();
                    $data['companyid'] = $company['cid'];
                    
                    $uid = $userinfo->add($data);
                    if ($uid) {
                          $brok['uid']=$uid;
                          $brok['brokerid']=I('post.brokerid');
                          $brok['mname'] = I('post.mname');
                          $managerinfo->add($brok);
                          $accid['uid']=$uid;
                          $accid['balance']=0;
                          $accid['frozen'] = 0;
                          M('accountinfo')->add($accid);
                    }
                    redirect(U('Customer/customerlist'),1, '新增用户成功...');
                }
            }else{
                $this->error($userinfo->getError());
            }
    	}
    	//判断跳转到修改页面或者新增页面
		$uid=I('get.uid');
    	if($uid){
    		$user=M('userinfo')->where('uid='.$uid)->find();
    		$usermsg=M('managerinfo')->where('uid='.$uid)->find();
    		$user['brokerid']=$usermsg['brokerid'];
    		$user['mid']=$usermsg['mid'];
    		$this->assign('user',$user);
    	}	
    	$this->display();
    }

	public function customerdetail()
    {
    	$uid=I('get.uid');
    	//普通会员信息
    	$user=M('userinfo')->where('uid='.$uid)->find();
    	$usermsg=M('managerinfo')->where('uid='.$uid)->find();
    	$account=M('accountinfo')->where('uid='.$uid)->find();
    	//普通会员上线信息
    	$ouid=M('department')->where('id='.$user['deptid'])->find();

    	$user['brokerid']=$usermsg['brokerid'];
    	$user['mname']=$usermsg['mname'];
    	$user['balance']=$account['balance'];
    	$user['oname']=$ouid['name'];

    	$this->assign('user',$user);
		$this->display();
    }
	
	
}