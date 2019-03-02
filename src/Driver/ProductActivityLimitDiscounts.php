<?php

namespace Activity;

use Activity\Models\ProductActivity;
use Activity\Models\ProductActivityRuleProducts;
use App\Service\ToolsService;
use Illuminate\Support\Facades\DB;
use Activity\Models\ProductActivityRuleRoles;

/**
 * 限时折扣方法类
 * @author xcalder
 *
 */
class ProductActivityLimitDiscounts implements ActivityInterface
{
    /**
     * 添加规则
     */
    public static function addActivityRule($request){
        
    }
    
    /**
     * 用id取活动规则
     * @param unknown $request
     */
    public static function getActivityRule($request){
        
    }
    
    /**
     * 删除规则
     */
    public static function delActivityRule($request){
        
    }
    
    /**
     * 添加商品
     */
    public static function addActivityProduct($request){
        $data = [];
        $data['status'] =  false;
        
        $rules = [
            'id' => 'required',
            'activity_id' => 'required',
            'rules_products' => 'required|array'
        ];
        $validation = new Validation();
        $result = $validation->return($request, $rules);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $activity_id = $request->input('activity_id');
        $rules_products = $request->input('rules_products');
        $products = [];
        $products_roles_price = [];
        $i = 0;
        
        $id = $request->input('id');
        $activity_info = ProductActivity::where('id', $id)->first();
        if(empty($activity_info)){
            return response()->json($data);
        }
        
        foreach ($rules_products as $key=>$value){
            if(isset($value['product_id'])){
                $products[$value['product_specification_value_to_product_id']]['product_id'] = $value['product_id'];
                $products[$value['product_specification_value_to_product_id']]['product_specification_value_to_product_id'] = $value['product_specification_value_to_product_id'];
                $products[$value['product_specification_value_to_product_id']]['activity_id'] = $activity_id;
                $products[$value['product_specification_value_to_product_id']]['status'] = $activity_info['status'];
                $products[$value['product_specification_value_to_product_id']]['sales_storage'] = $value['sales_storage'];
                
                $products_roles_price[$i]['product_id'] = $value['product_id'];
                $products_roles_price[$i]['role_id'] = $value['role_id'];
                $products_roles_price[$i]['price'] = $value['role_price'];
                $products_roles_price[$i]['activity_id'] = $id;
                $products_roles_price[$i]['product_specification_value_to_product_id'] = $value['product_specification_value_to_product_id'];
                $i++;
            }
        }
        
        DB::beginTransaction();
        try{
            //把已存在同一个活动中的删除
            $products_id = lumen_array_column($products, 'product_id');
            $products_specification_value_to_product_id = lumen_array_column($products, 'product_specification_value_to_product_id');
            ProductActivityRuleProducts::where('activity_id', $id)->whereIn('product_id', $products_id)->whereIn('product_specification_value_to_product_id', $products_specification_value_to_product_id)->delete();
            ProductActivityRuleRoles::where('activity_id', $id)->whereIn('product_id', $products_id)->whereIn('product_specification_value_to_product_id', $products_specification_value_to_product_id)->delete();
            
            ProductActivityRuleProducts::insert($products);
            ProductActivityRuleRoles::insert($products_roles_price);
            $data['id'] = $id;
            $data['status'] = true;
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
        }
        
        return response()->json($data);
    }
    
    /**
     * 删除商品
     */
    public static function delActivityProduct($request){
        $data = [];
        $data['status'] =  false;
        
        $rules = [
            'id' => 'required',
        ];
        $validation = new Validation();
        $result = $validation->return($request, $rules);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $roles_products = $request->input('rules_products');
        foreach ($roles_products as $key=>$value){
            if(!isset($value['product_id'])){
                unset($roles_products[$key]);
            }
        }
        if(!empty($roles_products)){
            $activity_id = $request->input('id');
            $product_id = lumen_array_column($roles_products, 'product_id');
            $product_specification_value_to_product_id = lumen_array_column($roles_products, 'product_specification_value_to_product_id');
            
            DB::beginTransaction();
            try{
                ProductActivityRuleProducts::where('activity_id', $activity_id)->whereIn('product_specification_value_to_product_id', $product_specification_value_to_product_id)->delete();
                ProductActivityRuleRoles::where('activity_id', $activity_id)->whereIn('product_specification_value_to_product_id', $product_specification_value_to_product_id)->delete();
                $data['status'] = true;
                DB::commit();
            }catch (\Exception $e) {
                DB::rollBack();
            }
        }
        return $data;
    }
    
