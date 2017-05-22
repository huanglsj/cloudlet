<?php

namespace Admin\Model;
use Think\Model;
class ProductLastpriceModel extends Model {
	
	/**
     * 自动验证
     */
	protected $_validate = array(
        array('displayname', 'require', '商品显示名称不能为空！'), //默认情况下用正则进行验证
        
        array('displayname', '', '该商品名称已被占用，请重新填写！', 0, 'unique', 1), // 新增的时候email字段是否唯一
	);
	
    /**
     * 自动完成
     */
     protected $_auto = array (
        
    );
	
}