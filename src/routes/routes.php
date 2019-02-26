<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'api'], function($router){
    $router->group(['prefix' => 'activity'], function($router){
        $router->get('get_activitys_for_type', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivitysForType',
            'description' => '根据活动类型取活动，包括详情'
        ]);
        $router->get('get_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivity',
            'description' => '用Id取活动'
        ]);
        $router->get('get_activity_manager_form', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@getActivityManagerForm',
            'description' => '用Id取活动管理详情表单'
        ]);
        $router->post('add_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@addActivity',
            'description' => '添加活动'
        ]);
        $router->delete('del_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@delActivity',
            'description' => '删除活动'
        ]);
        $router->put('edit_activity', [
            'group' => 'activity',
            'uses' => 'Activity\IndexController@editActivity',
            'description' => '修改活动'
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