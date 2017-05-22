<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;

use Think\Controller;
use Think\Log;

vendor('Jugui.MarketStatusJudge');

class GoodsController extends Controller
{
    public function gadd()
    {
        //判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();

        //实例化数据表
        $goods = D('productinfo');
        $lastprice = D('product_lastprice');
        //交易所
        $this->assign('bourses', C('BOURSE'));
        //交易所编码
        $this->assign('codes', C('GOODSTYPE'));
        //交易状态
        $this->assign('status', C('STATUS'));
        //判断如果是post提交，则添加数据，否则显示视图
        if (IS_POST) {
            $goods->startTrans();
            $data = $goods->create();
            if ($data) {

                //设置交易时间json
                $period = array();
                for ($index = 1; $index <= 7; $index++) {

                    $tstart = I('post.tstart' . $index);
                    $tend = I('post.tend' . $index);
                    $time = array();
                    foreach ($tstart as $key => $value) {
                        $plus = 0;
                        if ($value > $tend[$key]) {
                            $plus = 1;
                        }
                        $section = array(
                            's' => $value,
                            'e' => $tend[$key],
                            'plus' => $plus
                        );
                        array_push($time, $section);
                    }
                    $period[$index] = $time;
                    unset($time);
                }
                $data['tradeperiod'] = json_encode($period);
                //交易规则
                $traderule = array();
                for ($index = 1; $index <= 5; $index++) {
                    $dianshu = I('post.dianshu' . $index);
                    $profitDianshu = I('profit_dianshu_' . $index);
                    $shijian = I('shijian' . $index);
                    $profitDianshu = I('profit_shijian_' . $index);
                    $traderule[$index]['dianshu'] = $dianshu;
                    $traderule[$index]['profit_dianshu'] = $profitDianshu;
                    $traderule[$index]['shijian'] = $shijian;
                    $traderule[$index]['profit_shijian'] = $profitDianshu;
                    $traderule[$index]['poundage'] = I('poundage_' . $index);
                }
                $data['traderule'] = json_encode($traderule);

                $result = $goods->add($data);
                if ($result) {
                    //添加最新价格
                    $data = $lastprice->create();
                    if ($data) {
                        $data['code'] = I('post.code');
                        $data['pid'] = $result;
                        $result = $lastprice->add($data);
                        if ($result) {
                            $goods->commit();
                            $this->success('添加商品成功', U('Goods/glist'));
                        } else {
                            $goods->rollback();
                            $this->error('添加商品失败');
                        }
                    } else {
                        $goods->rollback();
                        $this->error($lastprice->getError());
                    }

                } else {
                    $goods->rollback();
                    $this->error('添加商品失败');
                }
            } else {
                $goods->rollback();
                $this->error($goods->getError());
            }
        } else {
            $this->display();
        }
    }

    public function glist()
    {
        //判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();

        $tq = C('DB_PREFIX');
        $goods = D('productinfo');

        //交易所
        $this->assign('bourses', C('BOURSE'));
        //交易所编码
        $this->assign('codes', C('GOODSTYPE'));
        //交易状态
        $this->assign('statusarray', C('STATUS'));

        $count = $goods->count();
        $pagecount = 20;
        $page = new \Think\Page($count, $pagecount);
        $page->parameter = $row; //此处的row是数组，为了传递查询条件
        $page->setConfig('first', '首页');
        $page->setConfig('prev', '&#8249;');
        $page->setConfig('next', '&#8250;');
        $page->setConfig('last', '尾页');
        $page->setConfig('theme', '%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');

        $show = $page->show();
        $goodlist = $goods->join($tq . 'product_lastprice on ' . $tq . 'productinfo.pid=' . $tq . 'product_lastprice.pid')->order("{$tq}productinfo.sort asc")->select();
        $this->assign('goodlist', $goodlist);
        $this->assign('page', $show);
        $this->display();
    }

