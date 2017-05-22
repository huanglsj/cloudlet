<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
use Think\Log;
require_once APP_PATH .'Common/Enum/BalanceOpTypeEnum.php';
require_once APP_PATH .'Common/Enum/PayWayEnum.php';

class TongjiController extends Controller {
	
	
	public function tongji(){
	    //判断用户是否登陆
	    $user= A('Admin/User');
	    $user->checklogin();
	   
	    $this->init();
	}
	
	function init(){
	    $tq=C('DB_PREFIX');
	     
	    //获取下线单位信息
	    $mycode = $_SESSION['deptinfo']['code'];
		if ($mycode === '000') {
			$mycode = '';
		}
	    $where['code'] = array(array('LIKE',$mycode.'___'),array('neq','000'));
	    $subdepts = M('department')->where($where)->field('id,code,name')->select();
	    //获取产品信息
	    $products = M('productinfo')->join($tq.'product_lastprice on '.$tq.'productinfo.pid='.$tq.'product_lastprice.pid')->
	    where($tq.'productinfo.status=1')->field($tq.'productinfo.pid as pid,displayname')->select();
	    $expr = M('experience')->select();
	    $this->assign("startTime",date('Y-m-d',strtotime("-7 day")));
	    $this->assign("endTime",date('Y-m-d'));
	    $this->assign('subdepts',$subdepts);
	    $this->assign('products',$products);
		$this->assign("mycode", $mycode);
		$this->assign("exprinfo", $expr);
	    $this->display();
	}
	   
