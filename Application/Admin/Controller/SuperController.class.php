<?php

namespace Admin\Controller;
use Think\Controller;
use Think\Log;
require APP_PATH.'/Admin/Common/Permissioninfo.php';
if (!defined('CAL_GREGORIAN')) 
    define('CAL_GREGORIAN', 1); 
class SuperController extends Controller {
	
	//管理员列表
    public function slist()
    {
    	//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
		
		$users = D('systemuser');
		//分页
		$count = $users->where(array('type'=>1,'state'=>array('neq',2)))->count();
        $pagecount = 20;
        $page = new \Think\Page($count , $pagecount);
        $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first','首页');
        $page->setConfig('prev','&#8249;');
        $page->setConfig('next','&#8250;');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
        $show = $page->show();
		//查询用户和账户信息
		$ulist = $users->where(array('type'=>1,'state'=>array('neq',2)))->order(	'id desc')->limit($page->firstRow.','.$page->listRows)->select();
		$this->assign('page',$show);
		$this->assign('ulist',$ulist);
		$this->display();
		
	}

	//添加管理员
	public function sadd()
	{
		//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
		//实例化userinfo表
		$users = D('systemuser');
		if(IS_POST){
		    if(I('post.password') != I('post.confirmpw')){
		        $this->error('两次输入的密码不一致！');
		    }
		    $data = $users->create();
			if($data){
			    $users->type=1;
			    $platinfo = M('department')->where('type=1')->find();
			    $users->deptid = $platinfo['id'];
			    $users->isadmin = 0;
			    $users->createuserid = $_SESSION['userid'];
				$result = $users->addSystemuser();
				if($result['result']){
					$this->success('添加管理员成功',U('Super/slist'));
				}else{
					$this->error($result['msg']);
				}
			}else{
				$this->error($users->getError());
			}
		}else{
			$this->display();	
		}
	}
	//基本设置
	public function esystem(){
		//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
		$config = D('webconfig');
		$isopen = I('post.isopen');
		$webname = I('post.webname');
        $onelevel = I('post.onelevel');
        $twolevel = I('post.twolevel');
        $Pscale = I('post.Pscale');
        $minorderamount = I('post.minorderamount');
        $maxorderamount = I('post.maxorderamount');
        $maxuseramount = I('post.maxuseramount');
        $registsendcp = I('post.registsendcp');
        $defdiancha = I('post.defdiancha');
		$notice = I('post.notice');
		$where = "id=1";
		if($isopen!=""){
			if($isopen==0){
				$config->where($where)->setField('isopen','1');
				
				$this->ajaxReturn("关闭成功，客户端将在60秒内休市");		
			}else{
				$config->where($where)->setField('isopen','0');
				$this->ajaxReturn("开启成功");
			}		
		}elseif($webname!=""){
			$result = $config->where($where)->setField('webname',$webname);
			if($result){
				$this->ajaxReturn("修改成功");
			}else{
				$this->ajaxReturn("修改失败");
			}
		}elseif($notice!=""){
			$result = $config->where($where)->setField('notice',$notice);
			if($result){
				$this->ajaxReturn("修改成功");
			}else{
				$this->ajaxReturn("修改失败");
			}
		}
        elseif($onelevel!=""){
            $result = $config->where($where)->setField('onelevel',$onelevel);
            if($result){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        elseif($twolevel!=""){
            $result = $config->where($where)->setField('twolevel',$twolevel);
            if($result){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        elseif($Pscale!=""){
            $result = $config->where($where)->setField('Pscale',$Pscale);
            if($result){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        elseif($minorderamount!=""){
            $result = $config->where($where)->setField('minorderamount',$minorderamount);
            if($result){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        elseif($maxorderamount!=""){
            $result = $config->where($where)->setField('maxorderamount',$maxorderamount);
            if($result){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        elseif($maxuseramount!=""){
            $result = $config->where($where)->setField('maxuseramount',$maxuseramount);
            if($result){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        elseif($registsendcp!=""){
            $result = $config->where($where)->setField('registsendcp',$registsendcp);
            if($result){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        elseif($defdiancha!=""){
            $result = $config->where($where)->setField('defdiancha',$defdiancha);
            if($result){
                $this->ajaxReturn("修改成功");
            }else{
                $this->ajaxReturn("修改失败");
            }
        }
        else{
			$conf = $config->where($where)->find();
			$exprInfo = D('experience')->select();
			$this->assign('conf',$conf);
			$this->assign('exprInfo',$exprInfo);
		}
		$this->display();
	}
	//修改管理员
	public function sedit()
    {
    	//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
		
		$users = D('systemuser');
		if(IS_POST){
		    $edittype = I('post.edittype');
		    $uid = I('post.uid');
		    if($edittype == 1){
		        //修改基本信息
    		    $data = $users->create();
    			if($data){
    				$result = $users->where('id='.$uid)->save($data);
    				if($result === FALSE){
    					$this->error("管理员修改失败！");
    				}else{
    					$this->success("管理员修改成功",U('Super/slist'));
    				}
    			}else{
    				$this->error($users->getError());
    			}
		    }else{
		        //修改密码
		        $oldpwd = I('post.oldpwd');
		        $upwd = I('post.upwd');
		        $qrpwd = I('post.qrpwd');
		        if($upwd != $qrpwd){
		            $this->error("两次输入密码不同！");
		        }
		        $result = $users->updatePassword($uid,$oldpwd,$upwd,$_SESSION['userid']);
		        if($result['result']){
		            $this->success("管理员修改成功",U('Super/slist'));
		        }else{
		            $this->error($result['msg']);
		        }
		    }
		}else{
			//根据修改管理员的id读取数据
			$uid = I('get.uid');
			$ult = $users->where('id='.$uid)->find();	
			$this->assign('edittype','1');
			$this->assign('ult',$ult);
			$this->display();
		}
	}
	//删除管理员
	public function sdel()
    {
    	$user = D('systemuser');
    	$uid = I('get.uid');
    	
    	//不能删除超级管理员
    	$userinfo = $user->where('id='.$uid)->find();
    	if($userinfo['username'] == 'wpadmin'){
    	    $this->error('不能删除超级管理员！');
    	}
		//单个删除
		$result = $user->where('id='.$uid)->setfield('state',2);
		if($result!==FALSE){
			$this->success("成功删除管理员！",U("Super/slist"));
		}else{
			$this->error('删除失败！');
		}
	}
	
	public function resetpw(){
	    $user = A('Admin/User');
	    $user->checklogin();
	     
	    $username = I('post.username');
	    $user = D('systemuser');
	    $result = $user->restpassword($username,$_SESSION['userid']);
	    $this->ajaxReturn($result);
	}
	
	//备份数据
	public function backupdb()
	{
		//判断用户是否登录
		$user=A('Admin/User');//实例化其他模块中的方法
		$user->checklogin();
		
		$users=D('userinfo');//获取用户信息
		$username=$users->field('username')->find();
		mysql_query("set names 'utf8'");
		$mysql = "set charset utf8;\r\n";
		$q1 = mysql_query("show tables");
		while ($t = mysql_fetch_array($q1))
		{
    		$table = $t[0];
    		$q2 = mysql_query("show create table `$table`");
    		$sql = mysql_fetch_array($q2);
    		$mysql .= $sql['Create Table'] . ";\r\n";
    		$q3 = mysql_query("select * from `$table`");
    		while ($data = mysql_fetch_assoc($q3))
    			{
    				$keys = array_keys($data);
    				$keys = array_map('addslashes', $keys);
    				$keys = join('`,`', $keys);
    				$keys = "`" . $keys . "`";
    				$vals = array_values($data);
    				$vals = array_map('addslashes', $vals);
    				$vals = join("','", $vals);
    				$vals = "'" . $vals . "'";
    				$mysql .= "insert into `$table`($keys) values($vals);\r\n";
    			} 
    		} 
		$filename = APP_PATH.'backup/'.date('Y-m-d_H-i-s').".sql"; //存放路径，默认存放到项目最外层
		//echo $filename;
		$fp = fopen($filename, 'w');
		fputs($fp, $mysql);
		fclose($fp);
		$this->success("数据备份成功",U('Index/index'));
	}
	//还原数据
	public function restoredb()
	{
	}
	public function withdrawConfig()
	{
        //判断用户是否登陆
        $user= A('Admin/User');
        $user->checklogin();
		$conf = M("sysconfig")->where("k like 'tixian.%'")->select();
		$this->assign("config", json_encode($conf));
		
		$this->display();
	}
	
	public function saveWithdrawConfig()
	{

		$configStr = $_POST['config'];
		$config = json_decode($configStr);
		$arr = get_object_vars($config);
		
		$sysconfig = M("sysconfig");
		foreach($arr as $k=>$v) {
			$conf = $sysconfig->where("k='".$k."'")->find();
			if ($conf == null) {
				$sysconfig->add(array("k"=>$k, "v"=>$v));
			}
			else {
				$conf['v'] = $v;
				$sysconfig->save($conf);
			}
		}
		
		$result['success'] = 1;
		echo json_encode($result);
	}

	public function calendar()
    {
            // 判断用户是否登录
        $user = A('Admin/User'); // 实例化其他模块中的方法
        $user->checklogin();
        
        // 获取商品列表信息
        $tq=C('DB_PREFIX');
        $goods = D('productinfo');
        $goodlist = $goods->select();
        $this->assign("goodlist", $goodlist);
        
        if (IS_POST) {
            $year = I('post.year');
            $opflg = I('post.opflg');
            $pid = I('post.goodspid');
            
            $this->assign("pid", $pid);
            $this->assign("opflg", $opflg);
            $this->assign("year", $year);
            
            $editgood = $goods->join($tq.'product_lastprice on '.$tq.'productinfo.pid='.$tq.'product_lastprice.pid')->where($tq.'productinfo.pid='.$pid)->find();
            $tradeperiod = json_decode($editgood['tradeperiod'],true);
            
            $workcalendar = D("workcalendar");
            $map['year'] = array('EQ', $year );
            $map['productpid'] = array('EQ', $pid );
            $list = $workcalendar->where($map)->select();
            if ($list != null && count($list) > 0) {
                $msg = "该年日历已经生成过了！";
                $this->assign("msg", $msg);
                
                $showList = array();
                $preM = null;
                for ($i = 0;$i < count($list); $i++) {
                    if ($preM == null || $preM != $list[$i]['month']) {
                        $preM = $list[$i]['month'];
                        $data['year'] = $list[$i]['year'];
                        $data['month'] = $list[$i]['month'];
                        $monthList = array();
                        $weekday = date('w', strtotime($data['year']."-".$data['month']."-1"));
                        if ($weekday <= 0) {
                            $weekday = 7;
                        }
                        for ($j = 1; $j < $weekday; $j ++) {
                            array_push($monthList, "");
                        }
                    }
                    array_push($monthList, $list[$i]);
                    if ($i == count($list) || $preM != $list[$i + 1]['month']) {
                        $data['monthList'] = $monthList;
                        array_push($showList, $data);
                    }
                }
                $this->assign("list", $showList);
                $this->assign("opflg", "2");
                
                $this->display();
            } else {
                // 生成日历
                $list = array();
                for ($month = 1; $month <= 12; $month ++) {
                    $monthday = cal_days_in_month(CAL_GREGORIAN, $month, $year);
                    for ($day = 1; $day <= $monthday; $day ++) {
                        $data['year'] = $year;
                        $data['month'] = $month;
                        $data['day'] = $day;
                        $data['fulldate'] = strtotime($year."-".$month."-".$day);
                
                        // 判断周末
                        $a = date("w", $data['fulldate']);
                        if ($a == "0" || $a == "6") {
                            $data['isworkday'] = '0';
                        } else {
                            $data['isworkday'] = '1';
                        }
                        $period = array();
                        if ($a == "0"){
                            $period = $tradeperiod["7"];
                        } else {
                            $period = $tradeperiod[strval($a)];
                        }
                        $data['tradeperiod'] = json_encode($period);
                        $data['creater'] = $_SESSION['userid'];
                        $data['createtime'] = time();
                        $data['modifyer'] = $_SESSION['userid'];
                        $data['modifytime'] = time();
                        $data['productpid'] = $pid;
                        
                        $workcalendar->add($data);
                        array_push($list, $data);
                    }
                }
                $showList = array();
                $preM = null;
                for ($i = 0;$i < count($list); $i++) {
                    if ($preM == null || $preM != $list[$i]['month']) {
                        $preM = $list[$i]['month'];
                        $data['year'] = $list[$i]['year'];
                        $data['month'] = $list[$i]['month'];
                        $monthList = array();
                        $weekday = date('w', strtotime($data['year']."-".$data['month']."-1"));
                        if ($weekday <= 0) {
                            $weekday = 7;
                        }
                        for ($j = 1; $j < $weekday; $j ++) {
                            array_push($monthList, "");
                        }
                    }
                    array_push($monthList, $list[$i]);
                    if ($i == count($list) || $preM != $list[$i + 1]['month']) {
                        $data['monthList'] = $monthList;
                        array_push($showList, $data);
                    }
                }
                $msg = "日历生成完毕,默认休市日为周六周日,请根据实际情况做手动修改!";
                $this->assign("msg", $msg);
                $this->assign("list", $showList);
                $this->assign("opflg", "2");
                $this->display();
            }
        } else {
            $pid = I('get.pid',$goodlist[0]['pid']);
            $year = I('get.year',date("Y"));
            $opflg = I('get.opflg',"1");
            $this->assign("pid", $pid);
            $this->assign("year", $year);
            
            $workcalendar = D("workcalendar");
            $map['year'] = array('EQ', $year );
            $map['productpid'] = array('EQ', $pid );
            $list = $workcalendar->where($map)->order('month, day')->select();
            if ($list != null && count($list) > 0) {
                $showList = array();
                $preM = null;
                for ($i = 0;$i < count($list); $i++) {
                    if ($preM == null || $preM != $list[$i]['month']) {
                        $preM = $list[$i]['month'];
                        $data['year'] = $list[$i]['year'];
                        $data['month'] = $list[$i]['month'];
                        $monthList = array();
                        $weekday = date('w', strtotime($data['year']."-".$data['month']."-1"));
                        if ($weekday <= 0) {
                            $weekday = 7;
                        }
                        for ($j = 1; $j < $weekday; $j ++) {
                            array_push($monthList, "");
                        }
                    }
                    array_push($monthList, $list[$i]);
                    if ($i == count($list) || $preM != $list[$i + 1]['month']) {
                        $data['monthList'] = $monthList;
                        array_push($showList, $data);
                    }
                }
                $this->assign("list", $showList);
                if($opflg == "1"){
                    $opflg = "2";
                }
            }
            $this->assign("opflg", $opflg);
            $this->display();
        }
    }
    
    //更新是否是工作日的状态
    public function updateWorkDay(){
            
        // 判断用户是否登录
        $user = A('Admin/User'); // 实例化其他模块中的方法
        $user->checklogin();
        
        $year = I('post.year');
        $month = I('post.selectMonth');
        $day = I('post.selectDay');
        $isworkday = I('post.isWorkDay');
        
        $workcalendar = D("workcalendar");
        $map = array();
        $map['year'] = array('EQ', $year );
        $map['month'] = array('EQ', $month );
        $map['day'] = array('EQ', $day );
        $map['month'] = array('EQ', $month );
        
        $info = $workcalendar->where($map)->select();
        if ($info != null && count($info) > 0) {
            $data['uid'] = $info[0]['uid'];
            $data['isworkday'] = $isworkday;
            $workcalendar->save($data);
            $this->ajaxReturn("设置成功!");
        } else {
            $this->ajaxReturn("更新日期不存在！");
        }
    }
    
    function editworkday(){
        // 判断用户是否登录
        $user = A('Admin/User'); // 实例化其他模块中的方法
        $user->checklogin();
        $workcalendar = M("workcalendar");
        $weekname = array("星期日","星期一","星期二","星期三","星期四","星期五","星期六");
        if(IS_POST){
            $uid = I('post.uid');
            $isworkday = I('post.isworkday');
			//设置交易时间json
		        $tstart = I('post.tstart');
		        $tend = I('post.tend');
		        $time = array();
		        foreach($tstart as $key=>$value){
		            $plus = 0;
		            if($value > $tend[$key]){
		                $plus = 1;
		            }
		            $section = array(
		                's' => $value,
		                'e' => $tend[$key],
		                'plus' => $plus
		            );
		            array_push($time, $section);
		        }
			    $data['tradeperiod'] = json_encode($time);
			    $data['isworkday'] = $isworkday;
			    $data['uid'] = $uid;
				$result = $workcalendar->save($data);
				if($result === FALSE){
					$this->error("修改失败！");
				}else{
				    $productpid = I('post.productpid');
				    $year = date("Y",I('post.fulldate'));
				    $this->success("修改成功",U('Super/calendar',array("pid"=>$productpid,"year"=>$year)));
				}
        } else {
            $fulldate = I('get.fulldate');
            $pid = I('get.pid');
            $this->assign("fulldate",$fulldate);
            $this->assign("fulldateY",date("Y-m-d ", $fulldate));
            $weekday = date('w', $fulldate);
            $this->assign("weekname",$weekname[$weekday]);
            $map['fulldate'] = array('EQ', $fulldate );
            $map['productpid'] = array('EQ', $pid );
            $info = $workcalendar->where($map)->find();
            $this->assign("info",$info);
            $tradeperiod = json_decode($info['tradeperiod'],true);
            $this->assign('period',$tradeperiod);
            $this->display();
        }
    }
    
    public function rlist()
    {
        // 判断用户是否登录
        $user = A('Admin/User'); // 实例化其他模块中的方法
        $user->checklogin();
        $tq=C('DB_PREFIX');
        $field= $tq.'role.id as id,'.$tq.'role.name as name,'.$tq.'role.rtype as rtype,'.$tq.'role.state as state,'.$tq.'role.remarks as remarks,'.$tq.'department.name as dname,'.$tq.'role.isdefault as isdefault';
        $role = M("role");
        $info = $role->join($tq.'department on '.$tq.'role.deptid='.$tq.'department.id','left')->order('rtype, id')->field($field)->select();
        $this->assign("rlist", $info);
        $this->display();
    }
    
    public function radd(){
        //判断用户是否登陆
        $user= A('Admin/User');
        $user->checklogin();
        $role = M("role");
        
        if(IS_POST){
            $data["name"] = I('post.rname');
            $data["remarks"] = I('post.remarks');
            $data["deptid"] = I('post.deptid');
            $data["state"] = "1";
            $data["rtype"] = I('post.rtype');
            $result = $role->add($data);
            if($result === FALSE){
                $this->error("添加失败！");
            }else{
                $this->success("添加角色成功,请设置角色权限。",U('Super/rlist'));
            }
        }else{
            $this->display();
        }
    }
    
    public function redit(){
        //判断用户是否登陆
        $user= A('Admin/User');
        $user->checklogin();
        $role = M("role");
        $rolepermission = M("rolepermission");
        if(IS_POST){
            $rid = I('post.id');
            $isdefault = I('post.isdefault');
            if ($isdefault == '1') {
                $info['isdefault'] = '0';
                $role->where('rtype='.I('post.rtype'))->save($info);
            }
            $data["id"] = $rid;
            $data["name"] = I('post.rname');
            $data["remarks"] = I('post.remarks');
            $data["deptid"] = I('post.deptid');
            $data["state"] = I('post.state');
            $data["rtype"] = I('post.rtype');
            $data["isdefault"] = $isdefault;
            $result = $role->save($data);
            if($result === FALSE){
                $this->error("修改失败！");
            }else{
                // 权限修改
                $checkedKeys = explode(",", I("post.checkedKey"));
                $rpdata = array();
                foreach ($checkedKeys as $key) {
                    $section = array(
                        'roleid' => $rid,
                        'permission' => $key
                    );
                    array_push($rpdata, $section);
                }
                $rolepermission->where('roleid='.$rid)->delete();
                $result = $rolepermission->addall($rpdata);
                if($result === FALSE){
                    $this->error("修改失败！");
                }else{
                    $this->success("修改成功!",U('Super/rlist'));
                }
            }
        }else{
            $id = I('get.id');
            $info = $role->where("id=".$id)->find();
            $this->assign("info",$info);
            
            $keylist = $rolepermission->where('roleid='.$id)->select(); 
            
            $keyMap = array();
            foreach ($keylist as $key){
                $keyMap[$key['permission']] = true;
            }
            $parray = getEditInfoArray($keyMap,$info['rtype']);
            $this->assign("parray",json_encode($parray));
            
            $this->display();
        }
    }
    
    public function rdel(){
        // 判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();
        $role = M("role");
        $id = I('get.id');
        $result = $role->delete($id);
        if ($result === FALSE) {
            $this->error("修改失败！");
        } else {
            $this->success("修改成功!", U('Super/rlist'));
        }
    }
    
	//会员角色列表
    public function mrlist()
    {
    	//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
    	
		$type = I('get.type');
		$this->assign("type",$type);
		
    	$tq=C('DB_PREFIX');
    	$companyinfo = M('companyinfo');
    	$systemuser = M('systemuser');
    	$map = array();
    	if($type == 1){
    	    $map['type'] = array('eq',$type);
    	} else {
    	    $map['type'] = array('in','2,3,4');
    	}
    	
    	$userrole = M('userrole');
    	//分页
    	$count = $systemuser->where($map)->count();
    	$pagecount = 20;
    	$page = new \Think\Page($count , $pagecount);
    	$map1['type'] = $type;
    	$page->parameter = $map1; //此处的row是数组，为了传递查询条件
    	$page->setConfig('first','首页');
    	$page->setConfig('prev','&#8249;');
    	$page->setConfig('next','&#8250;');
    	$page->setConfig('last','尾页');
    	$page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
    	$show = $page->show();
    	//查询用户和账户信息
    	$ulist = $systemuser->where($map)->order('createtime desc')->limit($page->firstRow.','.$page->listRows)->select();
    	
    	$field = $tq.'role.name as name';
    	
    	foreach ($ulist as &$item){
    	    $ccode = str_replace('admin','',$item['username']);
    	    $info = $companyinfo->where('ccode='.$ccode)->find();
    	    $item['cname'] = $info['comname'];
    	    
    	    $rolelist=array();
    	    $rolelist = $userrole->join($tq.'role on '.$tq.'role.id = '.$tq.'userrole.rid','left')->field($field)->where($tq.'userrole.uid='.$item['id'])->select();
    	    $rolename = "";
    	    foreach ($rolelist as $role){
    	        $rolename = $rolename.$role['name']."  ";
    	    }
    	    $item['rname'] = $rolename;
    	}
    	
    	$this->assign('page',$show);
    	$this->assign('ulist',$ulist);
		$this->display();
	}

    public function mrdel(){
        // 判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();
        $systemuser = M('systemuser');
        $userrole = M('userrole');
        $uid = I('get.uid');
        $type = I('post.type');
        $result = $systemuser->where('id='.$uid)->delete();
        if ($result === FALSE) {
            $this->error("修改失败！");
        } else {
            $userrole->where('uid='.$uid)->delete();
            $this->success("修改成功!", U('Super/mrlist',array("type"=>$type)));
        }
    }

	public function mredit(){
	    $user = A('Admin/User');
	    $user->checklogin();
	    $role = M('role');
	    $userrole = M('userrole');
	    if (IS_POST) {
            $uid = I('post.uid');
            $type = I('post.type');
            $userrole -> where('uid='.$uid)-> delete();
            $checkedrole = I('post.checkedrole');
            $data = array();
            foreach($checkedrole as $key){
                $section = array(
                    'uid' => $uid,
                    'rid' => $key,
                    'type' => $type
                );
                array_push($data, $section);
            }
            $result = $userrole->addall($data);
            if ($result) {
                if($type != 1){
                    $type = 99;
                }
                $this->success('修改成功', U('Super/mrlist',array("type"=>$type)));
            } else {
                $this->error('修改失败');
            }
	    } else {
	        $uid = I("get.uid");
	        $username = I("get.username");
	        $cname = I("get.cname");
	        $type = I("get.type");
	        $this->assign('uid',$uid);
	        $this->assign('username',$username);
	        $this->assign('cname',$cname);
	        $this->assign('type',$type);
	        
	        $roleList = $role->where('rtype='.$type)->select();
	        
	        $checkrolelist = $userrole->where('uid='.$uid)->select();
	        $roles = array();
	        if($checkrolelist != null && count($checkrolelist) > 0){
	            foreach ($checkrolelist as $checkrole){
	                $roles[$checkrole['rid']] = true;
	            }
	        }
	        foreach ($roleList as &$item){
	            if($roles[$item['id']]){
	                $item['checked'] = '1';
	            } else {
	                $item['checked'] = '0';
	            }
	        }
	        $this->assign('rlist',$roleList);
	        $this->display();
	    }
	}
	
	//编辑公告
	public function contentedit()
	{

		$content=M('content');
		if (IS_POST) {
            $Id = I('post.Id');
            $etitle = I('post.title');
            $econtent = I('post.content');
			$content-> where('Id='.$Id)-> delete();
            $data = array();
                $section = array(
                    'Id' => $Id,
                    'title' => $etitle,
                    'content' => $econtent,
                    'datatime' => time()
                );
                array_push($data, $section);
            $result = $content->addall($data);
            if ($result) {
                if($type != 1){
                    $type = 99;
                }
                $this->success('修改成功', U('Super/content',array("type"=>$type)));
            } else {
                $this->error('修改失败');
            }
		} else{
            $Id = I('get.Id');
			$ct=$content-> where('Id='.$Id)-> find();
	        $this->assign('content',$ct);
	        $this->display();
		}
	}
	
	//编辑公告
	public function contentadd()
	{ 
		$content=M('content');
		if (IS_POST) {
            $etitle = I('post.title');
            $econtent = I('post.content');
            $data = array();
                $section = array(
                    'Id' => $Id,
                    'title' => $etitle,
                    'content' => $econtent,
                    'datatime' => time()
                );
                array_push($data, $section);
            $result = $content->addall($data);
            if ($result) {
                if($type != 1){
                    $type = 99;
                }
                $this->success('添加成功', U('Super/content',array("type"=>$type)));
            } else {
                $this->error('添加失败');
            }
		} else{
	        $this->display();
		}
	}
	
	//删除公告
	public function contentdel()
	{ 
		$content=M('content');
		$Id=I('get.Id');
		$result=$content-> where('Id='.$Id)-> delete();
		$this->success('删除成功', U('Super/content',array("type"=>$type)));
	}
	//公告列表
	public function content()
	{
		//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
		//实例化content表
		$contents = D('content')->select();
		$this->assign('contents',$contents);
		$this->display();
	}

    //风控设置
    public function riskConfig(){
        //判断用户是否登陆
        $user= A('Admin/User');
        $user->checklogin();

        $map = array();
        $modelRisk = M('Risk');

        //分页
        $count = $modelRisk->where($map)->count();
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
        $list = $modelRisk->where($map)
            ->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
    }

    //添加风控
    public function riskadd()
    {
        if (IS_POST) {
            $modelRisk = D('Risk');
            $result = $modelRisk->update();
            if ($result) {
                $this->success('操作成功', U('Super/riskconfig'));
            } else {
                $this->error('操作失败!'.$modelRisk->getError());
            }
        } else{
            $this->display();
        }
    }

    //编辑风控
    public function riskedit()
    {
        if (IS_POST) {
            $modelRisk = D('Risk');
            $result = $modelRisk->update();
            if ($result) {
                $this->success('操作成功', U('Super/riskconfig'));
            } else {
                $this->error('操作失败!'.$modelRisk->getError());
            }
        } else{
            $modelRisk = D('Risk');
            $id = I('get.id', 0, 'intval');
            $info = $modelRisk->where(array('id'=>$id))->find();
            if(!$info){
                $this->error('没有该记录!');
            }
            $this->assign('info', $info);
            $this->display('riskadd');
        }
    }

    //删除风控
    public function riskdel()
    {
        $modelRisk = D('Risk');
        $id = I('get.id', 0, 'intval');
        $result = $modelRisk->where(array('id'=>$id))->delete();
        if ($result) {
            $this->success('操作成功', U('Super/riskconfig'));
        } else {
            $this->error('操作失败!'.$modelRisk->getError());
        }
    }
}
