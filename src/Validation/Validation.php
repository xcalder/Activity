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
    
    public function addActivity($request){
        $rules = [
            'api_token' => 'required',
            'title' => 'required',
            'description' => 'required',
            'tag' => 'required',
            'tag_img' => 'required',
            'started_at' => 'required',
            'ended_at' => 'required'
        ];
        if(empty($request->input('id'))){
            $rules['type'] = 'required';
            $rules['status'] = 'required';
        }
        if($request->has('id')){
            $rules['id'] = 'required';
        }
        return $this->return($request, $rules);
    }
    
    public function ActivitysForType($request){
        $rules = [
            'api_token' => 'required',
            'type' => 'required'
        ];
        return $this->return($request, $rules);
    }
    
    public function getActivity($request){
        $rules = [
            'api_token' => 'required',
            'id' => 'required'
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
