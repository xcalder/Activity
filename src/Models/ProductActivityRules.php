<?php

namespace Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ProductActivityRules extends Model
{
    protected $table = 'product_activity_rules';
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
    
    /**
     * 禁止自动更新日期时间
     * @return NULL
     */
    public function getUpdatedAtColumn(){
        return null;
    }
}
