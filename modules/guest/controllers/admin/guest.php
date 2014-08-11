<?php

class Guest extends Admin_Controller
{
	
	public function __construct(){
    	parent::__construct();
        $this->load->module_model('guest','guest_model');
        $this->lang->module_load('guest','guest');
        //$this->bep_assets->load_asset('jquery.upload'); // uncomment if image ajax upload
    }
    
	public function index()
	{
		// Display Page
		$data['header'] = 'guest';
		$data['page'] = $this->config->item('template_admin') . "guest/index";
		$data['module'] = 'guest';
		$this->load->view($this->_container,$data);		
	}

	public function json()
	{
		$this->_get_search_param();	
		$total=$this->guest_model->count();
		paging('guest_id');
		$this->_get_search_param();	
		$rows=$this->guest_model->getGuests()->result_array();
		echo json_encode(array('total'=>$total,'rows'=>$rows));
	}
	
	public function _get_search_param()
	{
		// Search Param Goes Here
		parse_str($this->input->post('data'),$params);
		if(!empty($params['search']))
		{
			($params['search']['first_name']!='')?$this->db->like('first_name',$params['search']['first_name']):'';
($params['search']['last_name']!='')?$this->db->like('last_name',$params['search']['last_name']):'';
($params['search']['email']!='')?$this->db->like('email',$params['search']['email']):'';
($params['search']['contact_no']!='')?$this->db->like('contact_no',$params['search']['contact_no']):'';
($params['search']['promoter_id']!='')?$this->db->where('promoter_id',$params['search']['promoter_id']):'';

		}  

		
		if(!empty($params['date']))
		{
			foreach($params['date'] as $key=>$value){
				$this->_datewise($key,$value['from'],$value['to']);	
			}
		}
		               
        
	}

		
    
	public function combo_json()
    {
		$rows=$this->guest_model->getGuests()->result_array();
		echo json_encode($rows);    	
    }    
    
	public function delete_json()
	{
    	$id=$this->input->post('id');
		if($id && is_array($id))
		{
        	foreach($id as $row):
				$this->guest_model->delete('GUESTS',array('guest_id'=>$row));
            endforeach;
		}
	}    

	public function save()
	{
		
        $data=$this->_get_posted_data(); //Retrive Posted Data		

        if(!$this->input->post('guest_id'))
        {
            $success=$this->guest_model->insert('GUESTS',$data);
        }
        else
        {
            $success=$this->guest_model->update('GUESTS',$data,array('guest_id'=>$data['guest_id']));
        }
        
		if($success)
		{
			$success = TRUE;
			$msg=lang('success_message'); 
		} 
		else
		{
			$success = FALSE;
			$msg=lang('failure_message');
		}
		 
		 echo json_encode(array('msg'=>$msg,'success'=>$success));		
        
	}
   
   private function _get_posted_data()
   {
   		$data=array();
        $data['guest_id'] = $this->input->post('guest_id');
$data['first_name'] = $this->input->post('first_name');
$data['last_name'] = $this->input->post('last_name');
$data['email'] = $this->input->post('email');
$data['contact_no'] = $this->input->post('contact_no');
$data['promoter_id'] = $this->input->post('promoter_id');

        return $data;
   }
   
   	
	    
}