<?php
// 本类由系统自动生成，仅供测试用途
namespace Ucenter\Controller;
use Ucenter\Controller\CommonController;
class AccountController extends CommonController{
    //代理列表
	public function agentlist()
    {
    	//获取登录代理商的id
        $tq=C('DB_PREFIX');
    	$oid = $_SESSION['cuid'];
    	$deptinfo = $_SESSION['deptinfo'];

    	if(I('get.type')=='daili'){
    	    $type = 3;
    	}else{
    	    $type = 4;
    	}
    	$map=array(
    	   $tq.'companyinfo.ccode'=>array('LIKE',$deptinfo['code'].'%'),
    	   $tq.'companyinfo.type'=> $type,
    	   $tq.'companyinfo.isdelete'=>array('neq','Y')
    	);
    	$count =M('companyinfo')->where($map)->count();
        $pagecount = 10;
        $page = new \Think\Page($count , $pagecount);
        $page->parameter = array('type'=>I('get.type')); //此处的row是数组，为了传递查询条件
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%');
        $show = $page->show();
        $field = $tq.'companyinfo.cid as cid,'.
            $tq.'systemuser.username as username,'.
        $tq.'companyinfo.manageraddress as manageraddress,'.
        $tq.'companyinfo.type as type,'.
        $tq.'companyinfo.comname as comname,'.
        $tq."department.invite_code as invcod,".
        $tq.'department.create_time as create_time';
		$ulist=M('companyinfo')->join($tq.'department on '.$tq.'companyinfo.deptid='.$tq.'department.id','left')->
		join($tq.'systemuser on '.$tq.'systemuser.deptid='.$tq.'companyinfo.deptid and '.$tq.'systemuser.isadmin=1','left')->field($field)->
		where($map)->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('ulist',$ulist);
        $this->assign('page',$show);
		$this->display();
    }
    //会员增加或者修改
	public function agentadd()
    {
    	header("Content-type: text/html; charset=utf-8");
    	$tq=C('DB_PREFIX');
        //获取登录代理商的id
        $myuid=$_SESSION['cuid'];
    	if (IS_POST) {
	    	$uid=I('post.uid');
	    	$company=D('companyinfo');
	    	$userinfo=D('Admin/systemuser');
	    	$departmentinfo = D('Admin/department');
            // 自动验证 创建数据集
            $data = $company->create();
            if ($data) {
               //验证身份证正确性
               $this->checkIdCard(I('post.manageridcard'));
               if($uid!=''){
                    //修改
                    M('companyinfo')->where('cid='.$uid)->save($data);
                    $companyinfo = $company->where('cid='.$uid)->find();
                    //修改用户表的公司名称
                    $result1 = M('department')->where('id='.$companyinfo['deptid'])->setField(array('name'=>$data['comname']));
                    $result2 = M('systemuser')->where('deptid='.$companyinfo['deptid'].' and isadmin=1')->
                    setField(array('displayname'=>$data['comname'],'tel'=>$data['managerphone'],'updatetime'=>time()));
                    
                    redirect(U('Account/agentlist'),1, '修改成功...');
                }else{
                    //添加
                    $userinfo->username = I('post.username');
                    $userinfo->tel = $data['managerphone'];
                    if(I('post.addtype') == 'daili'){
                        $userinfo->type = 3;
                    }else{
                        $userinfo->type = 4;
                    }
                    $userinfo->displayname = $data['comname'];
                    $userinfo->createuserid = $myuid;
                    $userinfo->isadmin = 1;
                    if(!$userinfo->validate()){
                        $this->error($userinfo->getError());
                    }
                    $deptid = $departmentinfo->addDepartment($data['comname'],$userinfo->type,$_SESSION['deptinfo']['deptid']);
                    if(!$deptid){
                        $this->error($departmentinfo->getError());
                    }
                    $userinfo->deptid = $deptid;
                    $result = $userinfo->addSystemuser();
                    if(!$result['result']){
                        $this->error($result['msg']);
                    }
                    
                    $deptinfo = $departmentinfo->where('id='.$deptid)->find();
                    $data['ccode'] = $deptinfo['code'];
                    if(I('post.addtype') == 'daili'){
                        $data['type'] = 3;
                    }else{
                        $data['type'] = 4;
                    }
                    $data['deptid'] = $deptid;
                    $data['isdelete'] = 'N';
                    $data['managefeerate'] = 0;
                    $data['cashdeposit'] = 0;
                    $data['equity'] = 0;
                    $data['updatetime']=time();
                    $uid = $company->add($data);
                    if(I('post.addtype') == 'daili'){
                        redirect(U('Account/agentlist',array('type'=>'daili')),1, '新增代理成功...');
                    }else{
                        redirect(U('Account/agentlist',array('type'=>'jigou')),1, '新增机构成功...');
                    }
                }
            }else{
                $this->error($userinfo->getError());
            }
    	}
    	//判断跳转到修改页面或者新增页面
		$uid=I('get.uid');
    	if($uid){
    	    $field = $tq.'companyinfo.cid as cid,'.
    	        $tq.'systemuser.username as username,'.
    	        $tq.'companyinfo.*,'.
    	        $tq.'department.create_time as create_time';
    	    $user=M('companyinfo')->join($tq.'department on '.$tq.'companyinfo.deptid='.$tq.'department.id',left)->
    	    join($tq.'systemuser on '.$tq.'systemuser.deptid='.$tq.'companyinfo.deptid and '.$tq.'systemuser.isadmin=1',left)->field($field)->
    	    where('cid='.$uid)->limit($page->firstRow.','.$page->listRows)->find();
    		$this->assign('user',$user);
    	}	
    	
    	$this->display();
    }
    //删除会员
    public function agentdel(){
    	header("Content-type: text/html; charset=utf-8");
    	$cid=I('get.cid');
    	$member=D('Admin/companyinfo');
    	$memberinfo = $member->where('cid='.$cid)->find();
    	$result = $member->delCompany($memberinfo['ccode']);
    	if($result['result']){
    	  redirect(U('Account/agentlist'),1, '删除用户成功...');
	    }else{
	      redirect(U('Account/agentlist'),1, '删除用户失败...');	
	    }
    }
    public function resetpw(){
        $user = A('Ucenter/Account');
        $user->checklogin();
         
        $cid = I('post.cid');
        $user = D('Admin/systemuser');
        $companyinfo = M('companyinfo')->join()->where('cid='.$cid)->find();
        $userinfo = $user->where('deptid='.$companyinfo['deptid'].' and isadmin=1')->find();
        $result = $user->restpassword($userinfo['username'],$_SESSION['userid']);
        $this->ajaxReturn($result);
    }
    
