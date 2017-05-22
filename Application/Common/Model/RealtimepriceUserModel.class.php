<?php

namespace Common\Model;
use Think\Model;

class RealtimepriceUserModel extends Model {

    /**
     * 添加数据
     * @return boolean 更新状态
     */
    public function addData($orderData=array()){

        if(!$orderData){
            return false;
        }

        $orderData['lost_ext'] = json_decode($orderData['lost_ext'], true);

        $data = array();
        $data['oid'] = $orderData['lost_ext']['oid'];
        $data['uid'] = $orderData['lost_ext']['uid'];
        $data['pid'] = $orderData['lost_ext']['pid'];
        $data['newprice'] = $orderData['lost_ext']['lost_point'];
        $data['recvtime'] = $orderData['lost_time'];
        $data['dealtime'] = $orderData['lost_time'];
        $res = $this->add($data);

        return $res;
    }

    public function deleteByOid($oid){
        if(!$oid){
            return false;
        }
        $map = array('oid'=>$oid);
        return $this->where($map)->delete();
    }

    public function getlistByUid($uid, $pid, $time, $type=1){
        $data = array();
        if($uid && $pid && $time){
            $map = array('uid'=>$uid, 'pid'=>$pid);
            $map['dealtime'] = array('elt', $time);
            if($type == 2){
                $data = $this->where($map)->order('dealtime asc')->limit(0,1)->select();
            }else{
                $data = $this->where($map)->order('dealtime asc')->select();
            }
        }

        return $data;
    }

    public function getKline($data, $interval, $uid, $pid, $time, $type){
        if($uid && $pid){
            $listRU = D('Common/RealtimepriceUser')->getlistByUid($uid, $pid, $time, $type);
            if($listRU){
                foreach($data as $key=>&$val){
                    foreach($listRU as $key2=>$val2){
                        if($interval == 0){
                            if(isset($highprice) || !empty($highprice)){
                                $highprice = $val['highest'];
                            }

                            if(isset($lowprice) || !empty($lowprice)){
                                $lowprice = $val['lowest'];
                            }

                            $highprice = $highprice ? $highprice : $val['highest'];
                            $lowprice = $lowprice ? $lowprice : $val['lowest'];

                            if($type == 2){
                                $timeDur = 1;
                            }else{
                                $timeDur = 30;
                            }

                            if(($val['time'] / 1000) >= $val2['dealtime'] && ($val['time'] / 1000) <= ($val2['dealtime'] + $timeDur)){ //接近真实行情时间的替换
                                if($val['highest'] < $val2['newprice']){
                                    $highprice = $val2['newprice'];
                                }elseif($val['lowest'] > $val2['newprice']){
                                    $lowprice = $val2['newprice'];
                                }

                                $val['price'] = $val2['newprice'];
                                unset($listRU[$key2]);
                            }
                            $val['highest'] = $highprice;
                            $val['lowest'] = $lowprice;
                        }else{

                            if($val['starttime'] <= $val2['dealtime'] && ($val['time'] / 1000) >= $val2['dealtime']){
                                if($val['highest'] < $val2['newprice']){
                                    $val['highest'] = $val2['newprice'];
                                }elseif($val['lowest'] > $val2['newprice']){
                                    $val['lowest'] = $val2['newprice'];
                                }
                            }

                        }
                    }
                }
            }
        }

        return $data;
    }

}
