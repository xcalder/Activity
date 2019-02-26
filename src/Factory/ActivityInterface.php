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
     * 删除活动
     */
    public static function delActivity($request);
    
    /**
     * 修改活动
     */
    public static function editActivity($request);
    
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
