<?php

namespace Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ProductActivityRuleProducts extends Model
{
    protected $table = 'product_activity_rule_products';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];
    
    public function roles()
    {
        return $this->hasMany('Activity\Models\ProductActivityRuleRoles', 'activity_rules_id', 'id');
    }
    
    public function rolesPrice()
    {
        return $this->hasMany('Activity\Models\ProductActivityRuleRoles', 'product_specification_value_to_product_id', 'product_specification_value_to_product_id');
    }
    
    /**
     * 禁止自动更新日期时间
     * @return NULL
     */
    public function getUpdatedAtColumn(){
        return null;
    }
}
