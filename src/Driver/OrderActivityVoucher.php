<?php

namespace Activity;

/**
 * 代金券活动接口
 * @author xcalder
 *
 */
class OrderActivityVoucher implements ActivityInterface
{
    /**
     * 新建活动
     */
    public static function addActivity($request){
        echo '1<br/>';
    }
    
    /**
     * 删除活动
     */
    public static function delActivity($request){
        
    }
    
    /**
     * 修改活动
     */
    public static function editActivity($request){
        
    }
    
    /**
     * 查活动列表
     */
    public static function getActivitys($request){
        
    }
    
    /**
     * 查活动详情
     */
    public static function getActivity($request){
        
    }
    
    /**
     * 验证活动状态/优惠条件是否满足
     */
    public static function checkout($request){
        
    }
    
    /**
     * 下单更新活动方法
     */
    public static function orderChangeActivity($request){
        
    }
    
    /**
     * 队列处理活动方法
     * 比如活动开始/结束
     */
    public static function queue($request){
        
    }
}