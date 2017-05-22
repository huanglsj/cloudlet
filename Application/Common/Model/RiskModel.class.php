<?php

namespace Common\Model;
use Think\Model;

class RiskModel extends Model {
	
	/**
     * 自动验证
     */
	protected $_validate = array(
        array('name', 'require', '名称不能为空！'),
        array('price_max', 'require', '最大价格不能为空！'),
        array('price_min', 'require', '最小价格不能为空！'),
        array('win_rate', 'require', '获胜比率不能为空！'),

        array('price_max', 'number', '最大价格必须为数字！'),
        array('price_min', 'number', '最小价格必须为数字！'),
        array('win_rate', '0,100', '获胜比率只能为0-100之间！', 1, 'between'),
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


    /**
     * 获取订单对应的风控
     * @param int $fee  订单金额
     */
    public function getInfoByFee($fee=0){
        $map = array();
        $map['price_min'] = array('elt', $fee);
        $map['price_max'] = array('egt', $fee);
        return $this->where($map)->find();
    }

    /**
     * 获取风控结果
     * @param int $fee  订单金额
     */
    public function getRiskResult($fee=0){
        $return = array('is_lost'=>0);
        $info = $this->getInfoByFee($fee);
        if($info){
            $rand = rand(0,100);
            if($rand > $info['win_rate']){
                $return['is_lost'] = 1;
            }
        }
        return $return;
    }
}
