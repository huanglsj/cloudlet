<?php
// 本类由系统自动生成，仅供测试用途
namespace Ucenter\Controller;

use Ucenter\Controller\CommonController;
use Workerman\Events\Select;

class TradeController extends CommonController
{

    public function tradelist()
    {
        // 获取登录商的id
        $myuid = $_SESSION['cuid'];

        $tp = C('DB_PREFIX');// 获取表前缀
        $order = D('order');
        $department = D('department');

        // 持仓0 平仓
        $ostaus = I('ostaus');

        $ptitlelist = D('order')
            ->field('ptitle')
            ->distinct(true)
            ->select();
        $this->assign('ptitlelist', $ptitlelist);
        // 拼接查询条件
        $wheres = "ostaus={$ostaus} and {$tp}order.settlementdate<>''";

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
            $wheres .= " and FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d') = FROM_UNIXTIME(unix_timestamp(), '%Y-%m-%d')";
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

        // 账号权限
        $uuu = D('systemuser')->field('type,deptid')->where("id={$myuid}")->select()[0];
        $this->assign("myutypedesu", $uuu['type']);
        $dept = D('department')->field('code')->where("id={$uuu['deptid']}")->select()[0];
        $wheres .= " and {$tp}department.huiyuan_code={$dept['code']}";
        // 要查询的字段
        $fieldarr = "FROM_UNIXTIME({$tp}order.settlementdate, '%Y-%m-%d') as sltd" // 结算日期
            . ",{$tp}userinfo.username as username" // 登录账号
            . ",'desu' as realname" // 用户姓名
            . ",{$tp}order.ptitle as ptitle" // 商品名称
            . ",{$tp}order.endprofit as endprofit" // 交易点数
            . ",count({$tp}order.oid) as odrcount" // 交易次数
            . ",'desu' AS profitcount" // 盈次数(假的)
            . ",'desu' AS losscount" // 亏次数(假的)
            . ",'desu' AS flatcount" // 亏次数(假的)
            . ",'desu' AS winnpro" // 胜率(假的)
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
            . ",{$tp}userinfo.uid as uid"
            . ",{$tp}department.type as dtype";


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

        // 开始查询
        $orderlist = $order
            ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
            ->join("{$tp}department on {$tp}department.code = {$tp}order.ucode", 'left')
            ->join("{$tp}authenticationinfo on {$tp}authenticationinfo.useruid = {$tp}userinfo.uid", 'left')
            ->field($fieldarr)
            ->where($wheres)
            ->group($group)
            ->order("sltd desc, {$tp}order.oid")
            ->limit($page->firstRow . ',' . $page->listRows)
            ->select();

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
                ->find()['realname'];;
            // 盈次数
            $orderlist[$i]['profitcount'] = $order
                ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
                ->where($wherec . " and {$tp}order.ploss>0")
                ->count();
            // 亏次数
            $orderlist[$i]['losscount'] = $order
                ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
                ->where($wherec . " and {$tp}order.ploss<0"
                )->count();
            // 平次数
            $orderlist[$i]['flatcount'] = $order
                ->join("{$tp}userinfo on {$tp}userinfo.uid = {$tp}order.uid", 'left')
                ->where($wherec . " and {$tp}order.ploss=0"
                )->count();
            // 计算胜率(%)
            $orderlist[$i]['winnpro'] = sprintf("%.2f", $orderlist[$i]['profitcount'] / ($orderlist[$i]['odrcount'] - $orderlist[$i]['flatcount']));
            // 推荐费
//            $orderlist[$i]['commissionsum'] *= 0.125;
            // 委托金额
//            $orderlist[$i]['feesum'] -= $orderlist[$i]['commissionsum'];
            // 有效委托金额
            $orderlist[$i]['tfeesum'] = $orderlist[$i]['feesum'] - $order
                    ->field("sum(fee) as tfeesum")
                    ->where($wherec . " and {$tp}order.ploss=0"
                    )->find()['tfeesum'];

            if ($orderlist[$i]['odrcount'] == $orderlist[$i]['flatcount']) {
                $orderlist[$i]['tfeesum'] = '0.00';
                $orderlist[$i]['commissionsum'] = '0.00';
            }

            // 交易市场
            $tmp = ($department
                ->field("name")
                ->where("code=000")
                ->find());
            $orderlist[$i]['dmshic'] = $tmp['name'];

            // 交易次数(总和)
            $ototal['odrcount'] += $orderlist[$i]['odrcount'];
            // 盈次数(总和)
            $ototal['profitcount'] += $orderlist[$i]['profitcount'];
            // 亏次数(总和)
            $ototal['losscount'] += $orderlist[$i]['losscount'];
            // 平次数(总和)
            $ototal['flatcount'] += $orderlist[$i]['flatcount'];
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

        }

        $this->assign('page', $show);
        $this->assign('orderlist', $orderlist);
        $this->assign('ototal', $ototal);

        $this->display();
    }
}