    public function gedit()
    {
        //判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();

        $tq = C('DB_PREFIX');
        $goods = D('productinfo');
        $lastprice = D('product_lastprice');
        //交易所
        $this->assign('bourses', C('BOURSE'));
        //交易所编码
        $this->assign('codes', C('GOODSTYPE'));
        //交易状态
        $this->assign('status', C('STATUS'));
        //判断执行，如果是post提交，执行修改方法，否则显示页面
        if (IS_POST) {
            $postpid = I('post.pid');
            //自动验证表单
            $data = $goods->create();
            if ($data) {
                //获取修改表单的数据，并做处理
                //设置交易时间json
                $period = array();
                for ($index = 1; $index <= 7; $index++) {

                    $tstart = I('post.tstart' . $index);
                    $tend = I('post.tend' . $index);
                    $time = array();
                    foreach ($tstart as $key => $value) {
                        $plus = 0;
                        if ($value > $tend[$key]) {
                            $plus = 1;
                        }
                        $section = array(
                            's' => $value,
                            'e' => $tend[$key],
                            'plus' => $plus
                        );
                        array_push($time, $section);
                    }
                    $period[$index] = $time;
                    unset($time);
                }
                $data['tradeperiod'] = json_encode($period);

                //交易规则
                $traderule = array();
                for ($index = 1; $index <= 5; $index++) {
                    $dianshu = I('post.dianshu' . $index);
                    $profitDianshu = I('profit_dianshu_' . $index);
                    $shijian = I('shijian' . $index);
                    $profitDianshu = I('profit_shijian_' . $index);
                    $traderule[$index]['dianshu'] = $dianshu;
                    $traderule[$index]['profit_dianshu'] = $profitDianshu;
                    $traderule[$index]['shijian'] = $shijian;
                    $traderule[$index]['profit_shijian'] = $profitDianshu;
                    $traderule[$index]['poundage'] = I('poundage_' . $index);
                }
                $data['traderule'] = json_encode($traderule);

                $result = $goods->where('pid=' . $postpid)->save($data);
                if ($result === FALSE) {
                    $this->error("修改失败！");
                } else {
                    unset($data);
                    $data['displayname'] = I('post.displayname');
                    $result = $lastprice->where('pid=' . $postpid)->save($data);
                    if ($result === FALSE) {
                        $this->error("修改失败！");
                    } else {
                        $this->success("修改成功", U('Goods/glist'));
                    }
                }
            } else {
                //自动验证返回结果
                $this->error($goods->getError());
            }
        } else {
            //通过获取的id查找该条数据
            $getpid = I('get.pid');
            $editgood = $goods->join($tq . 'product_lastprice on ' . $tq . 'productinfo.pid=' . $tq . 'product_lastprice.pid')->where($tq . 'productinfo.pid=' . $getpid)->find();
            $this->assign('editgood', $editgood);
            $tradeperiod = json_decode($editgood['tradeperiod'], true);
            for ($index = 1; $index <= 7; $index++) {
                $this->assign('period' . $index, $tradeperiod[strval($index)]);
            }
            $traderule = json_decode($editgood['traderule'], true);
            for ($index = 1; $index <= 5; $index++) {
                $this->assign('rule' . $index, $traderule[strval($index)]);
            }
            $this->display();
        }
    }

    // 2017-3-16 点差矫正 更新
    public function geditbypat()
    {
        //判断用户是否登陆
        $user = A('Admin/User');
        $user->checklogin();

        $productinfo = M('productinfo');
        $data = array();
        $re = false;
        $type = I('get.type');
        if (strlen($type) > 0) {

            $pid = I('get.pid');

            $patj = I('get.patj');
            if (strlen($patj) > 0 && $type == 1) {
                $data['patj'] = $patj;
            }

            $patx = I('get.patx');
            if (strlen($patx) > 0 && $type == 2) {
                $data['patx'] = $patx;
            }

            if (strlen($pid) > 0) {
                $re = $productinfo->where("pid={$pid}")->save($data);
            }
        }
        if ($re === false) {
            $this->ajaxReturn("修改失败");
        } else {
            $this->ajaxReturn("修改成功");
        }

    }

    public function gdel()
    {
        $pinfo = D('productinfo');
        $lastprice = D('product_lastprice');
        //批量删除id
        $arrpid = I('post.pid');
        //单个删除
        $pid = I('get.pid');

        if (IS_POST) {
            //批量删除
            $result = $pinfo->where('pid in(' . implode(',', $arrpid) . ')')->delete();
            if ($result !== FALSE) {
                $result = $lastprice->where('pid in(' . implode(',', $arrpid) . ')')->delete();
                if ($result !== FALSE) {
                    $this->success("成功删除{$result}条！", U("Goods/glist"));
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('删除失败！');
            }
        } else {
            //单条记录删除
            $result = $pinfo->where('pid=' . $pid)->delete();
            if ($result !== FALSE) {
                $result = $lastprice->where('pid=' . $pid)->delete();
                if ($result !== FALSE) {
                    $this->success("成功删除！", U("Goods/glist"));
                } else {
                    $this->error('删除失败！');
                }
            } else {
                $this->error('删除失败！');
            }
        }
    }

    /**
     * 获取所有产品的现在是否是开市
     */
    public function isOpen()
    {
        $pids = $_POST['pid'];
        $time = $_POST['time'];
        if (strpos($pids, ",") === false) {
            $pid = array($pids);
        } else {
            $pid = explode(",", $pids);
        }

        $ret = array();
        foreach ($pid as $p) {
            $ret[$p] = \MarketStatusJudge::isopen($p, $time) ? 1 : 0;
        }
        $ret['success'] = 1;
        $this->ajaxReturn($ret, "JSON");
    }
}
