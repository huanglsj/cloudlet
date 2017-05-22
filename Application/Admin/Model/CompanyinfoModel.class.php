<?php
namespace Admin\Model;

use Think\Model;
use Think\Log;

class CompanyinfoModel extends Model
{

    /**
     * 自动验证
     */
    protected $_validate = array(
        array(
            'comname',
            'require',
            '公司名称不能为空！'
        ),
        array(
            'comname',
            '',
            '公司名称已经存在',
            '0',
            'unique',
            1
        ),
        array(
            'managerphone',
            'require',
            '负责人电话不能为空！'
        ),
        array(
            'managerphone',
            '/^1[3|4|5|7|8][0-9]\d{4,8}$/',
            '负责人电话格式不正确！',
            1,
            'regex',
            1
        ),
        array(
            'managefeerate',
            'require',
            '管理费比例不能为空！'
        ),
        array(
            'managefeerate',
            'double',
            '管理费比例必须为数字！'
        ),
        array(
            'cashdeposit',
            'require',
            '会员缴纳的保证金不能为空！'
        ),
        array(
            'cashdeposit',
            'double',
            '会员缴纳的保证金必须为数字！'
        ),
        array(
            'equity',
            'require',
            '保证金扣除管理费和盈亏的净值不能为空！'
        ),
        array(
            'banknum',
            'require',
            '银行卡号不能为空！'
        ),
        array(
            'banknum',
            'number',
            '银行卡号格式不正确！'
        ),
        array(
            'bankusername',
            'require',
            '开户人名不能为空！'
        )
    );

    /**
     * 自动完成
     */
    protected $_auto = array(
        array(
            'updatetime',
            'time',
            1,
            'function'
        )
    );

    public function updateEquity($cid, $amount, $wtype, $opuid, $outid = null, $deptid = null)
    {

        $result = array(
            'result' => false,
            'msg' => '处理异常！'
        );
        $amount = floatval($amount);
        if ($amount < 0) {
        	$company = $this->where('cid=' . $cid)->field("equity, cashdeposit")->find();
        	if ($company === false) {
        		return $result;
        	}
        	
        	$equity = $company['equity'] + $amount;
        	if ($equity < $company['cashdeposit'] * 0.6) {
//         		$result['msg'] = '所属交易机构剩余保证金不足，请联系您的邀请人补充资金后再交易！';
        	    $result['msg'] = '交易机构目前繁忙，请稍后再试！';
        		return $result;
        	}
        }
        
		// 更新会员权益
		$sql = "update wp_companyinfo set equity=equity+".$amount.",equity_version=equity_version+1 where cid=".$cid;
		Log::debug("sql=".$sql);
		$crtn = $this->execute($sql);
		if ($crtn == false) {
			$result['msg'] = $this->getDbError();
			return $result;
		}
		// 添加权益流水
		$companywater = M('companywater');
		$water['cid'] = $cid;
		$water['wtype'] = $wtype;
		$water['equity'] = 0;
		$water['amount'] = $amount;
		$water['opuid'] = $opuid;
		if($outid){
			$water['outid'] = $outid;
		}
		$water['createtime'] = time();
		if ($deptid) {
			$water['ownerdeptid'] = $deptid;
		}else{
			//获取平台部门id
			$platinfo = M('department')->where(array('type'=>'1'))->find();
			$water['ownerdeptid'] = $platinfo['id'];
		}
		$wrtn = $companywater->add($water);
		if ($wrtn == false) {
			$result['msg'] = $companywater->getDbError();
			return $result;
		}else{
			$result['wid']=$wrtn;
		}
		
		$sql = "update wp_companywater water inner join wp_companyinfo comp on water.cid=comp.cid "
		      ."set water.equity=comp.equity,water.equity_version=comp.equity_version "
			  ."where water.wid=".$wrtn;
		$r = $this->execute($sql);
		if ($r === false) {
			$result['msg'] = $this->getDbError();
			return $result;
		}
		else {
			$result['result']=true;
			return $result;
		}
    }

    public function delCompany($code){
        $result = array(
            'result' => false,
            'msg' => '处理异常！'
        );
        //删除公司信息
        $rtn = $this->where(array('ccode'=>array('LIKE',$code."%"),'isdelete'=>array('neq','Y')))->setField('isdelete','Y');
        
        $department = M('department');
        $deptlist = $department->where(array('code'=>array('LIKE',$code."%"),'isdelete'=>array('neq','Y')))->field('id')->select();
        $rtn = $department->where(array('code'=>array('LIKE',$code."%"),'isdelete'=>array('neq','Y')))->setField('isdelete','Y');
        if(!$rtn){
            return $result;
        }
        //冻结关联登录用户
        $user = D('systemuser');
        foreach($deptlist as $deptid){
            $user->where('deptid='.$deptid['id'])->setField('state','2');
        }
        $result['result']=true;
        
        return $result;
    }

}

