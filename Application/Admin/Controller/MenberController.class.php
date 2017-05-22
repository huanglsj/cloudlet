<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Log;

require_once APP_PATH .'/Common/Enum/PayWayEnum.php';
require_once APP_PATH .'/Common/Enum/PayCheckStateEnum.php';
require_once APP_PATH .'/Common/Enum/PayStateEnum.php';

class MenberController extends Controller {
	
	private function checklogin()
	{
		return A('Admin/User')->checklogin();
	}
	
	//会员列表
    public function mlist()
    {
    	//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
    	
    	$tq=C('DB_PREFIX');
    	$user = M('companyinfo');
		$map = array();
		if(I('post.comname') != ''){
		    $map['comname'] = I('post.comname');
		}
		if(I('post.code') != ''){
		    $map['code'] = I('post.code');
		}
		if(I('post.managername') != ''){
		    $map['managername'] = I('post.managername');
		}
		if(I('post.managerphone') != ''){
		    $map['managerphone'] = I('post.managerphone');
		}
		$map['isdelete'] = array('neq','Y');
		$map['type'] = 2;
    	//分页
    	$count = $user->where($map)->count();
    	$pagecount = 20;
    	$page = new \Think\Page($count , $pagecount);
    	foreach ($map as $key=>$value){
    	    $page->parameter[$key] = urlencode($value);//此处的row是数组，为了传递查询条件
    	}
    	$page->setConfig('first','首页');
    	$page->setConfig('prev','&#8249;');
    	$page->setConfig('next','&#8250;');
    	$page->setConfig('last','尾页');
    	$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
    	$show = $page->show();
    	//查询用户和账户信息
    	$ulist = $user->where($map)
            ->field("*,'invcode' as invcode")
            ->order("cid desc")->limit($page->firstRow.','.$page->listRows)->select();

        foreach ($ulist as $k => $v) {
            $ulist[$k]['invcode'] = M('department')->field("invite_code")->where("id={$ulist[$k]['deptid']}")->select()[0]['invite_code'];
        }

    	$this->assign('page',$show);
    	$this->assign('ulist',$ulist);
		$this->display();
	}
	public function index()
	{
		$this->display('mlist');		
	}
	//读取关注微信的用户信息。
	public function wxlist(){
		//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();

		$userinfo = M('userinfo');
        //分页
		$count = $userinfo->where('usertype=1')->count();
        $pagecount = 10;
        $page = new \Think\Page($count , $pagecount);
//         $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first','首页');
        $page->setConfig('prev','&#8249;');
        $page->setConfig('next','&#8250;');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
        $show = $page->show();
		//查询用户和账户信息
		$ulist = $userinfo->where('usertype=1')->order('uid desc')->limit($page->firstRow.','.$page->listRows)->select();
		
		$this->assign('page',$show);
    	$this->assign('ulist',$ulist);


		$this->display();
	} 
	/*
	*获取最新的所有关注用户的信息，添加到用户列表，注意usertype，wxtype，2个参数。
	*/
	public function instruser(){
		//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
		$wxinfo=M('wechat')->find();
	    $appid = $wxinfo['appid'];  
	    $appsecret = $wxinfo['appsecret'];  

	    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";  
	    $ch = curl_init();  
	    curl_setopt($ch, CURLOPT_URL, $url);  
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);  
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);  
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
	    $output =curl_exec($ch);  
	    curl_close($ch);  
	    $jsoninfo = json_decode($output, true);  
	    $access_token = $jsoninfo["access_token"];     
	    $url = "https://api.weixin.qq.com/cgi-bin/user/get?access_token=$access_token";  
	    $result =$this->https_request($url);  
	    $jsoninfo = json_decode($result);  // 默认false，为Object，若是True，为Array  
	       
	    $data = $jsoninfo -> data;    
	    header("Content-type: text/html; charset=utf-8");
	    $userlist=array();
	    foreach($data -> openid as $x=>$x_value) {   
	        $url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".$access_token."&openid=".$x_value;
	        $result = $this->https_request($url); 
	        $php_json =(Array)json_decode($result);   //再把json格式的数据转换成php数组
	        $php_json['username']= substr($php_json['openid'], -5).time();//登录名
            $php_json['address']=$php_json['country'].$php_json['province'].$php_json['city'];//地址
            $php_json['portrait']=$php_json['headimgurl'];//头像
            $php_json['utime']=$php_json['subscribe_time'];//时间
            $php_json['openid']= $php_json['openid'];
            $php_json['nickname']=$php_json['nickname'];
	        $php_json['usertype']='1';
	        $php_json['wxtype']='1';
	        $userlist[]=$php_json;
	    }
        //重组数组
        $mydata=array();
        $userinfo = M('userinfo');
        foreach ($userlist as $key => $value) {
        	$mydata[$key]['username']=$value['username'];
        	$mydata[$key]['address']=$value['address'];
        	$mydata[$key]['portrait']=$value['portrait'];
        	$mydata[$key]['openid']=$value['openid'];
        	$mydata[$key]['utime']=$value['utime'];
        	$mydata[$key]['nickname']=$value['nickname'];
        	$mydata[$key]['usertype']=1;
        	$mydata[$key]['wxtype']=1;
        	$usersum=$userinfo->where("openid='".$value['openid']."'")->count();
        	if ($usersum<1) {
        		$userinfo->add($mydata[$key]);
        	}

        }
        $this->redirect('Menber/wxlist');
	}
	/**
	 * 添加会员
	 * */
	public function madd(){
		//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
		$user = D('systemuser');
		$member = D('companyinfo');
		$departmentinfo = D('department');
		$time = time();
		if(IS_POST){
		    $data=$member->create();
			if($data){
			    //创建相应的部门信息
			    $deptid = $departmentinfo->addDepartment($data['comname'],2);
			    if(!$deptid){
			        $this->error($departmentinfo->getError());
			    }
			    $deptinfo = $departmentinfo->where('id='.$deptid)->find();
			    //创建相应的系统用户信息
			    $username = $deptinfo['code'].'admin';
			    $user->username = $username;
			    $user->tel = $data['managerphone'];
			    $user->type = 2;
			    $user->displayname = $data['comname'];
			    $user->createuserid = $_SESSION['userid'];
			    $user->isadmin = 1;
			    $user->deptid = $deptid;
			    $result = $user->addSystemuser();
			    if(!$result['result']){
			        $this->error($result['msg']);
			    }
			    $data['ccode'] = $deptinfo['code'];
			    $data['type'] = 2;
			    $data['deptid'] = $deptid;
			    if(I('post.termtime')){
			        $data['termtime'] = strtotime($data['termtime']);
			    }
			    $data['equity'] = 0;//权益会在流水里加上
			    $data['isdelete'] = 'N';
			    $result = $member->add($data);
			    if($result){
    			    //插入权益流水
			        $result = $member->updateEquity($result,$data['cashdeposit'],'5',$_SESSION['userid']);
    			    if($result['result']){
    			        $this->success('添加成功',U('Menber/mlist'));
    			    }else{
    			        $this->error($result['msg']);
    			    }
			    }else{
			        $this->error($member->getError());
			    }
				
			}else{
				$this->error($member->getError());
			}
		}else{
			$this->display();	
		}
	}
	
	public function mupdate(){
	   $user = A('Admin/User');
        $user->checklogin();
        $member = D('companyinfo');
        $time = time();
        if (IS_POST) {
            $oldmemberinfo = $member->where('cid='.I('post.cid'))->find();
            $data = $member->create();
            $data['control']=I('post.control');
            if ($data) {
                // 创建相应的系统用户信息
                if (I('post.termtime')) {
                    $data['termtime'] = strtotime($data['termtime']);
                }
                $result = $member->save($data);
                //修改用户表的公司名称等信息
                $result1 = M('department')->where('id='.$oldmemberinfo['deptid'])->setField(array('name'=>$data['comname']));
                $result2 = M('systemuser')->where('deptid='.$oldmemberinfo['deptid'].' and isadmin=1')->
                setField(array('displayname'=>$data['comname'],'tel'=>$data['managerphone'],'control'=>$data['control']));
                if ($result) {
                    if($oldmemberinfo['cashdeposit'] <> $data['cashdeposit']){
                        //更新会员权益
                        $result = $member->updateEquity($data['cid'],$data['cashdeposit'] - $oldmemberinfo['cashdeposit'],'5',$_SESSION['userid']);
                        if($result['result']){
                            $this->success('修改成功',U('Menber/mlist'));
                        }else{
                            $this->error($result['msg']);
                        }
                    }                            $this->success('修改成功',U('Menber/mlist'));
                } else {
                    if($result == 0 && $result1 == 0 && $result2 == 0){
                        $this->error('什么也没改!');
                    }else{
                        if($member->getError()){
                            $this->error($member->getError());
                        }else{
                            $this->error('修改失败！');
                        }
                    }
                }
            } else {
                $this->error($member->getError());
            }
        } else {
            $memberinfo = $member->where('cid='.I('cid'))->find();
            $this->assign('memeber',$memberinfo);
            $this->display();
        }
	}
	
	public function updateequity(){
	    $user = A('Admin/User');
	    $user->checklogin();
	    $member = D('companyinfo');
	    if (IS_POST) {
	        $updateequity = I('post.updateequity');
	        $cid = I('post.cid');
	        if(is_numeric($updateequity)){
	            //添加流水
	            $result = $member->updateEquity($cid,$updateequity,'5',$_SESSION['userid']);
	            if($result['result']){
	                $this->success('修改成功',U('Menber/mlist'));
	            }else{
	                $this->error($result['msg']);
	            }
	        }else{
	            //请输入正确的增加权益值
	            $this->error('请输入正确的增加权益值!');
	        }
	    }else{
	        $memberinfo = $member->where('cid='.I('cid'))->find();
	        $this->assign('memeber',$memberinfo);
	        $this->display();
	    }
	}
	public function resetpw(){
	    $user = A('Admin/User');
	    $user->checklogin();
	    
	    $cid = I('post.cid');
	    $user = D('systemuser');
	    $companyinfo = M('companyinfo')->where('cid='.$cid)->find();
	    $result = $user->restpassword($companyinfo['ccode'].'admin',$_SESSION['userid']);
	    $this->ajaxReturn($result);
	}
	
	public function userdel(){
	    $user = A('Admin/User');
	    $user->checklogin();
	    $cid = I('get.cid');
	    $member = D('companyinfo');
	    $memberinfo = $member->where('cid='.$cid)->find();
	    $result = $member->delCompany($memberinfo['ccode']);
	    if($result!==FALSE){
	        $this->success("成功删除！",U("Menber/mlist"));
	    }else{
	        $this->error('删除失败！');
	    }
	}
	//微信基本配置
	public function wxinfo(){
		$wechat=D('wechat');
		if (IS_POST) {
			header("Content-type: text/html; charset=utf-8");
				if(!$wechat->create()){
					  // 如果创建失败 表示验证没有通过 输出错误提示信息
                    $this->error($wechat->getError());
				}else{
					//添加
					if (I('post.wcid')=='') {
						$data=$wechat->create();
					    $wechat->add($data);
					}else{
					//修改
						$data['wcid']=I('post.wcid');
						$data=$wechat->create();
					    $wechat->save($data);		
					}	
					
				};

		}
		$wx=$wechat->find();
		$this->assign('wx',$wx);
		$this->display();
	}
    //出来微信htpp地址方法
	function https_request($url)  
    {         
        $curl = curl_init();         
        curl_setopt($curl, CURLOPT_URL, $url);         
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);         
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);         
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);         
        $data = curl_exec($curl);         
        if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}         
        curl_close($curl);         
        return $data;  
    }

    function pcorder(){
        $count =M('pcorderinfo')->where('status=0')->count();
        $pagecount = 20;
        $page = new \Think\Page($count , $pagecount);
        $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%  第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show = $page->show();
        $list = M('pcorderinfo')->where('status=0')->order('time desc' )->limit($page->firstRow.','.$page->listRows)->select();   
        
        $this->assign('page',$show);
        for ($i=0; $i <count($list) ; $i++) { 
        	$list[$i]['username']=M('userinfo')->where(array('uid'=>$list[$i]['uid']))->getField('username');
        }
        $this->assign('list',$list);
    	$this->display();
    }


    function solve(){
    	$uid=$_SESSION['uid'];
        $count =M('pcorderinfo')->where('status=2 or status=3')->count();
        $pagecount = 20;
        $page = new \Think\Page($count , $pagecount);
        $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%  第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show = $page->show();
        $list = M('pcorderinfo')->where('status=2 or status=3')->order('time desc' )->limit($page->firstRow.','.$page->listRows)->select();   
        for ($i=0; $i <count($list) ; $i++) { 
        	$list[$i]['username']=M('userinfo')->where(array('uid'=>$list[$i]['uid']))->getField('username');
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
    	$this->display();
    }

    function nosolve(){
    	$uid=$_SESSION['uid'];
        $count =M('pcorderinfo')->where('status=1')->count();
        $pagecount = 20;
        $page = new \Think\Page($count , $pagecount);
        $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%  第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show = $page->show();
        $list = M('pcorderinfo')->where('status=1')->order('time desc' )->limit($page->firstRow.','.$page->listRows)->select();   
        for ($i=0; $i <count($list) ; $i++) { 
        	$list[$i]['username']=M('userinfo')->where(array('uid'=>$list[$i]['uid']))->getField('username');
        }
        $this->assign('list',$list);
        $this->assign('page',$show);
    	$this->display();
    }

    function agreen(){
    	$data_p=I('post.');
    	if ($data_p['status']==2) {
	    	$order=M('pcorderinfo')->where(array('id'=>$data_p['id'],'uid'=>$data_p['uid']))->find();
	    	if ($order) {
	    		M('accountinfo')->where(array('uid'=>$data_p['uid']))->setInc('balance',$data_p['pcfee']);
	    		$lipei['bptype']='理赔';
	    		$lipei['bptime']=time();
	    		$lipei['bpprice']=$data_p['pcfee'];
	    		$lipei['remarks']='理赔同意';
	    		$lipei['uid']=$data_p['uid'];
	    		$lipei['isverified']=1;
	    		$lipei['cltime']=time();
	    		M('balance')->add($lipei);
	    		M('pcorderinfo')->where(array('id'=>$data_p['id'],'uid'=>$data_p['uid']))->setField('status',2);
	    		$data=1;
	    	}else{
	    		$data=0;
	    	}
    	}elseif($data_p['status']==3){
    		$order=M('pcorderinfo')->where(array('id'=>$data_p['id'],'uid'=>$data_p['uid']))->find();
	    	if ($order) {
	    		M('pcorderinfo')->where(array('id'=>$data_p['id'],'uid'=>$data_p['uid']))->setField('status',3);
	    		$data=1;
	    	}else{
	    		$data=0;
	    	}
    	}

    	$this->ajaxReturn($data);
    }   
	
    private function getStartTimeOfDay($date) {
    	return mktime(0,0,0, substr($date, 5, 2), substr($date,8, 2), substr($date, 0, 4));
    }
    

    public function recharge(){
    	$this->checklogin();
    
    	$opType = I("get.optype");
    	$opDate = I("get.opdate");
    	$deptcode = I("get.deptcode");
    	$checkState = I("get.checkstate");
    	$checkUser = I("get.checkuser");
    	$checkDate = I("get.checkdate");
    	$state = I("get.state");
    	$payway = I("get.payway");
    	foreach($_GET as $name => $value) {
    		$this->assign($name, $value);
    	}
    
    	$params = array();
    	$condition = " from wp_pay pay inner join wp_department dept on pay.uid=dept.id inner join wp_companyinfo comp on comp.deptid=dept.id"
		            ." left join wp_systemuser u2 on pay.checkuserid=u2.id where utype=2";
    	if ($opType!= "" && $opType!=0) {
    		array_push($params, $opType);
    		$condition .= " and pay.type=%d";
    	}
    	if ($opDate != "") {
    		array_push($params, str_replace("-", "", $opDate));
    		$condition .= " and pay.opDate='%s'";
    	}
    	if ($deptcode != "") {
    		array_push($params, $deptcode);
    		$condition .= " and dept.code like '%%%s%%'";
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
    		$end = $start + (3600*24);
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
    		}
    		else if ($payway == "alipay") {
    			array_push($params, \PayWayEnum::ALIPAY);
    			array_push($params, \PayWayEnum::ALIPAY_QRCODE);
    			$condition .= " and (pay.payway=%d or pay.payway=%d)";
    		}
    		else if ($payway == "unionpay") {
    			array_push($params, \PayWayEnum::UNIONPAY);
    			$condition .= " and pay.payway=%d";
    		}
    	}
    	$curPage = I("p");
    	if ($curPage == "") {
    		$curPage = 1;
    	}
    	$dataSql = "select pay.id, pay.uid, dept.code as deptcode, pay.begintime, pay.type as optype, pay.state, pay.payway, pay.utype,"
		          ."pay.amount, pay.commissionfee fee, pay.checkstate, pay.checkuserid, comp.cid, u2.username checkuser, pay.checktime, 'desu' as realname";
		$dataSql .= $condition . " order by id desc limit ". ($curPage-1)*20 . ",20";

    	$model = M();
    	$totalSql = "select pay.amount" . $condition;
        $totals = $model->query($totalSql, $params);
        $totalAmount = 0;
        if (empty($totals)) {
            $totalCount = 0;
        }
        else {
            $totalCount = count($totals);
            foreach($totals as $r) {
                $totalAmount += $r['amount'];
            }
        }

        $this->assign("totalcount", $totalCount);
        $this->assign("totalamount", $totalAmount);
    
    	$rechargelist = $model->query($dataSql, $params);

        foreach ($rechargelist as $k => $value) {
            $rechargelist[$k]['realname'] = M("authenticationinfo")->field('realname')
            ->where("useruid={$rechargelist['deptcode']}")->find()['realname'];
        }

    	$this->assign("rechargelist", $rechargelist);
    
    	$page = new \Think\Page($totalCount, 20);
    	//$page->parameter = $row;
    	$page->setConfig('first','首页');
    	$page->setConfig('prev','&#8249;');
    	$page->setConfig('next','&#8250;');
    	$page->setConfig('last','尾页');
    	$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
    	$show = $page->show();
    	$this->assign('page',$show);

    	$this->display();
    }
    
    public function rechargedetail() {
    	$this->checklogin();
    	$id = I("get.id");
    
    	$sql = "select pay.id, pay.uid, dept.code as deptcode, pay.begintime, pay.type as optype, pay.state, pay.payway, pay.amount,"
    			." pay.commissionfee fee, pay.checkstate, pay.checkuserid, u2.username checkuser, pay.checktime, pay.checkFailReason, pay.orderNo, pay.outOrderNo, comp.cid as compid, comp.equity, comp.comname, pay.errMsg"
    			." from wp_pay pay inner join wp_department dept on pay.uid=dept.id left join wp_systemuser u2 on pay.checkuserid=u2.id inner join wp_companyinfo comp on comp.deptid=dept.id where pay.id=%d limit 0,1";
    	$model = M();
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
    
    public function agreeWithdraw() {
    	$uid = $this->checklogin();
    	$id = I("post.id");
    
    	$paymodel = M("pay");
    	$paydata = $paymodel->where("id=".$id)->find();
    	if ($paydata === false) {
    		Log::dberror("failed to get withdraw info", $paymodel);
    		echo "系统繁忙！";
    		return;
    	}
    	if ($paydata == null) {
    		Log::error("payid not exist. id=".$id);
    		echo "订单不存在！";
    		return;
    	}
    
    	$compmodel = M("companyinfo");
    	$compdata = $compmodel->field("equity,banknum,bankusername")->where("deptid=".$paydata['uid'])->find();
    	if ($compdata === false) {
    		Log::dberror("failed to get company equity", $compmodel);
    		echo "系统繁忙！";
    		return;
    	}
    	if ($compdata == null) {
    		Log::error("company with deptid=%d not exist", array($paydata['uid']));
    		echo "账户不存在！";
    		return;
    	}
    
    	if ($paydata['amount'] > $compdata['equity']) {
    		Log::error("withdraw amount > equity. amount=%f, equity=%f", array($paydata['amount'], $compdata['equity']));
    		echo "账户余额不足！";
    		return;
    	}

    	$paymodel->startTrans();

    	//更新审核状态
    	$now = time();
    	$paynew = array("state" => \PayStateEnum::START,
    			"checkState"=>\PayCheckStateEnum::SUCCESS,
    			"checkUserId"=>$uid,
    			"checkTime"=>$now,
    			"payReqTime" => date('YmdHis', $now)
    	);
    	$result = $paymodel->where("id=".$id)->save($paynew);
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
    	}
    	else if ($paydata['payWay'] == \PayWayEnum::UNIONPAY) {
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
    	if ($payway == \PayWayEnum::WEIXIN) {
    		$openid = M("userinfo")->where("uid=".$paydata['uid'])->getField("openid");
    		if ($openid === false) {
    			Log::dberror("failed to get userinfo", $paymodel);
    			echo "系统繁忙！";
    			return;
    		}
    		if ($openid == null) {
    			Log::error("userinfo not exist. uid=".$paydata['uid']);
    			echo "账户不存在！";
    			return;
    		}
    			
    		$param['openid'] = $openid;
    		if ($paydata['wxCheckName'] == 1) {
    			$param['checkName'] = "FORCE_CHECK";
    			$param["realUserName"] = $paydata['wxRealName'];
    		}
    		else {
    			$param['checkName'] = "NO_CHECK";
    			$param["realUserName"] = "";
    		}
    
    		$msg = "";
    		$result =  $withdraw->weipay($param, $msg);
    		if ($result === false) {
    			Log::error("weipay tixian failed. msg=".$msg);
    			echo $msg;
    		}
    		else {
    			echo "操作成功！";
    		}
    	}
    	else if ($payway == \PayWayEnum::UNIONPAY) {
    		$param['bankCardNo'] = $paydata["bankCardNo"];
    		$param['idCardNo'] = 'xxxxxxxxxxxxxxxxx';//$compdata['banknum']; TODO
    		$param['realName'] = $compdata['bankusername'];
    		$param['notifyURL'] = U('/Home/Withdraw/unionpay_notify', '', true, true);
    		$param['reqTime'] = $paydata['payReqTime'];
    			
    		$msg = "OK";
    		$tixian = A('Common/Withdraw', 'Event');
    		$result = $tixian->unionpay($param, $msg);
    		if ($result == false) {
    			Log::error("unionpay tixian failed. msg=".$msg);
    			echo $msg;
    		}
    		else {
    			echo "银联已受理转账，请注意查看订单状态";
    		}
    	}    	else if ($payway == \PayWayEnum::ALIPAY) {
    		$param['bankCardNo'] = $paydata["bankCardNo"];
    		$param['idCardNo'] = 'xxxxxxxxxxxxxxxxx';//$compdata['banknum']; TODO
    		$param['realName'] = $compdata['bankusername'];
    		$param['notifyURL'] = U('/Home/Withdraw/alipay_notify', '', true, true);
    		$param['reqTime'] = $paydata['payReqTime'];
    			
    		$msg = "OK";
    		$tixian = A('Common/Withdraw', 'Event');
    		$result = $tixian->alipay($param, $msg);
    		if ($result == false) {
    			Log::error("alipay tixian failed. msg=".$msg);
    			echo $msg;
    		}
    		else {
    			echo "支付宝已受理转账，请注意查看订单状态";
    		}
    	}
    }

    public function manualWithdraw() {
        $uid = $this->checklogin();
        $id = I("post.id");
        $remark = I("post.remark");

        $paymodel = M("pay");
        $paydata = $paymodel->where("id=".$id)->find();
        if ($paydata === false) {
            Log::dberror("failed to get withdraw info", $paymodel);
            echo "系统繁忙！";
            return;
        }
        if ($paydata == null) {
            Log::error("payid not exist. id=".$id);
            echo "订单不存在！";
            return;
        }

        $compmodel = M("companyinfo");
        $compdata = $compmodel->field("equity,banknum,bankusername")->where("deptid=".$paydata['uid'])->find();
        if ($compdata === false) {
            Log::dberror("failed to get company equity", $compmodel);
            echo "系统繁忙！";
            return;
        }
        if ($compdata == null) {
            Log::error("company with deptid=%d not exist", array($paydata['uid']));
            echo "账户不存在！";
            return;
        }

        if ($paydata['amount'] > $compdata['equity']) {
            Log::error("withdraw amount > equity. amount=%f, equity=%f", array($paydata['amount'], $compdata['equity']));
            echo "账户余额不足！";
            return;
        }

        $paymodel->startTrans();

        //更新审核状态
        $now = time();
        $paynew = array("state" => \PayStateEnum::START,
            "checkState"=>\PayCheckStateEnum::SUCCESS,
            "checkUserId"=>$uid,
            "checkTime"=>$now,
            "payReqTime" => date('YmdHis', $now),
            "is_manual"=>1,
            "remark"=>$remark
        );
        $result = $paymodel->where("id=".$id)->save($paynew);
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
        }
        else if ($paydata['payWay'] == \PayWayEnum::UNIONPAY) {
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
    
    public function rejectWithdraw() {
    	$uid = $this->checklogin();
    	$id = I("post.id");
    	$rejectReason = I("post.rejectReason");
    
    	$paymodel = M("pay");
    	$paydata = $paymodel->where("id=".$id)->find();
    	if ($paydata === false) {
    		Log::dberror("failed to get withdraw info", $paymodel);
    		echo "系统繁忙！";
    		return;
    	}
    	if ($paydata == null) {
    		Log::error("payid not exist. id=".$id);
    		echo "订单不存在！";
    		return;
    	}
    
    	$paymodel->startTrans();

		//更新订单状态
    	$paynew = array("checkState"=>\PayCheckStateEnum::FAIL,
    			"checkUserId"=>$uid,
    			"checkTime"=>time(),
    			"endTime"=>time(),
    			"checkFailReason" => $rejectReason
    	);

    	$result = $paymodel->where("id=".$id)->save($paynew);
    	if ($result === false) {
    		$paymodel->rollback();
			Log::dberror("failed to update wp_pay", $paymodel);
    		echo "系统繁忙";
    		return;
    	}

		//更新会员权益
		$deptid = $paydata['uid'];
		$compmodel = D("companyinfo");
		$cid = $compmodel->where("deptid=".$deptid)->getField("cid");
		if ($cid === false) {
    		$paymodel->rollback();
			Log::dberror("failed to get companyinfo. deptid=".$deptid, $compmodel);
    		echo "系统繁忙！";
			return;
		}
		if ($cid === null) {
    		$paymodel->rollback();
			Log::error("company with deptid=%d not exitst.", array($deptid));
    		echo "会员不存在！";
			return;
		}

		$result = $compmodel->updateEquity($cid, $paydata['amount'], 4, $paydata['opUserId'], $id, $deptid);
		if ($result['result'] === false) {
			$paymodel->rollback();
			Log::error("failed to update company's equity.cid=%d, amount=%f", array($cid, $paydata['amount']));
			return;
		}
    
    	$paymodel->commit();
    
    	echo "success";
    }
    

	public function equitylist() {
		$this->checklogin();
		
		$companys = M('companyinfo')->where($cmap)->field('cid,comname')->select();
		$this->assign('companys',$companys);
	 
		$opType = I("get.optype");
		$compid = I("get.companyid");
		$opDate = I("get.opdate");
		$amount1 = I("get.amount1");
		$amount2 = I("get.amount2");
		foreach($_GET as $name => $value) {
			$this->assign($name, $value);
		}
		 
		$params = array();
		$condition = " from wp_companywater water inner join wp_companyinfo comp on water.cid=comp.cid where 1=1";
		if ($opType!= "" && $opType!=0) {
			array_push($params, $opType);
			$condition .= " and water.wtype=%d";
		}
		if ($opDate != "") {
/*			$tm = getStartTimeOfDay($opDate);
			array_push($params, $tm);
			array_push($params, $tm+24*3600);*/
            $tm = strtotime($opDate);
            array_push($params, $tm);
            array_push($params, $tm + 60);
			$condition .= " and water.createtime>=%d and water.createtime<%d";
		}
		if ($compid != "" && $compid != 0) {
			array_push($params, $compid);
			$condition .= " and comp.cid=%d";
		}
		if ($amount1 != "") {
			array_push($params, $amount1);
			$condition .= " and water.amount >= %f";
		}
		if ($amount2 != "") {
			array_push($params, $amount2);
			$condition .= " and water.amount <= %f";
		}
		 
		$curPage = I("p");
		if ($curPage == "") {
			$curPage = 1;
		}
		$dataSql = "select water.wid, water.cid, water.wtype as optype, water.amount, water.equity, water.outid, water.opuid, water.createtime, comp.ccode, comp.comname";
		$dataSql .= $condition . " order by water.wid desc limit ". ($curPage-1)*20 . ",20";
		$countSql = "select count(1) cc" . $condition;
		 
		$model = M();
		$totalCount = $model->query($countSql, $params)[0]['cc'];
		$balancelist = $model->query($dataSql, $params);
		foreach($balancelist as &$balance) {
			if ($balance['optype'] == 5) {
				$opuserid = $balance['opuid'];
				//取得该用户姓名
				$opusername = M("systemuser")->where("id=".$opuserid)->getField("username");
				$balance['opusername'] = $opusername;
			}
		}
		$this->assign("equitylist", $balancelist);
		 
		$page = new \Think\Page($totalCount, 20);
		//$page->parameter = $row;
		$page->setConfig('first','首页');
		$page->setConfig('prev','&#8249;');
		$page->setConfig('next','&#8250;');
		$page->setConfig('last','尾页');
		$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
		$show = $page->show();
		$this->assign('page',$show);
		
		$this->display();
	}


}
