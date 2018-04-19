<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends My_Controller {
	
	//$data variable will be accessible in all controller extending MY_Controller. Use $this->data['']
	public function __construct(){
		parent::__construct();
	}
	
	// public function index(){

	// }

	public function sales_performance(){		
		$this->data['users_list']=	$this->model->getField("users","user_id,CONCAT(`first_name`,' ',`last_name`) AS `sales_person`","role != ".ADMIN);
		// $this->debug($this->data['users_list']);
		$this->data['view']	=	'reports/sales_performance';
		$this->load->view('components/layout',$this->data);
	}

	public function sales_summery(){		
		$this->data['view']	=	'reports/sales_summery';
		$this->load->view('components/layout',$this->data);
	}

	
}
