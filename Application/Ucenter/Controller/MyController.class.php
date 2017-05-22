<?php
// 本类由系统自动生成，仅供测试用途
namespace Ucenter\Controller;
use Ucenter\Controller\CommonController;
class MyController extends CommonController {
	
	private function checklogin()
	{
		//判断用户是否已经登录
		if (!isset($_SESSION['cuid'])) {
			$this->redirect('Admin/User/signin');
		}
		return $_SESSION['cuid'];
	}
	
    //会员列表
	public function deposit()
    {
    	//获取登录代理商的id
    	//$oid=$_SESSION['cuid'];
    	
    	$this->display();
    }
    
    
    public function doDeposit()
    {
    	$payway = I("post.payway");
    	$amount = I("post.amount");
    	
    	if ($payway == "weixin") {
    		
    	}
    	
    }
    
    public function equitylist()
    {
    	$sysuserid = $this->checklogin();
    	$sysuser = M("systemuser")->where("id=".$sysuserid)->field("type,deptid")->find();
    	if ($sysuser['type'] != 2) {
    		$this->error("只有会员才能查看权益流水！");
    	}
		$cid = M("companyinfo")->where("deptid=".$sysuser['deptid'])->getField("cid");
    	
    	$opType = I("post.optype");
   		$startDate = I("post.startDate");
   		$endDate = I("post.endDate");
   		$amount1 = I("post.amount1");
    	$amount2 = I("post.amount2");
    	foreach($_POST as $name => $value) {
    		$this->assign($name, $value);
    	}
    		
    	$params = array();
    	$condition = " from wp_companywater water where water.cid=$cid";
    	if ($opType!= "" && $opType!=0) {
    		array_push($params, $opType);
    		$condition .= " and water.wtype=%d";
    	} 	
    	if ($startDate != "") {
    		$tm = getStartTimeOfDay($startDate);
    		array_push($params, $tm);
    		$condition .= " and water.createtime>=%d";
    	}
    	if ($endDate != "") {
    		$tm = getStartTimeOfDay($endDate);
    		array_push($params, $tm+24*3600);
    		$condition .= " and water.createtime<%d";
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
    	$dataSql = "select water.wid, water.cid, water.wtype as optype, water.amount, water.equity, water.outid, water.opuid, water.createtime";
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
    
    public function personinfo(){
        $sysuserid = $this->checklogin();
        $userinfo = M('systemuser');
        $companyinfo = M('companyinfo');
        $department = M('department');
        $alipayinfo=M('alipay');
        if (IS_POST) {
            $cid = I('post.cid');
            $managername = I('post.managername');
            $managerphone = I('post.managerphone');
            $aliusername= I('post.aliusername');
            $alirealname= I('post.alirealname');
            $result1 = $companyinfo->where('cid='.$cid)->setField(array('managername'=>$managername,'managerphone'=>$managerphone));
            $result2 = $alipayinfo->where('cid='.$cid)->setField(array('aliusername'=>$aliusername,'alirealname'=>$alirealname));
            if($result2){
                $this->success('修改成功!');
            }else{
                $this->error("修改失败!");
            }
        }else{
            //获取本人信息
            
            $user = $userinfo->where('id='.$sysuserid)->find();
            if($userinfo){
                //获取本部门信息
                
                $deptInfo = $department->where('id='.$user['deptid'])->find();
                $company = $companyinfo->where('deptid='.$deptInfo['id'])->find();
	       $aliuser = $alipayinfo->where('cid='.$company['cid'])->find();
                if($deptInfo){
                    //获取上级单位信息
                    $parentDeptInfo = $department->where('id='.$deptInfo['parent_id'])->find();
                    $parentCompany = $companyinfo->where('deptid='.$deptInfo['parent_id'])->find();
                }else{
                    $this->error("获取部门信息失败！");
                }
                $hostlink= $_SERVER['HTTP_HOST'];
                $url=  $hostlink.U('User/reg')."?uid={$sysuserid}&invitecode=".$deptInfo['invite_code'];
                $this->assign('url_encode', urlencode(str_replace("Ucenter", "Home", $url)));
                
                $this->assign('user',$user);
                $this->assign('aliuser',$aliuser);
                $this->assign('deptinfo',$deptInfo);
                $this->assign('companyinfo',$company);
                $this->assign('parentcompanyinfo',$parentCompany);
                $this->assign('parentdeptinfo',$parentDeptInfo);
                $this->display();
            }else{
                $this->error("获取个人信息失败！");
            }
        }
    }
    
    public function updatepwd(){
        $sysuserid = $this->checklogin();
        $userinfo = M('systemuser')->where('id='.$sysuserid)->find();
        if (IS_POST) {
            $oldpwd = I('post.oldpwd');
            $newpwd = I('post.newpwd');
            $qrpwd = I('post.qrpwd');
            $pattern = '#(?=^.*?[a-z])(?=^.*?[A-Z])(?=^.*?\d)^(.{8,})$#';
            if(md5($oldpwd.$userinfo['createtime']) <> $userinfo['password']){
                $this->error("旧密码输入不对！");
            }else if(!preg_match($pattern,$newpwd)){
                $this->error("密码不符合规则，请输入6到20位的大小写字母和数字的组合!");
            }else{
                $newpwd = md5($newpwd.$userinfo['createtime']);
                M('systemuser')->where('id='.$sysuserid)->setField('password',$newpwd);
                $this->success("修改成功");
            }
        }else{
            $this->assign("user",$userinfo);
            $this->display();
        }
        
    }
}