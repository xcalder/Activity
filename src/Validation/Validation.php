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
        $date = date("Y-m-d");
        $start_time = $request->input('started_at', $date);
        $rules = [
            'api_token' => 'required',
            'title' => 'required',
            'description' => 'required',
            'tag' => 'required',
            'tag_img' => 'required',
            'started_at' => "required|date_format:Y-m-d H:i:s|after_or_equal:$date",
            'ended_at' => "required|date_format:Y-m-d H:i:s|after_or_equal:$start_time"
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
