<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// require_once ($_SERVER["DOCUMENT_ROOT"]);

class Settings extends My_Controller {
	
	//$data variable will be accessible in all controller extending MY_Controller. Use $this->data['']
	public function __construct(){
		parent::__construct();
	}

	public function index(){	
		$this->data['_settings'] = $this->session->_settings;
		// $this->debug($this->data['_settings']);
		$this->data['view']	=	'components/settings';
		$this->load->view('components/layout',$this->data);
	}

	public function update(){
		$this->debug($_POST);
	}
}