    /**
     * 领取活动日志
     */
    public static function receiveActivityLog($request){
        
    }
    
    /**
     * 返回当前活动管理表单
     */
    public static function getManagetForm($request){
        $id = $request->input('id');
        $api_token = $request->input('api_token');
        $site_role = $request->input('site_role', 'sales');
        $action_product_search_form = url('/api/product/search?width=24&height=24&type=0&activity_search=1&id='.$id.'&api_token='.$api_token);
        $action_add_product_to_rule = url('/api/activity/add_product_to_activity_rule?site_role='.$site_role);
        $action_del_product_to_rule = url('/api/activity/del_product_to_activity_rule?site_role='.$site_role);
        echo <<<ETO
          <div class="modal-dialog modal-lg manager-activity-products" role="document">
            <div class="modal-content p-3" style="min-height: 600px">
                <h4>活动商品管理</h4>
                <form id="product-search-form" method="get" enctype="multipart/form-data" action="$action_product_search_form">
                    <table class="table product-list"><tbody><tr><td>分类</td><td><select class="form-control" name="category_id"></select></td><td>品牌</td><td><select class="form-control" name="brand_id"></select></td><td>标题</td><td><input name="keyword" class="form-control" type="text"></td><td><button type="submit" class="btn btn-default">搜索</button></td></tr></tbody></table>
                </form>
                <!-- Nav tabs -->
                  <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#joined" aria-controls="joined" role="tab" data-toggle="tab" >已加入</a></li>
                    <li role="presentation"><a href="#not-joined" aria-controls="not-joined" role="tab" data-toggle="tab">未加入</a></li>
                  </ul>
                
                  <!-- Tab panes -->
                  <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="joined">
                        <form id="unjoined-form" action="$action_del_product_to_rule" method="post" enctype="multipart/form-data">
                            <input type="hidden" name="api_token" value="$api_token">
                            <input type="hidden" name="id" value="$id">
                            <table class="table"><thead><tr><td><button class="btn btn-default btn-sm" type="submit">移出</button></td></tr><tr><td><input type="checkbox" class="select-all">全选</td><td>规格</td><td>活动数量</td><td>销量</td><td>角色名</td><td>角色价</td><td>商品名</td></tr></thead><tbody></tbody><tfoot></tfoot></table>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane" id="not-joined">
                        <form id="add-rule-products" method="post" enctype="multipart/form-data" action="$action_add_product_to_rule">
                            <input type="hidden" name="api_token" value="$api_token">
                            <input type="hidden" name="activity_id" value="$id">
                            <input type="hidden" name="id" value="$id">
                            <table class="table"><thead><tr><td><button class="btn btn-default btn-sm" type="submit">加入</button></td></tr><tr><td><input type="checkbox" class="select-all">全选</td><td>规格</td><td>活动数量</td><td>角色名</td><td>角色价</td><td>商品名</td></tr></thead><tbody></tbody><tfoot id="search-product-list-page"></tfoot></table>
                        </form>
                    </div>
                  </div>
            </div>
          </div>
        <script type="text/javascript">
            var this_roles;
            $(document).ready(function () {
                getroles();
                getBrand();
                getCategory();
                
                //添加商品到活动全选反选事件
                $(document).on('click','#add-rule-products .select-all',function(event){
                    if($(this).prop('checked')){
                        $('#add-rule-products .select-product').prop('checked', true);
                    }else{
                        $('#add-rule-products .select-product').prop('checked', false);
                    }
                });
                $(document).on('click','#add-rule-products .select-product',function(event){
                    changeProductselectAll('#add-rule-products');
                });
                //从活动中移出，全选反选事件
                $(document).on('click','#unjoined-form .select-all',function(event){
                    if($(this).prop('checked')){
                        $('#unjoined-form .select-product').prop('checked', true);
                    }else{
                        $('#unjoined-form .select-product').prop('checked', false);
                    }
                });
                $(document).on('click','#unjoined-form .select-product',function(event){
                    changeProductselectAll('#unjoined-form');
                });                

            })

            function changeProductselectAll(div){
                var select_all = true;
                $(div+' .select-product').each(function(e, item){
                    if(!$(this).prop('checked')){
                        select_all = false;
                        return false;
                    }
                });
                if(!select_all){
                    $(div+' .select-all').prop('checked', false);
                }else{
                    $(div+' .select-all').prop('checked', true);
                }
            }

            function getroles(){
                $.ajax({
            	    type: 'GET',
            	    url: url+'/api/role/get_roles',
            	    data: {api_token: api_token, set_price:1},
            	    dataType: 'json',
            	    success: function(data){
            	    	if(data.status){
                            this_roles = data.data.data;
                            getActivityRulesProducts();
                        }
                    }
                });
            }

            var options = {
    		   beforeSubmit: showRequestSearch,
    		   success: showResponseSearch,
    		   dataType: 'json',
    		   timeout: 3000
    		}
            //搜索商品用
            $('#product-search-form').ajaxForm(options);
            
            function showRequestSearch(formData, jqForm, options){
                $('a[aria-controls="not-joined"]').trigger('click');
            	return true;
            };  
            
            function showResponseSearch(responseText, statusText){
            	var data = responseText;
            	if(data.status){
                    var html = '';
                    var products = data.data.data;
                    var e = 0;
                    var coincide_specification_value_ids = data.data.coincide_product_specification_value_to_product_ids;
                    for(var i in products){
                        var product = products[i];
                        if(!isEmpty(product.specification)){
                            for(var c in product.specification){
                                var specification = product.specification[c];
                                html += '<tr>';
                                for(var d in this_roles){
                                    if(!isEmpty(coincide_specification_value_ids) && in_array(specification.product_specification_value_to_product_id, coincide_specification_value_ids)){
                                        html += '<td title="此商品在当前活动与另一个活动时间重合,不能添加"><input data-i="'+e+'" class="product-id-'+e+'" type="checkbox" name="rules_products['+e+'][product_id]" value="'+product.id+'" disabled><input class="product_specification_value_to_product_id-'+e+'" type="hidden" name="rules_products['+e+'][product_specification_value_to_product_id]" value="'+specification.product_specification_value_to_product_id+'" disabled></td>';
                                    }else{
                                        html += '<td><input data-i="'+e+'" class="select-product product-id-'+e+'" type="checkbox" name="rules_products['+e+'][product_id]" value="'+product.id+'"><input class="product_specification_value_to_product_id-'+e+'" type="hidden" name="rules_products['+e+'][product_specification_value_to_product_id]" value="'+specification.product_specification_value_to_product_id+'"></td>';
                                    }
                                    html += '<td>'+specification.specification_title+':'+specification.specification_value_title+'</td><td><input type="text" name="rules_products['+e+'][sales_storage]" value="0" class="form-control"></td><td>'+this_roles[d].name+'</td><td><input type="text" name="rules_products['+e+'][role_price]" class="form-control" value="0.00"><input type="hidden" name="rules_products['+e+'][role_id]" class="form-control" value="'+this_roles[d].id+'"></td><td><img src="'+product.thumb_img+'">'+product.title+'</td></tr>';
                                    e++;
                                }
                                e++;
                            }
                        }
                        e++;
                    }
                    $('#not-joined tbody').html(html);
            		pagination(data, '#search-product-list-page', 7);
            	}else{
            		toastr.warning('搜索失败');
            	}
            }

            function getCategory(){
                $.ajax({
            	    type: 'GET',
            	    url: url+'/api/category/get_list',
            	    data: {api_token: api_token},
            	    dataType: 'json',
            	    success: function(data){
            	    	if(data.status){
                            html = '<option value=0>--请选择--</option>';
                            for(var i in data.data.data){
                                var category = data.data.data[i];
                                html += '<option value="'+category.id+'">'+category.title+'</option>';
                            }
                            $('#product-search-form select[name="category_id"]').html(html);
                        }
                    }
                });
            }

            function getBrand(){
                $.ajax({
            	    type: 'GET',
            	    url: url+'/api/product_brand/get_list',
            	    data: {api_token: api_token},
            	    dataType: 'json',
            	    success: function(data){
            	    	if(data.status){
                            html = '<option value=0>--请选择--</option>';
                            for(var i in data.data.data){
                                var brand = data.data.data[i];
                                html += '<option value="'+brand.id+'">'+brand.title+'</option>';
                            }
                            $('#product-search-form select[name="brand_id"]').html(html);
                        }
                    }
                });
            }
    
            function getActivityRulesProducts(){
                var html = '';
                $.ajax({
            	    type: 'GET',
            	    url: url+'/api/activity/get_activity_rule_products?site_role=$site_role',
            	    data: {api_token: api_token,id:$id},
            	    dataType: 'json',
            	    success: function(data){
            	    	if(data.status){
                            var products = data.data.data;
                            var e = 0;
                            for(var i in products){
                                var product = products[i];
                                for(var d in product.roles_price){
                                    var role = product.roles_price[d];
                                    html += '<tr><td><input type="checkbox" name="rules_products['+e+'][product_id]" value="'+product.product_id+'" class="select-product"><input type="hidden" name="rules_products['+e+'][product_specification_value_to_product_id]" value="'+product.product_specification_value_to_product_id+'"><input type="hidden" name="rules_products['+e+'][rule_id]" value="'+product.activity_rules_id+'"></td><td>';
                                    for(var c in product.specification){
                                        html += c+';'+product.specification[c];
                                    }
                                    html += '</td><td>'+product.sales_storage+'</td><td>'+product.sales_volume+'</td>';
                                    for(var b in this_roles){
                                        if(this_roles[b].id == role.role_id){
                                             html += '<td>'+this_roles[b].name+'</td>';
                                        }
                                    }
                                    html += '<td>'+role.price+'</td><td><img src="'+product.thumb_img+'">'+product.title+'</td></tr>';
                                }
                                e++;
                            }
                            pagination(data, '#unjoined-form tfoot', 7);
                        }
                        $('#unjoined-form tbody').html(html);
                    }
                });
            }
            
            var options = {
    		   beforeSubmit: showRequestAddRuleProduct,
    		   success: showResponseAddRuleProduct,
    		   dataType: 'json',
    		   timeout: 3000
    		}
            //添加商品到活动
            $('#add-rule-products').ajaxForm(options);
            
            function showRequestAddRuleProduct(formData, jqForm, options){
            	return true;
            };  
            
            function showResponseAddRuleProduct(responseText, statusText){
            	var data = responseText;
                if(data.status){
                    getActivityRulesProducts(data.rule_id);
                    toastr.success('添加成功');
                }else{
                    toastr.warning('添加失败');
                }
            }

            var options = {
    		   beforeSubmit: showRequestDelProductToRule,
    		   success: showResponseDelProductToRule,
    		   dataType: 'json',
    		   timeout: 3000
    		}
            //创建使用条件表单
            $('#unjoined-form').ajaxForm(options);
            
            function showRequestDelProductToRule(formData, jqForm, options){
            	return true;
            };  
            
            function showResponseDelProductToRule(responseText, statusText){
            	var data = responseText;console.log(data);
            	if(data.status){
                    getActivityRulesProducts(data.rule_id);
            		toastr.success('删除成功');
            	}else{
            		toastr.warning('删除失败');
            	}
            }   

        </script>
ETO;
    }
        
