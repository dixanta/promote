<?php

class Event extends Admin_Controller
{
	protected $uploadPath = 'uploads/event';
protected $uploadthumbpath= 'uploads/event/thumb/';

	public function __construct(){
    	parent::__construct();
        $this->load->module_model('event','event_model');
        $this->lang->module_load('event','event');
        //$this->bep_assets->load_asset('jquery.upload'); // uncomment if image ajax upload
    }
    
	public function index()
	{
		// Display Page
		$data['header'] = 'event';
		$data['page'] = $this->config->item('template_admin') . "event/index";
		$data['module'] = 'event';
		$this->load->view($this->_container,$data);		
	}

	public function json()
	{
		$this->_get_search_param();	
		$total=$this->event_model->count();
		paging('event_id');
		$this->_get_search_param();	
		$rows=$this->event_model->getEvents()->result_array();
		echo json_encode(array('total'=>$total,'rows'=>$rows));
	}
	
	public function _get_search_param()
	{
		// Search Param Goes Here
		parse_str($this->input->post('data'),$params);
		if(!empty($params['search']))
		{
			($params['search']['promoter_id']!='')?$this->db->where('promoter_id',$params['search']['promoter_id']):'';
($params['search']['event_name']!='')?$this->db->like('event_name',$params['search']['event_name']):'';

		}  

		
		if(!empty($params['date']))
		{
			foreach($params['date'] as $key=>$value){
				$this->_datewise($key,$value['from'],$value['to']);	
			}
		}
		               
        
	}

	
	private function _datewise($field,$from,$to)
	{
			if(!empty($from) && !empty($to))
			{
				$this->db->where("(date_format(".$field.",'%Y-%m-%d') between '".date('Y-m-d',strtotime($from)).
						"' and '".date('Y-m-d',strtotime($to))."')");
			}
			else if(!empty($from))
			{
				$this->db->like($field,date('Y-m-d',strtotime($from)));				
			}		
	}	
    
	public function combo_json()
    {
		$rows=$this->event_model->getEvents()->result_array();
		echo json_encode($rows);    	
    }    
    
	public function delete_json()
	{
    	$id=$this->input->post('id');
		if($id && is_array($id))
		{
        	foreach($id as $row):
				$this->event_model->delete('EVENTS',array('event_id'=>$row));
            endforeach;
		}
	}    

	public function save()
	{
		
        $data=$this->_get_posted_data(); //Retrive Posted Data		

        if(!$this->input->post('event_id'))
        {
            $success=$this->event_model->insert('EVENTS',$data);
        }
        else
        {
            $success=$this->event_model->update('EVENTS',$data,array('event_id'=>$data['event_id']));
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
        $data['event_id'] = $this->input->post('event_id');
		$data['promoter_id'] = $this->input->post('promoter_id');
		$data['event_name'] = $this->input->post('event_name');
		$data['event_description'] = $this->input->post('event_description');
		$data['event_image'] = $this->input->post('event_image');
		$data['event_start_date'] = $this->input->post('event_start_date');
		$data['event_end_date'] = $this->input->post('event_end_date');

        return $data;
   }
   
      function upload_image(){
		//Image Upload Config
		$config['upload_path'] = $this->uploadPath;
		$config['allowed_types'] = 'gif|png|jpg';
		$config['max_size']	= '10240';
		$config['remove_spaces']  = true;
		//load upload library
		$this->load->library('upload', $config);
		if(!$this->upload->do_upload())
		{
			$data['error'] = $this->upload->display_errors('','');
			echo json_encode($data);
		}
		else
		{
		  $data = $this->upload->data();
 		  $config['image_library'] = 'gd2';
		  $config['source_image'] = $data['full_path'];
          $config['new_image']    = $this->uploadthumbpath;
		  //$config['create_thumb'] = TRUE;
		  $config['maintain_ratio'] = TRUE;
		  $config['height'] =100;
		  $config['width'] = 100;

		  $this->load->library('image_lib', $config);
		  $this->image_lib->resize();
		  echo json_encode($data);
	    }
	}
	
	function upload_delete(){
		//get filename
		$filename = $this->input->post('filename');
		@unlink($this->uploadPath . '/' . $filename);
	} 	
	    
		
	function invite()
	{
		$this->load->module_model('email_template','email_template_model');
    	$id=$this->input->post('id');
		if($id && is_array($id))
		{
			$mail_template=$this->email_template_model->getEmailTemplates(array('slug_name'=>'Event-Invitation-Template'))->row_array();
			
        	foreach($id as $row):
				$this->event_model->joins=array('PROMOTERS');
				$event=$this->event_model->getEvents(array('event_id'=>$row))->row_array();
				$this->send_invitation($event,$mail_template);
            endforeach;
		}		
	}	
	
	private function send_invitation($event,$mail_template)
	{
		$this->load->module_model('guest','guest_model');
		$this->load->library('email');
		$this->load->library('parser');
		$guests=$this->guest_model->getGuests(array('promoter_id'=>$event['promoter_id']))->result_array();
		
		$this->email->initialize($this->mail_config());
		$subject=$this->parser->parse_string($mail_template['subject'],$event,TRUE);
		
		$this->email->subject($subject);
		$this->email->from($this->preference->item('automated_from_email'),$this->preference->item('automated_from_name'));
		
		foreach($guests as $guest){
			$event['first_name']=$guest['first_name'];
			$event['last_name']=$guest['last_name'];
			$event['event_link']=site_url('');
			$event['accept_link']=site_url('invitation/'.$event['event_id'].'/'.$guest['guest_id'].'/accept');
			$event['reject_link']=site_url('invitation/'.$event['event_id'].'/'.$guest['guest_id'].'/reject');
			$message=$this->parser->parse_string($mail_template['body'],$event,TRUE);
			$this->email->to($guest['email']);
			$this->email->message($message);
			$this->email->send();
		}
		
		
	}	
}