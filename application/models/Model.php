<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model extends CI_Model {
	
	private $uploadPath, $allowedTypes, $hash_algo, $hash_key;

	public function __construct(){
		parent::__construct();

		$this->hash_algo = ($this->config->item("hash_algo"))?$this->config->item("hash_algo"):"md5";
		$this->hash_key = ($this->config->item("hash_key"))?$this->config->item("hash_key"):"67a05e3822ce48a6386746388e6c81f5";

		$this->uploadPath		=	$_SERVER['DOCUMENT_ROOT']."/".PROJECT_ROOT."/uploads/attachments/";
		$this->allowedTypes		=	"gif|jpg|png|txt|docx|pdf|rtf";
		$this->tables			=	array("users","clients");
	}	
	
	protected function debug($var,$die=1){
		echo "<pre>";
		print_r($var);
		echo "</pre>";
		if($die){
			exit;
		}
	}
	
	// Login Related Functionaligy
	public function login($username,$password){		
		$where	=	array('username'=>$username,'password'=>$this->hash_password($password),'status'=>ACTIVE);
		return $this->getField('users','*',$where);
	}

	public function hash_password($password){
		return hash($this->hash_algo,$password.$this->hash_key);		//md5($password);
	}
	
	public function logout(){
		$this->session->sess_destroy();		// Destroys Session
		redirect('login');
	}

	public function is_loged_in(){
		return (bool) ($this->session->_user_session)?true:false;
	}
	
	// Data retrival from database	
	public function get($table,$where=NULL){
		if($where){
			$this->db->where($where);
		}
		$resultSet	=	$this->db->get($table);
		return $resultSet->result_array();
	}
	
	public function getField($table,$fieldStr,$where=NULL){	//"name='rakes' and ..." or array
		$this->db->select($fieldStr);
		if($where){
			$this->db->where($where);
		}
		$resultSet	=	$this->db->get($table);
		return $resultSet->result_array();
	}

	// public function sales_summery_query($date1 = NULL, $date2 = NULL){
	// 	$query = "SELECT CONCAT(`us`.`first_name`,`us`.`last_name`) AS `sales_person`,`prd`.`name` AS `product`, count(`prd`.`product_id`) AS `product_sale` FROM `orders` AS `or`
	// 		LEFT JOIN `orders_details` AS `od` ON `od`.`order_id` = `or`.`order_id`
	// 		LEFT JOIN `products` AS `prd` ON `prd`.`product_id` = `od`.`product_id`
	// 		LEFT JOIN `users` AS `us` ON `us`.`user_id` = `or`.`user_id`
	// 		GROUP BY `us`.`user_id`, `prd`.`product_id`";

	// 	if($date1 && $date2){
	// 		$query .= " WHERE `or`.`order_date` BETWEEN '$date1' AND '$date2'";
	// 	}

	// 	$rs = $this->db->query($query);
	// 	// $this->debug($rs);
	// 	return $rs->result_array();
	// }

	public function sales_summery_datatable(){
		$filter_flag = false;
		include 'ChromePhp.php';
		$requestData= $_REQUEST;
		// ChromePhp::log();
		$columns = array( 
		// datatable column index  => database column name
			0 => 'sr',
			1 =>'sales_person', 
			2 => 'product',
			3=> 'product_sale'
		);

		$query1 = "SELECT CONCAT(`us`.`first_name`,' ',`us`.`last_name`) AS `sales_person`,`prd`.`name` AS `product`, SUM(`od`.`quantity`) AS `product_sale` FROM `orders` AS `or`
			LEFT JOIN `orders_details` AS `od` ON `od`.`order_id` = `or`.`order_id`
			LEFT JOIN `products` AS `prd` ON `prd`.`product_id` = `od`.`product_id`
			LEFT JOIN `users` AS `us` ON `us`.`user_id` = `or`.`assigned_to`
			GROUP BY `or`.`assigned_to`, `prd`.`product_id`";

		// getting total number records without any search
		$rs = $this->db->query($query1);
		$recordsTotal = $this->db->affected_rows();
		$recordsFiltered = $recordsTotal;

		$where = "WHERE 1=1";
		$where2 = "WHERE 1=1";

		if(strlen($requestData['date_filter']['from_date']) ==10 && strlen($requestData['date_filter']['to_date']) ==10){
			$from_date = $requestData['date_filter']['from_date'];
			$to_date = $requestData['date_filter']['to_date'];
			$where .= " AND `or`.`order_date` BETWEEN '{$from_date}' AND '{$to_date}'";

			// $filter_flag = true;
		}

		$limit = "";

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $_REQUEST['search']['value'] contains search parameter
			$search_value = $requestData['search']['value'];			

			$where2.=" AND ( `sales_person` LIKE '%".$search_value."%' ";
			$where2.=" OR `product` LIKE '%".$search_value."%' ";
			$where2.=" OR `product_sale` LIKE '".$requestData['search']['value']."%' )";

			$filter_flag = true;
		}

		$query = "SELECT * FROM (
		SELECT CONCAT(`us`.`first_name`,' ',`us`.`last_name`) AS `sales_person`,`prd`.`name` AS `product`, SUM(`od`.`quantity`) AS `product_sale`, `or`.`assigned_to` AS `sales_person_id`
			FROM `orders` AS `or`
			LEFT JOIN `orders_details` AS `od` ON `od`.`order_id` = `or`.`order_id`
			LEFT JOIN `products` AS `prd` ON `prd`.`product_id` = `od`.`product_id`
			LEFT JOIN `users` AS `us` ON `us`.`user_id` = `or`.`assigned_to`
			 $where
			GROUP BY `or`.`assigned_to`, `prd`.`product_id`) AS `sales_summery`
			$where2";

		if( count($requestData['order']) > 0 ) {   // if there is a ordering parameter, $_REQUEST['order']['columnkey']['value'] contains search parameter
			$temp = array();
			foreach($requestData['order'] as $order){
				$temp[]= "`".$columns[$order['column']]."` ".$order['dir'];
			}
			$query .= " ORDER BY ";
			$query .= implode(",",$temp);
		}
		$limit .= " LIMIT ".$requestData['start']." ,".$requestData['length'];
		//$query .= " LIMIT ".$requestData['start']." ,".$requestData['length'];
		// echo $query.$limit;exit;
		$rs = $this->db->query($query.$limit);
		// $this->debug($rs);
		$result_rows =  $rs->result_array();
		
		if( $filter_flag) {
			$recordsFiltered = $this->db->affected_rows();
		}		

		$data = array();
		foreach( $result_rows as $k=>$row ) {  // preparing an array
			$nestedData=array(); 
			$nestedData["sr"] = $k+1;
			$nestedData["sales_person"] = $row["sales_person"];
			$nestedData["product"] = $row["product"];
			$nestedData["product_sale"] = $row["product_sale"];
			$nestedData["sales_person_id"] = $row["sales_person_id"];
			
			$data[] = $nestedData;
		}

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw. 
			"recordsTotal"    => intval( $recordsTotal ),  // total number of records
			"recordsFiltered" => intval( $recordsFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
			"data"            => $data   // total data array
		);

		return json_encode($json_data);
	}
	
	public function sales_performance_datatable(){

		include 'ChromePhp.php';	
		$requestData= $_REQUEST;
		
		$columns = array( 
		// datatable column index  => database column name
			0 =>'order_id', 
			1 => 'sales_person',
			2 => 'client_name',
			3 => 'order_date',
			4 => 'processing_status'
		);

		$query1 = "SELECT `or`.`order_id`, CONCAT(`cl`.`first_name`,' ',`cl`.`last_name`) AS `client_name`, `or`.`order_date`, `or`.`status`, `or`.`processing_status`,CONCAT(`us`.`first_name`,' ',`us`.`last_name`) AS `sales_person`, `or`.`assigned_to` AS `sales_person_id`
			FROM `orders` AS `or`
			LEFT JOIN `clients` AS `cl` ON `cl`.`client_id` = `or`.`client_id`            
            LEFT JOIN `users` AS `us` ON `or`.`assigned_to` = `us`.`user_id`
            WHERE `or`.`assigned_to` IS NOT NULL ";

		// getting total number records without any search
		$rs = $this->db->query($query1);
		$recordsTotal = $this->db->affected_rows();
		$recordsFiltered = $recordsTotal;

		$where = "";		
		$where2 = "WHERE 1=1";		

		if(isset($requestData['user_ids'])){
			$user_id_list = implode(",",$requestData['user_ids']);
			$where .= " AND `us`.`user_id` IN ({$user_id_list})";
		}

		if(strlen($requestData['date_filter']['from_date']) ==10 && strlen($requestData['date_filter']['to_date']) ==10){
			$from_date = $requestData['date_filter']['from_date'];
			$to_date = $requestData['date_filter']['to_date'];
			$where .= " AND `or`.`order_date` BETWEEN '{$from_date}' AND '{$to_date}'";
		}

		if( !empty($requestData['search']['value']) ) {   // if there is a search parameter, $_REQUEST['search']['value'] contains search parameter
			$search_value = $requestData['search']['value'];
			$where2.=" AND ( `tbl1`.`sales_person` LIKE '%".$search_value."%' ";
			$where2.=" OR `tbl1`.`client_name` LIKE '%".$search_value."%' ";
			$where2.=" OR `tbl1`.`order_date` LIKE '".$search_value."%' ";
			$where2.=" OR `tbl1`.`order_id` LIKE '".$search_value."%' )";
		}

		$query = "SELECT * FROM (SELECT `or`.`order_id`, CONCAT(`cl`.`first_name`,' ',`cl`.`last_name`) AS `client_name`, `or`.`order_date`, `or`.`status`, `or`.`processing_status`,CONCAT(`us`.`first_name`,' ',`us`.`last_name`) AS `sales_person`, `or`.`assigned_to` AS `sales_person_id`,`cl`.`client_id` AS `client_id`
			FROM `orders` AS `or`
			LEFT JOIN `clients` AS `cl` ON `cl`.`client_id` = `or`.`client_id`            
            LEFT JOIN `users` AS `us` ON `or`.`assigned_to` = `us`.`user_id`
            WHERE `or`.`assigned_to` IS NOT NULL $where) AS `tbl1` $where2";

		if( count($requestData['order']) > 0 ) {
			$temp = array();
			foreach($requestData['order'] as $order){
				$temp[]= "`tbl1`.`".$columns[$order['column']]."` ".$order['dir'];
			}
			$query .= " ORDER BY ";
			$query .= implode(",",$temp);
		}

		$query .= " LIMIT ".$requestData['start']." ,".$requestData['length'];

		$rs = $this->db->query($query);

		$result_rows =  $rs->result_array();
		$recordsFiltered = $this->db->affected_rows();

		$data = array();

		$json_data = array(
			"draw"            => intval( $requestData['draw'] ),
			"recordsTotal"    => intval( $recordsTotal ),
			"recordsFiltered" => intval( $recordsFiltered ),
			"data"            => $result_rows
		);

		return json_encode($json_data);
	}

	public function add($table,$data,$multiple=false){
		if($multiple){
			return $this->db->insert_batch($table,$data);
			
		}else{
			$rs = $this->db->insert($table,$data);
			return ($rs)?$this->db->insert_id():false;
		}
	}

	public function update($table,$data,$where){
		$this->db->where($where);
		$rs = $this->db->update($table,$data);	//$data is array
		// $this->debug($this->db->last_query());
		return ($this->db->affected_rows()>=0)?true:false;
	}

	public function inactive_bulk($table,$where,$pk){
		if(is_array($table)){
			$this->db->trans_start();
			foreach ($table as $tbl){
				$this->update($tbl,array("status" =>INACTIVE),"`$pk` IN ($where)");
			}
			$this->db->trans_complete();		
			return $this->db->trans_status();
		}else{
			$this->update($table,array("status" =>INACTIVE),"`$pk` IN ($where)");
			return $this->db->affected_rows();
		}
	}

	public function delete($table,$where){
		$this->db->where($where);
		$this->db->delete($table);
		return ($this->db->affected_rows()>0)?true:false;
	}

	public function delete_bulk($table,$where,$pk){
		if(is_array($table)){
			$this->db->trans_start();
			foreach ($table as $tbl){
				$this->db->query("DELETE FROM `$tbl` WHERE `$pk` IN ($where)");
			}
			$this->db->trans_complete();		
			return $this->db->trans_status();
		}else{
			$this->db->query("DELETE FROM `$table` WHERE `$pk` IN ($where)");		
			return $this->db->affected_rows();
		}
	}
	
	public function transection($data,$pk,$where=NULL){
		$this->db->trans_start();
		
		if($where){
			$this->update($data["base"]["table"],$data["base"]["data"],$where);
			$insId	= 	$where["$pk"];
		}else{
			$insId	=	$this->add($data["base"]["table"],$data["base"]["data"]);
		}		
		
		foreach($data["child"]["data"] as $k=>$v){
			$data["child"]["data"][$k]["$pk"]	=	$insId;
		}
		
		$this->add($data["child"]["table"],$data["child"]["data"],true);
		
		$this->db->trans_complete();
		
		return $this->db->trans_status();
	}

	public function order_transection($data,$pk,$where=NULL){
		$this->db->trans_start();
		
		if($where){
			$this->update($data["base"]["table"],$data["base"]["data"],$where);
			$insId	= 	$where["$pk"];
		}else{
			$insId	=	$this->add($data["base"]["table"],$data["base"]["data"]);
		}		
		
		foreach($data["child"]["data"] as $k=>$v){
			$data["child"]["data"][$k]["$pk"]	=	$insId;
		}
		
		$this->delete($data["child"]["table"],"order_id = $insId");

		$this->add($data["child"]["table"],$data["child"]["data"],true);
		
		$this->db->trans_complete();
		
		return $this->db->trans_status();
	}

	public function get_user_client_by_join($table,$where=NULL){
		$query = "SELECT `us`.*,`ct`.`name` AS `country_name`,`st`.`name` AS `state_name`, `cit`.`name` AS `city_name`
					FROM `$table` AS `us`
					LEFT JOIN `countries` AS `ct` ON `ct`.`country_id` = `us`.`country_id`
					LEFT JOIN `states` AS `st` ON `st`.`state_id` = `us`.`state_id`
					LEFT JOIN `cities` AS `cit` ON `cit`.`city_id` = `us`.`city_id`
				";
		$query .= ($where)?"WHERE $where":"";
		$rs = $this->db->query($query);
		return $rs->result_array();
	}

	public function get_order($where=NULL){	

      $query = "
      		SELECT 
				`t1`.*,
				GROUP_CONCAT(`cp`.`name`) AS `file_name_list`,
				GROUP_CONCAT(`cp`.`file_name`) AS `file_name_disk_list` 
			FROM
				(
					SELECT 
						`or`.`order_id`, 
						CONCAT(`cl`.`first_name`,' ',`cl`.`last_name`) AS `client_name`, 
						`cl`.`client_id`,
						`cl`.`email`,`cl`.`contact`,
						`or`.`order_date`,
						`or`.`assigned_to`,
						`or`.`assign_date`, 
						`or`.`status` AS `order_status`, 
						`or`.`processing_status`, 
						`od`.`product_id`,
						GROUP_CONCAT(`pr`.`name`) AS `product_name_list`,
						GROUP_CONCAT(`od`.`orders_details_id`) AS `orders_details_id_list`,
						CONCAT(`us`.`first_name`,' ',`us`.`last_name`) AS `assigned_to_user`
					FROM `orders` AS `or`
					LEFT JOIN `clients` AS `cl` ON `cl`.`client_id` = `or`.`client_id`				
					LEFT JOIN `orders_details` AS `od` ON `or`.`order_id` = `od`.`order_id`
					LEFT JOIN `products` AS `pr` ON `pr`.`product_id` = `od`.`product_id`            
					LEFT JOIN `users` AS `us` ON `or`.`assigned_to` = `us`.`user_id` ";

        $query .= ($where)?" WHERE $where ":"";

        $query .= "GROUP BY `or`.`order_id`
							order BY `or`.`order_id`
						) AS `t1`
					LEFT JOIN `cp_attachments` AS `cp` ON FIND_IN_SET(`cp`.`orders_details_id`, `t1`.`orders_details_id_list`) > 0
					GROUP BY `t1`.`order_id`";

		$rs = $this->db->query($query);
// echo $this->db->last_query();exit;
		return $rs->result_array();
	}

	public function uploadFile($fieldName,$renameFile=NULL,$uploadPath=NULL,$allowedTypes=NULL){
		
		$config['upload_path']		=	($uploadPath)?$this->uploadPath."$uploadPath/":$this->uploadPath;
		$config['allowed_types']	=	($allowedTypes)?$allowedTypes:$this->allowedTypes;
		$config['max_size']			=	1024*10; // 10 mb
		
		if($renameFile){
			$config['encrypt_name']		=	true;
		}
		
		$totalFiles					=	count($_FILES[$fieldName]['name']);
		
		$result			=	array("errorCount"=>0);
		
		for ($i = 0; $i<$totalFiles; $i++){
			
			$fileName	=	$_FILES[$fieldName]['name'][$i];

			$_FILES[$fileName]['name']		=	$_FILES[$fieldName]['name'][$i];
			$_FILES[$fileName]['type']		=	$_FILES[$fieldName]['type'][$i];
			$_FILES[$fileName]['tmp_name']	=	$_FILES[$fieldName]['tmp_name'][$i];
			$_FILES[$fileName]['error']		=	$_FILES[$fieldName]['error'][$i];
			$_FILES[$fileName]['size']		=	$_FILES[$fieldName]['size'][$i];
			
			$this->load->library("upload",$config);
			$this->upload->initialize($config);
			
			if(!$this->upload->do_upload($fileName)){
				$temp	=	$this->upload->data();
				$temp["errorMsg"]	=	$this->upload->error_msg[0];
				$result["error"][] = $temp;
				$result["errorCount"]++;
			}else{
				$result["success"][] = $this->upload->data();
			}
		}
		
		return $result;
	}

	public function upload_profile_image($fieldName,$renameFile=NULL,$uploadPath=NULL,$allowedTypes=NULL){

		$config['upload_path']		=	($uploadPath)?$this->uploadPath."$uploadPath/":$this->uploadPath;
		$config['allowed_types']	=	($allowedTypes)?$allowedTypes:$this->allowedTypes;
		if($renameFile){
			$config['encrypt_name']		=	true;
		}

		$this->load->library("upload",$config);
		// $this->upload->initialize($config);
		
		if(!$this->upload->do_upload($fieldName)){
			 return array('status'=>'error','message' => $this->upload->error_msg);
		}else{
			return array('status'=>'success', "message" => $this->upload->data());
		}
	}

	public function fieldUnique($fieldName,$value,$tableArray=NULL,$original_value=NULL){

		$tables	=	($tableArray)?$tableArray:$this->tables;
		
		$query = " WHERE `$fieldName` = '$value'";
		$query .= ($original_value)?" AND `$fieldName` != '$original_value'":"";

		foreach($tables as $tbl){
			$this->db->query("SELECT * FROM `$tbl`".$query);
			if($this->db->affected_rows()>0){
				return false;
			}
		}
		return true;		
	}

	function verify_dob($date=NULL){
		$end_date = date('Y-m-d');
		$start_date = date('Y-m-d',strtotime($end_date.'-100 years'));
		if($date>=$start_date && $date<=$end_date)
		{
			return true;
		}else{
			return "DOB neither exced today, nor older then 100 years from today";	
		}		
	}

		// public function get_client_order_list($where = null){
	// 	 $query = "SELECT `or`.`order_id`, CONCAT(`cl`.`first_name`,' ',`cl`.`last_name`) AS `client_name`, `cl`.`client_id`,`cl`.`email`,`cl`.`contact`,`or`.`order_date`,`or`.`assigned_to`,`or`.`assign_date`, `or`.`status` AS `order_status`, `or`.`processing_status`, `od`.`product_id`,GROUP_CONCAT(`cp`.`name`) AS `file_name_list`,GROUP_CONCAT(`cp`.`file_name`) AS `file_name_disk_list`, CONCAT(`us`.`first_name`,' ',`us`.`last_name`) AS `assigned_to_user`
	// 		FROM `orders` AS `or`
	// 		LEFT JOIN `clients` AS `cl` ON `cl`.`client_id` = `or`.`client_id`				
 //            LEFT JOIN `orders_details` AS `od` ON `or`.`order_id` = `od`.`order_id`
 //            LEFT JOIN `products` AS `pr` ON `pr`.`product_id` = `od`.`product_id`
 //            LEFT JOIN `cp_attachments` AS `cp` ON `cp`.`orders_details_id` = `od`.`orders_details_id`
 //            LEFT JOIN `users` AS `us` ON `or`.`assigned_to` = `us`.`user_id`
	// 		GROUP BY `or`.`order_id`
	// 		order BY `or`.`order_id`";
	// 		$query .= ($where)?" WHERE $where":"";
	// 		$rs = $this->db->query($query);
	// 		return $rs->result_array();
	// }

	public function send_email($to,$subject,$message){

		$sender_details = $this->session->_settings;

		$config = array(
		'protocol'		=>		'smtp',
		'smtp_host'		=>		'ssl://smtp.googlemail.com',
		'smtp_port'		=>		'465',
		'smtp_timeout'	=>		'7',
		'smtp_user'		=>		"{$sender_details['smtp_email']}",
		'smtp_pass'		=>		"{$sender_details['smtp_pass']}",
		'charset'		=>		'utf-8',
		'newline'		=>		"\r\n",
		'send_multipart'	=>	false,
		'mailtype'		=>		'html', // or html
		'validation'	=>		TRUE // bool whether to validate email or not  
		);

		$this->load->library('email',$config);

		$this->email->from("{$sender_details['smtp_email']}", "{$sender_details['email_alias']}");
		$this->email->to($to);
		$this->email->subject($subject);
		$this->email->message($message);

		return ($this->email->send())?true:false;
		
	}
}
