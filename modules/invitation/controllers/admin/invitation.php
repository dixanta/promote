<?php

class Invitation extends Admin_Controller
{
	
	public function __construct(){
    	parent::__construct();
        $this->load->module_model('invitation','invitation_model');
        $this->lang->module_load('invitation','invitation');
        //$this->bep_assets->load_asset('jquery.upload'); // uncomment if image ajax upload
    }
    
	public function index()
	{
		// Display Page
		$data['header'] = 'invitation';
		$data['page'] = $this->config->item('template_admin') . "invitation/index";
		$data['module'] = 'invitation';
		$this->load->view($this->_container,$data);		
	}

	public function json()
	{
		$this->_get_search_param();	
		$total=$this->invitation_model->count();
		paging('invitation_id');
		$this->_get_search_param();	
		$rows=$this->invitation_model->getInvitations()->result_array();
		echo json_encode(array('total'=>$total,'rows'=>$rows));
	}
	
	public function _get_search_param()
	{
		// Search Param Goes Here
		parse_str($this->input->post('data'),$params);
		if(!empty($params['search']))
		{
			($params['search']['promoter_id']!='')?$this->db->where('promoter_id',$params['search']['promoter_id']):'';
($params['search']['guest_id']!='')?$this->db->where('guest_id',$params['search']['guest_id']):'';
($params['search']['event_id']!='')?$this->db->where('event_id',$params['search']['event_id']):'';
($params['search']['rsvp']!='')?$this->db->like('rsvp',$params['search']['rsvp']):'';

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
		$rows=$this->invitation_model->getInvitations()->result_array();
		echo json_encode($rows);    	
    }    
    
	public function delete_json()
	{
    	$id=$this->input->post('id');
		if($id && is_array($id))
		{
        	foreach($id as $row):
				$this->invitation_model->delete('INVITATIONS',array('invitation_id'=>$row));
            endforeach;
		}
	}    

	public function save()
	{
		
        $data=$this->_get_posted_data(); //Retrive Posted Data		

        if(!$this->input->post('invitation_id'))
        {
            $success=$this->invitation_model->insert('INVITATIONS',$data);
        }
        else
        {
            $success=$this->invitation_model->update('INVITATIONS',$data,array('invitation_id'=>$data['invitation_id']));
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
        $data['invitation_id'] = $this->input->post('invitation_id');
$data['promoter_id'] = $this->input->post('promoter_id');
$data['guest_id'] = $this->input->post('guest_id');
$data['event_id'] = $this->input->post('event_id');
$data['rsvp'] = $this->input->post('rsvp');

        return $data;
   }
   
   	
	    
}