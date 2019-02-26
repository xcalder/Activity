<?php

namespace Activity;

use Illuminate\Http\Request;
use Laravel\Lumen\Routing\Controller as BaseController;

class IndexController extends BaseController
{
    //
    /**
     * 取所有活动
     * @param Request $request
     */
    public function getAllActivity(Request $request){
        
    }
    
    /**
     * 根据类型取活动
     * 包括详情
     * @param Request $request
     */
    public function getActivitys(Request $request){
        
    }
    
    /**
     * 用Id取活动详情
     * @param Request $request
     */
    public function getActivity(Request $request){
        
    }
    
    /**
     * 添加活动
     * @param Request $request
     */
    public function addActivity(Request $request){
        
    }
    
    /**
     * 删除一个活动
     * @param Request $request
     */
    public function delActivity(Request $request){
        
    }
    
    /**
     * 修改活动
     * @param Request $request
     */
    public function editActivity(Request $request){
        
    }
    
    /**
     * 验证订单是否满足活动
     * @param Request $request
     */
    public function checkoutActivity(Request $request){
        
    }
    
    /**
     * 取活动配置
     * @param Request $request
     */
    public function getActivityConfig(Request $request){
        $config = config('all_status.activity');
        $data = [];
        $data['status'] = true;
        $data['config'] = $config;
        return response()->json($data);
    }
}
