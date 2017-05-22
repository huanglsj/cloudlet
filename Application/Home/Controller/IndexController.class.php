<?php
namespace Home\Controller;
$rtn = vendor('Jugui.MarketStatusJudge');
class IndexController extends CommonController {

    public function index(){
        $tq=C('DB_PREFIX');
        //产品及价格
		
        $source=M("product_lastprice")->join($tq.'productinfo on '.$tq.'product_lastprice.pid='.$tq.'productinfo.pid and '.$tq.'productinfo.status=1')->field($tq.'product_lastprice.pid as pid,'.$tq.'product_lastprice.code as code'.',displayname,ask,eidtime,tradeperiod')->order("{$tq}productinfo.sort asc")->select();
        if(isset($_SESSION['uid'])) {
            
            $uid=$_SESSION['uid'];
            //账号余额
            $userimg=M('userinfo')->where('uid='.$uid)->find();
            $price=M('accountinfo')->where('uid='.$uid)->find();
            //账号体验卷数
            $endtime=date(time());
            $exper=M('experienceinfo')->join($tq.'experience on '.$tq.'experienceinfo.eid='.$tq.'experience.eid')->where($tq.'experienceinfo.uid='.$uid.' and '.$endtime.' < '.$tq.'experienceinfo.endtime and '.$tq.'experienceinfo.getstyle=0')->count();     
            $user['price']=round($price['balance'],2);
            $user['exper']=$exper;
            $user['portrait']=$userimg['portrait'];
            $user['readrisk']=$userimg['readrisk'];
            $this->assign('user',$user);
        }
        $uorder=$this->userorder();
        $news=$this->information();
        $focus=$this->focus();
        $notices=$this->notice();
        foreach ($source as $key=>$sc){
            $source[$key]['isopen'] = \MarketStatusJudge::isopen($sc['pid']);
            if($source[$key]['isopen']){
                $source[$key]['eidtime'] = date('H:i:s',$source[$key]['eidtime']);
            }else{
                $source[$key]['eidtime'] = date('H:i:s',$source[$key]['eidtime']);
                //$source[$key]['eidtime'] = '已休市';
            }
        }
		
        $content=M('content')->select();
        $this->assign('source',$source);
        $this->assign('contents',$content);
        $this->assign('nolist',$uorder);
        $this->assign('news',$news);
        $this->assign('focus',$focus);
        $this->assign('notices',$notices);
		$this->display();
    }
    public function trade(){
        if(isset($_SESSION['uid'])) {
            $tq=C('DB_PREFIX');
            $uid=$_SESSION['uid'];
            //账号余额
            $userimg=M('userinfo')->where('uid='.$uid)->find();
            $price=M('accountinfo')->where('uid='.$uid)->find();
            //账号体验卷数
            $endtime=date(time());
            $exper=M('experienceinfo')->join($tq.'experience on '.$tq.'experienceinfo.eid='.$tq.'experience.eid')->where($tq.'experienceinfo.uid='.$uid.' and '.$endtime.' < '.$tq.'experienceinfo.endtime and '.$tq.'experienceinfo.getstyle=0')->order('endtime desc')->find();
            $user['price']=round($price['balance'],2);
            $user['exper']=$exper;
            $user['portrait']=$userimg['portrait'];
            $this->assign('user',$user);
        }
        $pid = I('get.pid');
        $goods = M('productinfo')->join($tq.'product_lastprice on '.$tq.'product_lastprice.pid='.$tq.'productinfo.pid')->order("{$tq}productinfo.sort asc")->select();
        $curGoods=M('productinfo')->join($tq.'product_lastprice on '.$tq.'product_lastprice.pid='.$tq.'productinfo.pid')->where($tq.'productinfo.pid='.$pid)->find();
        $uorder=M('order')->where(array('uid'=>$uid,'ostaus'=>0,'pid'=>$pid))->select();
//         $news=$this->information();
//         $focus=$this->focus();
//         $notices=$this->notice();
        $webconfig = M('webconfig')->find();
        $isopen=\MarketStatusJudge::isopen($curGoods['pid']);
        $this->assign('isopen',$isopen);
        if(count($uorder) > 0){
            $this->assign('EndTime',$uorder[0]['selltime']);
        }
        $traderule = json_decode($curGoods['traderule'],true);
        for ($index = 1; $index <=5;$index++){
            $this->assign('rule'.$index,$traderule[strval($index)]);
        }
        $this->assign('nolist',$uorder);
        
//         $this->assign('news',$news);
//         $this->assign('focus',$focus);
//         $this->assign('notices',$notices);
        $this->assign('webconfig',$webconfig);
        $this->assign('goods',$goods);
        $this->assign('curgoods',$curGoods);
        $this->display();
    }

