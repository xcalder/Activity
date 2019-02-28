<?php

namespace Activity;

use Illuminate\Support\Facades\DB;
use Activity\Models\ProductActivityRules;
use Activity\Models\ProductActivityRuleRoles;
use Activity\Models\ProductActivityRuleProducts;

/**
 * 代金券活动接口
 * @author xcalder
 *
 */
class OrderActivityVoucher implements ActivityInterface
{
    /**
     * 添加规则
     */
    public static function addActivityRule($request){
        $data = [];
        $data['status'] =  false;
        
        $rules = [
            'id' => 'required',
            'limit' => 'required',
            'roles' => 'required|array',
            'price' => 'required',
            'total' => 'required',
            'get_limit' => 'required'
        ];
        $validation = new Validation();
        $result = $validation->return($request, $rules);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        $id = $request->input('id');
        $insert = [];
        $insert['activity_id'] = $id;
        $insert['limit'] = $request->input('limit');
        $insert['price'] = $request->input('price');
        $insert['total'] = $request->input('total');
        $insert['get_limit'] = $request->input('get_limit');
        $roles = $request->input('roles');
        
        DB::beginTransaction();
        try{
            $activity_info = ProductActivity::where('id', $id)->first();
            if(empty($activity_info)){
                return response()->json($data);
            }
            $insert['status'] = $activity_info['status'];
            $activity_rules_id = ProductActivityRules::insertGetId($insert);
            $insert_role = [];
            $i = 0;
            foreach ($roles as $role_id){
                $insert_role[$i]['role_id'] = $role_id;
                $insert_role[$i]['activity_id'] = $id;
                $insert_role[$i]['activity_rules_id'] = $activity_rules_id;
                $i++;
            }
            ProductActivityRuleRoles::insert($insert_role);
            $data['status'] = true;
            $data['id'] = $id;
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
        }
        return response()->json($data);
    }
    
    /**
     * 用id取活动规则
     * @param unknown $request
     */
    public static function getActivityRule($request){
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
        
        $id = $request->input('id');
        $result = ProductActivityRules::with('roles')->where('activity_id', $id)->get();
        $data['status'] = true;
        $data['data'] = $result;
        return response()->json($data);
    }
    
    /**
     * 删除规则
     */
    public static function delActivityRule($request){
        $data = [];
        $data['status'] =  false;
        
        $rules = [
            'id' => 'required',
            'rule_id' => 'required',
        ];
        $validation = new Validation();
        $result = $validation->return($request, $rules);
        if ($result) {
            $data['error'] = $result;
            return response()->json($data);
        }
        
        DB::beginTransaction();
        $id = $request->input('id');
        $rule_id = $request->input('rule_id');
        try{
            ProductActivityRules::where('id', $rule_id)->where('activity_id', $id)->delete();
            ProductActivityRuleRoles::where('activity_id', $id)->where('activity_rules_id', $rule_id)->delete();
            ProductActivityRuleProducts::where('activity_id', $id)->where('activity_rules_id', $rule_id)->delete();
            $data['status'] = true;
            DB::commit();
        }catch (\Exception $e) {
            DB::rollBack();
        }
        return response()->json($data);
    }
    
    /**
     * 添加商品
     */
    public static function addActivityProduct($request){
        //订单代金券 不涉及商品，些方法不用返回
    }
    
