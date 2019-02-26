<?php

namespace Activity;

use Activity\Models\ProductActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Routing\Controller as BaseController;
use App\Service\ToolsService;
use Activity\Facades\Activity;

class IndexController extends BaseController
{
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
        
        $result = ProductActivity::where('type', $request->input('type'))->orderBy('id', 'desc')->paginate(env('PAGE_LIMIT', 25))->toArray();
        if(!empty($result['data'])){
            $config_activity = config('all_status.activity');
            $config_activity_order = $config_activity['order'];
            $config_activity_product = $config_activity['product'];
            $config_activity_order = array_under_reset($config_activity_order, 'type');
            $config_activity_product = array_under_reset($config_activity_product, 'type');
            $config_activity_status = config('all_status.activity_status');
            $tools_service = new ToolsService();
            foreach ($result['data'] as $key=>$value){
                $result['data'][$key]['type_text'] = $config_activity_order[$value['type']]['full_name'] ?? $config_activity_product[$value['type']]['full_name'];
                $result['data'][$key]['status_text'] = $config_activity_status[$value['status']];
                $result['data'][$key]['thumb_tag_img'] = $tools_service->serviceResize($value['tag_img'], $width, $height);
            }
            $data['status'] = true;
            $data['data'] = $result;
        }
        
        return response()->json($data);
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
            $config_activity = config('all_status.activity');
            $config_activity_order = $config_activity['order'];
            $config_activity_product = $config_activity['product'];
            $config_activity_order = array_under_reset($config_activity_order, 'type');
            $config_activity_product = array_under_reset($config_activity_product, 'type');
            $config_activity_status = config('all_status.activity_status');
            $tools_service = new ToolsService();
            
            $result['type_text'] = $config_activity_order[$result['type']]['full_name'] ?? $config_activity_product[$result['type']]['full_name'];
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
     * 修改活动详情
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
    
    public function getActivityManagerForm(Request $request){
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
        if(empty($result)){
            $data['error']['none'][0] = '没有找到要管理的活动';
            return response()->json($data);
        }
        
        $type = $result['type'];
        $driver_config = $this->getConfigDriver();
        if(empty($driver_config)){
            $data['error']['none'][0] = '没有找到要管理的活动';
            return response()->json($data);
        }
        Activity::with($driver_config[$type])->getManagetForm($request);
    }
    
    private function getConfigDriver(){
        $data = [];
        $config = config('all_status.activity');
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
}
