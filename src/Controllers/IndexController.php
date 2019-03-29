<?php

namespace Activity;

use Activity\Models\ProductActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Service\ToolsService;
use Activity\Facades\Activity;
use Activity\Models\ProductActivityRuleRoles;

class IndexController extends BaseController
{
    /**
     * 取所有的平台活动，给商家报名用
     * @param Request $request
     */
    public function getSiteActivity(Request $request){
        $data = [];
        $data['status'] = false;
        $site_activity_config = config('all_status.activity.admin.product');
        if(empty($site_activity_config)){
            return response()->json($data);
        }
        $types = lumen_array_column($site_activity_config, 'type');
        $result = ProductActivity::with(['rules'])->whereIn('type', $types)->orderBy('status', 'asc')->orderBy('started_at', 'desc')->paginate(env('PAGE_LIMIT', 25))->toArray();
        $site_activity_config = array_under_reset($site_activity_config, 'type');
        
        if(!empty($result['data'])){
            $site_activity_status = config('all_status.site_activity_status');
            $rule_ids = [];
            foreach ($result['data'] as $key=>$value){
                $result['data'][$key]['status_text'] = $site_activity_status[$value['status']] ?? '';
                $result['data'][$key]['type_text'] = $site_activity_config[$value['type']]['name'];
                $rule_text = $site_activity_config[$value['type']]['rule_text'];
                if(!empty($value['rules'])){
                    foreach ($value['rules'] as $k=>$v){
                        $result['data'][$key]['rules'][$k]['rule_text'] = sprintf($rule_text, $v['limit'], $v['price'], $v['get_limit']);
                        $rule_ids[] = $v['id'];
                    }
                    //取角色
                }
            }
            $rule_ids = array_unique(array_filter($rule_ids));
            $roles = ProductActivityRuleRoles::whereIn('product_activity_rule_roles.activity_rules_id', $rule_ids)->join('users_roles as ur', function($join){
                $join->on('ur.id', '=', 'product_activity_rule_roles.role_id');
            })->select(['ur.tag', 'ur.id', 'product_activity_rule_roles.activity_rules_id'])->get();
            
            $data['roles'] = $roles;
            $data['data'] = $result;
            $data['status'] = true;
        }
        
        return response()->json($data);
    }
    
    /**
     * 用id取活动规则列表
     * @param Request $request
     */
    public function getActivityRuleList(Request $request){
        $driver = $this->getDriver($request);
        if(!$driver){
            return response()->json(['status' => false]);
        }
        return Activity::with($driver)->getActivityRule($request);
    }
    
    public function delRulesProduct(Request $request){
        $driver = $this->getDriver($request);
        if(!$driver){
            return response()->json(['status' => false]);
        }
        return Activity::with($driver)->delActivityProduct($request);
    }
    
    /**
     * 添加商品到活动规则
     * @param Request $request
     */
    public function addProductToActivityRule(Request $request){
        $driver = $this->getDriver($request);
        if(!$driver){
            return response()->json(['status' => false]);
        }
        return Activity::with($driver)->addActivityProduct($request);
    }
    
    /**
     * 用id删除一个活动规则
     * @param Request $request
     */
    public function delActivityRule(Request $request){
        $driver = $this->getDriver($request);
        if(!$driver){
            return response()->json(['status' => false]);
        }
        return Activity::with($driver)->delActivityRule($request);
    }
    
    /**
     * 添加一个活动规则
     * @param Request $request
     */
    public function addActivityRule(Request $request){
        $driver = $this->getDriver($request);
        if(!$driver){
            return response()->json(['status' => false]);
        }
        return Activity::with($driver)->addActivityRule($request);
    }
    /**
     * 根据类型取活动
     * @param Request $request
     */
    public function getActivitysForType(Request $request){
        $data = [];
        $data['status'] =  false;
        
        $validation = new Validation();
        $result = $validation->ActivitysForType($request);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $width = $request->input('width', 36);
        $height = $request->input('height', 36);
        
        $user = Auth::user();
        $user_id = $user['id'];
        $store_id = $user['store_id'] ?? 0;
        
        $result = ProductActivity::where('type', $request->input('type'))->where('store_id', $store_id)->orderBy('id', 'desc')->paginate(env('PAGE_LIMIT', 25))->toArray();
        if(!empty($result['data'])){
            $config_activity = config('all_status.activity')[$request->input('site_role')];
            $config_activity_order = $config_activity['order'] ?? [];
            $config_activity_product = $config_activity['product'] ?? [];
            $config_activity_order = array_under_reset($config_activity_order, 'type');
            $config_activity_product = array_under_reset($config_activity_product, 'type');
            $config_activity_status = config('all_status.activity_status');
            $tools_service = new ToolsService();
            foreach ($result['data'] as $key=>$value){
                $result['data'][$key]['type_text'] = $config_activity_order[$value['type']]['full_name'] ?? ($config_activity_product[$value['type']]['full_name'] ?? []);
                $result['data'][$key]['status_text'] = $config_activity_status[$value['status']];
                $result['data'][$key]['thumb_tag_img'] = $tools_service->serviceResize($value['tag_img'], $width, $height);
            }
            $data['status'] = true;
            $data['data'] = $result;
        }
        
        return response()->json($data);
    }
    
