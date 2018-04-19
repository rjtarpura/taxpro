<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Clients extends My_Controller {
	
	//$data variable will be accessible in all controller extending MY_Controller. Use $this->data['']
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		
		$where = ($this->user_role_sess == USER)?"create_by = ".$this->user_id_sess:"";
		$clients		=	$this->model->get("clients",$where);		
		$this->data["clients"]	=	$clients;
		$this->data['products']	=	$this->model->get("products","status = ".ACTIVE);

		$countryArr		=	$this->model->getField("countries","country_id, name");
		$this->data["countries"]	=	array_column($countryArr,"name","country_id");

		$this->data['view']	=	'clients/add';
		$this->load->view('components/layout',$this->data);
	}
	
	public function add($client_id = NULL){

		// $this->debug($_POST);

		if($client_id){

			$editing_client	=	$this->model->get("clients","client_id = $client_id");
			$editing_client	=	($editing_client)?$editing_client[0]:array();
			$this->data['editing_client'] = $editing_client;

			// echo "<pre>";print_r($this->data['editing_client']);exit;

			$countryArr		=	$this->model->getField("countries","country_id, name");
			$this->data["countries"]	=	array_column($countryArr,"name","country_id");

			$stateArr		=	$this->model->getField("states","state_id, name","country_id = '".$editing_client['country_id']."'");
			$this->data["states"]	=	array_column($stateArr,"name","state_id");

			$cityArr		=	$this->model->getField("cities","city_id, name","state_id = '".$editing_client['state_id']."'");	
			$this->data["cities"]	=	array_column($cityArr,"name","city_id");

			$attachments		=	$this->model->get("clients_attachments","client_id = '".$editing_client['client_id']."'");	
			$this->data["attachments"]	=	$attachments;
			// var_dump($attachments);exit;

		}else{

			$countryArr		=	$this->model->getField("countries","country_id, name");
			$this->data["countries"]	=	array_column($countryArr,"name","country_id");

		}

		if($this->input->server('REQUEST_METHOD') == "POST"){
			
			$this->form_validation->set_rules($this->get_clients_validation_rule());

			$dataToAdd		=	array();

			$dataToAdd["country_id"]	=	$this->input->post("country_id");
			$dataToAdd["state_id"]		=	$this->input->post("state_id");
			$dataToAdd["city_id"]		=	$this->input->post("city_id");			

			if($this->form_validation->run() === false){

				$countryArr		=	$this->model->getField("countries","country_id, name");
				$this->data["countries"]	=	array_column($countryArr,"name","country_id");

				$stateArr		=	$this->model->getField("states","state_id, name","country_id = '".$dataToAdd['country_id']."'");
				$this->data["states"]	=	array_column($stateArr,"name","state_id");

				$cityArr		=	$this->model->getField("cities","city_id, name","state_id = '".$dataToAdd['state_id']."'");	
				$this->data["cities"]	=	array_column($cityArr,"name","city_id");

			}else{
				$this->load->model("orders_m");

				$have_order 			=	$this->input->post("place_order_check");
				$dataToAdd["first_name"]=	$this->input->post("first_name");
				$dataToAdd["last_name"]	=	$this->input->post("last_name");
				$dataToAdd["email"]		=	($this->input->post("email"))?$this->input->post("email"):null;
				$dataToAdd["contact"]	=	$this->input->post("contact");
				$dataToAdd["dob"]		=	$this->input->post("dob");
				$dataToAdd["gender"]	=	$this->input->post("gender");				
				$dataToAdd["street1"]	=	$this->input->post("street1");
				$dataToAdd["street2"]	=	$this->input->post("street2");				
				$dataToAdd["zip"]		=	$this->input->post("zip");
				$dataToAdd["status"]	=	$this->input->post("status");
				
				if(!$client_id){
					$dataToAdd["create_by"] = $this->user_id_sess;
				}				

				$order_details = array();
				$result_array = array();
				if($have_order){					
					// Uploading Files
					$order_details = $this->input->post("order_description");
					$result_array = $this->orders_m->upload_order_files("attachment",$order_details,true,null,"attachments/orders");
				}

				if($client_id){
					$rs2		=	$this->model->update("clients",$dataToAdd,"client_id = $client_id");
					if($rs2){
						$this->session->set_flashdata("success","Client updated.");						
					}else{					
						$this->session->set_flashdata("error","Client not updated. Please try again.");
					}
				}else{
					
					$status = $this->orders_m->create_order_for_client($dataToAdd,$order_details,$result_array);

					if($status){						
						$this->session->set_flashdata("success","Client added.");						
					}else{						
						$this->session->set_flashdata("error","Client not added. Please try again.");			
					}
				}
				redirect('clients/view_list');
			}
		}		

		$this->data['view']	=	'clients/add';
		$this->load->view('components/layout',$this->data);
	}

	public function delete($client_id){

		if($this->model->delete("clients","client_id = $client_id")){
			$this->session->set_flashdata("success","Deleted Successfully");
		}else{
			$this->session->set_flashdata("error","Unable to delete at this moment");
		}
		redirect("clients/view_list");
		
	}

	public function inactive($client_id){

		if($this->model->update("clients",array("status" =>INACTIVE),"client_id = $client_id")){
			$this->session->set_flashdata("success","Inactive Successfully");
		}else{
			$this->session->set_flashdata("error","Unable to inactive at this moment");
		}
		redirect("clients/view_list");
		
	}

	public function delete_bulk(){
		// $this->debug($_POST)	;	
		$client_id_array = $_POST['user_id_array'];
		$count = count($client_id_array);
		$client_id_list = implode(",",$client_id_array);
		$table_name = $_POST['table_name'];
		if($table_name=="clients"){
			$pk = "client_id";
			$table_name = array("clients","clients_attachments");
		}else{
			$this->session->set_flashdata('error',"Logic Error (Clients.php).");
			redirect("clients/view_list");
		}

		if($affected_rows = $this->model->delete_bulk($table_name,$client_id_list,$pk)){
			$this->session->set_flashdata('success',"$count records deleted.");
		}else{
			$this->session->set_flashdata('error',"Records not deleted.");
		}

		redirect("clients/view_list");
	}

	public function inactive_bulk(){
		// $this->debug($_POST)	;	
		$client_id_array = $_POST['user_id_array'];
		$count = count($client_id_array);
		$client_id_list = implode(",",$client_id_array);
		// $table_name = $_POST['table_name'];
		// if($table_name=="clients"){
		// 	$pk = "client_id";
		// }else{
		// 	$this->session->set_flashdata('error',"Logic Error (Clients.php).");
		// 	redirect("clients/view_list");
		// }

		if($affected_rows = $this->model->update("clients",array("status"=>INACTIVE),"client_id in ($client_id_list)")){
		// if($affected_rows = $this->model->inactive_bulk($table_name,$client_id_list,$pk)){
			$this->session->set_flashdata('success',"$count records inactive.");
		}else{
			$this->session->set_flashdata('error',"Records not inactive.");
		}

		redirect("clients/view_list");
	}
	
	public function view_list(){

		$where = ($this->user_role_sess == USER)?"create_by = ".$this->user_id_sess:"";
		$clients		=	$this->model->get("clients",$where);
		// $this->debug($this->db->last_query());
		$this->data["clients"]	=	$clients;
		// $this->debug($this->data["clients"]);
		$this->data['view']	=	'clients/view_list';
		$this->load->view('components/layout',$this->data);
	}

	public function view_single($client_id){
		if($client_id){
			$client_single		=	$this->model->get_user_client_by_join("clients","client_id = $client_id");
			$this->data["client_single"]	=	($client_single[0])?$client_single[0]:array();
			// $this->debug($this->data["client_single"]);
			$this->data['view']	=	'clients/view_single';
			$this->load->view('components/layout',$this->data);
		}else{
			$this->session->set_flashdata("error","Bad Request");
			redirect('clients/view_list');
		}
	}
	
	private function get_clients_validation_rule(){
		return	array(
						array(
									"field"	=>	"first_name",
									"label"	=>	"First Name",
									"rules"	=>	"required|regex_match[/^[a-z][a-z ]*$/i]"
							),
						array(
									'field'	=>	'last_name',
									'label'	=>	'Last Name',
									'rules'	=>	'regex_match[/^[a-z][a-z ]*$/i]'
							),
						array(
									'field'	=>	'contact',
									'label'	=>	'Contact',
									'rules'	=>	'exact_length[10]|numeric'
							),
						array(
									'field'	=>	'email',
									'label'	=>	'Email',
									'rules'	=>	'required|valid_email'
							),
						array(
									'field'	=>	'dob',
									'label'	=>	'Date Of Birth',
									'rules'	=>	'required|callback_verify_dob'
							),	
						array(
									'field'	=>	'gender',
									'label'	=>	'Gender',
									'rules'	=>	'required'
							),						
						array(
									'field'	=>	'country_id',
									'label'	=>	'Country',
									'rules'	=>	'required'
							),
						array(
									'field'	=>	'state_id',
									'label'	=>	'State',
									'rules'	=>	'required'
							),
						array(
									'field'	=>	'city_id',
									'label'	=>	'City',
									'rules'	=>	'required'
							)
					);
	}

	public function verify_dob($date){		
		$status = $this->model->verify_dob($date);
		if(is_string($status)){
			$this->form_validation->set_message('verify_dob',$status);
			return false;
		}else{
			return true;
		}
	}
}