    /**
     * 删除商品
     */
    public static function delActivityProduct($request){
        //订单代金券 不涉及商品，些方法不用返回
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
        $action_product_search_form = url('/api/product/search?width=24&height=24');
        $action_add_product_to_rule = url('/api/activity/add_product_to_activity_rule?site_role='.$site_role);
        $action_del_product_to_rule = url('/api/activity/del_product_to_activity_rule?site_role='.$site_role);
        echo <<<ETO
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content p-3">
                <h4>订单代金券管理</h4>
                    <div class="row">
                        <table class="table mb-0">
                            <thead>
                                <tr><td class="w-260-g">规则详情</td><td>角色</td><td>操作</td></tr>
                            </thead>
                            <tbody id="OrderActivityFullDelivery-rules"></tbody>
                            <tfoot><tr><td colspan="3" class="text-right"><button class="btn btn-success btn-sm" type="button" onclick="add_rules();">添加</button></td></tr></tfoot>
                        </table>
                    </div>
            </div>
        </div>
        <div class="modal fade add-rolues" tabindex="-1" role="dialog">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content p-3">
                <form action="" id="add-rules-form" method="post" enctype="multipart/form-data">
                  <input type="hidden" name="api_token" value="$api_token">
                  <input type="hidden" name="id" value="$id">
                  <div class="form-group">
                    <label for="total">发放总数</label>
                    <input type="text" class="form-control" id="total" placeholder="发放总数" name="total" value="0">
                  </div>
                  <div class="form-group">
                    <label for="limit">金额限制</label>
                    <div class="input-group">
                      <span class="input-group-addon">满￥</span>
                      <input type="text" class="form-control" name="limit" value="0">
                      <span class="input-group-addon">.00</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="price">代金券面额</label>
                    <div class="input-group">
                      <span class="input-group-addon">￥</span>
                      <input type="text" class="form-control" name="price" value="0">
                      <span class="input-group-addon">.00</span>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="get_limit">每人限领</label>
                    <input type="text" class="form-control" id="get_limit" placeholder="发放总数" name="get_limit" value="0">
                    <p>0不限</p>
                  </div>
                  <div class="form-group">
                    <label for="roles" class="btn-block">角色</label>
                    <div class="allowed-role"></div>
                  </div>
                  <button type="submit" class="btn btn-default btn-block">提交</button>
                </form>
            </div>
          </div>
        </div>
        
        <script type="text/javascript">
            var this_roles;
            $(document).ready(function () {
                getroles();
            })
            
            function getroles(){
                $.ajax({
            	    type: 'GET',
            	    url: url+'/api/role/get_roles',
            	    data: {api_token: api_token, set_price:1},
            	    dataType: 'json',
            	    success: function(data){
            	    	if(data.status){
                            this_roles = data.data.data;
                            var html = '';
                            for(var i in data.data.data){
                                var role = data.data.data[i];
                                html += '<label class="checkbox-inline">';
                                html += '<input type="checkbox" name="roles[]" value="'+role.id+'">'+role.name;
                                html += '</label>';
                            }
                            $('.allowed-role').html(html);
                        }
                        getRules();
                    }
                });
            }
            
            var i = 0;
            function add_rules(){
                $('#add-rules-form').attr('action', url+'/api/activity/add_activity_rules?site_role=$site_role');
                $('.add-rolues input[name="total"]').val('0');
                $('.add-rolues input[name="price"]').val('0');
                $('.add-rolues input[name="roles[]"]').prop('checked', false);
                $('.add-rolues').modal();
            }
            
            var options = {
    		   beforeSubmit: showRequestAddRule,
    		   success: showResponseAddRule,
    		   dataType: 'json',
    		   timeout: 3000
    		}
            //创建使用条件表单
            $('#add-rules-form').ajaxForm(options);
            
            function showRequestAddRule(formData, jqForm, options){
            	return true;
            };
            
            function showResponseAddRule(responseText, statusText){
            	var data = responseText;console.log(data);
            	if(data.status){
            		$('.add-rolues').modal('hide');
                    getRules();
            		toastr.success('添加成功');
            	}else{
            		toastr.warning('添加失败');
            	}
            }
            function getRules(){
                $.ajax({
            	    type: 'GET',
            	    url: url+'/api/activity/get_activity_rule_list',
            	    data: {api_token: api_token, id:$id, site_role:'$site_role'},
            	    dataType: 'json',
            	    success: function(data){
            	    	if(data.status){
                            html = '';
                            if(!isEmpty(data.data)){
                                for(var i in data.data){
                                    var rule = data.data[i];
                                    html += '<tr>';
                                    html += '<td>满￥'+rule.limit+'减￥'+rule.price+';总'+rule.total+'张</td>';
                                    html += '<td>';
                                    if(!isEmpty(rule.roles) && !isEmpty(this_roles)){
                                        for(var d in this_roles){
                                            var this_role = this_roles[d];
                                            for(var c in rule.roles){
                                                var role = rule.roles[c];
                                                if(role.role_id == this_role.id){
                                                    html += this_role.name+';';
                                                }
                                            }
                                        }
                                    }
                                    html += '</td>';
                                    html += '<td>';
                                    
                                    html += '<div class="btn-group" role="group">';
                                    html += '<button type="button" class="btn btn-default" onclick="delete_rule('+rule.id+');">删除</button>';
                                    html += '</div>';
                                    
                                    html += '</td>';
                                    html += '</tr>';
                                }
                            }
                            $('#OrderActivityFullDelivery-rules').html(html);
                        }else{
                            toastr.warning('取规则失败');
                        }
                    }
                });
            }
            function delete_rule(rule_id){
                   $.ajax({
            	    type: 'DELETE',
            	    url: url+'/api/activity/del_activity_rule?site_role=$site_role',
            	    data: {api_token: api_token, id:$id, rule_id:rule_id},
            	    dataType: 'json',
            	    success: function(data){
            	    	if(data.status){
                            getRules();
            		        toastr.success('规则删除成功');
                        }else{
                            toastr.warning('规则删除失败');
                        }
                    }
                });
            }
        </script>
ETO;
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
        //订单代金券 不涉及商品，些方法不用返回
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