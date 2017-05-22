<?php
// 本类由系统自动生成，仅供测试用途
namespace Admin\Controller;
use Think\Controller;
class ActivityController extends Controller {
    public function add()
	{
		//判断用户是否登陆
		$user= A('Admin/User');
		$user->checklogin();
		if(IS_POST){
            $modelActivity = D('Activity');
            $result = $modelActivity->update();
            if ($result) {
                $this->success('操作成功', U('Activity/lists'));
            } else {
                $this->error('操作失败!'.$modelActivity->getError());
            }
		}else{
			$this->display();
		}
	}


	public function lists(){
        //判断用户是否登陆
        $user= A('Admin/User');
        $user->checklogin();

        $map = array();
        $modelActivity = M('Activity');

        //分页
        $count = $modelActivity->where($map)->count();
        $pagecount = 20;
        $page = new \Think\Page($count , $pagecount);
        foreach ($map as $key=>$value){
            $page->parameter[$key] = urlencode($value);//此处的row是数组，为了传递查询条件
        }
        $page->setConfig('first','首页');
        $page->setConfig('prev','&#8249;');
        $page->setConfig('next','&#8250;');
        $page->setConfig('last','尾页');
        $page->setConfig('theme','%FIRST% %UP_PAGE% %LINK_PAGE% %DOWN_PAGE% %END% ');
        $show = $page->show();
        //查询用户和账户信息
        $list = $modelActivity->where($map)
            ->limit($page->firstRow.','.$page->listRows)->select();

        $this->assign('page',$show);
        $this->assign('list',$list);
        $this->display();
	}

    //编辑
    public function edit()
    {
        if (IS_POST) {
            $modelActivity = D('Activity');
            $result = $modelActivity->update();
            if ($result) {
                $this->success('操作成功', U('Activity/lists'));
            } else {
                $this->error('操作失败!'.$modelActivity->getError());
            }
        } else{
            $modelActivity = D('Activity');
            $id = I('get.id', 0, 'intval');
            $info = $modelActivity->where(array('id'=>$id))->find();
            if(!$info){
                $this->error('没有该记录!');
            }
            $this->assign('info', $info);
            $this->display('add');
        }
    }

    //删除
    public function del()
    {
        $modelActivity = D('Activity');
        $id = I('get.id', 0, 'intval');
        $result = $modelActivity->where(array('id'=>$id))->delete();
        if ($result) {
            $this->success('操作成功', U('Activity/lists'));
        } else {
            $this->error('操作失败!'.$modelActivity->getError());
        }
    }

}