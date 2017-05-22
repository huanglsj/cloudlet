<?php

/**
 * @author xierh
 *
 */
class MarketStatusJudge
{
    
    /**
     * 查询当前的品种设置的交易时间是否处于开市状态
     * @param int $pid 需要判断的品种ID
     * @param int $time 需要判断的时间点，如果为空，则获取当前时间
     * @return boolean
     */
    static public function isopen($pid,$intime=''){
        if($intime == ''){
            $intime = time();
        }
        //转换为日期的时间戳
        $curtdateime = strtotime(date('Y-m-d',$intime));
        $nextdatetime = $curtdateime + 86400;
        $predatetime = $curtdateime - 86400;
        
        $webconfiger = D('webconfig')->cache(true,60)->find();
        if($webconfiger['isopen'] == 1){
            return false;
        }
        
        $productinfo = D('productinfo')->where("pid=".$pid)->cache(true,60)->find();
        if($productinfo['status'] == '2'){
            //该品种停止交易
            return false;
        }
        $workcalendar = D('workcalendar');
        $currWorkinfo = $workcalendar->where('productpid='.$pid.' and fulldate='.$curtdateime)->cache(true,60)->find();
        $preWorkinfo = $workcalendar->where('productpid='.$pid.' and fulldate='.$predatetime)->cache(true,60)->find();
        
        //判断一个时间点属于当天市场还是前天市场
        $judgeResult = self::isInWhichDate($currWorkinfo['tradeperiod'],$preWorkinfo['tradeperiod'],$intime);
        if($judgeResult == 'today'){
            //当天市场，判断当天是否是开始日
            $isworkday = $currWorkinfo['isworkday'];
        }else if($judgeResult == 'yesterday'){
            //前一天市场
            $isworkday = $preWorkinfo['isworkday'];
        }else{
            //都不是
            return false;
        }
        
        if($isworkday == 0){
            return false;
        }
        return true;
    }
    
    /**
     * 判断一个时间点属于当天市场还是前天市场(假设开始交易时间开始日一定是当天)
     * @param json $curperiods 当天的开市时间参数
     * @param json $preperiods 前一天的开市时间参数
     * @param unknown $time
     * @return Multi today：当天市场，yesterday：前一天市场，false:都不是(可以判断为休市)
     */
    static private function isInWhichDate($curperiods,$preperiods,$time){
        $currperiodArr = json_decode($curperiods,true);
        $preperiodArr = json_decode($preperiods,true);
        $curDate = date('Y-m-d',$time);
        $nextdatetime = strtotime($curDate) + 86400;
        $predatetime = strtotime($curDate) - 86400;
        $preDate = date('Y-m-d',$predatetime);
        
        //判断是否属于当天市场
        if($curperiods){
            foreach ($currperiodArr as $today){
                if($today['plus'] == '0'){
                    //当天市场
                    $start = strtotime($curDate.' '.$today['s']);
                    $end = strtotime($curDate.' '.$today['e']);
                    if($time >= $start && $time < $end){
                        return 'today';
                    }
                }else if($today['plus'] == '1'){
                    $start = strtotime($curDate.' '.$today['s']);
                    $end = $nextdatetime;
                    if($time >= $start && $time < $end){
                        return 'today';
                    }
                }
            }
        }
        
        //判断是否属于前一天市场
        if($preperiods){
            foreach ($preperiodArr as $yesterday){
                if($yesterday['plus'] == '1'){
                    //前一天市场
                    if($yesterday['s'] < $yesterday['e']){
                        //开始时间也在第二天凌晨
                        $start = strtotime($curDate.' '.$yesterday['s']);
                    }else{
                        //开始时间在前一天
                        $start = strtotime($preDate.' '.$yesterday['s']);
                    }
                    //结束时间一定在当天
                    $end = strtotime($curDate.' '.$yesterday['e']);
                    if($time >= $start && $time < $end){
                        return 'yesterday';
                    }
                }
            }
        }
        return false;
    }
}

?>