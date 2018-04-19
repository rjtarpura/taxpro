<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends My_Controller {
	
	//$data variable will be accessible in all controller extending MY_Controller. Use $this->data['']

	public function __construct(){
		parent::__construct();
	}
	
	public function index(){		
		$countryArr		=	$this->model->getField("countries","country_id, name");
		$this->data["countries"]	=	array_column($countryArr,"name","country_id");

		$this->data['view']	=	'users/add';
		$this->load->view('components/layout',$this->data);
	}
	
	public function add($user_id = NULL){

		if(!$this->is_admin()){
			$this->session->set_flashdata("error","Unauthorized Access");
			redirect("users/view_list");
		}		

		if($user_id){

			$editing_user	=	$this->model->get("users","user_id = $user_id");
			// if($editing_user){				
			// 	$editing_user = $editing_user[0];
			// 	$this->debug($editing_user);
			// 	$editing_user['password'] = $this->model->hash_password($editing_user['password']);
			// 	$this->data['editing_user'] = $editing_user;
			// }else{
			// 	$this->data['editing_user'] = array();
			// }
			$editing_user	=	($editing_user)?$editing_user[0]:array();
			$this->data['editing_user'] = $editing_user;

			// echo "<pre>";print_r($this->data['editing_user']);exit;

			$countryArr		=	$this->model->getField("countries","country_id, name");
			$this->data["countries"]	=	array_column($countryArr,"name","country_id");

			$stateArr		=	$this->model->getField("states","state_id, name","country_id = '".$editing_user['country_id']."'");
			$this->data["states"]	=	array_column($stateArr,"name","state_id");

			$cityArr		=	$this->model->getField("cities","city_id, name","state_id = '".$editing_user['state_id']."'");	
			$this->data["cities"]	=	array_column($cityArr,"name","city_id");

			$attachments		=	$this->model->get("users_attachments","user_id = '".$editing_user['user_id']."'");	
			$this->data["attachments"]	=	$attachments;
			// var_dump($attachments);exit;

		}else{

			$countryArr		=	$this->model->getField("countries","country_id, name");
			$this->data["countries"]	=	array_column($countryArr,"name","country_id");

		}

		if($this->input->server('REQUEST_METHOD') == "POST"){
			
			$this->form_validation->set_rules($this->get_users_validation_rule());

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

				$dataToAdd["first_name"]=	$this->input->post("first_name");
				$dataToAdd["last_name"]	=	$this->input->post("last_name");
				$dataToAdd["email"]		=	($this->input->post("email"))?$this->input->post("email"):null;
				$dataToAdd["contact"]	=	$this->input->post("contact");
				$dataToAdd["dob"]		=	$this->input->post("dob");
				$dataToAdd["gender"]	=	$this->input->post("gender");
				$dataToAdd["username"]	=	$this->input->post("username");
				// $dataToAdd["password"]	=	$this->model->hash_password($this->input->post("password"));
				$dataToAdd["street1"]	=	$this->input->post("street1");
				$dataToAdd["street2"]	=	$this->input->post("street2");				
				$dataToAdd["zip"]		=	$this->input->post("zip");
				$dataToAdd["status"]	=	$this->input->post("status");
				

				$uploadElement			=	"attachment";

				if($_FILES[$uploadElement]['name'][0] !=''){

					$stat=	$this->model->uploadFile($uploadElement,true,"users");

					if($stat["errorCount"] ==0){

						$attachmentData	=	array();

						foreach($stat["success"] as $key=>$value){

							$attachmentData[]	=	array(
														"name"		=>	$value["orig_name"],
														"file_name"	=>	$value["file_name"],
														"size"		=>	$value["file_size"],
														"type"		=>	$value["file_type"],
														"extension"	=>	substr($value["file_ext"],1,strlen($value["file_ext"])),
													);
						}	
						
						$transData = array(
										"base"	=>array(
													"table"		=>	"users",
													"data"		=>	$dataToAdd
												),
										"child"	=>array(
													"table"		=>	"users_attachments",
													"data"		=>	$attachmentData
												)
									);
						
						$where = ($user_id)?array("user_id" => $user_id):null;
						$rs1		=	$this->model->transection($transData,"user_id",$where);

						if($rs1){
						
							$this->session->set_flashdata("success","User added.");
							redirect('users/view_list');
						
						}else{
						
							$this->session->set_flashdata("error","User not added. Please try later");
							redirect('users/view_list');
						}
					}else{
											
						$fileErrorArray			=	array_column($stat["error"],"errorMsg","file_name");
						$this->session->set_flashdata("fileerror",$fileErrorArray);						
					}
				}else{

					if($user_id){
						$rs2		=	$this->model->update("users",$dataToAdd,"user_id = $user_id");
						if($rs2){
							$this->session->set_flashdata("success","User updated.");
							redirect('users/view_list');					
						}else{
						
							$this->session->set_flashdata("error","User not updated. Please try again.");						
						}
					}else{
						$rs2		=	$this->model->add("users",$dataToAdd);
						if($rs2){
							$this->session->set_flashdata("success","User added.");
							redirect('users/view_list');					
						}else{
						
							$this->session->set_flashdata("error","User not added. Please try again.");						
						}
					}
				}
			}
		}		

		$this->data['view']	=	'users/add';
		$this->load->view('components/layout',$this->data);
	}

	public function delete($user_id=NULL){

		if(!$this->is_admin()){
			$this->session->set_flashdata("error","Unauthorized Access");
			redirect("users/view_list");
		}


		if($user_id){
			if($this->model->delete("users","user_id = $user_id")){
				$this->session->set_flashdata("success","Deleted Successfully");
			}else{
				$this->session->set_flashdata("error","Unable to delete at this moment");
			}
		}else{
			$this->session->set_flashdata("error","Bad Request");
		}
		
		redirect("users/view_list");		
	}

	public function inactive($user_id=NULL){

		if(!$this->is_admin()){
			$this->session->set_flashdata("error","Unauthorized Access");
			redirect("users/view_list");
		}


		if($user_id){
			if($this->model->update("users",array("status" =>INACTIVE),"user_id = $user_id")){
				$this->session->set_flashdata("success","User Inactivated");
			}else{
				$this->session->set_flashdata("error","Unable to delete at this moment");
			}
		}else{
			$this->session->set_flashdata("error","Bad Request");
		}
		
		redirect("users/view_list");		
	}

	public function delete_bulk(){	
		
		if(!$this->is_admin()){
			$this->session->set_flashdata("error","Unauthorized Access");
			redirect("users/view_list");
		}

		$user_id_array = $_POST['user_id_array'];
		$count = count($user_id_array);
		$user_id_list = implode(",",$user_id_array);
		$table_name = $_POST['table_name'];
		if($table_name=="users"){
			$pk = "user_id";
			$table_name = array("users","users_attachments");
		}else{
			$this->session->set_flashdata('error',"Bad Request");
			redirect("users/view_list");
		}

		if($affected_rows = $this->model->delete_bulk($table_name,$user_id_list,$pk)){
			$this->session->set_flashdata('success',"$count records deleted.");
		}else{
			$this->session->set_flashdata('error',"Records not deleted.");
		}

		redirect("users/view_list");
	}

	public function inactive_bulk(){	
		
		if(!$this->is_admin()){
			$this->session->set_flashdata("error","Unauthorized Access");
			redirect("users/view_list");
		}

		$user_id_array = $_POST['user_id_array'];
		$count = count($user_id_array);
		$user_id_list = implode(",",$user_id_array);
		// $table_name = $_POST['table_name'];
		// if($table_name=="users"){
		// 	$pk = "user_id";
		// }else{
		// 	$this->session->set_flashdata('error',"Bad Request");
		// 	redirect("users/view_list");
		// }

		// if($affected_rows = $this->model->inactive_bulk($table_name,$user_id_list,$pk)){
		if($affected_rows = $this->model->update("users",array("status"=>INACTIVE),"user_id in ($user_id_list)")){
			$this->session->set_flashdata('success',"$count records inactive.");
		}else{
			$this->session->set_flashdata('error',"Records not inactive.");
		}

		redirect("users/view_list");
	}
	
	public function view_list(){
		$users		=	$this->model->get("users","role = ".USER);
		$this->data["users"]	=	$users;

		$this->data['view']	=	'users/view_list';
		$this->load->view('components/layout',$this->data);
	}

	public function view_single($user_id=NULL){
		if($user_id){
			$user_single		=	$this->model->get_user_client_by_join("users","user_id = $user_id");
			$this->data["user_single"]	=	($user_single[0])?$user_single[0]:array();
			// $this->debug($this->data["user_single"]);
			$this->data['view']	=	'users/view_single';
			$this->load->view('components/layout',$this->data);
		}else{
			$this->session->set_flashdata('error','Bad Request');
			redirect('users/view_list');
		}
	}
	
	private function get_users_validation_rule($which=NULL){		
		$personal = array(
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
									'field'	=>	'username',
									'label'	=>	'Username',
									'rules'	=>	'required'
							)
					);
		$contact = 	array(
						array(
									'field'	=>	'email',
									'label'	=>	'Email',
									'rules'	=>	'required|valid_email'
							),
						array(
									'field'	=>	'contact',
									'label'	=>	'Contact',
									'rules'	=>	'exact_length[10]|numeric'
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
		$other = array(						
						// array(
						// 			'field'	=>	'password',
						// 			'label'	=>	'Password',
						// 			'rules'	=>	'required'
						// 	)
					);
		if($which){
			if($which==UPDATE_PERSONAL){
				return $personal;
			}else
			if($which==UPDATE_CONTACT){
				return $contact;
			}
		}else{
			return array_merge($personal,$contact,$other);
		}
	}

	public function verify_dob($date){
		if($date){
			$status = $this->model->verify_dob($date);
			if(is_string($status)){
				$this->form_validation->set_message('verify_dob',$status);
				return false;
			}else{
				return true;
			}
		}else{
			return true;
		}
	}

	public function verify_old_password($old_password){		
		// $status = $this->model->get("users","password = '".$this->model->hash_password($old_password)."' AND ");

		if($old_password && $this->model->hash_password($old_password) != $this->session->_user_session['password']){
			$this->form_validation->set_message('verify_old_password',"Old Password is not correct.");
			return false;
		}else{
			return true;
		}
	}

	public function view_profile($update_type=null){		
		// var_dump(($this->model->hash_password('admin') != $this->session->_user_session['password']));exit;
		$this->data['active_tab'] = ($update_type)?$update_type:UPDATE_PERSONAL;

		$user_single		=	$this->model->get_user_client_by_join("users","user_id = $this->user_id_sess");
		$user_profile		=	($user_single[0])?$user_single[0]:array();
		$this->data["user_profile"]	=	$user_profile;
		
		// $this->debug($user_profile);
		
		$countryArr		=	$this->model->getField("countries","country_id, name");
		$this->data["countries"]	=	array_column($countryArr,"name","country_id");

		$stateArr		=	$this->model->getField("states","state_id, name","country_id = '".$user_profile['country_id']."'");
		$this->data["states"]	=	array_column($stateArr,"name","state_id");

		$cityArr		=	$this->model->getField("cities","city_id, name","state_id = '".$user_profile['state_id']."'");	
		$this->data["cities"]	=	array_column($cityArr,"name","city_id");
		
		// $this->debug($this->data["user_profile"]);		
		
		$this->data['view']	=	'components/profile';
		$this->load->view('components/layout',$this->data);
	}

	public function profile_update($user_id=NULL){
		$update_type = $this->input->post('update_type');
		// $this->debug($_POST);

		if($update_type && $user_id){

			$this->data['active_tab'] = $update_type;
			// redirect("users/view_profile/$update_type");
			$validation_rules = array();

			if($update_type == UPDATE_PERSONAL){
				$validation_rules = $this->get_users_validation_rule(UPDATE_PERSONAL);
			}elseif($update_type == UPDATE_CONTACT){
				$validation_rules = $this->get_users_validation_rule(UPDATE_CONTACT);
			}if($update_type == UPDATE_PASSWORD){
				$validation_rules = array(
											array(
												'field'	=>	'old_password',
												'label'	=>	'Old Password',
												'rules'	=>	'required|callback_verify_old_password'
											),
											array(
												'field'	=>	'password',
												'label'	=>	'New Password',
												'rules'	=>	'required'
											),
											array(
												'field'	=>	'new_password_confirm',
												'label'	=>	'New Password',
												'rules'	=>	'required|matches[password]'
											),
										);
			}

			$this->form_validation->set_rules($validation_rules);

			if($this->form_validation->run() === true){

				// $this->debug($_POST);
				$update_data = array();
				foreach($_POST as $k=>$v){
					if($k == 'update_type' || $k == 'old_password' || $k == 'new_password_confirm'){
						continue;
					}elseif($k == 'password'){
						$update_data[$k] = $this->model->hash_password($this->input->post($k));
					}else{
						$update_data[$k] = $this->input->post($k);
					}
				}

				// $this->debug($update_data);
				$status = $this->model->update("users",$update_data,"user_id = $user_id");
				if($status){
					$user = $this->model->get("users","user_id = $user_id");
					$this->session->set_userdata("_user_session", $user[0]);
					$this->session->set_flashdata('success','Profile updated Successfully');
				}else{
					$this->session->set_flashdata('success','Profile not updated');
				}

				redirect("users/view_profile/$update_type");

			}else{

				$user_single		=	$this->model->get_user_client_by_join("users","user_id = $this->user_id_sess");
				$user_profile		=	($user_single[0])?$user_single[0]:array();
				$this->data["user_profile"]	=	$user_profile;

				$country_id = $user_profile["country_id"];
				$state_id = $user_profile["state_id"];

				if($update_type == UPDATE_CONTACT){
					$country_id = $this->input->post("country_id");
					$state_id =  $this->input->post("state_id");
				}

				$countryArr		=	$this->model->getField("countries","country_id, name");
				$this->data["countries"]	=	array_column($countryArr,"name","country_id");

				$stateArr		=	$this->model->getField("states","state_id, name","country_id = '".$country_id."'");
				$this->data["states"]	=	array_column($stateArr,"name","state_id");

				$cityArr		=	$this->model->getField("cities","city_id, name","state_id = '".$state_id."'");	
				$this->data["cities"]	=	array_column($cityArr,"name","city_id");
				
				$this->data['view']	=	'components/profile';
				$this->load->view('components/layout',$this->data);
			}
		}else{
			$this->session->set_flashdata("error","Bad Request");			
			redirect('users/view_profile');
		}
	}

	public function image_update($user_id=NULL){
		if($user_id){
			if($result = $this->model->upload_profile_image("photo",true,"photos/users","gif|jpg|png")){
				if($result['status'] == 'success'){
					if($this->model->update("users",array("photo"=>$result["message"]["file_name"]),"user_id=$user_id")){
						$user = $this->model->get("users","user_id=$user_id");
						$this->session->set_userdata("_user_session", $user[0]);
						echo json_encode(array('status'=>'success', "message" => "File Uploaded Successfully"));
					}else{
						echo json_encode(array('status'=>'error', "message" => "Internal Server Error #101"));
					}					
				}else{
					echo json_encode($result);
				}
			}
		}else{
			echo json_encode(array("status"=>"error","message"=>"Bad Request"));
		}
	}

	public function dashboard(){
		$this->data['view']	=	'users/dashboard';
		$this->data['client_count'] = $this->model->getField("clients","COUNT(`client_id`) AS 'client_count'")[0]['client_count'];
		$this->data['user_count'] = $this->model->getField("users","COUNT(`user_id`) AS 'user_count'")[0]['user_count'];
		$this->data['pending_orders'] = $this->model->getField("orders","COUNT(`order_id`) AS 'pending_orders'","`processing_status` = ".PENDING)[0]['pending_orders'];
		$this->data['processing_orders'] = $this->model->getField("orders","COUNT(`order_id`) AS 'processing_orders'","`processing_status` = ".PROCESSING)[0]['processing_orders'];
		// $this->debug($this->data['pending_orders']);
		$this->load->view('components/layout',$this->data);
	}

}
