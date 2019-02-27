<?php

namespace Activity;

/**
 * 订单活动接口
 * 所有的订单活动实现此接口，并在业务逻辑中实例化
 * @author xcalder
 *
 */
interface ActivityInterface
{
    /**
     * 添加规则
     */
    public static function addActivityRule($request);
    
    /**
     * 用id取活动规则
     * @param unknown $request
     */
    public static function getActivityRule($request);
    
    /**
     * 删除规则
     */
    public static function delActivityRule($request);
    
    /**
     * 添加商品
     */
    public static function addActivityProduct($request);
    
    /**
     * 删除商品
     */
    public static function delActivityProduct($request);
    
    /**
     * 领取活动日志
     */
    public static function receiveActivityLog($request);
    
    /**
     * 返回当前活动管理的表单
     */
    public static function getManagetForm($request);
    
    /**
     * 查活动详情
     */
    public static function getActivity($request);
    
    /**
     * 验证活动状态/优惠条件是否满足
     */
    public static function checkout($request);
    
    /**
     * 下单更新活动方法
     */
    public static function orderChangeActivity($request);
    
    /**
     * 队列处理活动方法
     * 比如活动开始/结束
     */
    public static function queue($request);
}