    /**
     * 报名表单
     * @param unknown $request
     */
    public static function getApplyForm($request){
        
    }
    
    /**
     * 查活动详情
     */
    public static function getActivity($request){
        
    }
    
    /**
     * 用规则id查活动商品
     */
    public static function getActivityProducts($request){
        $data = [];
        $data['status'] =  false;
        
        $rules = [
            'id' => 'required'
        ];
        $validation = new Validation();
        $result = $validation->return($request, $rules);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $width = $request->input('width', 24);
        $height = $request->input('height', 24);
        
        $activity_id = $request->input('id');
        $result = ProductActivityRuleProducts::with(['rolesPrice'])->join('product_version as pv', function($join){
            $join->on('pv.product_id', '=', 'product_activity_rule_products.product_id')->on('pv.product_specification_value_to_product_id', '=', 'product_activity_rule_products.product_specification_value_to_product_id');
        })->where('product_activity_rule_products.activity_id', $activity_id)->select(['pv.product_id', 'pv.title', 'pv.specification', 'pv.img', 'product_activity_rule_products.activity_id', 'product_activity_rule_products.activity_rules_id', 'product_activity_rule_products.product_specification_value_to_product_id', 'product_activity_rule_products.sales_storage', 'product_activity_rule_products.sales_volume'])->paginate(env('PAGE_LIMIT', 25))->toArray();
        
        if(!empty($result['data'])){
            $roles_price = '';
            $data['status'] = true;
            $tools_service = new ToolsService();
            foreach ($result['data'] as $key=>$value){
                $result['data'][$key]['specification'] = unserialize($value['specification']);
                $result['data'][$key]['thumb_img'] = $tools_service->serviceResize($value['img'], $width, $height);
            }
        }
        
        $data['data'] = $result;
        return response()->json($data);
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
