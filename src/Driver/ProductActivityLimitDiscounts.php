<?php

namespace Activity;

/**
 * 限时折扣方法类
 * @author xcalder
 *
 */
class ProductActivityLimitDiscounts implements ActivityInterface
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
                <h4>商品列表</h4>
                <form>
                    <table class="table product-activity-limit-discounts">
                        <thead><tr><td><input type="checkbox"></td><td>商品名</td><td>规格</td><td>成本价</td><td>售价</td><td>真实库存</td><td>销售库存</td><td>活动库存</td></tr></thead>
                        <tbody></tbody>
                        <tfoot>
                            <tr>
                                <td colspan="10"><input type="checkbox"><span>全选</span><span>提交</span><span>删除</span></td>
                            </tr>
                        </tfoot>
                    </table>
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
                                html += '<td colspan="2">'+role.name+'</td>';
                            }
                            $('.product-activity-limit-discounts thead tr').append(html);
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
