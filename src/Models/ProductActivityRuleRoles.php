<?php

namespace Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ProductActivityRuleRoles extends Model
{
    protected $table = 'product_activity_rule_roles';
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
    
    /**
     * 表明模型是否应该被打上时间戳
     *
     * @var bool
     */
    public $timestamps = false;
}