    /**
     * 用rule_id 取活动规则下的商品
     * @param Request $request
     */
    public function getActivityRuleProducts(Request $request){
        $driver = $this->getDriver($request);
        if(!$driver){
            return response()->json(['status' => false]);
        }
        return Activity::with($driver)->getActivityProducts($request);
    }
    
    /**
     * 用Id取活动
     * @param Request $request
     */
    public function getActivity(Request $request){
        $data = [];
        $data['status'] =  false;
        
        $validation = new Validation();
        $result = $validation->getActivity($request);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $width = $request->input('width', 36);
        $height = $request->input('height', 36);
        
        $user = Auth::user();
        $user_id = $user['id'];
        $store_id = $user['store_id'] ?? 0;
        
        $result = ProductActivity::where('id', $request->input('id'))->first();
        
        if(!empty($result)){
            $config_activity = config('all_status.activity')[$request->input('site_role')];
            $config_activity_order = $config_activity['order'] ?? [];
            $config_activity_product = $config_activity['product'] ?? [];
            $config_activity_order = array_under_reset($config_activity_order, 'type');
            $config_activity_product = array_under_reset($config_activity_product, 'type');
            $config_activity_status = config('all_status.activity_status');
            
            $tools_service = new ToolsService();
            $result['type_text'] = $config_activity_order[$result['type']]['full_name'] ?? ($config_activity_product[$result['type']]['full_name'] ?? []);
            $result['status_text'] = $config_activity_status[$result['status']];
            $result['thumb_tag_img'] = $tools_service->serviceResize($result['tag_img'], $width, $height);
            
            $data['status'] = true;
            $data['data'] = $result;
        }
        
        return response()->json($data);
    }
    
    /**
     * 添加或修改一个活动
     * @param Request $request
     */
    public function addActivity(Request $request){
        $data = [];
        $data['status'] =  false;
        
        $validation = new Validation();
        $result = $validation->addActivity($request);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $user = Auth::user();
        $user_id = $user['id'];
        $store_id = $user['store_id'] ?? 0;
        
        $date = date("Y-m-d H:i:s");
        $insert = [];
        $insert['title'] = $request->input('title');
        $insert['description'] = $request->input('description');
        $insert['tag'] = $request->input('tag');
        $insert['tag_img'] = $request->input('tag_img');
        $insert['started_at'] = $request->input('started_at');
        $insert['ended_at'] = $request->input('ended_at');
        $insert['store_id'] = $store_id;
        $insert['updated_at'] = $date;
        if($request->input('status', 0) != 2){
            $insert['status'] = $request->input('status');
        }
        if(!$request->has('id')){
            $insert['created_at'] = $date;
            $insert['type'] = $request->input('type');
            $result = ProductActivity::insert($insert);
        }else{
            $result = ProductActivity::where('id', $request->input('id'))->update($insert);
        }
        if($result){
            $data['status'] = true;
        }
        
        return response()->json($data);
    }
    
    /**
     * 删除一个活动
     * @param Request $request
     */
    public function delActivity(Request $request){
        
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
        $data = [];
        $data['status'] =  false;
        
        $rules = [
            'site_role' => 'required'
        ];
        $validation = new Validation();
        $result = $validation->return($request, $rules);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $config = config('all_status.activity');
        $config = $config[$request->input('site_role', 'sales')];
        $data = [];
        $data['status'] = true;
        $data['config'] = $config;
        return response()->json($data);
    }
    
    public function getActivityManagerForm(Request $request){
        $driver = $this->getDriver($request);
        if(!$driver){
            return response()->json(['status' => false]);
        }
        Activity::with($driver)->getManagetForm($request);
    }
    
    public function getActivityApplyForm(Request $request){
        $driver = $this->getDriver($request);
        if(!$driver){
            return response()->json(['status' => false]);
        }
        Activity::with($driver)->getApplyForm($request);
    }
    
    private function getConfigDriver($request){
        $data = [];
        $data['status'] =  false;
        
        $rules = [
            'site_role' => 'required'
        ];
        $validation = new Validation();
        $result = $validation->return($request, $rules);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $config = config('all_status.activity');
        $config = $config[$request->input('site_role', 'sales')];
        
        if(!empty($config['order'])){
            foreach ($config['order'] as $key=>$value){
                $data[$value['type']] = $key;
            }
        }
        if(!empty($config['product'])){
            foreach ($config['product'] as $key=>$value){
                $data[$value['type']] = $key;
            }
        }
        return $data;
    }
    
    private function getDriver($request){
        $validation = new Validation();
        $result = $validation->getActivity($request);
        if ($result) {
            return false;
        }
        
        $id = $request->input('id');
        
        $result = ProductActivity::where('id', $id)->first();
        if(empty($result)){
            return false;
        }
        
        $type = $result['type'];
        $driver_config = $this->getConfigDriver($request);
        if(empty($driver_config)){
            return false;
        }
        return $driver_config[$type];
    }
    
    /**
     * 取活动日志列表
     * @param Request $request
     */
    public function getActivityLog(Request $request){
        
    }
    
    /**
     * 添加活动日志
     * @param Request $request
     */
    public function addActivityLog(Request $request){
        
    }
}
