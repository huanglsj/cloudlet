<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;

use Think\Controller;
use Think\Log;

class OrderController extends Controller
{
    public function ocontent()
    {
        //判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();
        $order = D('order');
        $users = D('userinfo');
        $binfo = D('bankinfo');
        $pinfo = D('productinfo');
        $manager = D('managerinfo');
        $account = D('accountinfo');
        //获取订单id
        $oid = I('get.oid');
        //查询订单数据基本信息
        $oinfo = $order->where('oid=' . $oid)->find();
        //客户信息
        $uinfo = $users->where('uid=' . $oinfo['uid'])->find();
        //商品信息
        $goods = $pinfo->where('pid=' . $oinfo['pid'])->find();
        //银行卡信息
        $bank = $binfo->where('uid=' . $oinfo['uid'])->field('bnkmber')->find();
        //身份证信息
        $mger = $manager->where('uid=' . $oinfo['uid'])->field('mname,brokerid')->find();
        //用户账户信息
        $acount = $account->where('uid=' . $oinfo['uid'])->field('balance')->find();

        $this->assign('oinfo', $oinfo);
        $this->assign('uinfo', $uinfo);
        $this->assign('goods', $goods);
        $this->assign('bank', $bank);
        $this->assign('mger', $mger);
        $this->assign('acount', $acount);
        $this->display();
    }