	/**
	 * 入出金报表
	 * @param PHPExcel $excel excel实例
	 * @return string 文件名
	 */
	function exportkehuzijinliushui($excel){
	    $tq=C('DB_PREFIX');
	    $sheet = $excel->setActiveSheetIndex(0);
	    //获取数据
	    if(I('post.username') <> ''){
	        //用户名
	        $map['user.username'] = I('post.username');
	    }
	    if(I('post.member') <> '' && I('post.member') <> '0'){
	        //所属单位
	        $map['user.ucode'] = array('LIKE',I('post.member').'%');
	    }
	    if(I('post.otype') <> ''){
	        //操作类型
	        if (I('post.otype') == \BalanceOpTypeEnum::TXSQ) {
	        	$map['balance.optype'] = array(
	        		array("eq", \BalanceOpTypeEnum::TXSQ),
	        		array("eq", \BalanceOpTypeEnum::TXSB),
	        		array("eq", \BalanceOpTypeEnum::TXBH),
	        		"or"
	        	);
	        }
	        else {
	        	$map['balance.optype'] = I('post.otype');
	        }
	    }
	    
	    $optime = array();
	    if(I('post.startTime') <> ''){
	        array_push($optime,array('egt',strtotime(I('post.startTime'))));
	    }
	    if(I('post.endTime') <> ''){
	        //小于的要加当天
	        array_push($optime,array('lt',strtotime(I('post.endTime')) + 86400));
	    }
	    if(count($optime) > 0){
	        if(count($optime) == 2){
	            array_push($optime,'and');
	        }
	        $map['balance.optime'] = $optime;
	    }

	    $balance = M('balance')->alias('balance')->join($tq.'userinfo user on balance.uid = user.uid')
	    		->join($tq.'department dept on dept.id=user.deptid')
	    		->join($tq.'managerinfo manager on manager.uid=user.uid', 'left')
				->join($tq.'pay pay on pay.id=balance.opid and balance.optype in(1,2,5,6)', 'left')
	    		->field("balance.id, user.uid, user.username, manager.mname, dept.huiyuan_code,dept.huiyuan_name, dept.daili_name, dept.jigou_name, "
	    				."balance.optime, balance.opid, balance.optype, balance.amount, balance.balance, pay.payway, pay.outOrderNo")
	            ->where($map)->order('balance.optime,balance.uid,balance.balance_version')->select();
	    $count = 2;
	    $sumbalance = 0;
	    $sumfee = 0;
	    foreach ($balance as $ob){
	        $sheet->setCellValue('A'.$count,date('Y-m-d',$ob['optime']));
	    	$sheet->setCellValueExplicit('B'.$count,$ob['huiyuan_code'], \PHPExcel_Cell_DataType::TYPE_STRING);
	    	$sheet->setCellValue('C'.$count,$ob['huiyuan_name']);
	    	$sheet->setCellValue('D'.$count,$ob['username']);
	    	$sheet->setCellValue('E'.$count,$ob['mname']);
	            
	        if($ob['optype'] == \BalanceOpTypeEnum::CZ){
	        	$opname = "充值";
	        }
	    	else if($ob['optype'] == \BalanceOpTypeEnum::TXSQ){
	    		$opname = "提现";
	        }
	    	else if($ob['optype'] == \BalanceOpTypeEnum::TXSB){
	    		$opname = "提现失败";
	        }
	    	else if($ob['optype'] == \BalanceOpTypeEnum::TXBH){
	    		$opname = "提现驳回";
	        }
	        else if($ob['optype'] == \BalanceOpTypeEnum::JC){
	        	$opname = "建仓";
	        }
	        else if($ob['optype'] == \BalanceOpTypeEnum::PC){
	        	$opname = "平仓";
	        }
	        else if($ob['optype'] == \BalanceOpTypeEnum::SDXG){
	        	$opname = "手动修改";
	        }
			else {
				$opname = "";
			}

			if ($ob['payway'] == \PayWayEnum::WEIXIN) {
				$payway = "微信";
			}
			else if ($ob['payway'] == \PayWayEnum::WEIXIN_QRCODE) {
				$payway = "微信";
			}
			else if ($ob['payway'] == \PayWayEnum::ALIPAY) {
				$payway = "支付宝";
			}
			else if ($ob['payway'] == \PayWayEnum::ALIPAY_QRCODE) {
				$payway = "支付宝";
			}
			else if ($ob['payway'] == \PayWayEnum::UNIONPAY) {
				$payway = "银联";
			}
			else {
				$payway = "";
			}
	         
	        $sheet->setCellValue('F'.$count,$opname);
	        $sheet->setCellValue('G'.$count,date('Y-m-d H:i:s', $ob['optime']));
	        $sheet->setCellValue('H'.$count,$ob['amount']);
	        $sheet->setCellValue('I'.$count,$ob['balance']);
	        $sheet->setCellValue('J'.$count,$payway);
	    	$sheet->setCellValueExplicit('K'.$count,$ob['outOrderNo'], \PHPExcel_Cell_DataType::TYPE_STRING);
       
	        $count++;
	    }

	    //设置边框
	    $this->setBorder($sheet,'A2:K'.($count-1));
	    
	    return "客户资金流水.xls";
	}
	
	
	/**
	 * 入出金报表
	 * @param PHPExcel $excel excel实例
	 * @return string 文件名
	 */
	function exportkehuzijinzhuangkuang($excel){
	    $tq=C('DB_PREFIX');
	    $sheet = $excel->setActiveSheetIndex(0);
	    //获取数据

		$map = array();
	    if(I('post.username') <> ''){
	        //用户名
	        $map['user.username'] = I('post.username');
	    }
	    if(I('post.member') <> '' && I('post.member') <> '0'){
	        //所属单位
	        $map['user.ucode'] = array('LIKE',I('post.member').'%');
	    }
	    
	    $userlist = M("accountinfo")->alias("acc")
				->join($tq.'userinfo user on acc.uid = user.uid')
	    		->join($tq.'department dept on dept.id=user.deptid')
	    		->join($tq.'managerinfo manager on manager.uid=user.uid', 'left')
	    		->field("user.uid, user.username, manager.mname, dept.huiyuan_code,dept.huiyuan_name, dept.daili_name, dept.jigou_name, acc.balance")
	            ->where($map)->select();
		foreach($userlist as $user) {
			$uinfo = array('account' => $user['username'],
			               'name' => $user['mname'],
						   'huiyuan' => $user['huiyuan_name'],
						   'daili' => $user['daili_name'],
						   'jigou' => $user['jigou_name'],
						   'balance' => $user['balance']
						   );
			$userinfo[$user['uid']] = $uinfo;
		}

	    $optime = array();
	    if(I('post.startTime') <> ''){
	        array_push($optime,array('egt',strtotime(I('post.startTime'))));
	    }
	    if(I('post.endTime') <> ''){
	        //小于的要加当天
	        array_push($optime,array('lt',strtotime(I('post.endTime')) + 86400));
	    }
	    if(count($optime) > 0){
	        if(count($optime) == 2){
	            array_push($optime,'and');
	        }
	        $map['balance.optime'] = $optime;
	    }

	    $balance = M('balance')->alias('balance')
				->join($tq.'userinfo user on balance.uid = user.uid')
				->join($tq.'pay pay on pay.id=balance.opid and balance.optype=2 and pay.state=2', 'left')
				->join($tq.'order oder on oder.oid=balance.opid and (balance.optype=3 or balance.optype=4)', 'left')
	    		->field("balance.uid, balance.optime, balance.optype, balance.amount, balance.opdate,balance.balance, balance.balance_version,"
				       ."pay.state paystate, pay.commissionFee, oder.ostaus, oder.ploss, oder.managefee")
	            ->where($map)->order('balance.opdate')->select();

		$list = array();
	    foreach ($balance as $ob){
			$opdate = $ob['opdate'];
			$uid = $ob['uid'];
			if (!isset($list[$opdate][$uid])) {
				$rowdata = array();
				$rowdata['rj'] = 0;
				$rowdata['cj'] = 0;
				$rowdata['yk'] = 0;
				$rowdata['sx'] = 0;
				$rowdata['glf'] = 0;
				$rowdata['ver'] = 0;
				$rowdata['bal'] = 0;
				$rowdata['ver1'] = PHP_INT_MAX;
				$rowdata['bal1'] = 0;
				$list[$opdate][$uid] = $rowdata;
			}

			$rowdata = &$list[$opdate][$uid];
			$optype = $ob['optype'];
			if ($ob['balance_version'] > $rowdata['ver']) {
				$rowdata['bal'] = $ob['balance'];
				$rowdata['ver'] = $ob['balance_version'];
			}
			if ($ob['balance_version'] < $rowdata['ver1']) {
				$rowdata['bal1'] = $ob['balance'] - $ob['amount'];
				$rowdata['ver1'] = $ob['balance_version'];
			}
			if ($optype == \BalanceOpTypeEnum::CZ) {
				$rowdata['rj'] += $ob['amount'];
			}
			else if ($optype == \BalanceOpTypeEnum::TXSQ || $optype == \BalanceOpTypeEnum::TXBH || $optype == \BalanceOpTypeEnum::TXSB) {
				$rowdata['cj'] += $ob['amount'];
				if ($ob['paystate'] == 2) {
					$rowdata['sx'] += $ob['commissionFee'];
				}
			}
			else if ($optype == \BalanceOpTypeEnum::JC) {
				$rowdata['glf'] += $ob['managefee'];
			}
			else if ($optype == \BalanceOpTypeEnum::PC) {
				$rowdata['yk'] += $ob['ploss'];
			}
			unset($rowdata);
		}
		
		$sumYE = 0;
		$sumYE1 = 0;
		$sumRJ = 0;
		$sumCJ = 0;
		$sumYK = 0;
		$sumSX = 0;
		$sumGLF = 0;
	    $count = 2;
	    foreach ($list as $opdate => $data){
			foreach($data as $uid => $udata) {
				$user = $userinfo[$uid];
				$sheet->setCellValue('A'.$count,date('Y-m-d',$opdate));
				$sheet->setCellValue('B'.$count,$user['huiyuan']);
				$sheet->setCellValue('C'.$count,$user['daili']);
				$sheet->setCellValue('D'.$count,$user['jigou']);
				$sheet->setCellValue('E'.$count,$user['account']);
				$sheet->setCellValue('F'.$count,$user['name']);
				$sheet->setCellValue('G'.$count,$udata['bal1']);
				$sheet->setCellValue('H'.$count,$udata['rj']);
				$sheet->setCellValue('I'.$count,$udata['cj']);
				$sheet->setCellValue('J'.$count,$udata['yk']);
				$sheet->setCellValue('K'.$count,$udata['bal']);
				$sheet->setCellValue('L'.$count,$udata['sx']);
				$sheet->setCellValue('M'.$count,$udata['glf']);
		   
		   		$sumYE += $udata['bal'];
		   		$sumYE1 += $udata['bal1'];
		   		$sumRJ += $udata['rj'];
		   		$sumCJ += $udata['cj'];
		   		$sumYK += $udata['yk'];
		   		$sumSX += $udata['sx'];
		   		$sumGLF += $udata['glf'];
				$count++;
			}
	    }

		//合计行
		$sheet->setCellValue('A'.$count,'合计');
		$sheet->setCellValue('G'.$count,$sumYE1);
		$sheet->setCellValue('H'.$count,$sumRJ);
		$sheet->setCellValue('I'.$count,$sumCJ);
		$sheet->setCellValue('J'.$count,$sumYK);
		$sheet->setCellValue('K'.$count,$sumYE);
		$sheet->setCellValue('L'.$count,$sumSX);
		$sheet->setCellValue('M'.$count,$sumGLF);

		//设置合计行粗体
		$fontstyle = array('font' => array('bold' => true));
		$sheet->getStyle('A'.$count.':M'.$count)->applyFromArray($fontstyle);

	    //设置边框
	    $this->setBorder($sheet,'A2:M'.$count);
	    
	    return "客户资金状况.xls";
	}
	
