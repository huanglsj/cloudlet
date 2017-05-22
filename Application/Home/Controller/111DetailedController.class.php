<?php
// 明细表，包括收支明细和交易明细
namespace Home\Controller;
vendor('Jugui.MarketStatusJudge');
require_once APP_PATH .'Common/Enum/BalanceOpTypeEnum.php';

class DetailedController extends CommonController {

    //交易明细列表
    public function dtrading(){
        $uid=$_SESSION['uid'];
        //根据传来的时间查询对应的数据(只传递月份和时间便可以)
        $mytoday=I('get.today');
        // 判断是否是点击月份左右的按钮
        if($mytoday){
             // 判断是上个月的
             if(I('get.no')==1) {
                 $timestamp=strtotime($mytoday);
                 $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)-1).'-01'));
                 $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
             //判断如果是本月的，则下个月数据不存在。
             }else if(I('get.no')==2&&$mytoday==date('Y-m-01', strtotime(date("Y-m-d")))){
                 $firstday=date('Y-m-01', strtotime(date("Y-m-d")));
                 $lastday=date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
             //判断是下个月的
             }else{
                $timestamp=strtotime($mytoday);
                 $arr=getdate($timestamp);
                 if($arr['mon'] == 12){
                    $year=$arr['year'] +1;
                    $month=$arr['mon'] -11;
                    $firstday=$year.'-0'.$month.'-01';
                    $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
                 }else{
                    $firstday=date('Y-m-01',strtotime(date('Y',$timestamp).'-'.(date('m',$timestamp)+1).'-01'));
                    $lastday=date('Y-m-d',strtotime("$firstday +1 month -1 day"));
                 }
             }
             
             $begintime=$firstday;
             $endtime=$lastday;
        }else{
            $begintime=date('Y-m-01', strtotime(date("Y-m-d")));
            $endtime=date('Y-m-d', strtotime("$BeginDate +1 month -1 day"));
         }
        $tq=C('DB_PREFIX');
        $last_day1 =  strtotime(date('Y-m-01', strtotime($begintime)));
        $last_day2 =  strtotime(date('Y-m-d', strtotime($endtime)));
        $where = $last_day1.'<='.$tq.'order.selltime and '.$last_day2.'>='.$tq.'order.selltime';
        //查询多条记录
        $count = M('order')->join($tq.'productinfo on '.$tq.'order.pid='.$tq.'productinfo.pid')->where($tq.'order.uid='.$uid.' and '.$tq.'order.ostaus=1 and '.$where)->count();
        $pagecount = 50;
        $page = new \Think\Page($count , $pagecount);
        $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%  第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show = $page->show();
        $list = M('order')->join($tq.'productinfo on '.$tq.'order.pid='.$tq.'productinfo.pid')->where($tq.'order.uid='.$uid.' and '.$tq.'order.ostaus=1 and '.$where)->order($tq.'order.oid desc' )->limit($page->firstRow.','.$page->listRows)->select();   
        //计算出一段时间的盈亏beg

        //计算总收益
        $trading['money']=M('order')->where($tq.'order.uid='.$uid.' and '.$tq.'order.ostaus=1 and '.$where)->sum('ploss');
        //end
        //总单数
        $sumcount=M('order')->where($tq.'order.uid='.$uid.' and '.$tq.'order.ostaus=1 and '.$where)->count();
        $trading['count']=$sumcount;
        //总手数
        $sumonumber=M('order')->where($tq.'order.uid='.$uid.' and '.$tq.'order.ostaus=1 and '.$where)->sum('onumber');
        $trading['onumber']=$sumonumber;
        //时间
        $trading['time']=$last_day1;
     
        $this->assign('trading',$trading);
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();//显示模板
    }
    //交易详情页
    public function tradingid(){
        $tq=C('DB_PREFIX');
        $order=M('order')->join($tq.'productinfo on '.$tq.'order.pid='.$tq.'productinfo.pid')->join($tq.'product_lastprice on '.$tq.'productinfo.pid='.$tq.'product_lastprice.pid')->where($tq.'order.oid='.I('oid'))->find();
        $lastprice = M('product_lastprice')->field('ask')->where('pid='.$order['pid'])->find();
        if($order['ostaus'] == '1'){
            $this->assign('status','平仓');
        }else{
            $this->assign('status','建仓');
        }
        $this->assign('order',$order);
        $this->assign('lastprice',$lastprice['ask']);
        $this->display();
    }
     //收支明细列表(显示日志记录)
    public function drevenue(){
        $uid=$_SESSION['uid'];
        $count =M('journal')->where('uid='.$uid)->count();
        $pagecount = 5;
        $page = new \Think\Page($count , $pagecount);
        $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first','首页');
        $page->setConfig('prev','上一页');
        $page->setConfig('next','下一页');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END%  第 '.I('p',1).' 页/共 %TOTAL_PAGE% 页 ( '.$pagecount.' 条/页 共 %TOTAL_ROW% 条)');
        $show = $page->show();
        $list = M('journal')->where('uid='.$uid)->order('jtime desc' )->limit($page->firstRow.','.$page->listRows)->select();   
        $this->assign('list',$list);
        $this->assign('page',$show);
        $this->display();
    }
     //收支详细页
    public function revenueid(){
        $jno=I('jno');
        $order=M('journal')->where('jno='.$jno)->find();
        $this->assign('order',$order);
        $this->display();
    }
    //购买商品，获取信息，生成订单表。
    public function addorder(){
        $data_p=I('post.');
        // var_dump($data_p);
        // die();
        $data['uid']=$_SESSION['uid'];
        $data['pid']=$data_p['pid'];
        $data['eid']=$data_p['moshi'];
        if ($data_p['fx']=='涨') {
            $data['ostyle']=0;      
        }else if($data_p['fx']=='跌'){
            $data['ostyle']=1;
        }
        $data['buytime']=time();
        $isopen = \MarketStatusJudge::isopen($data['pid']);
        if($isopen == false){
            $this->assign('msg','当前处于休市期，不能下单！');
            die("<script>alert('休市期间不能下单！');history.go(-1);</script>");
			return;
        }
        if ($data['eid']==1) {
            $data['selltime']='';
        }else if($data['eid']==2){
            $data['selltime']=time()+$data_p['dianshu']*60;
            $isopen = \MarketStatusJudge::isopen($data['pid'],$data['selltime']);
            if($isopen == false){
                $this->assign('msg','很快就要休市了，请您选择一个较短时间周期下单，速战速决吧！');
                //$this->display('Index/trade/pid/'.$data['pid']);
				die("<script>alert('很快就要休市了，请您选择一个较短时间周期下单，速战速决吧！');history.go(-1);</script>");
            }
        }
        $data['ostaus']='0';
        $data['buyprice']=$data_p['rcj'];
        //买入点数
        $data['endprofit']=$data_p['dianshu'];
        $data['endloss']=$data_p['sybl'];
        if($data_p['isquan'] == 0){
            $data['fee']=$data_p['pay'];
            $pay=$data_p['pay'];
        }else{
            $data['fee']=$data_p['eprice'];
            $pay=$data_p['eprice'];
        }
        $data['orderno']=date('YmdHis').rand(1111,9999);
        $data['ptitle']=$data_p['ptitle'];
        //获取系统配置，校验最大建仓金额
        $webconfig = D('webconfig')->cache(true,60)->find();
        $minorderamount = $webconfig['minorderamount'];
        $maxorderamount = $webconfig['maxorderamount'];
        $maxuseramount = $webconfig['maxuseramount'];
        
        //判断余额
        $userye=M('accountinfo')->where(array('uid'=>$_SESSION['uid']))->field('balance,frozen')->find();
        if ($userye['balance'] - $userye['frozen'] < $pay) {
            die("<script>alert('余额不足，请充值！');location.href='".U('/Home/User/deposit')."';</script>");
        }
        
        //判断会员权益
        $companyinfo = D('Admin/companyinfo');
        $userinfo = M('userinfo')->where('uid='.$data['uid'])->find();
        if($userinfo['ustatus'] == 3){
            die("<script>alert('您的账户异常，请稍后重试！');history.go(-1);</script>");
        }
        $company = $companyinfo->where('cid='.$userinfo['companyid'])->find();
        //查询所属会员单位
        $huiyuancode = substr($userinfo['ucode'], 0,3);
        $huiyuaninfo = $companyinfo->where('ccode='.$huiyuancode)->find();
        //计算本次应该扣除的管理费
        $managefee = number_format($pay * $huiyuaninfo['managefeerate'] / 100,2);
        $data['managefee']=$managefee;
        $data['ucode']=$company['ccode'];
        if($huiyuaninfo['cashdeposit'] < 50000){
//            die("<script>alert('交易机构目前繁忙，请稍后再试！');history.go(-1);</script>");
             die("<script>alert('所属交易机构保证金缴纳不足，请联系您的邀请人补充资金后再交易！');history.go(-1);</script>");
        }
        if($huiyuaninfo['equity'] - $managefee < $huiyuaninfo['cashdeposit'] * 0.6){
            //交易后的权益少于当初缴纳保证金的60%时，不允许交易
//            die("<script>alert('交易机构目前繁忙，请稍后再试！');history.go(-1);</script>");
             die("<script>alert('所属交易机构剩余保证金不足，请联系您的邀请人补充资金后再交易！');history.go(-1);</script>");
        }
        
        if($data_p['isquan'] == 0 && $pay < $minorderamount){
            die("<script>alert('低于最小单笔建仓金额！');history.go(-1);</script>");
        }
        if($pay > $maxorderamount){
            die("<script>alert('超过最大单笔建仓金额！');history.go(-1);</script>");
        }
        
        //获取用户目前持仓总额
        $keepamount = D('order')->where('uid='.$data['uid'].' and '.'ostaus=0')->sum('fee');
        if($keepamount + $pay > $maxuseramount){
            die("<script>alert('超过最大持仓金额！');history.go(-1);</script>");
        }
        if($data_p['isquan'] == 0){
            //没有使用券
        	//$result = M('accountinfo')->where(array('uid'=>$_SESSION['uid']))->setDec('balance',$pay);
			$result = true;
        }else{
            //使用了券，扣减券
            $result = M('experienceinfo')->where('exid='.$data_p['exid'])->setField('getstyle',2);
            $data['exid']=$data_p['exid'];
        }
        if ($result) {
            $ortn = M('order')->add($data);
            if($ortn){
                //修改会员权益，添加会员权益流水
                $result = $companyinfo->updateEquity($huiyuaninfo['cid'], $managefee * -1,'1',$data['uid'],$ortn,$userinfo['deptid']);
                if($result['result']){
                    //设置平仓数据
                    $pcdata = array();
                    $pcdata['oid'] = $ortn;
                    $pcdata['pid'] = $data_p['pid'];
                    if($data['eid'] == 1){
                        //点数模式
                        $pcdata['close_price_low'] = $data_p['rcj'] - $data_p['dianshu'];
                        $pcdata['close_price_high'] = $data_p['rcj'] + $data_p['dianshu'];
                        $pcdata['order_type'] = 1;
                    }else{
                        //时间模式
                        $pcdata['close_time'] = time() + $data_p['dianshu'] * 60;
                        $pcdata['order_type'] = 2;
                    }
                    if(!M('waitcloseorder')->add($pcdata)){
                        die("<script>alert('买入失败！');history.go(-1);</script>");
                    }
                    if($data_p['isquan'] == 0){
                    	$result = D("Home/Account")->updateBalance($_SESSION['uid'], \BalanceOpTypeEnum::JC, $_SESSION['uid'],
                    							-$pay, $ortn, $data['orderno']);
                        if($result === false){
                            die("<script>alert('买入失败！');history.go(-1);</script>");
                        }
                    }
                    //$this->redirect("/Home/Index/trade/pid/$data['pid']");
                    die("<script>alert('买入成功！');history.go(-1);</script>");
                }else{
                    die("<script>alert('".$result['msg']."');history.go(-1);</script>");
                }
            }else{
                die("<script>alert('买入失败！');history.go(-1);</script>");
            }
        }else{
            die("<script>alert('买入失败！');history.go(-1);</script>");
        }

    }
    public function checkMoney(){
        $pay=I('post.pay');
        $pid=I('post.mypid');
        $rcj=I('post.rcj');
        $uid=$_SESSION['uid'];
        
        //获取系统配置，校验最大建仓金额
        $webconfig = D('webconfig')->cache(true,60)->find();
        $maxorderamount = $webconfig['maxorderamount'];
        $maxuseramount = $webconfig['maxuseramount'];
        
        if($pay > $maxorderamount){
            $this->ajaxReturn('超过系统设置最大单笔建仓金额！'); 
        }
        
        //获取用户目前持仓总额
        $keepamount = D('order')->where('uid='.$uid.' and '.'ostaus=0')->sum('fee');
        if($keepamount + $pay > $maxuseramount){
            $this->ajaxReturn('超过系统设置用户最大持仓金额！');
        }
        
        $userinfo = D('userinfo')->where('uid='.$uid)->find();
        $productinfo = D('product_lastprice')->where('pid='.$pid)->find();
        if(abs($productinfo['ask'] - $rcj) > $userinfo['diancha']){
            $this->ajaxReturn('超过允许偏离的最大点差，请重新确认行情后下单！');
        }
        $this->ajaxReturn('');
    }
    //判断是否购买过此类商品
    public function judgment(){ 

        //商品id
        $mypid=I('post.mypid');
        $uid=$_SESSION['uid'];
        $porder = M('order')->where('uid='.$uid.' and pid='.$mypid.' and ostaus=0')->count();
        if($porder)
        {
            $this->ajaxReturn(99); 
            //echo "<script>alert('亲，您已经购买了此产品')</script>";
        }else{
            $this->ajaxReturn(100);  
        }
    }
    //查询订单信息(接收修改后的订单，或者直接平仓，或者购买完后平仓，3中情况)
    public function orderid(){
        $tq=C('DB_PREFIX');
        $orderid=I('orderid');
        $order=M('order')->join($tq.'productinfo on '.$tq.'order.pid='.$tq.'productinfo.pid')
        ->join($tq.'product_lastprice on '.$tq.'productinfo.pid='.$tq.'product_lastprice.pid')->where('oid='.$orderid)->find();
        $shijian=$order['selltime']-$order['buytime'];
        if ($shijian<=60) {
            $order['shijian']=1;
        }elseif ($shijian<=300) {
            $order['shijian']=5;
        }else{
            $order['shijian']=15;
        }
        $this->assign('order',$order);
        $this->display();
    }
    //修改订单的止盈和止亏
    public function edityk(){
        $order=M('order');
        $order->oid=I('post.oid');
        $order->endprofit=I('post.zy');
        $order->endloss=I('post.zk');
        $order->save();
        $this->redirect('Detailed/orderid', array('orderid' =>I('post.oid')));
    }
    //获取随时的动态值，计算盈亏金额和盈余数据
    public function orderxq(){
        $tq=C('DB_PREFIX');
        $youjia=I('youjia');
        $cid = I('cid');
        if($youjia!=0){
            $order=M('order')->join($tq.'productinfo on '.$tq.'order.pid='.$tq.'productinfo.pid')->where($tq.'order.oid='.I('oid'))->find();
            $orderid=$order;
            //建仓金额
            if ($order['eid']==0) {
                 $orderid['jc']=  round($order['uprice']*$order['onumber'],1);
                //判断是买张还是买跌。0涨，1跌
                if ( $orderid['ostyle']==0) {
                     //盈亏资金
                     if($cid==1){
                        $orderid['ykzj']=round(($youjia-$order['buyprice'])*$order['onumber']*1*$order['wave'],2);  
                     }else{
                        $orderid['ykzj']=round(($youjia-$order['buyprice'])*$order['onumber']*$order['wave'],2);
                     }
                     //本单盈余
                     $orderid['bdyy']=round($orderid['jc']+$orderid['ykzj'],1);
                     //盈亏百分百
                     $orderid['ykbfb']=$orderid['ykzj']/ $orderid['jc']*1; 
                }else{
                    //盈亏资金
                    if($cid==1){
                        $orderid['ykzj']=round(($order['buyprice']-$youjia)*$order['onumber']*1*$order['wave'],2);  
                    }else{
                        $orderid['ykzj']=round(($order['buyprice']-$youjia)*$order['onumber']*$order['wave'],2);
                    }
                     //本单盈余
                     $orderid['bdyy']=round($orderid['jc']+$orderid['ykzj'],1);
                     //盈亏百分百
                     $orderid['ykbfb']=$orderid['ykzj']/ $orderid['jc'];  
                }
            }else{
                 $orderid['jc']=0;
                //判断是买张还是买跌。0涨，1跌
                if ( $orderid['ostyle']==0) {
                     //盈亏资金
                    if($cid==1){
                        $orderid['ykzj']=round(($youjia-$order['buyprice'])*$order['onumber']*1*$order['wave'],2);  
                    }else{
                        $orderid['ykzj']=round(($youjia-$order['buyprice'])*$order['onumber']*$order['wave'],2);
                    }
                     //本单盈余
                     $orderid['bdyy']=round($orderid['jc']+$orderid['ykzj'],1);
                     //盈亏百分百
                     $orderid['ykbfb']=$orderid['ykzj']/ $orderid['jc']*1; 
                     if($orderid['ykzj']<0){
                        $orderid['ykzj']=0;
                        $orderid['bdyy']=0; 
                     } 
                }else{
                    //盈亏资金
                    if($cid==1){
                        $orderid['ykzj']=round(($order['buyprice']-$youjia)*$order['onumber']*1*$order['wave'],2);  
                    }else{
                        $orderid['ykzj']=round(($order['buyprice']-$youjia)*$order['onumber']*$order['wave'],2);
                    }
                     //本单盈余
                     $orderid['bdyy']=round($orderid['jc']+$orderid['ykzj'],1);
                     //盈亏百分百
                     $orderid['ykbfb']=$orderid['ykzj']/ $orderid['jc']*1; 
                     if ($orderid['ykzj']<0) {
                         $orderid['ykzj']=0;
                         $orderid['bdyy']=0; 
                     } 
                }
            }
            
            $this->ajaxReturn($orderid);
        }
    }

    //平仓
    public function updateore(){
         //获取账户余额
        $uid=$_SESSION['uid'];
        $users = D('userinfo');
        $username = $_SESSION['husername'];
        $user=M('accountinfo')->where('uid='.$uid)->find();
        //获取传递过来的值
        $oid=I('post.oid');
        //现在油价
        $youjia=I('post.youjia');
        if($youjia<=0)
        {  
                
        }
        else{
        //结余的金额，需要给当前用户的账户添加
        $bdyy=I('post.bdyy');
         //建仓金额
        $jiancj=I('post.jiancj'); 
        //盈亏资金
        $ykzj=I('post.ykzj'); 
        //产品单价
        $uprice = I('post.uprice');
        //先修改订单信息，返回成功信息后修改账户余额和添加日志记录
        $orderno= $this->build_order_no();
        $tq=C('DB_PREFIX');
        $myorder=M('order')->join($tq.'productinfo on '.$tq.'order.pid='.$tq.'productinfo.pid')->where($tq.'order.oid='.$oid)->find();
        $order=M('order');
        $order->selltime=date(time());
        $order->ostaus=1;
        $order->sellprice=$youjia;
        //盈亏资金
        $order->ploss=$ykzj;
        //手续费
        $fee = $myorder['feeprice']*$myorder['onumber'];
        $order->fee=$fee;
//      //佣金
//      $order->commission=$youjia-$myorder['buyprice']-$fee;
//      //盈亏
//      $order->ploss=$youjia-$myorder['buyprice'];
        $msg= $order->save();               
        if ($msg) {
            $myprice=M('accountinfo')->where('uid='.$uid)->find();
            $acco= M('accountinfo');
            $acco->uid=$uid;
            $acco->balance=$myprice['balance']+$bdyy;
            $acco->save();
            //根据商品id查询商品
            $goods=M('productinfo')->where('pid='.$myorder['pid'])->find();
            //用户亏损了，返点
            if($ykzj<0){
                //查询该用户级别
                $thisuser = $users->field('otype,oid')->where('uid='.$uid)->find();
                //返佣记录
                $otype = $thisuser['otype'];            //用户类型
                $ouid = $thisuser['oid'];               //上级id
                //如果有oid，是分销用户
                if($ouid!=""){
                    if($otype==0){
                        //此id用户是普通客户，oid为代理用户id
                        $otype = "客户";
                        //查会员单位返点比例
                        $agent = $users->field('oid,rebate,feerebate,otype,username')->where('uid='.$ouid)->find();
                        $agent_user=M('accountinfo')->where('uid='.$ouid)->find();
                        //判断上级用户，如果是代理商
                        if($agent['otype']==1){
                            $agent_rebate = $agent['rebate'];               //代理盈亏返点
                            $agent_feerebate = $agent['feerebate'];         //代理手续费返点
                            $menber_id = $agent['oid'];                     //用户的上级id
                            if($menber_id!=""){
                                $menber = $users->field('rebate,feerebate,username')->where('uid='.$menber_id)->find();
                                $menber_rebate = $menber['rebate'];                 //会员盈亏返点
                                $menber_feerebate = $agent['feerebate'];            //会员手续费返点
                                $newykzj = abs($ykzj);
                                $menber_ploss = $newykzj*$menber_rebate/100;            //会员盈亏反金
                                $menber_feeploss = $fee*$menber_feerebate/100;      //会员手续费反金
                                $agent_ploss = $menber_ploss*$agent_rebate/100;                 //代理盈亏反金
                                $agent_feeploss = $menber_feeploss*$agent_feerebate/100;        //代理手续费反金
                                $menber_user=M('accountinfo')->where('uid='.$menber_id)->find();
                                //写两条记录，一条代理，一条会员
                                $distribution = M('journal');
                                $disj['jno']=$orderno;                                      //订单号
                                $disj['uid'] = $ouid;                                       //用户id
                                $disj['jtype'] = '返点';                                      //类型
                                $disj['jtime'] = date(time());                              //操作时间
                                $disj['balance'] = $agent_user['balance']+$agent_ploss+$agent_feeploss;         //账户余额
                                $disj['jfee'] = $agent_feeploss;                            //手续费反金
                                $disj['jploss'] = $agent_ploss;                             //盈亏反金
                                $disj['jaccess'] = $agent_feeploss+$agent_ploss;            //出入金额
                                $disj['jusername'] = $agent['username'];                    //用户名
                                $disj['oid'] = $oid;                                    //订单id
                                $disj['explain'] = '代理反金';                                  //操作标记
                                $disj['remarks'] = $goods['ptitle'];                        //产品名称  
                                $disj['number'] = $myorder['onumber'];                      //数量    
                                $disj['jostyle'] = $myorder['ostyle'];                      //涨、跌
                                $disj['jbuyprice'] = $myorder['buyprice'];                  //入仓价
                                $disj['jsellprice'] = $youjia;                              //平仓价
                                $distribution->add($disj);
                                
                                //写入会员记录
                                $distribution = M('journal');
                                $mdisj['jno']=$orderno;                                     //订单号
                                $mdisj['uid'] = $ouid;                                      //用户id
                                $mdisj['jtype'] = '返点';                                     //类型
                                $mdisj['jtime'] = date(time());                             //操作时间
                                $mdisj['balance'] = $menber_user['balance']+$menber_ploss+$menber_feeploss;         //账户余额
                                $mdisj['jfee'] = $menber_feeploss;                          //手续费反金
                                $mdisj['jploss'] = $menber_ploss;                           //盈亏反金
                                $mdisj['jaccess'] = $menber_feeploss+$menber_ploss;         //出入金额
                                $mdisj['jusername'] = $menber['username'];                  //用户名
                                $mdisj['oid'] = $oid;                                   //订单id
                                $mdisj['explain'] = '会员反金';                             //操作标记
                                $mdisj['remarks'] = $goods['ptitle'];                       //产品名称  
                                $mdisj['number'] = $myorder['onumber'];                     //数量    
                                $mdisj['jostyle'] = $myorder['ostyle'];                     //涨、跌
                                $mdisj['jbuyprice'] = $myorder['buyprice'];                 //入仓价
                                $mdisj['jsellprice'] = $youjia;                             //平仓价
                                $distribution->add($mdisj);
                            }
                        }else if($agent['otype']==2){
                            //如果上级是会员
                            $menber_rebate = $agent['rebate'];              //代理盈亏返点
                            $menber_feerebate = $agent['feerebate'];        //代理手续费返点
                            $menber_ploss = $newykzj*$menber_rebate/100;            //会员盈亏反金
                            $menber_feeploss = $fee*$menber_feerebate/100;      //会员手续费反金
                            //echo $ykzj*$menber_rebate/100;
                            //echo $menber_rebate.'----------------';
                            //写入会员记录
                            $distribution = M('journal');
                            $mdisj['jno']=$orderno;                                     //订单号
                            $mdisj['uid'] = $ouid;                                      //用户id
                            $mdisj['jtype'] = '返点';                                     //类型
                            $mdisj['jtime'] = date(time());                             //操作时间
                            $mdisj['balance'] = $user['balance']+$menber_ploss+$menber_feeploss;            //账户余额
                            $mdisj['jfee'] = $menber_feeploss;                          //手续费反金
                            $mdisj['jploss'] = $menber_ploss;                           //盈亏反金
                            $mdisj['jaccess'] = $menber_feeploss+$menber_ploss;         //出入金额
                            $mdisj['jusername'] = $agent['username'];                   //用户名
                            $mdisj['oid'] = $oid;                                   //订单id
                            $mdisj['explain'] = '会员反金';                             //操作标记
                            $mdisj['remarks'] = $goods['ptitle'];                       //产品名称  
                            $mdisj['number'] = $myorder['onumber'];                     //数量    
                            $mdisj['jostyle'] = $myorder['ostyle'];                     //涨、跌
                            $mdisj['jbuyprice'] = $myorder['buyprice'];                 //入仓价
                            $mdisj['jsellprice'] = $youjia;                             //平仓价
                            $distribution->add($mdisj);
                        }else{
                            //上级是平台
                            
                        }
                    }else if($otype==1){
                        //此id用户是代理
                        $menber = $users->field('oid,rebate,feerebate,otype')->where('uid='.$ouid)->find();
                        if($menber['oid']!=""){
                            $menber_rebate = $menber['rebate'];             //会员盈亏返点
                            $menber_feerebate = $menber['feerebate'];       //会员手续费返点
                            $menber_ploss = $newykzj*$menber_rebate/100;            //会员盈亏反金
                            $menber_feeploss = $fee*$menber_feerebate/100;      //会员手续费反金
                            //写入会员记录
                            $distribution = M('journal');
                            $mdisj['jno']=$orderno;                                     //订单号
                            $mdisj['uid'] = $ouid;                                      //用户id
                            $mdisj['jtype'] = '返点';                                     //类型
                            $mdisj['jtime'] = date(time());                             //操作时间
                            $mdisj['balance'] = $user['balance']+$menber_ploss+$menber_feeploss;            //账户余额
                            $mdisj['jfee'] = $menber_feeploss;                          //手续费反金
                            $mdisj['jploss'] = $menber_ploss;                           //盈亏反金
                            $mdisj['jaccess'] = $menber_feeploss+$menber_ploss;         //出入金额
                            $mdisj['jusername'] = $menber['username'];                  //用户名
                            $mdisj['oid'] = $oid;                                   //订单id
                            $mdisj['explain'] = '会员反金';                                 //操作标记
                            $mdisj['remarks'] = $goods['ptitle'];                       //产品名称  
                            $mdisj['number'] = $myorder['onumber'];                     //数量    
                            $mdisj['jostyle'] = $myorder['ostyle'];                     //涨、跌
                            $mdisj['jbuyprice'] = $myorder['buyprice'];                 //入仓价
                            $mdisj['jsellprice'] = $youjia;                             //平仓价
                            $distribution->add($mdisj);
                        }
                    }else if($otype==2){
                        //此id用户是会员
                        
                    }               
                }else{
                    //不是分销用户
                    
                }
            }
            //添加平仓日志表
            //随机生成订单号
            $myjournal=M('journal');
            $journal['jno']=$orderno;                                       //订单号
            $journal['uid'] = $uid;                                         //用户id
            $journal['jtype'] = '平仓';                                       //类型    
            $journal['jtime'] = date(time());                               //操作时间
            $journal['jincome'] = $bdyy;                                    //收支金额【要予以删除】
            $journal['number'] = $myorder['onumber'];                       //数量            
            $journal['remarks'] = $goods['ptitle'];                         //产品名称  
            $journal['balance'] = $user['balance']+$bdyy;                   //账户余额  
            if ($bdyy>$jiancj){
                  $journal['jstate']=1;                                     //盈利还是亏损
            }else{
                  $journal['jstate']=0;
            }           
            $journal['jusername'] = $username;                              //用户名
            $journal['jostyle'] = $myorder['ostyle'];                       //涨、跌
            $journal['juprice'] = $uprice;                                  //单价
            $journal['jfee'] = $fee;                                        //手续费
            $journal['jbuyprice'] = $myorder['buyprice'];                   //入仓价
            $journal['jsellprice'] = $youjia;                               //平仓价
            $journal['jaccess'] = $bdyy;                                    //出入金额
            $journal['jploss'] = $ykzj;                                     //出入金额
            $journal['oid'] = $oid;                                         //改订单流水的订单id
            $journal['explain'] = $otype.'平仓';
            $myjournal->add($journal);
            $order->where('oid='.$oid)->setField('commission',$journal['balance']);
        }else{
           $msg="平仓失败，稍后平仓";
        }

        $this->ajaxReturn($msg); 
        }        
    }

    //随机生成订单编号
    function build_order_no(){
        return date(time()).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 3);
    }
        
    public function klinedata(){
        $this->display();
    }
    
    public function getKchartData(){
        //获取时间间隔
        $interval = I('get.interval');
        $pid = I('get.pid');
        
        if($interval == 0){
            //分时图，获取120个点
            $subQuery = M('realtimeprice')->where('pid='.$pid.' and isopen=1')->order('dealtime desc')->field("newprice as price,openPrice as open,lastClosePrice as close,lowprice as lowest,highprice as highest,dealtime*1000 as time,FROM_UNIXTIME(dealtime, '%Y-%m-%d %H:%i:%s') as fulltime")->limit('0,130')->cache(true,0.5)->select(false);
        }else{
            //K线图，获取30个点
            $subQuery = M('k'.$interval.'price')->where('pid='.$pid.' and isopen=1')->order('updatetime desc')->field("open,close,low as lowest,high as highest,updatetime*1000 as time,FROM_UNIXTIME(updatetime, '%Y-%m-%d %H:%i:%s') as fulltime")->limit('0,45')->cache(true,0.5)->select(false);
        }
        $model = new \Think\Model();
        $data = $model->table($subQuery.' a')->order('time asc')->select();
        $this->ajaxReturn($data);
    }
    
    public function getNewData(){
        //获取实时数据
        //获取时间间隔
        $interval = I('get.interval');
        $pid = I('get.pid');
        
        //获取最大日期
        if(isset($_GET["curtime"])){
            $curtime = strtotime($_GET["curtime"]);
        }
        else{
            $this->ajaxReturn();
        }
        
        if($interval == 0){
            $data = M('realtimeprice')->where('pid='.$pid.' and dealtime > '.$curtime.' and isopen=1')->order('dealtime desc')->field("newprice as price,openPrice as open,lastClosePrice as close,lowprice as lowest,highprice as highest,dealtime*1000 as time,FROM_UNIXTIME(dealtime, '%Y-%m-%d %H:%i:%s') as fulltime")->limit('0,1')->cache(true,0.5)->select();
        }else{
            $data = M('k'.$interval.'price')->where('pid='.$pid.' and updatetime > '.$curtime.' and isopen=1')->order('updatetime desc')->field("open,close,low as lowest,high as highest,updatetime*1000 as time")->limit('0,1')->cache(true,0.5)->select();
        }
        $this->ajaxReturn($data);
    }
    
}
