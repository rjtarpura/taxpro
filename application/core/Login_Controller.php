<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 // require_once APPPATH.'core/MY_Protectedcontroller.php';
class Login_Controller extends MY_Controller{
	
	// Declare $data array variable which we'll pass to each view.
	// At MY_Controller level we save general data like user's name, site title etc. on the fly.
	
	public function __construct(){
		parent::__construct();

		if(!$this->session->_user_session){
			redirect("login");
		}
	}
}
