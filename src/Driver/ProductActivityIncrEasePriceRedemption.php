<?php

namespace Activity;

/**
 * 加价购方法类
 * @author xcalder
 *
 */
class ProductActivityIncrEasePriceRedemption implements ActivityInterface
{
    /**
     * 删除活动
     */
    public static function delActivity($request){
        
    }
    
    /**
     * 修改活动
     */
    public static function editActivity($request){
        
    }
    
    /**
     * 返回当前活动管理表单
     */
    public static function getManagetForm($request){
        echo <<<ETO
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content p-3">
                <form>
                    <div class="row">
                        <div class="col-md-6">
                          <h4>商品加价购管理</h4>
                          <!--
                          <div class="form-group">
                            <label for="total">发放总数</label>
                            <input type="text" class="form-control" id="total" placeholder="发放总数" value="0">
                            <p>可领取的总数量</p>
                          </div>
                          -->
                          <div class="form-group">
                            <label for="limit">使用条件</label>
                            <input type="text" class="form-control" id="limit" placeholder="使用条件" value="0.00">
                            <p>可使用的最低订单总金额</p>
                          </div>
                          <div class="form-group">
                            <label for="limit" class="btn-block">可使用的角色</label>
                            <div class="allowed-role mb-3"></div>
                            <p>指定勾选的角色可以领取并使用</p>
                          </div>
                          <button type="submit" class="btn btn-default">提交</button>
                        </div>
                      <div class="col-md-6">
                           <h4>商品列表</h4>
                      </div>
                    </div>
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function () {
                $.ajax({
            	    type: 'GET',
            	    url: url+'/api/role/get_roles',
            	    data: {api_token: api_token, set_price:1},
            	    dataType: 'json',
            	    success: function(data){
            	    	if(data.status){
                            var html = '';
                            for(var i in data.data.data){
                                var role = data.data.data[i];
                                html += '<label class="checkbox-inline">';
                                html += '<input type="checkbox" value="'+role.id+'">'+role.name;
                                html += '</label>';
                            }
                            $('.allowed-role').html(html);
                        }
                    }
                });
            })
        </script>
ETO;
    }
    
    /**
     * 查活动详情
     */
    public static function getActivity($request){
        
    }
    
    /**
     * 用商品id查活动商品
     */
    public static function getActivityProducts($request){
        
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