	/**
	 * 微交易点位单据明细查询
	 * @param PHPExcel $excel excel实例
	 * @return string 文件名
	 */
	private function exportjiaoyimingxi($excel){
	    $tq=C('DB_PREFIX');
	    $sheet = $excel->setActiveSheetIndex(0);
	    
	    
	    $eid = I('post.eid');
	    $ploss = I('post.ploss');
	    $product = I('post.product');
	    $member = I('post.member');
	    $startTime = I('post.startTime');
	    $endTime = I('post.endTime');
	    
	    if($product <> ''){
	       $where[$tq.'order.pid'] = $product;
	    }
	    
	    if($eid <> ''){
	        $where[$tq.'order.eid'] = $eid;
	    }
	    
	    if($ploss <> ''){
	        if($ploss == '1'){
	            //盈利
	            $where[$tq.'order.ploss'] = array('gt',0);
	        }else{
	            //亏损
	            $where[$tq.'order.ploss'] = array('lt',0);
	        }
	    }else{
	        //未选择
	        $where[$tq.'order.ploss'] = array('neq',0);
	    }
	    if($member <> '' && $member <> '0'){
	        $where[$tq.'order.ucode'] = array('LIKE',$member.'%');
	    }
		else {
			if($_SESSION['deptinfo']['type'] != 1){
				$mycode = $_SESSION['deptinfo']['code'];
				$where[$tq.'order.ucode'] = array('LIKE',$mycode.'%');
			}
		}
	    $buytime = array();
	    if($startTime <> ''){
	        array_push($buytime,array('gt',strtotime($startTime)));
	    }
	    if($endTime <> ''){
	        array_push($buytime,array('lt',strtotime($endTime) + 86400));
	    }
	    if(count($buytime) > 0){
	        $where[$tq.'order.selltime'] = $buytime;
	    }
	    $where['ostaus'] = 1;
	    
	    $order = M('order');
	    $fields = $tq.'order.*,'.
	    $tq.'userinfo.username as username,'.
	    $tq.'managerinfo.mname as mname,'.
	    $tq.'department.huiyuan_name as huiyuan_name,'.
	    $tq.'department.daili_name as daili_name,'.
	    $tq.'department.jigou_name as jigou_name';
	    $orders = $order->where($where)->
	    join($tq.'userinfo on '.$tq.'userinfo.uid='.$tq.'order.uid',left)->
	    join($tq.'managerinfo on '.$tq.'managerinfo.uid='.$tq.'order.uid',left)->
	    join($tq.'product_lastprice on '.$tq.'product_lastprice.pid='.$tq.'order.pid',left)->
	    join($tq.'department on '.$tq.'department.code='.$tq.'order.ucode',left)->field($fields)->order('selltime asc')->select();
	    
	    $count = 1;
		if ($orders == null) {
			$orders = array();
		}
	    foreach ($orders as $order){
	        $count++;
	        $sheet->setCellValue('A'.$count,date('Y-m-d',$order['selltime']));
	        $sheet->setCellValue('B'.$count,$order['username']);
	        $sheet->setCellValue('C'.$count,$order['mname']);
	        $sheet->setCellValue('D'.$count,$order['ptitle']);
	        if($order['eid'] == 1){
	            $sheet->setCellValue('E'.$count,'点数');
	        }else{
	            $sheet->setCellValue('E'.$count,'时间');
	        }
	        $sheet->setCellValue('F'.$count,$order['endprofit']);
	        if($order['ploss'] > 0){
	           $sheet->setCellValue('G'.$count,'盈利');
	        }else{
	           $sheet->setCellValue('G'.$count,'亏损');
	        }
	        if($order['ostyle'] == 0){
	            $sheet->setCellValue('H'.$count,'买涨');
	        }else{
	            $sheet->setCellValue('H'.$count,'买跌');
	        }
	        
	        $sheet->setCellValue('I'.$count,$order['buyprice']);
	        $sheet->setCellValue('J'.$count,$order['sellprice']);
	        $sheet->setCellValue('K'.$count,$order['fee']);
	        if($order['ploss'] > 0){
	            $sheet->setCellValue('L'.$count,$order['fee']);
	        }else{
	            $sheet->setCellValue('L'.$count,$order['fee'] * -1);
	        }
	        $sheet->setCellValue('M'.$count,$order['ploss']);
	        $sheet->setCellValue('N'.$count,$order['endloss'].'%');
	        $sheet->setCellValue('O'.$count,$order['managefee']);
	        $sheet->setCellValue('P'.$count,$order['orderno'].' ');
	        $sheet->setCellValue('Q'.$count,date('Y-m-d H:i:s',$order['buytime']));
	        $sheet->setCellValue('R'.$count,date('Y-m-d H:i:s',$order['selltime']));
	        $sheet->setCellValue('S'.$count,$order['huiyuan_name']);
	        $sheet->setCellValue('T'.$count,$order['daili_name']);
	        $sheet->setCellValue('U'.$count,$order['jigou_name']);
	        
	    }
	    //设置边框
	    $this->setBorder($sheet,'A2:U'.$count);
	    return '交易明细查询.xls';
	}
	/**
	 * 微交易点位单据明细查询
	 * @param PHPExcel $excel excel实例
	 * @return string 文件名
	 */
	private function exportjiaoyihuizong($excel){
	    $tq=C('DB_PREFIX');
	    $sheet = $excel->setActiveSheetIndex(0);
	     
	    $eid = I('post.eid');
	    $ploss = I('post.ploss');
	    $product = I('post.product');
	    $member = I('post.member');
	    $startTime = I('post.startTime');
	    $endTime = I('post.endTime');
	     
	    if($product <> ''){
	        $where[$tq.'order.pid'] = $product;
	    }
	     
	    if($eid <> ''){
	        $where[$tq.'order.eid'] = $eid;
	    }
	     
	    if($ploss <> ''){
	        if($ploss == '1'){
	            //盈利
	            $where[$tq.'order.ploss'] = array('gt',0);
	        }else{
	            //亏损
	            $where[$tq.'order.ploss'] = array('lt',0);
	        }
	    }else{
	        //未选择
	        $where[$tq.'order.ploss'] = array('neq',0);
	    }
	    if($member <> '' && $member <> '0'){
	        $where[$tq.'order.ucode'] = array('LIKE',$member.'%');
	    }
		else {
			if($_SESSION['deptinfo']['type'] != 1){
				$mycode = $_SESSION['deptinfo']['code'];
				$where[$tq.'order.ucode'] = array('LIKE',$mycode.'%');
			}
		}
	    $buytime = array();
	    if($startTime <> ''){
	        array_push($buytime,array('gt',strtotime($startTime)));
	    }
	    if($endTime <> ''){
	        array_push($buytime,array('lt',strtotime($endTime) + 86400));
	    }
	    if(count($buytime) > 0){
	        $where[$tq.'order.selltime'] = $buytime;
	    }
	    $where['ostaus'] = 1;
	    
	    $order = M('order');
	    
	    $subfields = $tq.'order.settlementdate as settlementdate,'.
	   	    $tq.'order.ucode as ucode,'.
	   	    $tq.'order.pid as pid,'.
	   	    $tq.'order.eid as eid,'.
	   	    $tq.'order.ptitle as ptitle,'.
	   	    $tq.'order.endprofit as endprofit,'.
	   	    'case when ploss > 0 then 1 else 0 end as yinli_count,'.
	   	    'case when ploss > 0 then ploss else 0 end as yinli_amount,'.
	   	    'case when ploss < 0 then 1 else 0 end as kuisun_count,'.
	   	    'case when ploss < 0 then ploss else 0 end as kuisun_amount,'.
	   	    'case when ploss = 0 then 1 else 0 end as ping_count,'.
	   	    'case when ploss = 0 then ploss else 0 end as ping_amount,'.
	   	    $tq.'order.fee as fee,'.
	   	    'case when ploss = 0 then 0 else fee end as realfee_amount,'.
	   	    $tq.'order.managefee as managefee';
	    
	    $subquery = $order->field($subfields)->where($where)->buildSql();
        $field = 'settlementdate,
        	ucode,
        	pid,
        	eid,
            ptitle,
            endprofit,
            count(1) as jiaoyi_count,
        	sum(yinli_count) AS yinli_count,
        	sum(yinli_amount) AS yinli_amount,
        	sum(kuisun_count) AS kuisun_count,
        	sum(kuisun_amount) AS kuisun_amount,
        	sum(ping_count) AS ping_count,
        	sum(ping_amount) AS ping_amount,
        	sum(fee) AS fee,
        	sum(realfee_amount) AS realfee_amount,
        	sum(managefee) AS managefee,'.
            $tq.'department.huiyuan_name as huiyuan_name,'.
    	    $tq.'department.daili_name as daili_name,'.
    	    $tq.'department.jigou_name as jigou_name';
        
	    $orders = $order->table($subquery.' T1')->
	    join($tq.'department on '.$tq.'department.code=T1.ucode','left')->field($field)->group('settlementdate,ucode,pid,eid,endprofit')->order('settlementdate')->select();
	    
	    $count = 1;
		$jiaoyi_count_sum = 0;
		$yinli_count_sum = 0;
		$kuisun_count_sum = 0;
		$ping_count_sum = 0;
		$fee_sum = 0;
		$realfee_sum = 0;
		$yingkui_sum = 0;
		$yinliAmount_sum = 0;
		$kuisunAmount_sum = 0;
		$realyingkui_sum = 0;
		$managefee_sum = 0;

		if ($orders == null) {
			$orders = array();
		}
	    foreach ($orders as $order){
	        $count++;
	        $sheet->setCellValue('A'.$count,date('Y-m-d',$order['settlementdate']));
	        $sheet->setCellValue('B'.$count,$order['huiyuan_name']);
	        $sheet->setCellValue('C'.$count,$order['daili_name']);
	        $sheet->setCellValue('D'.$count,$order['jigou_name']);
	        $sheet->setCellValue('E'.$count,$order['ptitle']);
	        if($order['eid'] == 1){
	            $sheet->setCellValue('F'.$count,'点数');
	        }else{
	            $sheet->setCellValue('F'.$count,'时间');
	        }
	        $sheet->setCellValue('G'.$count,$order['endprofit']);
	        $sheet->setCellValue('H'.$count,$order['jiaoyi_count']);
	        $jiaoyi_count_sum += $order['jiaoyi_count'];
	        $yinliCount = $order['yinli_count'];
	        $kuisunCount = $order['kuisun_count'];
	        $yinliAmount = $order['yinli_amount'];
	        $kuisunAmount = $order['kuisun_amount'];
	        
	        $sheet->setCellValue('I'.$count,$yinliCount);
	        $yinli_count_sum += $yinliCount;
	        $sheet->setCellValue('J'.$count,$kuisunCount);
	        $kuisun_count_sum += $kuisunCount;
	        $sheet->setCellValue('K'.$count,$order['ping_count']);
	        $ping_count_sum += $order['ping_count'];
	        $sheet->setCellValue('L'.$count,(number_format($yinliCount/$order['jiaoyi_count'],2) * 100).'%');
	        $sheet->setCellValue('M'.$count,$order['fee']);
	        $fee_sum += $order['fee'];
	        $sheet->setCellValue('N'.$count,$order['realfee_amount']);
	        $realfee_sum += $order['realfee_amount'];
	        $sheet->setCellValue('O'.$count,$yinliAmount + $kuisunAmount);
	        $yingkui_sum += $yinliAmount + $kuisunAmount;
	        $sheet->setCellValue('P'.$count,$yinliAmount);
	        $yinliAmount_sum += $yinliAmount;
	        $sheet->setCellValue('Q'.$count,$kuisunAmount);
	        $kuisunAmount_sum +=$kuisunAmount;
	        $sheet->setCellValue('R'.$count,$yinliAmount + $kuisunAmount);
	        $realyingkui_sum += $yinliAmount + $kuisunAmount;
	        $sheet->setCellValue('S'.$count,$order['managefee']);
	        $managefee_sum += $order['managefee'];
	    }
	    $count++;
	    //合计行
	    $sheet->setCellValue('A'.$count,'合计');
	    $sheet->setCellValue('H'.$count,$jiaoyi_count_sum);
	    $sheet->setCellValue('I'.$count,$yinli_count_sum);
	    $sheet->setCellValue('J'.$count,$kuisun_count_sum);
	    $sheet->setCellValue('K'.$count,$ping_count_sum);
	    $sheet->setCellValue('M'.$count,$fee_sum);
	    $sheet->setCellValue('N'.$count,$realfee_sum);
	    $sheet->setCellValue('O'.$count,$yingkui_sum);
	    $sheet->setCellValue('P'.$count,$yinliAmount_sum);
	    $sheet->setCellValue('Q'.$count,$kuisunAmount_sum);
	    $sheet->setCellValue('R'.$count,$realyingkui_sum);
	    $sheet->setCellValue('S'.$count,$managefee_sum);
	    
	    //设置边框
	    $this->setBorder($sheet,'A1:S'.$count);
	    
	    return '交易汇总查询.xls';
	}
	