    public function olist()
    {// 2017-3-12
        //判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();

        $tp = C('DB_PREFIX');// 获取表前缀
        $order = D('order');
        $department = D('department');

        // 持仓0 平仓
        $ostaus = I('ostaus');
        // 是否初始化
        $init = I("init");

        $ptitlelist = D('order')
            ->field('ptitle')
            ->distinct(true)
            ->select();
        $this->assign('ptitlelist', $ptitlelist);
        // 拼接查询条件
        $wheres = "{$tp}order.ostaus={$ostaus}";

        if ($ostaus == 1) {
            $selectTimeFiled = "{$tp}order.selltime";
//            $wheres .= " and {$tp}order.settlementdate<>''";
        } else {
            $selectTimeFiled = "{$tp}order.buytime";
        }

        $psize = I('psize');
        if (strlen($psize) <= 0) {
            $psize = 10;
        }

        // 返回条件给视图层
        foreach ($_GET as $name => $value) {
            $this->assign($name, $value);
        }

        $this->assign("psize", $psize);

        // 结算日期
        $history = I('history');
        if ($history) {
            $wheres .= " and FROM_UNIXTIME({$selectTimeFiled}, '%Y-%m-%d %H:%k') >= '{$history}'";
        } else {
//            $wheres .= " and FROM_UNIXTIME({$selectTimeFiled}, '%Y-%m-%d') = FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d')";
        }

        // 结算日期
        /*        $history = I('history');
                if ($init == 1) {
                    $history = "tday";
                }
                if ($history == "yday") { // 昨天
                    $wheres .= " and to_days(FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d'))-to_days(FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d')) = 1";
                } elseif ($history == "7day") { // 近7天
                    $wheres .= " and date_sub(curdate(),interval 7 day)<=FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d')";
                } elseif ($history == "30dy") { // 近30天
                    $wheres .= " and date_sub(curdate(),interval 30 day)<=FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d')";
                } elseif ($history == "tsmh") { // 本月
                    $wheres .= " and FROM_UNIXTIME({$tp}order.settlementdate, '%Y%m')=date_format(curdate(),'%Y%m')";
                } elseif ($history == "ltmh") { // 上月
                    $wheres .= " and date_format(now(),'%Y%m')-FROM_UNIXTIME({$tp}order.settlementdate, '%Y%m')=1";
                } elseif ($history == "qutr") { // 本季度
                    $wheres .= " and QUARTER(FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d'))=QUARTER(FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d'))";
                } elseif ($history == "ltqr") { // 上季度
                    $wheres .= " and QUARTER(FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d'))=QUARTER(DATE_SUB(FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d'),interval 1 QUARTER))";
                } elseif ($history == "year") { // 今年
                    $wheres .= " and YEAR(FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d')) = YEAR(NOW())";
                } elseif ($history == "ltyr") { // 上年
                    $wheres .= " and YEAR(FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d')) = year(date_sub(now(),interval 1 year))";
                } else {

                    $wheres .= " and FROM_UNIXTIME({$selectTimeFiled}, '%Y-%m-%d') = FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d')";
                }*/

        // 订单号
        $orderno = I('orderno');
        if (strlen($orderno) > 0) {
            $wheres .= " and {$tp}order.orderno like '%{$orderno}%'";
        }

        // 登录账号
        $username = I('username');
        if (strlen($username) > 0) {
            $wheres .= " and {$tp}userinfo.username like '%{$username}%'";
        }

        // 用户姓名
        $realname = I('realname');
        if (strlen($realname) > 0) {
            $wheres .= " and {$tp}authenticationinfo.realname like '%{$realname}%'";
        }

        // 商品名称
        $ptitle = I('ptitle');
        if (strlen($ptitle) > 0) {
            $wheres .= " and {$tp}order.ptitle like '%{$ptitle}%'";
        }

        // 交易点数
        $endprofit = I('endprofit');
        if (strlen($endprofit) > 0) {
            $wheres .= " and {$tp}order.endprofit = {$endprofit}";
        }

        // 机构名称
        $dmjig = I('dmjig');
        if (strlen($dmjig) > 0) {
            $wheres .= " and {$tp}department.jigou_name like '%{$dmjig}%'";
        }

        // 代理名称
        $dmdail = I('dmdail');
        if (strlen($dmdail) > 0) {
            $wheres .= " and {$tp}department.daili_name like '%{$dmdail}%'";
        }

        // 会员编号
        $dmhuiy = I('dmhuiy');
        if (strlen($dmhuiy) > 0) {
            $wheres .= " and {$tp}department.huiyuan_code={$dmhuiy}";
        }

//        var_dump($wheres);

        // 要查询的字段
        $fieldarr = "FROM_UNIXTIME({$tp}order.selltime, '%Y-%m-%d %H:%i:%s') as sltd, {$tp}order.selltime" // 结算日期
            . ",{$tp}userinfo.username as username" // 登录账号
            . ",'desu' as realname" // 用户姓名
            . ",{$tp}order.ptitle as ptitle" // 商品名称
            . ",{$tp}order.endprofit as endprofit" // 交易点数
            . ",count({$tp}order.oid) as odrcount" // 交易次数
//            . ",'desu' AS profitcount" // 盈次数(假的)
//            . ",'desu' AS losscount" // 亏次数(假的)
//            . ",'desu' AS flatcount" // 亏次数(假的)
//            . ",'desu' AS winnpro" // 胜率(假的)
            . ",sum({$tp}order.fee) as feesum" // 委托金额
            . ",sum({$tp}order.fee) as tfeesum" // 有效委托金额
            . ",sum({$tp}order.ploss) as plosssum" // 盈亏金额
            . ",sum(case when {$tp}order.ploss > 0 then {$tp}order.ploss else 0 end) AS profit" // 盈利金额
            . ",sum(case when {$tp}order.ploss < 0 then {$tp}order.ploss else 0 end) AS loss" // 亏损金额
            . ",sum({$tp}order.managefee) as managefeesum" // 交易管理费
            . ",'0.00' as commissionsum" // 推荐费
            . ",{$tp}department.jigou_name AS dmjig" // 所属机构名称(假的)
            . ",{$tp}department.daili_name AS dmjinj" // 所属代理名称(假的)
            . ",{$tp}department.huiyuan_code AS dmhuiy" // 所属会员编号(假的)
            . ",'desu' AS dmshic" // 交易市场(假的)
            . ",{$tp}order.eid as eid" // 交易类型
            . ",{$tp}order.ostyle as ostyle" // 买卖类型
            . ",{$tp}order.endloss as endloss"
            . ",{$tp}order.ucode as ucode"
            . ",{$tp}order.orderno as orderno"
            . ",{$tp}order.poundage as poundage"
            . ",{$tp}userinfo.uid as uid";


        // 分组
        $group = "username,{$tp}order.ptitle,{$tp}order.eid, {$tp}order.endprofit, {$tp}order.endloss, sltd";

        // 获取查询总数
        $ocount = D('order')
            ->table(D('order')
                    ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
                    ->join("{$tp}department on {$tp}department.code = {$tp}order.ucode", 'left')
                    ->join("{$tp}authenticationinfo on {$tp}authenticationinfo.useruid = {$tp}userinfo.uid", 'left')
                    ->field($fieldarr)
                    ->where($wheres)
                    ->group($group)
                    ->buildSql() . ' ocount')->count();


        // 分页
        $page = new \Think\Page($ocount, $psize);
        //$page->parameter = $parameter; //此处的row是数组，为了传递查询条件
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '&#8249;');
        $page->setConfig('next', '&#8250;');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
        $show = $page->show();

        $listSort = I('sort');
        $listWay = I('way');
        if ($listSort) {
            switch ($listSort) {
                case 'sltd':
                    $orders = "sltd " . $listWay;
                    break;
                case 'profit':
                    $orders = "sum(case when {$tp}order.ploss > 0 then {$tp}order.ploss else 0 end) " . $listWay;
                    break;
                case 'plosssum':
                    $orders = "sum({$tp}order.ploss) " . $listWay;
                    break;
            }
        } else {
            $orders = "sltd desc";
        }
        $dorder = I('dorder');
        $omodel = I('omodel');
        if (strlen($omodel) <= 0) {
            $omodel = "asc";
        }
        if (strlen($dorder) > 0 && strlen($omodel) > 0) {
            $orders = "{$dorder} {$omodel}";
        }
        $this->assign('dorder', $dorder);
        $this->assign('omodel', $omodel);

