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
    
    private $array_config = [];
    
    private $config_tag_type = [];
    
    private $config_rule_text = [];
    
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
        $this->products = ProductActivityRuleProducts::whereIn('product_id', $product_ids)->whereIn('product_specification_value_to_product_id', $product_specification_value_to_product_ids)->whereIn('status', [0, 1, 3,5])->get()->toArray();
        $this->activity_ids = lumen_array_column($this->products, 'activity_id');
        return $this->products;
    }
    
    /**
     * 取正在活动中的商品
     * @param unknown $product_ids
     * @param unknown $product_specification_value_to_product_ids
     * @return unknown
     */
    public function getRuningProducts($product_ids, $product_specification_value_to_product_ids){
        if(empty($this->products)){
            //return $this->products;
        }
        $this->products = ProductActivityRuleProducts::join('product_activity as pa', function($join){
            $join->on('pa.id', '=', 'product_activity_rule_products.activity_id');
        })->where('product_activity_rule_products.rule_product_type', 1)->whereIn('product_activity_rule_products.product_id', $product_ids)->whereIn('product_activity_rule_products.product_specification_value_to_product_id', $product_specification_value_to_product_ids)->where('product_activity_rule_products.status', 6)->select(['pa.id', 'product_activity_rule_products.activity_id', 'pa.tag', 'pa.type', 'pa.tag_img','product_activity_rule_products.product_id'])->get()->toArray();
        //$this->activity_ids = lumen_array_column($this->products, 'activity_id');
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
    
    public function checkoutActivityToProductList($request){
        if(empty($request['data'])){
            return $request;
        }
        $this->getProductIds($request);
        $this->getProductSpecificationValueToProductIds($request);
        $products = $this->getRuningProducts($this->product_ids, $this->product_specification_value_to_product_ids);
        
        if(empty($products)){
            foreach ($request['data'] as $key=>$value){
                $request['data'][$key]['activitys'] = [];
            }
            return $request;
        }
        
        $this->getActivityConfig(config('all_status.activity'));
        foreach ($products as $key=>$value){
            $products[$key]['tag_type'] = $this->config_tag_type[$value['type']] ?? '';
        }
        $products = array_under_reset($products, 'product_id', 2);
        if(!empty($products)){
            foreach ($products as $key=>$value){
                $products[$key] = array_under_reset($value, 'activity_id');
            }
        }
        foreach ($request['data'] as $key=>$value){
            $request['data'][$key]['activitys'] = $products[$value['id']] ?? [];
        }
        return $request;
    }
    
    public function checkoutActivityToProduct($request){
        if(empty($request)){
            return $request;
        }
        $activitys =  ProductActivityRuleProducts::join('product_activity_rules as par', function($join){
            $join->on('par.id', '=', 'product_activity_rule_products.activity_rules_id');
        })->where('product_activity_rule_products.product_id', $request['id'])->where('product_activity_rule_products.status', 6)->where('product_activity_rule_products.rule_product_type', 1)->get()->toArray();
        if(!empty($activitys)){
            $this->getActivityConfig(config('all_status.activity'));
            foreach ($activitys as $key=>$value){
                //$activitys[$key]['rule_text'] = sprintf($this->config_rule_text[$value['type']], $value['total']);
            }
        }
        $request['activitys'] = $activitys;
        return $request;
    }
    
    private function getActivityConfig($activity_config){
        if(!empty($activity_config)){
            foreach ($activity_config as $key=>$value){
                if(is_array($value) && !isset($value['name'])){
                    $this->getActivityConfig($value);
                }else{
                    $this->config_tag_type[$value['type']] = $value['tag_type'] ?? '';
                    $this->array_config[$value['type']] = $value['name'] ?? '';
                    $this->config_rule_text[$value['type']] = $value['rule_text'] ?? '';
                }
            }
        }
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
