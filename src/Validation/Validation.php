<?php

namespace Activity;

use Illuminate\Support\Facades\Validator;

class Validation
{
    /**
     * Create a new Validation instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    
    public function addActivity($request, $rules){
        $rules = [
            'api_token' => 'required',
            'type' => 'required',
            'title' => 'required',
            'description' => 'required',
            'tag' => 'required',
            'tag_img' => 'required',
            'status' => 'required',
            'stared_at' => 'required',
            'ended_at' => 'required'
        ];
        return $this->return($request, $rules);
    }
    
    public function return($request, $rules){
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors();
        }
        return false;
    }
}
