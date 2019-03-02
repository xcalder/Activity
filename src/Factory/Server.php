<?php

namespace Activity;

use Activity\Models\ProductActivity;
use Activity\Models\ProductActivityRuleProducts;

class Server
{
    private $id;//要添加的活动id
    
    private $activity;
    
    private $products;
    
    private $product_specification_value_to_product_ids;
    
    private $product_ids;
    
    private $activity_ids = [];
    
    /**
     * 取未开始/进行中的活动
     */
    public function getActivitys($activity_ids){
        if(!empty($this->activity)){
            return $this->activity;
        }
        $activity_ids[] = $this->id;
        $this->activity = ProductActivity::whereIn('id', $activity_ids)->whereIn('status', [0, 1])->get()->toArray();
        return $this->activity;
    }

    /**
     * 取未开始/进行中的商品
     */
    public function getProducts($product_ids, $product_specification_value_to_product_ids){
        if(!empty($this->products)){
            return $this->products;
        }
        $this->products = ProductActivityRuleProducts::whereIn('product_id', $product_ids)->whereIn('product_specification_value_to_product_id', $product_specification_value_to_product_ids)->whereIn('status', [0, 1, 5])->get()->toArray();
        $this->activity_ids = lumen_array_column($this->products, 'activity_id');
        return $this->products;
    }
    
    public function getProductSpecificationValueToProductIds($request){
        if(empty($request['data'])){
            return $request;
        }
        if(!empty($this->product_specification_value_to_product_ids)){
            return $this->product_specification_value_to_product_ids;
        }
        $product_specification_value_to_product = [];
        foreach ($request['data'] as $product){
            if(!empty($product['specification'])){
                foreach ($product['specification'] as $specification){
                    $product_specification_value_to_product[] = $specification;
                }
            }
        }
        $this->product_specification_value_to_product_ids = lumen_array_column($product_specification_value_to_product, 'product_specification_value_to_product_id');
        return $this->product_specification_value_to_product_ids;
    }
    
    public function getProductIds($request){
        if(empty($request['data'])){
            return $request;
        }
        if(!empty($this->product_ids)){
            return $this->product_ids;
        }
        $this->product_ids = lumen_array_column($request['data'], 'id');
        return $this->product_ids;
    }
    
    public function getActivityIds(){
        return $this->activity_ids;
    }
    
    public function checkoutDateTimeCoincide($request, $id){
        $this->id = intval($id);
        if(empty($id)){
            return $request;
        }
        if(empty($request['data'])){
            return $request;
        }
        $this->getProductIds($request);
        $this->getProductSpecificationValueToProductIds($request);
        $products = $this->getProducts($this->product_ids, $this->product_specification_value_to_product_ids);
        
        if(empty($products)){
            return $request;
        }
        
        $activitys = $this->getActivitys($this->activity_ids);
        
        if(empty($activitys)){
            return $request;
        }
        
        $activitys = array_under_reset($activitys, 'id');
        $this_activity = $activitys[$this->id] ?? [];
        
        if(empty($this_activity)){
            return $request;
        }
        
        $this_activity['started_at'] = strtotime($this_activity['started_at']);
        $this_activity['ended_at'] = strtotime($this_activity['ended_at']);
        
        $coincide_activity_ids = [];//重合的活动
        
        foreach ($activitys as $key=>$value){
            $value['started_at'] = strtotime($value['started_at']);
            $value['ended_at'] = strtotime($value['ended_at']);
            
            //当前活动开始时间 < 已有活动结束时间
            //当前活动结束时间 > 已有活动的开始时间
            if($this_activity['started_at'] <= $value['ended_at'] || $this_activity['ended_at'] >= $value['started_at']){
                $coincide_activity_ids[] = $value['id'];
            }
        }
        if(empty($coincide_activity_ids)){
            //没有重合活动
            return $request;
        }
        
        $coincide_product_specification_value_to_product_ids = [];
        foreach ($products as $key=>$value){
            if(in_array($value['activity_id'], $coincide_activity_ids)){
                $coincide_product_specification_value_to_product_ids[] = $value['product_specification_value_to_product_id'];
            }
        }
        $request['coincide_product_specification_value_to_product_ids'] = $coincide_product_specification_value_to_product_ids;
        return $request;
    }
}
