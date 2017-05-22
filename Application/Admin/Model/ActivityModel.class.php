<?php

namespace Admin\Model;
use Think\Model;

class ActivityModel extends Model {
	
	/**
     * 自动验证
     */
	protected $_validate = array(
        array('name', 'require', '名称不能为空！'),
        array('max_money', 'require', '最大充值金额不能为空！'),
        array('min_money', 'require', '最小充值金额不能为空！'),
        array('give_money', 'require', '赠送金额不能为空！'),

        array('max_money', 'number', '最大价格必须为数字！'),
        array('min_money', 'number', '最小价格必须为数字！'),
        array('give_money', 'number', '最小价格必须为数字！'),
	);
	
    /**
     * 自动完成
     */
     protected $_auto = array (
         array('create_time','time',1,'function'),
         array('update_time','time',3,'function'),
    );

    /**
     * 更新
     * @return boolean 更新状态
     */
    public function update(){
        $data = $this->create();
        if(!$data){ //数据对象创建错误
            return false;
        }

        /* 添加或更新数据 */
        if(empty($data['id'])){
            $res = $this->add();
        }else{
            $res = $this->save();
        }

        return $res;
    }
}