	/**
	 * 优惠券查询
	 * @param PHPExcel $excel excel实例
	 * @return string 文件名
	 */
	private function exportyouhuiquan($excel){
	    $tq=C('DB_PREFIX');
	    $sheet = $excel->setActiveSheetIndex(0);
	    $eid = I('post.eid');
	    $sendtype = I('post.sendtype');
	    $ploss = I('post.ploss');
	    $member = I('post.member');
	    $startTime = I('post.startTime');
	    $endTime = I('post.endTime');
	    
	    if($eid <> ''){
	        $where[$tq.'experienceinfo.eid'] = $eid;
	    }
	    if($sendtype == '1'){
	        $where[$tq.'experienceinfo.getway'] = '注册赠送';
	    }elseif($sendtype == '2'){
	        $where[$tq.'experienceinfo.getway'] = '后台发放';
	    }
	   if($ploss <> ''){
	        if($ploss == '1'){
	            //盈利
	            $where[$tq.'order.ploss'] = array('gt',0);
	        }else{
	            //亏损
	            $where[$tq.'order.ploss'] = array('lt',0);
	        }
	    }else{
	        //未选择
	        $where[$tq.'order.ploss'] = array('neq',0);
	    }
		if($member <> ''){
	        $where[$tq.'order.ucode'] = array('LIKE',$member.'%');
	    }
	    $buytime = array();
	    if($startTime <> ''){
	        array_push($buytime,array('gt',strtotime($startTime)));
	    }
	    if($endTime <> ''){
	        array_push($buytime,array('lt',strtotime($endTime) + 86400));
	    }
	    if(count($buytime) > 0){
	        $where[$tq.'order.buytime'] = $buytime;
	    }
	    $where[$tq.'experienceinfo.getstyle'] = 1;//已使用
	    if($_SESSION['deptinfo']['type'] != 1){
	        $mycode = $_SESSION['deptinfo']['code'];
	        $where[$tq.'order.ucode'] = array('LIKE',$mycode.'%');
	    }
	    $field = $tq.'experience.eprice as eprice,'.
	   	    $tq.'experience.limittime as limittime,'.
	   	    $tq.'experienceinfo.exid as exid,'.
	   	    $tq.'experienceinfo.getway as getway,'.
	   	    $tq.'experienceinfo.exgtime as exgtime,'.
	   	    $tq.'experienceinfo.endtime as endtime,'.
	   	    $tq.'userinfo.username as username,'.
	   	    $tq.'order.buytime as buytime,'.
	   	    $tq.'order.orderno as orderno,'.
	   	    $tq.'order.ploss as ploss,'.
	   	    $tq.'order.fee as fee,'.
	   	    $tq.'department.huiyuan_name as huiyuan_name,'.
	   	    $tq.'department.daili_name as daili_name,'.
	   	    $tq.'department.jigou_name as jigou_name';
	   
	   $exprinfo = M('experienceinfo')->
	   join($tq.'experience on '.$tq.'experience.eid='.$tq.'experienceinfo.eid')->
	   join($tq.'order on '.$tq.'order.exid='.$tq.'experienceinfo.exid')->
	   join($tq.'userinfo on '.$tq.'userinfo.uid='.$tq.'order.uid')->
	   join($tq.'department on '.$tq.'department.code='.$tq.'order.ucode')->
	   field($field)->where($where)->order($tq.'experienceinfo.exid desc')->select();
	   
	   $count = 1;
	   foreach ($exprinfo as $expr){
	       $count++;
	       $eprice = '有效期'.$expr['limittime'].'天'.$expr['eprice'].'元券';
	       $sheet->setCellValue('A'.$count,$eprice);
	       $sheet->setCellValue('B'.$count,$expr['exid']);
	       $sheet->setCellValue('C'.$count,$expr['getway']);
	       $sheet->setCellValue('D'.$count,date('Y-m-d',$expr['exgtime']));
	       $sheet->setCellValue('E'.$count,date('Y-m-d',$expr['endtime']));
	       //促销成本核算
	       $cost = 0;
	       if($expr['ploss'] > 0){
	           //客户盈利，无成本
	           $cost = 0;
	       }elseif($expr['ploss'] < 0){
	           $cost = abs($expr['ploss']);
	       }
	       $sheet->setCellValue('F'.$count,date('Y-m-d',$expr['buytime']));
	       $sheet->setCellValue('G'.$count,$cost);
	       $sheet->setCellValue('H'.$count,$expr['username']);
	       $sheet->setCellValue('I'.$count,$expr['orderno'].' ');
	       $sheet->setCellValue('J'.$count,$expr['fee']);
	       if($expr['ploss'] > 0){
	           $sheet->setCellValue('K'.$count,'盈利');
	       }elseif($expr['ploss'] < 0){
	           $sheet->setCellValue('K'.$count,'亏损');
	       }
	       $sheet->setCellValue('L'.$count,$expr['ploss']);
	       $sheet->setCellValue('M'.$count,$expr['huiyuan_name']);
	       $sheet->setCellValue('N'.$count,$expr['daili_name']);
	       $sheet->setCellValue('O'.$count,$expr['jigou_name']);
	   }
	   //设置边框
	   $this->setBorder($sheet,'A1:O'.$count);
	   
	   return '已使用优惠券成本核算.xls';
	}
	