    //显示最新资讯
    public function information(){
        $news=M('newsinfo')->where('ncategory=1')->order('nid desc')->limit(3)->select();
        return $news;
    }
    //显示财经要闻Focus
    public function focus(){
    $news=M('newsinfo')->where('ncategory=2')->order('nid desc')->limit(3)->select();
    return $news;
    }
    //显示系统公告Notice
    public function notice(){
    $news=M('newsinfo')->where('ncategory=3')->order('nid desc')->limit(3)->select();
    return $news;
    }
    //获取动态油的价格，显示到页面
   public function price()
    {
        $pid = I('post.pid');
        $source = M('product_lastprice')->where(array(
            'pid' => $pid
        ))->find();
        $myprice[0] = $source['ask']; // 最新
        $myprice[8] = $source['open']; // 今开
        $myprice[7] = $source['close']; // 昨开
        $myprice[4] = $source['high']; // 最高
        $myprice[5] = $source['low']; // 最低
        $myprice[12] = $myprice[0] - $myprice[8];
        $this->ajaxReturn($myprice);
    }
    
    //更新风险阅读标志
    public function updateriskread()
    {
        $uid=$_SESSION['uid'];
        
        $result = M('userinfo')->where('uid='.$uid)->setField('readrisk',1);
        
        if($result){
            $this->ajaxReturn(true);
        }else{
            $this->ajaxReturn(false);
        }
    }

    //根据传回来的id获取商品的信息
    public function selectid(){
        $tq=C('DB_PREFIX');
        $pid=I('post.pid');
        $uid=$_SESSION['uid'];
        //获取当前id对应的商品
        $good=M('productinfo')->where('pid='.$pid)->find();
        //查询用户时候有对应的体验卷
        $sum=M('experienceinfo')->join($tq.'experience on '.$tq.'experienceinfo.eid='.$tq.'experience.eid')->where($tq.'experienceinfo.uid='.$uid.' and '.date(time()).' < '.$tq.'experienceinfo.endtime and '.$tq.'experienceinfo.getstyle=0 and '.$tq.'experience.eprice='.$good['uprice'])->count();

        $good['sum']=$sum;
        $this->ajaxReturn($good);
    }

    //查询当前用户正在交易的订单
    public function userorder(){
        $tq=C('DB_PREFIX');
        $uid = $_SESSION['uid'];
        $userorders=M('order')->join($tq.'product_lastprice on '.$tq.'order.pid='.$tq.'product_lastprice.pid')->where($tq.'order.uid='.$uid.' and ostaus=0')->select();
        return $userorders;
    }
	//查询订单信息(接收修改后的订单，或者直接平仓，或者购买完后平仓，3中情况)
    public function orderid(){
        $tq=C('DB_PREFIX');
        $orderid=I('orderid');
        $order=M('order')->join($tq.'product_lastprice on '.$tq.'order.pid='.$tq.'product_lastprice.pid')->where('oid='.$orderid)->find();
    	$order['expend'] = $order['onumber']*$order['uprice'];	//支出
    	$order['paytime'] = date('Y-m-d',$order['buytime']);
		$this->ajaxReturn($order);
    }
	//修改订单的止盈和止亏
    public function edityk(){
        $order=M('order');
        $order->oid=I('post.oid');
        $order->endprofit=I('post.zy');
        $order->endloss=I('post.zk');
        $order->save();
        $this->redirect('Index/index');
    }

    function sdpccl(){
        $oid=I('post.oid');
        $order=M('order')->where(array('oid'=>$oid))->find();
        if ($order) {
            $source1=M('product_lastprice')->where(array('pid'=>$order['pid']))->find();
            $sellprice=$source1['ask'];//最新
            if ($sellprice!='') {
            $yinli=$order['fee']*(1+$order['endloss']/100);
            $kuisun="-".$order['fee'];
            $data['sellprice']=$sellprice;
            $data['ostaus']=1;
            $data['selltime']=time();
            if ($order['ostyle']==0) {
                if ($sellprice>=($order['buyprice']+$order['endprofit'])) {
                    M('accountinfo')->where(array('uid'=>$order['uid']))->setInc('balance',$yinli);
                    $data['ploss']=$yinli;
                    M('order')->where(array('oid'=>$order['oid']))->save($data);
                    $this->ajaxReturn(array('data'=>0));
                }else{
                    $data['ploss']=$kuisun;
                    M('order')->where(array('oid'=>$order['oid']))->save($data);
                    $this->ajaxReturn(array('data'=>0));
                }
            }elseif ($order['ostyle']==1) {
                if ($sellprice<=($order['buyprice']-$order['endprofit'])) {
                    M('accountinfo')->where(array('uid'=>$order['uid']))->setInc('balance',$yinli);
                    $data['ploss']=$yinli;
                    M('order')->where(array('oid'=>$order['oid']))->save($data);
                    $this->ajaxReturn(array('data'=>0));
                }else{
                    $data['ploss']=$kuisun;
                    M('order')->where(array('oid'=>$order['oid']))->save($data);
                    $this->ajaxReturn(array('data'=>0));
                }
            }else{
                $this->ajaxReturn(array('data'=>1));
            }
        }else{
                $this->ajaxReturn(array('data'=>1)); 
        }
       }else{
           $this->ajaxReturn(array('data'=>1)); 
       }
    }

	
}