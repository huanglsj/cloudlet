<?php
 namespace Home\Model;
 use Think\Model;
 use Think\Log;
 require_once APP_PATH .'Common/Enum/BalanceOpTypeEnum.php';
 
 class AccountModel extends Model {
 	protected $tableName = "accountinfo";
 	
	protected $_validate = array(
	);
	 
	/**
	 * 添加账户余额变更历史（先更新余额再调用本函数）
	 * @param unknown $uid
	 * @param unknown $optype
	 * @param unknown $uid
	 * @param unknown $amount
	 * @param unknown $opid
	 * @param unknown $oporderno
	 */
	public function updateBalance($uid, $optype, $opuserid, $amount, $oporderid, $oporderno) {
		
		if ($optype == \BalanceOpTypeEnum::SDXG) {
			return $this->setBalance($uid, $opuserid, $amount);
		}

		if($optype == \BalanceOpTypeEnum::CZZS){
            $sql = 'update wp_accountinfo set balance=balance+'.$amount.', give=give+'.$amount.', balance_version=balance_version+1 where uid='.$uid;
		}elseif($optype == \BalanceOpTypeEnum::QKZS){
            $sql = 'update wp_accountinfo set balance=balance+'.$amount.', give=give+'.$amount.', balance_version=balance_version+1 where uid='.$uid;
        }else{
            $sql = 'update wp_accountinfo set balance=balance+'.$amount.', balance_version=balance_version+1 where uid='.$uid;
		}

		$result = $this->execute($sql);
		if ($result == false) {
			Log::dberror("failed to update user balance", $this);
			return false;
		}
		
		$optime = time();
		$opdate = date('Ymd', $optime);
		$opdate = mktime(0,0,0, substr($opdate, 4, 2), substr($opdate,6, 2), substr($opdate, 0, 4));

		//添加余额记录
		$baldata['uid'] = $uid;
		$baldata['optype'] = $optype;
		$baldata['opid'] = $oporderid;
		$baldata['oporderno'] = $oporderno;
		$baldata['optime'] = $optime;
		$baldata['opdate'] = $opdate;
		$baldata['opuserid'] = $opuserid;
		$baldata['amount'] = $amount;
		$baldata['balance'] = $amount;
		$id = M("balance")->add($baldata);
		if ($id == false) {
			Log::dberror("failed to add balance info", M("balance"));
			return false;
		}
		
		if ($optype != \BalanceOpTypeEnum::SDXG) {
			$sql ='update wp_balance balance inner join wp_accountinfo account on balance.uid=account.uid '
			     .'set balance.balance=account.balance,balance.balance_version=account.balance_version '
				 .'where balance.id='.$id;
			$result = $this->execute($sql);
			if ($result === false) {
				Log::dberror("failed to add balance info", $this);
				return false;
			}
		}
		return true;
	}

	/**
	* 设定账户余额并添加余额流水
	*/
	public function setBalance($uid, $opuserid, $balance) {
		
		$optime = time();
		$opdate = date('Ymd', $optime);
		$opdate = mktime(0,0,0, substr($opdate, 4, 2), substr($opdate,6, 2), substr($opdate, 0, 4));

		//添加余额记录
		$baldata['uid'] = $uid;
		$baldata['optype'] = \BalanceOpTypeEnum::SDXG;
		$baldata['optime'] = $optime;
		$baldata['opdate'] = $opdate;
		$baldata['opuserid'] = $opuserid;
		$baldata['amount'] = $balance;
		$baldata['balance'] = 0;
		$id = M("balance")->add($baldata);
		if ($id == false) {
			Log::dberror("failed to add balance info", M("balance"));
			return false;
		}
		
		//设定账户余额
		$sql ='update wp_balance balance inner join wp_accountinfo account on balance.uid=account.uid '
			 .'set balance.balance='.$balance .',balance.balance_version=account.balance_version+1,account.balance='.$balance .',account.balance_version=account.balance_version+1 '
			 .'where balance.id='.$id;
		$result = $this->execute($sql);
		if ($result === false) {
			Log::dberror("failed to add balance info", $this);
			return false;
		}

		return true;
	}
 }
?>