	public function exportExcel(){
	    //判断用户是否登陆
	    $user= A('Admin/User');
	    $user->checklogin();
	    
	    $this->output();
	}
	function output(){
	    $xlsTitle = iconv('utf-8', 'gb2312', 'test');//文件名称
	    $rtn = vendor('Phpexcel.PHPExcel');
	    $objReader = \PHPExcel_IOFactory::createReader ( 'Excel5' );
	    //读入模板
	    $objPHPExcel = $objReader->load (IA_ROOT.'/Application/Admin/data/'.I('post.reporttype').'_tmpl.xls' );
	    $function = array($this,'export'.I('post.reporttype'));
	    $fileName = call_user_func($function,$objPHPExcel);
	    
	    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	    header("Pragma: public");
	    header("Expires: 0");
	    header("Cache-Control:must-revalidate,post-check=0,pre-check=0");
	    header("Content-Type:application/force-download");
	    header("Content-Type:application/vnd.ms-execl");
	    header("Content-Type:application/octet-stream");
	    header("Content-Type:application/download");
	    header('Content-Disposition:attachment;filename="'.$fileName);
	    header("Content-Transfer-Encoding:binary");
	    $objWriter->save('php://output');
	}
	/**
	 * @param String $scope excel：（A2:J100）
	 */
	private function setBorder($sheet,$scope){
	    $styleArray = array(
	        'borders' => array(
	            'allborders' => array(
	                //'style' => PHPExcel_Style_Border::BORDER_THICK,//边框是粗的
	                'style' => \PHPExcel_Style_Border::BORDER_THIN,//细边框
	                //'color' => array('argb' => 'FFFF0000'),
	            ),
	        ),
	    );
	    $sheet->getStyle($scope)->applyFromArray($styleArray);
	}
	
