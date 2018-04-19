<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax extends My_Controller {
	
	//$data variable will be accessible in all controller extending MY_Controller. Use $this->data['']
	public function __construct(){
		parent::__construct();
	}
	
	public function get_state(){
		$country_id	=	$this->input->post('country_id');
		
		$where	=	($country_id)?array("country_id"=>$country_id):NULL;
		$stateArr	=	$this->model->getField("states","state_id, name",$where);
		$resultArr	=	array();
		foreach($stateArr as $k=>$v){
			$resultArr[$k]	=	"<option value={$v['state_id']}>{$v['name']}</option>";
		}
		
		header("Content-Type: application/json");
		echo json_encode($resultArr);
	}

	/*
    Developed By : Rakesh Jhangir
    Date : 2017/02/22
    Function For : bla bla bla

	*/

	public function get_city(){
		$state_id	=	$this->input->post('state_id');
		//
		$where	=	($state_id)?array("state_id"=>$state_id):NULL;
		$cityArr	=	$this->model->getField("cities","city_id, name",$where);
		$resultArr	=	array();
		foreach($cityArr as $k=>$v){
			$resultArr[$k]	=	"<option value={$v['city_id']}>{$v['name']}</option>";
		}
		
		header("Content-Type: application/json");
		echo json_encode($resultArr);
	}

	public function email_unique($original=null){
		$original_decoded = null;
		if($original){
			$original_decoded = urldecode($original);
		}
		
		$val = $_POST['email'];
		echo json_encode(($this->model->fieldUnique('email',$val,NULL,$original_decoded)));
	}

	public function contact_unique($original=null){
		$original_decoded = null;
		if($original){
			$original_decoded = urldecode($original);
		}
		$val = $_POST['contact'];
		echo json_encode(($this->model->fieldUnique('contact',$val,NULL,$original_decoded)));
	}

	public function username_unique($original=null){
		$original_decoded = null;
		if($original){
			$original_decoded = urldecode($original);
		}
		$val = $_POST['username'];
		echo json_encode(($this->model->fieldUnique('username',$val,array("users"),$original_decoded)));
	}

	public function product_unique($original=null){
		$original_decoded = null;
		if($original){
			$original_decoded = urldecode($original);
		}
		$val = $_POST['product_identifier'];
		echo json_encode(($this->model->fieldUnique('product_identifier',$val,array("products"),$original_decoded)));
	}

	public function remove_attachment(){
		$id = $_POST['id'];
		$table_name = $_POST['table_name'];

		$file_path = $_SERVER['DOCUMENT_ROOT']."/".PROJECT_ROOT."/uploads/attachments/";

		if($table_name=='users_attachments'){
			$file_path .= "users/";
		}elseif($table_name=='clients_attachments'){
			$file_path .= "clients/";
		}else{
			echo json_encode("unidentified table");
			exit;
		}

		$file_name = $this->model->getField($table_name,'file_name',"id=$id");

		if(count($file_name)){
			if(file_exists($file_path.$file_name[0]['file_name'])){
				unlink($file_path.$file_name[0]['file_name']);
			}			
		}

		echo json_encode($this->model->delete($table_name,"id = $id"));
	}

	public function sales_summery_ajax(){		
		echo $this->model->sales_summery_datatable();
	}

	public function sales_performance_ajax(){
		echo $this->model->sales_performance_datatable();
	}


	public function send_email(){

		$id = $this->input->post('id');
		$message_type = $this->input->post('message_type');
		$master_name = $this->input->post('master_name');

		$field_name = ($master_name="clients")?"client_id":"user_id";

		$person_details = $this->model->get($master_name,"$field_name = $id");

		$to_email = $person_details[0]['email'];

		if($to_email){

			$person_details = $person_details[0];
			$this->data['client_details'] = $person_details;

			// $message_template = $this->model->get("message_master","message_code = $message_type");

			$template_name = '';

			switch($message_type){
				case MESSAGE_PROCESSING:
						$template_name = "processing";
				case MESSAGE_DONE:
						$template_name = "processing";//"done";
			}

			$message_template = $this->load->view("templates/$template_name",$this->data,true);

			if($message_template){
				
				// $message_template = $message_template[0];
				
				// $subject = $message_template['subject'];
				
				// $message_body = $message_template['message_body'];

				// $status = $this->model->send_email($to_email,$subject,$message_body);

				$status = $this->model->send_email($to_email,"Taxproprotection",$message_template);

				if($status){					
					echo json_encode(array('status'=>'success', "message" => "Email Sent to {$person_details['first_name']} {$person_details['last_name']}"));
				}else{
					echo json_encode(array('status'=>'error', "message" => "Email not Sent to {$person_details['first_name']} {$person_details['last_name']}"));
				}

			}else{
				echo json_encode(array('status'=>'error', "message" => "Internal Server Error #1234"));
			}
		}else{
			echo json_encode(array('status'=>'error', "message" => "No email id is registered for this person."));
		}		
	}
}
