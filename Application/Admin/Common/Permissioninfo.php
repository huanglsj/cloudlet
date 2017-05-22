<?php
function  getPermissioninfoArray($type){
    /**
     * admin权限array
     * 结构：权限key，权限名称，权限id，父级id（0为根权限）
     */
    $infoArray1 = array(
        array('key' => 'HOME_PAGE_MA', 'name' => '系统首页','id' => 100000, 'pId' => 0),
        array('key' => 'HOME_PAGE', 'name' => '系统首页','id' => 100100, 'pId' => 100000),
        array('key' => 'GOODS_MA', 'name' => '产品管理','id' => 200000, 'pId' => 0),
        array('key' => 'GOODS_LIST', 'name' => '产品列表','id' => 200100, 'pId' => 200000),
        array('key' => 'CALENDAR', 'name' => '日历管理','id' => 200200, 'pId' => 200000),
        array('key' => 'CALENDAR_ADD', 'name' => '日历生成','id' => 200201, 'pId' => 200200),
        array('key' => 'ORDER_MA', 'name' => '订单管理','id' => 300000, 'pId' => 0),
        array('key' => 'ORDER_LIST', 'name' => '持仓订单','id' => 300100, 'pId' => 300000),
        array('key' => 'ORDER_ZX', 'name' => '平仓订单','id' => 300200, 'pId' => 300000),
        array('key' => 'USER_MA', 'name' => '客户管理','id' => 400000, 'pId' => 0),
        array('key' => 'USER_LIST', 'name' => '客户列表','id' => 400100, 'pId' => 400000),
        array('key' => 'USER_EDIT', 'name' => '客户信息编辑','id' => 400101, 'pId' => 400100),
        array('key' => 'USER_CF', 'name' => '客户实名认证审核','id' => 400102, 'pId' => 400100),
        array('key' => 'USER_DEPOSIT', 'name' => '充值列表','id' => 400200, 'pId' => 400000),
        array('key' => 'USER_WITHDRAW', 'name' => '提现列表','id' => 400201, 'pId' => 400000),
    	array('key' => 'USER_BALANCELIST', 'name' => '余额流水','id' => 400210, 'pId' => 400000),
    	array('key' => 'USER_AGENT_LIST', 'name' => '代理商申请列表','id' => 400300, 'pId' => 400000),
        array('key' => 'USER_AGENT_YES', 'name' => '代理商申请通过','id' => 400301, 'pId' => 400300),
        array('key' => 'USER_AGENT_NG', 'name' => '代理商申请拒绝','id' => 400302, 'pId' => 400300),
        array('key' => 'USER_AUTHEN_LIST', 'name' => '实名认证申请列表','id' => 400400, 'pId' => 400000),
        array('key' => 'USER_AUTHEN_YES', 'name' => '实名认证申请通过','id' => 400401, 'pId' => 400400),
        array('key' => 'USER_AUTHEN_NG', 'name' => '实名认证申请拒绝','id' => 400402, 'pId' => 400400),
        array('key' => 'MENBER_MA', 'name' => '会员管理','id' => 500000, 'pId' => 0),
        array('key' => 'MENBER_ADD', 'name' => '添加会员','id' => 500100, 'pId' => 500000),
        array('key' => 'MENBER_LIST', 'name' => '会员列表','id' => 500200, 'pId' => 500000),
        array('key' => 'MENBER_EQUITYWATER', 'name' => '权益流水','id' => 500300, 'pId' => 500000),
        array('key' => 'MENBER_EDIT', 'name' => '会员编辑','id' => 500201, 'pId' => 500200),
        array('key' => 'MENBER_DEL', 'name' => '会员删除','id' => 500202, 'pId' => 500200),
        array('key' => 'MENBER_EQUTY_EDIT', 'name' => '修改会员权益','id' => 500203, 'pId' => 500200),
        array('key' => 'MENBER_DEPOSIT', 'name' => '充值列表','id' => 500300, 'pId' => 500000),
        array('key' => 'MENBER_WITHDRAW', 'name' => '提现列表','id' => 500301, 'pId' => 500000),
        array('key' => 'COUPONS_MA', 'name' => '优惠券管理','id' => 600000, 'pId' => 0),
        array('key' => 'COUPONS_ADD', 'name' => '添加优惠券','id' => 600100, 'pId' => 600000),
        array('key' => 'COUPONS_LIST', 'name' => '优惠券列表','id' => 600200, 'pId' => 600000),
        array('key' => 'COUPONS_DEL', 'name' => '优惠券删除','id' => 600201, 'pId' => 600200),
        array('key' => 'COUPONS_SEND', 'name' => '优惠券发放','id' => 600300, 'pId' => 600000),
        array('key' => 'TONGJI_MA', 'name' => '统计数据','id' => 700000, 'pId' => 0),
        array('key' => 'TONGJI_OUT', 'name' => '导出报表','id' => 700100, 'pId' => 700000),
        array('key' => 'LOGIN_LOG', 'name' => '登录日志','id' => 700200, 'pId' => 700000),
        array('key' => 'SUPER_MA', 'name' => '系统管理员管理','id' => 800000, 'pId' => 0),
        array('key' => 'SUPER_ADD', 'name' => '添加系统管理员','id' => 800100, 'pId' => 800000),
        array('key' => 'SUPER_LIST', 'name' => '系统管理员列表','id' => 800200, 'pId' => 800000),
        array('key' => 'SUPER_EDIT', 'name' => '系统管理员修改','id' => 800201, 'pId' => 800200),
        array('key' => 'SUPER_DEL', 'name' => '系统管理员删除','id' => 800202, 'pId' => 800200),
        array('key' => 'PERMISSION_MA', 'name' => '权限管理','id' => 900000, 'pId' => 0),
        array('key' => 'ROLE_ADD', 'name' => '添加角色','id' => 900100, 'pId' => 900000),
        array('key' => 'ROLE_LIST', 'name' => '角色列表','id' => 900200, 'pId' => 900000),
        array('key' => 'ROLE_EDIT', 'name' => '角色编辑','id' => 900201, 'pId' => 900200),
        array('key' => 'ROLE_DEL', 'name' => '角色删除','id' => 900202, 'pId' => 900200),
        array('key' => 'MR_LIST', 'name' => '会员角色管理','id' => 900300, 'pId' => 900000),
        array('key' => 'MR_EDIT', 'name' => '会员角色编辑','id' => 900301, 'pId' => 900300),
        array('key' => 'SYS_LIST', 'name' => '管理员角色管理','id' => 900400, 'pId' => 900000),
        array('key' => 'SYS_EDIT', 'name' => '管理员角色编辑','id' => 900401, 'pId' => 900400),
        array('key' => 'SYS_CONFIG_MA', 'name' => '系统设置','id' => 1000000, 'pId' => 0),
        array('key' => 'SYS_E_CONFIG', 'name' => '基本设置','id' => 1000100, 'pId' => 1000000),
        array('key' => 'SYS_D_CONFIG', 'name' => '提现设置','id' => 1000200, 'pId' => 1000000),
        array('key' => 'WEIXIN_MA', 'name' => '微信管理','id' => 1100000, 'pId' => 0),
        array('key' => 'WEIXIN_INFO', 'name' => '微信基本信息','id' => 1100100, 'pId' => 1100000),
        array('key' => 'WEIXIN_USER', 'name' => '微信用户列表','id' => 1100200, 'pId' => 1100000),
        array('key' => 'WEIXIN_UPDATE', 'name' => '更新微信用户','id' => 1100300, 'pId' => 1100000)
    );
    
    /**
     * Ucenter权限array
     * 结构：权限key，权限名称，权限id，父级id（0为根权限）
     */
    $infoArray2 = array(
        array('key' => 'ACCOUNT_MA', 'name' => '开户管理','id' => 100000, 'pId' => 0),
        array('key' => 'AGENT_LIST', 'name' => '代理/机构列表','id' => 100100, 'pId' => 100000),
        array('key' => 'AGENT_EDIT', 'name' => '代理/机构编辑','id' => 100101, 'pId' => 100100),
        array('key' => 'AGENT_DEL', 'name' => '代理/机构删除','id' => 100102, 'pId' => 100100),
        array('key' => 'AGENT_ADD', 'name' => '添加代理/机构','id' => 100200, 'pId' => 100000),
        array('key' => 'TRADE_MA', 'name' => '交易流水','id' => 200000, 'pId' => 0),
        array('key' => 'TRADE_LIST', 'name' => '交易流水列表','id' => 200100, 'pId' => 200000),
        array('key' => 'CUSTOMER_MA', 'name' => '客户管理','id' => 300000, 'pId' => 0),
        array('key' => 'CUSTOMER_LIST', 'name' => '客户列表','id' => 300100, 'pId' => 300000),
        array('key' => 'CUSTOMER_ADD', 'name' => '添加客户','id' => 300200, 'pId' => 300000),
        array('key' => 'REPORT', 'name' => '报表管理','id' => 301000, 'pId' => 0),
        array('key' => 'REPORT_EXPORT', 'name' => '导出报表','id' => 301001, 'pId' => 301000),
        array('key' => 'MY_MA', 'name' => '个人中心','id' => 400000, 'pId' => 0),
        array('key' => 'DEPOSIT', 'name' => '账户充值','id' => 400100, 'pId' => 400000),
        array('key' => 'WITHDRAW', 'name' => '账户提现','id' => 400200, 'pId' => 400000),
        array('key' => 'EQUITYLIST', 'name' => '权益流水','id' => 400300, 'pId' => 400000),
        array('key' => 'PERSONINFO', 'name' => '基本信息','id' => 400400, 'pId' => 400000),
        array('key' => 'UPDATEPWD', 'name' => '修改密码','id' => 400500, 'pId' => 400000)
    );
    
    if($type == 1){
        return $infoArray1;
    } elseif ($type == 2 || $type == 3 || $type == 4) {
        return $infoArray2;
    }
    
}

function getEditInfoArray($keyMap,$type){
    $infoArray = getPermissioninfoArray($type);
    $infoMap = getPermissionInfoMap($type);
    foreach ($infoArray as &$info){
        if($keyMap[$info['key']]){
            $info['checked'] = true;
            if($info['pId'] != 0){
                $pinfo = &$infoArray[$infoMap[$info['pId']]];
                $pinfo['open'] = true;
                if($pinfo['pId'] != 0){
                    $ppinfo = &$infoArray[$infoMap[$pinfo['pId']]];
                    $ppinfo['open'] = true;
                }
            }
        }
    }
    return $infoArray;
}

function  getPermissionInfoMap($type){
    $infoMap = array();
    $infoArray = getPermissioninfoArray($type);
    $i = 0;
    foreach ($infoArray as $item){
        $infoMap[$item['id']] = $i;
        $i++;
    }
    return $infoMap;
}
?>
