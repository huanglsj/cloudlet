<?php

namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index()
    {
    	header("Content-type: text/html; charset=utf-8");
    	$user= A('Admin/User');
		$user->checklogin();

		$tq=C('DB_PREFIX');
    	$user = D("userinfo");
		$order = D("order");
		$product = D("productinfo");
		$account = D("accountinfo");
		$companyinfo = D("companyinfo");
	//计算当前干预比率
	$control=$companyinfo->field("control")->where("ccode='001'")->find()['control'];
	$nowfee=$order->where(" FROM_UNIXTIME(wp_order.settlementdate, '%Y-%m-%d') = FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d')")->sum("fee");
	$nowploss=$order->where("FROM_UNIXTIME(wp_order.settlementdate, '%Y-%m-%d') = FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d')")->sum("ploss");
	$nowfee=isset($nowfee)?$nowfee:1;
	$nowploss=isset($nowploss)?$nowploss:0;
    	$nowcontrol=sprintf("%.2f", ($nowploss / $nowfee)*100);;
	//访问量

        $Dao = M();
        //总入金
        $inTotalMoney = $Dao->query("select sum(amount) as total from wp_balance where optype in (1,100)");
        $inTotalMoney = $inTotalMoney[0]['total'] ? $inTotalMoney[0]['total'] : 0;

        //总出金
        $outTotalMoney = $Dao->query("select sum(balance) as total from wp_accountinfo");
        $outTotalMoney = $outTotalMoney[0]['total'] ? $outTotalMoney[0]['total'] : 0;

    	//用户数量
    	$userCount = $user->count();

    	//今日订单数量，最近7天订单数量，最近30天交易总金额，订单信息
    	$orderDay = $order->where("date_format(from_UNIXTIME(selltime),'%Y-%m-%d')>='".date('Y-m-d')."'")->count();
		$sevenDay = date('Y-m-d',strtotime("-7 day"));
		$orderCount = $order->where("date_format(from_UNIXTIME(selltime),'%Y-%m-%d')>='".$sevenDay."'")->count();
		$last_day = date('Y-m-d',strtotime("-30 day"));
		$result = $order->where("date_format(from_UNIXTIME(selltime),'%Y-%m-%d')>='".$last_day."'")->select();
		for($i=0;$i<count($result);$i++){
			$total +=$result[$i]['fee'];
		}
		//最近30天交易总金额
		$total = number_format($total);

        $psize = I('psize');
        if (strlen($psize) <= 0) {
            $psize = 10;
        }
        $this->assign("psize", $psize);
		$orders = $order->table('wp_userinfo u,wp_order o')
            ->where('u.uid=o.uid')->field("u.uid as uid,"
                ."u.username as username,"
                ."o.buytime as buytime,"
                ."o.pid as pid,"
                ."o.ptitle as ptitle,"
                ."o.onumber as onumber,"
                ."o.ostyle as ostyle,"
                ."o.eid as eid,"
                ."o.endprofit as endprofit,"
                ."o.ploss as ploss,"
                ."o.buyprice as buyprice,"
                ."o.sellprice as sellprice,"
                ."o.ostaus as ostaus,"
                ."'desu' as realname,"
                ."o.fee as fee,"
                ."o.orderno as orderno")
            ->order('o.oid desc')->limit($psize)->select();

        foreach ($orders as $k => $v) {
            $orders[$k]['realname'] = D('authenticationinfo')
                ->field("realname")
                ->where("useruid=" . $orders[$k]['uid'])
                ->find()['realname'];
        }


		//var_dump($orders[1]['pid']);
		//die;
		//产品信息展示
		$plist = D('product_lastprice')->order('pid desc')->select();

        $this->assign('outTotalMoney',$outTotalMoney);
        $this->assign('inTotalMoney',$inTotalMoney);
		$this->assign('orderDay',$orderDay);
		$this->assign('nowcontrol', $nowcontrol);
		$this->assign('control', $control);
    	$this->assign('userCount',$userCount);
		$this->assign('orderCount',$orderCount);
		$this->assign('total',$total);
		$this->assign('orders',$orders);
		$this->assign('plist',$plist);
		$this->display();
	}
	public function price(){
		 $diancha=$this->selectcid(1);
         $source=file_get_contents("xh/you.txt");
         $msg = explode(',',$source);
         $myprice[0] = round(str_replace('price:', '',str_replace('"','',$msg[1])));//最新
         $myprice[0] =$myprice[0]*$diancha['myat']*$diancha['myatjia'];
         return $myprice[0];
    }
	public function byprice(){
		 $diancha=$this->selectcid(2);
         $source=file_get_contents("xh/baiyin.txt");
         $msg = explode(',',$source);
         $myprice[0] = round(str_replace('price:', '',str_replace('"','',$msg[1])));//最新
          $myprice[0] =$myprice[0]*$diancha['myat']*$diancha['myatjia'];
         return $myprice[0];
    }
	public function toprice(){
		 $diancha=$this->selectcid(3);
         $source=file_get_contents("xh/tong.txt");
         $msg = explode(',',$source);
         $myprice[0] = round(str_replace('price:', '',str_replace('"','',$msg[1])));//最新
          $myprice[0] =$myprice[0]*$diancha['myat']*$diancha['myatjia'];
         return $myprice[0];
    }
}