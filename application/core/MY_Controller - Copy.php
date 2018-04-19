<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller{
	
	// Declare $data array variable which we'll pass to each view.
	// At MY_Controller level we save general data like user's name, site title etc. on the fly.
	
	protected $data = array();
	protected $sessionPrefix	=	'680f09d6ac81f11014de696d155695df_';	//md5('rakesh');
	protected $sessionName		=	'logedInUser';
	protected $login			=	'login';
	
	public function __construct(){
		parent::__construct();
		
		$this->load->database();
		$this->load->helper(array('url','form'));
		$this->load->library('form_validation');
		$this->load->library('session');		
		
		$this->load->model('model');
		
		$this->data["sessionName"] = $this->sessionName;
		
		// if(isset($_SESSION["$this->sessionPrefix"])){
			// foreach($_SESSION["$this->sessionPrefix"] as $key=>$sess){
				// if(isset($sess['appendOnData']) && $sess['appendOnData'] === 1){					
					// $this->data["$key"] = $sess["$key"];
				// }
			// }			
		// }
		
		$template_skins = array('skin-blue'			=>	'skin-blue.min.css',
								'skin-blue-light'	=>	'skin-blue-light.min.css',
								'skin-yellow'		=>	'skin-yellow.min.css',
								'skin-yellow-light'	=>	'skin-yellow-light.min.css',
								'skin-green'		=>	'skin-green.min.css',
								'skin-green-light'	=>	'skin-green-light.min.css',
								'skin-purple'		=>	'skin-purple.min.css',
								'skin-purple-light'	=>	'skin-purple-light.min.css',
								'skin-red'			=>	'skin-red.min.css',
								'skin-red-light'	=>	'skin-red-light.min.css',
								'skin-black'		=>	'skin-black.min.css',
								'skin-black-light'	=>	'skin-black-light.min.css'
								);
		$template_sking_random_index=	array_rand($template_skins,1);
		
		// Web Site Variable
		$this->data['websiteTitle']	=	"AdminLTE 2 | Starter";
		$this->data['logo_mini']	=	"<b>A</b>LT";
		$this->data['logo_lg']		=	"<b>Admin</b>LTE";
		$this->data['skin']['name']			=	'skin-purple';//;$template_sking_random_index;
		$this->data['skin']['path']			=	'skin-purple.min.css';//$template_skins[$template_sking_random_index];		
		
		// echo "<pre>";print_r($this->data);exit;
	}
	
	//SESSION RELATED METHODS
	public function getSession($name){
		// $item	=	$this->sessionPrefix.$name;
		// return $this->session->$item;						// Return NULL if item not found.
		if(isset($_SESSION[$this->sessionPrefix]["$name"])){
			return $_SESSION[$this->sessionPrefix]["$name"]["$name"];
		}else{
			return null;
		}
	}
	
	public function setSession($key,$data,$appendOnData=0){	// 1 or true- Append data to $this->data array.
		$new_data = array();
		if(!is_array($data)){
			$temp = array($key,array($key=>$data));
			// var_dump($temp);exit;
			$new_data[] = $temp;
		}
		if($appendOnData){
			$new_data = array($key=>$data);
			$new_data['appendOnData'] = 1;
			$this->data["$key"] = $data;
		}else{
			$new_data = array($key=>$data);
			$new_data['appendOnData'] = 0;
		}
		
		$_SESSION[$this->sessionPrefix][$key] = $new_data;
		// $this->session->set_userdata($this->sessionPrefix.$key,$data);		
	}
	
	public function removeSession($key,$unsetOnData=0){	//1- Remove from $this->data array too
		$responce = array();
		if(is_array($key)){
			foreach($key as $value){
				
				if(isset($_SESSION["$this->sessionPrefix"]["$value"])){					
					unset($_SESSION["$this->sessionPrefix"]["$value"]);					
				}else{
					$responce[] = "$value not exist in Session.";
				}
				
				if($unsetOnData){
					if(isset($this->data["$value"])){
						unset($this->data["$value"]);
					}else{
						$responce[] = "$value not exist in this->data.";
					}
				}
			}
		}else{
			if(isset($_SESSION["$this->sessionPrefix"]["$key"])){
				unset($_SESSION["$this->sessionPrefix"]["$key"]);				
			}else{
				$responce[] = "$key not exist in Session.";
			}
			
			if($unsetOnData){
				if(isset($this->data["$key"])){
					unset($this->data["$key"]);
				}else{
					$responce[] = "$key not exist in this->data.";
				}
			}
		}
		
		return ($responce)?$responce:true;
	}
	
	// public function addData($key,$data){
		// $this->data["$key"]=$data;
	// }
	
	public function unsetAppendOnData($key){
		$responce = array();
		if(is_array($key)){
			foreach($key as $value){
				if(isset($_SESSION["$this->sessionPrefix"]["$value"])){
					$_SESSION["$this->sessionPrefix"]["$value"]["appendOnData"] = 0;
				}else{
					$responce[] = "$value not exist in Session.";
				}
			}
		}else{
			if(isset($_SESSION["$this->sessionPrefix"]["$key"])){
				$_SESSION["$this->sessionPrefix"]["$key"]["appendOnData"] = 0;
			}else{
				$responce = "$value not exist in Session.";
			}
		}
		return ($responce)?$responce:true;
	}
	
	public function isLogin() {
		return (bool) $this->getSession($this->loginSession);		
	}
	
	protected function layout(){
		$this->load->view('components/layout',$this->data);
	}
	
	protected function debug($var,$die=1){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		if($die){
			exit;
		}
	}	
}