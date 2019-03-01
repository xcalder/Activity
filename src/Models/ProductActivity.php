<?php

namespace Activity\Models;

use Illuminate\Database\Eloquent\Model;

class ProductActivity extends Model
{
    protected $table = 'product_activity';
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
    
    public function rules()
    {
        return $this->hasMany('Activity\Models\ProductActivityRules', 'activity_id', 'id');
    }
}
