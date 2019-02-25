<?php

namespace Activity;

class ProductActivityIncrEasePriceRedemptionValidation extends Validation
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function validationAddressList($request){
        $rules = [
            'api_token' => 'required'
        ];
        return $this->return($request, $rules);
    }
}
