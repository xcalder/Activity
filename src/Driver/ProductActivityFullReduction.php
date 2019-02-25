<?php

namespace Activity;

/**
 * 满减方法类
 * @author xcalder
 *
 */
class ProductActivityFullReduction implements ActivityInterface
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
     * 用商品id查活动商品
     */
    public static function getActivityProducts($request){
        
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
