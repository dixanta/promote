<?php

class Api extends API_Controller
{
	public function __construct()
    {
    	parent::__construct();
        $this->load->module_model('guest','guest_model');
    }
    
    public function guest_get()
    {
    	//$this->get('param_name') for get method
    	$this->response(array('id'=>1),200);
    }

    public function guest_post()
    {
	    //$this->post('param_name') for post method
    	$this->response(array('success'=>TRUE),200);
    }
    
    public function guest_delete()
    {
	    //$this->post('param_name') for post method
    	$this->response(array('success'=>TRUE),200);
    }    
    
    
}