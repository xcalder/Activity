<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function($router){
    $router->group(['prefix' => 'activity'], function($router){
        $router->get('get_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivity',
            'description' => '用Id取活动'
        ]);
        $router->get('get_site_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getSiteActivity',
            'description' => '取所有的平台活动，给商家报名用'
        ]);
        $router->get('get_activitys_for_type', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivitysForType',
            'description' => '根据活动类型取活动，包括详情'
        ]);
        $router->get('get_activity_rule_list', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivityRuleList',
            'description' => '用Id取活动规则列表'
        ]);
        $router->delete('del_activity_rule', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@delActivityRule',
            'description' => '用Id删除一个活动规则'
        ]);
        $router->get('get_activity_manager_form', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivityManagerForm',
            'description' => '用Id取活动管理表单'
        ]);
        $router->get('get_activity_apply_form', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivityApplyForm',
            'description' => '用Id取活动报名表单'
        ]);
        $router->get('get_activity_rule_products', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivityRuleProducts',
            'description' => '用rule_id取活动规则下的商品//已加入活动'
        ]);
        $router->post('del_product_to_activity_rule', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@delRulesProduct',
            'description' => '删除已加入活动的商品'
        ]);
        $router->post('add_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@addActivity',
            'description' => '添加活动'
        ]);
        $router->post('add_product_to_activity_rule', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@addProductToActivityRule',
            'description' => '添加商品到活动规则'
        ]);
        $router->post('add_activity_rules', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@addActivityRule',
            'description' => '添加活动规则'
        ]);
        $router->delete('del_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@delActivity',
            'description' => '删除活动'
        ]);
        $router->post('checkout_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@checkoutActivity',
            'description' => '验证订单是否满足活动'
        ]);
        $router->get('get_activity_config', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivityConfig',
            'description' => '取活动配置'
        ]);
    });
});