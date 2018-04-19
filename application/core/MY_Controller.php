<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
	
	// Declare $data array variable which we'll pass to each view.
	// At MY_Controller level we save general data like user's name, site title etc. on the fly.
	
	protected $data = array();

	private $template_skins = array(
								'skin-blue'			=>	'skin-blue.min.css',
								'skin-blue-light'	=>	'skin-blue-light.min.css',
								'skin-yellow'		=>	'skin-yellow.css',
								'skin-yellow-light'	=>	'skin-yellow-light.min.css',
								'skin-green'		=>	'skin-green.min.css',
								'skin-green-light'	=>	'skin-green-light.min.css',
								'skin-purple'		=>	'skin-purple.min.css',
								'skin-purple-light'	=>	'skin-purple-light.min.css',
								'skin-red'			=>	'skin-red.min.css',
								'skin-red-light'	=>	'skin-red-light.min.css',
								'skin-black'		=>	'skin-black.min.css',
								'skin-black-light'	=>	'skin-black-light.min.css',
								);
	private $allowed_uri = array(			
									"login",
									"login/index",
									"login/login",
									"login/logout",
									"login/forgot"
								);

	private $website_settings = array();
	

	
	public function __construct(){
		parent::__construct();
		
		if($this->session->_settings){

			$this->website_settings = $this->session->_settings;

			$this->data['website_title']		=	$this->website_settings['website_title'];
			$this->data['logo_mini']			=	$this->website_settings['logo_mini'];
			$this->data['logo_lg']				=	$this->website_settings['logo_lg'];
			$this->data['skin']['name']			=	$this->website_settings['template_skin'];
			$this->data['skin']['path']			=	$this->template_skins[$this->data['skin']['name']];

		}else{

			$rs = $this->model->get("settings");

			if(!$rs){
				show_error("Website settings are not defined",404,"Settings Missing");
			}

			$this->session->set_userdata("_settings",$rs[0]);

			$this->website_settings = $rs[0];

			$this->data['website_title']		=	$this->website_settings['website_title'];
			$this->data['logo_mini']			=	$this->website_settings['logo_mini'];
			$this->data['logo_lg']				=	$this->website_settings['logo_lg'];
			$this->data['skin']['name']			=	$this->website_settings['template_skin'];
			$this->data['skin']['path']			=	$this->template_skins[$this->data['skin']['name']];				
		}

		$this->data['js_variables']	=	json_encode(

							array(
								'notification_display_duration_ms'=> intval($this->session->_settings['notification_display_duration_ms']),
								'allowed_file_extensions'=> $this->session->_settings['allowed_file_extensions'],
								'file_upload_size_bytes'=> intval($this->session->_settings['file_upload_size_bytes']),
								'autologout'=> ($this->session->_settings['autologout'] == AUTOLOGOUT_ON)?true:false,
								'autologout_mins'=> intval($this->session->_settings['autologout_mins']),
								'profile_pic_extensions' => $this->session->_settings['profile_pic_extensions'],
								'profile_pic_size_bytes' => intval($this->session->_settings['profile_pic_size_bytes'])
							)
						);

		// if(!in_array(uri_string(), $this->allowed_uri)) {
		if($this->uri->segment(1) != "login"){

			if(!$this->model->is_loged_in()){
				
				redirect("login");

			}else{

				$current_timestamp = strtotime(date('Y-m-d h:i:s'));

				if($this->session->_settings['autologout'] == AUTOLOGOUT_ON){

					$time = ($this->session->_settings['autologout_mins'])?$this->session->_settings['autologout_mins']:AUTOLOGOUT_MINS_DEFAULT;

					if($current_timestamp - $this->session->_last_login > $time*60){	//Seconds
						$this->model->logout();
					}

					$this->session->set_userdata("_last_login", strtotime(date('Y-m-d h:i:s')));
				}

				$this->user_id_sess = $this->session->_user_session["user_id"];
				$this->user_role_sess = $this->session->_user_session["role"];
				$this->data['user_role_sess'] = $this->user_role_sess;
				$this->data['user_id_sess'] = $this->user_id_sess;

				$this->data['pending_orders'] = $this->model->getField("orders","count(*) AS `pending_order`","`processing_status` = ".PENDING." AND assigned_to = ".$this->user_id_sess)[0]['pending_order'];

				// $this->debug($this->data['pending_order']);


			}
		}
	}

	protected function is_admin(){
		return ($this->user_role_sess == ADMIN)?true:false;
	}
	
	protected function debug($var=NULL,$die=1){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		if($die){
			exit;
		}
	}	

	
}