        // 开始查询
        $orderlist = $order
            ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
            ->join("{$tp}department on {$tp}department.code = {$tp}order.ucode", 'left')
            ->join("{$tp}authenticationinfo on {$tp}authenticationinfo.useruid = {$tp}userinfo.uid", 'left')
            ->field($fieldarr)
            ->where($wheres)
            ->group($group)
            ->order($orders)
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();
        echo "<!--";
        echo $order->getLastSql();
        echo "-->";

        // 计算总和
        $ototal = array(
            'odrcount' => 0,
            'profitcount' => 0,
            'losscount' => 0,
            'flatcount' => 0,
            'feesum' => 0,
            'tfeesum' => 0,
            'plosssum' => 0,
            'profit' => 0,
            'loss' => 0,
            'managefeesum' => 0,
            'poundage' => 0,
            'commissionsum' => 0
        );

        for ($i = 0; $i < count($orderlist); $i++) {
            $wherec = "ostaus={$ostaus} and {$tp}order.settlementdate<>''"
                . " and FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d')='{$orderlist[$i]['sltd']}'"
                . " and {$tp}userinfo.username='{$orderlist[$i]['username']}'"
                . " and {$tp}order.ptitle='{$orderlist[$i]['ptitle']}'"
                . " and {$tp}order.eid='{$orderlist[$i]['eid']}'"
                . " and {$tp}order.endprofit='{$orderlist[$i]['endprofit']}'"
                . " and {$tp}order.endloss='{$orderlist[$i]['endloss']}'";

            // 用户姓名
            $orderlist[$i]['realname'] = D('authenticationinfo')
                ->field("realname")
                ->where("useruid=" . $orderlist[$i]['uid'])
                ->find()['realname'];
            // 盈次数
//            $orderlist[$i]['profitcount'] = $order
//                ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
//                ->where($wherec . " and {$tp}order.ploss>0")
//                ->count();
            // 亏次数
//            $orderlist[$i]['losscount'] = $order
//                ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
//                ->where($wherec . " and {$tp}order.ploss<0"
//                )->count();
            // 平次数
//            $orderlist[$i]['flatcount'] = $order
//                ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
//                ->where($wherec . " and {$tp}order.ploss=0"
//                )->count();
            // 计算胜率(%)
            // $orderlist[$i]['winnpro'] = sprintf("%.2f", $orderlist[$i]['profitcount'] / ($orderlist[$i]['odrcount'] - $orderlist[$i]['flatcount']));
            // 推荐费
//            $orderlist[$i]['commissionsum'] *= 0.125;
            // 委托金额
//            $orderlist[$i]['feesum'] -= $orderlist[$i]['commissionsum'];
            // 有效委托金额
            $feesumnr = $orderlist[$i]['feesum'] - $order
                    ->field("sum(fee) as tfeesum")
                    ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
                    ->where($wherec . " and {$tp}order.ploss=0")->find()['tfeesum'];
            $feesumnr == null ? 0 : $feesumnr;
            $orderlist[$i]['tfeesum'] = $feesumnr;

            if ($orderlist[$i]['odrcount'] == $orderlist[$i]['flatcount']) {
                $orderlist[$i]['tfeesum'] = '0.00';
                $orderlist[$i]['commissionsum'] = '0.00';
            }

            // 交易市场
            $tmp = $department
                ->field("name")
                ->where("code=000")
                ->find();
            $orderlist[$i]['dmshic'] = $tmp['name'];

            // 交易次数(总和)
            $ototal['odrcount'] += $orderlist[$i]['odrcount'];
            // 盈次数(总和)
            // $ototal['profitcount'] += $orderlist[$i]['profitcount'];
            // 亏次数(总和)
            // $ototal['losscount'] += $orderlist[$i]['losscount'];
            // 平次数(总和)
            //$ototal['flatcount'] += $orderlist[$i]['flatcount'];
            // 委托金额(总和)
            $ototal['feesum'] += $orderlist[$i]['feesum'];
            // 有效委托金额(总和)
            $ototal['tfeesum'] += $orderlist[$i]['tfeesum'];
            // 盈亏金额(总和)
            $ototal['plosssum'] += $orderlist[$i]['plosssum'];
            // 盈利金额(总和)
            $ototal['profit'] += $orderlist[$i]['profit'];
            // 亏损金额(总和)
            $ototal['loss'] += $orderlist[$i]['loss'];
            // 交易管理费(总和)
            $ototal['managefeesum'] += $orderlist[$i]['managefeesum'];
            //手续费总和

//            var_dump($orderlist[$i]['poundage']);
            if ($orderlist[$i]['plosssum'] > 0) {
                $ototal['poundage'] += round($orderlist[$i]['feesum'] * $orderlist[$i]['poundage'] / 100, 2);
            } else {
                $ototal['poundage'] += 0;
            }
            $ototal['commissionsum'] += $orderlist[$i]['commissionsum'];

        }

//        var_dump($orderlist);
//        var_dump($show);
        $this->assign('page', $show);
        $this->assign('orderlist', $orderlist);
        $this->assign('ototal', $ototal);
        $this->display();
    }

    //最新订单，默认查询30秒内要平仓的全部订单。并统计。
    public function zxlist()
    {
        //判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();
        $tq = C('DB_PREFIX');
        $order = D('order');
        $pinfo = D('productinfo');
        if (IS_POST) {
            //商品分类：
            $ostyle = I('post.ostyle');
            //交易金额
            $ploss = I('post.ploss');
            //平仓时间
            $ostaus = I('post.ostaus');

            if ($ostyle != "") {
                $where['cid'] = $ostyle;
            }
            if ($ploss != '') {
                $where['uprice'] = $ploss;
            }
            if ($ostaus != "") {
                $time1 = time() + $ostaus;
                $where['selltime'] = array(array('EGT', time()), array('ELT', $time1));
            }

            if ($ostyle == "" && $ploss == "" && $ostaus == "") {
                $time1 = time() + 60;
                $where['selltime'] = array(array('EGT', time()), array('ELT', $time1));
            }
            $where['ostaus'] = 0;

        } else {
            $time1 = time() + 60;
            $where['selltime'] = array(array('EGT', time()), array('ELT', $time1));
            $where['ostaus'] = 0;
        }

        $orders = $order->join($tq . 'userinfo on ' . $tq . 'order.uid=' . $tq . 'userinfo.uid', 'left')->join($tq . 'productinfo on ' . $tq . 'order.pid=' . $tq . 'productinfo.pid')->where($where)->order($tq . 'order.oid desc')->select();

        //买涨的手数。
        $zhang = $order->join($tq . 'userinfo on ' . $tq . 'order.uid=' . $tq . 'userinfo.uid', 'left')->join($tq . 'productinfo on ' . $tq . 'order.pid=' . $tq . 'productinfo.pid')->where($where . ' and ' . $tq . 'order.ostyle=0')->sum($tq . 'order.onumber');
        $sum['zhang'] = $zhang;
        //买跌的手数。
        $die = $order->join($tq . 'userinfo on ' . $tq . 'order.uid=' . $tq . 'userinfo.uid', 'left')->join($tq . 'productinfo on ' . $tq . 'order.pid=' . $tq . 'productinfo.pid')->where($where . ' and ' . $tq . 'order.ostyle=1')->sum($tq . 'order.onumber');
        $sum['die'] = $die;
        //查询所有商品信息
        $goods = $pinfo->distinct(true)->field('uprice')->select();

        //echo $order->getLastSql();
        $this->assign('goods', $goods);
        $this->assign('sum', $sum);
        $this->assign('orders', $orders);

        $this->display();
    }

    public function tlist()
    {
        //判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();
        $tq = C('DB_PREFIX');
        $journal = D('journal');
        $bournal = D('bournal');
        $user = D('userinfo');

        $today = date("Y-m-d", time());
        $today = explode('-', $today);
        $begintime = mktime(0, 0, 0, $today[1], $today[2], $today[0]);

        $tlist = $journal->order('jtime desc')->where('jtime>' . $begintime)->select();
        $lt = count($tlist);
        $blist = $bournal->order('btime desc')->where('btime>' . $begintime)->select();
        $times = array();
        foreach ($blist as $k => $v) {
            $tlist[$lt + $k]['jno'] = $v['bno'];
            $tlist[$lt + $k]['uid'] = $v['uid'];
            $tlist[$lt + $k]['jtype'] = $v['btype'];
            $tlist[$lt + $k]['jtime'] = $v['btime'];
            $tlist[$lt + $k]['number'] = 1;
            $tlist[$lt + $k]['juprice'] = $v['bprice'];
            $tlist[$lt + $k]['jusername'] = $v['username'];
            $tlist[$lt + $k]['isverified'] = $v['isverified'];
            $tlist[$lt + $k]['balance'] = $v['balance'];
        }
        foreach ($tlist as $k => $v) {
            $times[$k] = $v['jtime'];
        }
        array_multisort($times, SORT_STRING, SORT_DESC, $tlist);
        $this->assign('tlist', $tlist);
        $this->display();
    }

}