	public function loginlist()
	{
	    //判断用户是否登陆
	    $user= A('Admin/User');
	    $user->checklogin();
	    $tq=C('DB_PREFIX');
	
	    $keyaccess = M('keyaccess');
	    $key = I('key');
	    if($key <> ''){
	        $map['key'] = $key;
	    }else{
	        $key = \KeyAccessEnum::CUSTOMER_LOGIN;
	        $map['key'] = \KeyAccessEnum::CUSTOMER_LOGIN;
	    }
	    $param['key'] = $map['key'];
	    $result = I('result');
	    if($result <> ''){
	        $map['result'] = $result;
	        $param['result'] = $result;
	    }
	    $risk = I('risk');
	    if($risk <> ''){
	        $map['risk'] = $risk;
	        $param['risk'] = $risk;
	    }
	
	    $statTime = I('StartTime');
	    if (! empty($statTime)) {
	        array_push($time, array('gt',strtotime(I('StartTime'))));
	        $param['StartTime']=urlencode(urldecode(I('StartTime')));
	    }
	    $endTime = I('EntTime');
	    if (! empty($endTime)) {
	        array_push($time, array('lt',strtotime(I('EntTime')) + 86400));
	        $param['EntTime']=urlencode(urldecode(I('EntTime')));
	    }
	    if(count($time) > 0){
	        $map['accesstime'] = $time;
	    }
	
	    $count = $keyaccess->where($map)->count();
	    $pagecount = 20;
	    $page = new \Think\Page($count , $pagecount);
	    $page->parameter = $param; //此处的row是数组，为了传递查询条件
	    $page->setConfig('first','首页');
	    $page->setConfig('prev','&#8249;');
	    $page->setConfig('next','&#8250;');
	    $page->setConfig('last','尾页');
	    $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
	    $show = $page->show();
	
	    $field = $tq.'keyaccess.*';
	    if($key == \KeyAccessEnum::CUSTOMER_LOGIN){
	        //客户表
	        $field = $field.','.$tq.'userinfo.username as username';
	        $keyaccess = $keyaccess->join($tq.'userinfo on '.$tq.'userinfo.uid='.$tq.'keyaccess.outid',left);
	    }else if($key == \KeyAccessEnum::UNKNOWN_LOGIN){
	        //什么都不做
	    }else{
	        //系统用户
	        $field = $field.','.$tq.'systemuser.username as username';
	        $keyaccess = $keyaccess->join($tq.'systemuser on '.$tq.'systemuser.id='.$tq.'keyaccess.outid',left);
	    }
	    $loginlist = $keyaccess->field($field)->where($map)->order('accesstime desc')->limit($page->firstRow.','.$page->listRows)->select();
	    $this->assign('loginlist',$loginlist);
	    $this->assign('page',$show);
	    $this->display();
	}
}