	public function memberlist()
    {
		$this->display();
    }
	public function memberadd()
    {
		$this->display();
    }
    //身份证号验证
    function checkIdCard($idcard){  
        // 只能是18位  
        if(strlen($idcard)!=18){ 
            $this->error("身份证号不正确，请重新输入"); 
            return false;  
        }  
        // 取出本体码  
        $idcard_base = substr($idcard, 0, 17);  
        // 取出校验码  
        $verify_code = substr($idcard, 17, 1);  
        // 加权因子  
        $factor = array(7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2);  
        // 校验码对应值  
        $verify_code_list = array('1', '0', 'X', '9', '8', '7', '6', '5', '4', '3', '2');  
        // 根据前17位计算校验码  
        $total = 0;  
        for($i=0; $i<17; $i++){  
            $total += substr($idcard_base, $i, 1)*$factor[$i];  
        }  
        // 取模  
        $mod = $total % 11;  
      
        // 比较校验码  
        if($verify_code == $verify_code_list[$mod]){  
            return true;  
        }else{  
            $this->error("身份证号不正确，请重新输入");
            return false;  
        }  
    }
    
    /**
     * 用户注销
     */
    public function signinout()
    {
        // 清楚所有session
        header("Content-type: text/html; charset=utf-8");
        session(null);
        redirect(R('User/signin'), 2, '正在退出登录...');
    }
    
    public function checklogin(){
        $uid=islogin();
        if(!$uid)
        {
            $this->error('请登录',U('/Admin/User/signin'));
        }
        return $uid;
    }
}
