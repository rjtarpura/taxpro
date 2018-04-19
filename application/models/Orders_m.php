<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders_m extends CI_Model {

	/**
	* @var string
	* Default path to upload folder
	**/
	private $root = '';

	/**
	* @var string
	* Allowed file types to upload
	**/
	private $file_types = '';


	public function __construct(){
		parent::__construct();

		$this->root = $_SERVER['DOCUMENT_ROOT']."/".PROJECT_ROOT."/uploads/";
		$this->file_types = "gif|jpg|png|txt|doc|docx|xls|xlsx|pdf|rtf";

	}// End Constructor

	/**
	* @param string 	REQUIRED 	$field_name 			html input tag's name attribute
	* @param array 		REQUIRED 	$key_mapping array
	* @param boolean 	OPTIONAL 	$rename_uniquely		when true rename all uploaded files uniquely
	* @param string 	OPTIONAL 	$allowed_file_types		A string containing '|' seprated extensions of allowed 															files types to upload
	* @param string 	OPTIONAL 	$upload_path_after_root	path to folder where we want to upload files,																 relative to project root/uploads 
	* Date : 08-03-2018
	* @author Rakesh Jangir
	* @return multi-dimensional array
	**/
	public function upload_order_files($field_name, $key_mapping, $rename_uniquely=true, $allowed_file_types=NULL, $upload_path_after_root=NULL){

		$file_array = array($field_name=>array());
		$attachmentData = array();
		$files_names=array();
		$file_upload_errors = array("upload_errors"=>0);

		foreach($_FILES[$field_name]['name'] as $key=>$array){
			
			if($array[0] != ''){				
				foreach($array as $k=>$v){
					$file_array[$field_name][$key_mapping[$key]['product_id']][$k]['name']=$v;
					$file_array[$field_name][$key_mapping[$key]['product_id']][$k]['type']=$_FILES[$field_name]['type'][$key][$k];
					$file_array[$field_name][$key_mapping[$key]['product_id']][$k]['tmp_name']=$_FILES['attachment']['tmp_name'][$key][$k];
					$file_array[$field_name][$key_mapping[$key]['product_id']][$k]['error']=$_FILES['attachment']['error'][$key][$k];
					$file_array[$field_name][$key_mapping[$key]['product_id']][$k]['size']=$_FILES['attachment']['size'][$key][$k];
				}
			}
		}

		$config['upload_path']		=	($upload_path_after_root)?$this->root.$upload_path_after_root:$this->root;

		$config['allowed_types']	=	($allowed_file_types)?$allowed_file_types:$this->file_types;

		$config['max_size']			=	1024*10; // 10 mb

		$config['encrypt_name']		=	($rename_uniquely)?true:false;

		foreach($file_array[$field_name] as $key1=>$value1){		

			foreach($file_array[$field_name][$key1] as $key2=>$value2){

				// var_dump(move_uploaded_file($value2['tmp_name'],$upload_path.$value2['name']));

				$_FILES[$field_name]['name']		=	$value2['name'];
				$_FILES[$field_name]['type']		=	$value2['type'];
				$_FILES[$field_name]['tmp_name']	=	$value2['tmp_name'];
				$_FILES[$field_name]['error']		=	$value2['error'];
				$_FILES[$field_name]['size']		=	$value2['size'];
				
				$this->load->library("upload",$config);
				$this->upload->initialize($config);

				if(!$this->upload->do_upload($field_name)){
					// $file_upload_errors[$key1][$key2] = array(
					$file_upload_errors['details'][] = array(
															"error_msg"=>$this->upload->error_msg[0],
															"product_name"=>$this->model->getField("products","name","product_id = $key1")[0]['name'],
															"file_name" => $_FILES[$field_name]['name']
															);					
					$file_upload_errors["upload_errors"]++;
					
				}else{
					$succes_data_array = $this->upload->data();
					$attachmentData[$key1][$key2]	=	array(
													"name"		=>	$succes_data_array["orig_name"],
													"file_name"	=>	$succes_data_array["file_name"],
													"size"		=>	$succes_data_array["file_size"],
													"type"		=>	$succes_data_array["file_type"],
													"extension"	=>	substr($succes_data_array["file_ext"],1,strlen($succes_data_array["file_ext"])),
												);
					$files_names[] = $succes_data_array["file_name"];
				}
			}
		}

		return array(
						"success"	=>	$attachmentData,
						"errors"	=>	$file_upload_errors,
						"file_names"=> 	$files_names
					);
	}// End upload_order_files function

	public function create_order($order_array,$orders_details,$attachment_array,$where=NULL){
		
		if($where){
			return $this->update_order($order_array,$orders_details,$attachment_array,$where);
		}

		$this->db->trans_start();

		$rs = $this->db->insert("orders",$order_array);		
		$order_id = ($rs)?$this->db->insert_id():null;
		
		foreach($orders_details as $k=>$v){

			$orders_details[$k]["order_id"]	=	$order_id;

			$this->db->insert("orders_details",$orders_details[$k]);
			
			$orders_details_id = ($rs)?$this->db->insert_id():null;
			
			if($orders_details_id){
				if(array_key_exists($orders_details[$k]['product_id'], $attachment_array['success'])){
					foreach($attachment_array['success'][$orders_details[$k]['product_id']] as $k=>$v){
						$data = $v;
						$data['orders_details_id'] = $orders_details_id;
						$this->db->insert("cp_attachments",$data);
					}					
				}
			}
		}

		$this->db->trans_complete();		
		return $this->db->trans_status();

	}// End create_order function

	public function update_order($order_array,$orders_details,$attachment_array,$where){

		$this->db->trans_start();

		$this->db->where($where);
		$rs = $this->db->update("orders",$order_array);
		$order_id = ($rs)?$where["order_id"]:null;
		
		$product_ids = implode(",",array_column($orders_details,"product_id"));

		$query = "SELECT GROUP_CONCAT(`orders_details_id`) as `orders_details_id_to_delete` FROM `orders_details` WHERE `order_id` = $order_id AND `product_id` NOT IN ($product_ids)";
		$result = $this->db->query($query);

		$orders_details_id_to_delete = $result->result_array()[0]['orders_details_id_to_delete'];

		if($orders_details_id_to_delete){
			$this->db->delete("orders_details","orders_details_id IN ($orders_details_id_to_delete)");
			$this->db->delete("cp_attachments","orders_details_id IN ($orders_details_id_to_delete)");
		}

		foreach($orders_details as $k=>$v){

			$this->db->where("order_id = $order_id AND product_id = ".$v['product_id']);
			$result = $this->db->get("orders_details");
			$result = $result->result_array();

			$orders_details[$k]["order_id"]	=	$order_id;

			$orders_details_id = null;

			if(!$result){	// Empty array -> new product in this order				
				$this->db->insert("orders_details",$orders_details[$k]);
				$orders_details_id = ($rs)?$this->db->insert_id():null;
			}else{
				$orders_details_id = $result[0]['orders_details_id'];
				$st = $this->db->update("orders_details",$orders_details[$k],"orders_details_id = ".$orders_details_id);
				$orders_details_id = ($st)?$orders_details_id:null;
			}

			if($orders_details_id){
				if(array_key_exists($orders_details[$k]['product_id'], $attachment_array['success'])){
					foreach($attachment_array['success'][$orders_details[$k]['product_id']] as $k=>$v){
						$data = $v;
						$data['orders_details_id'] = $orders_details_id;
						$this->db->insert("cp_attachments",$data);
					}					
				}
			}
		}

		$this->db->trans_complete();		
		return $this->db->trans_status();

	}

	public function create_order_for_client($client_data,$order_details,$result_array){
		$this->db->trans_start();

		$rs = $this->db->insert("clients",$client_data);
		$ins_id = ($rs)?$this->db->insert_id():null;

		$order_array['client_id'] = $ins_id;
		$order_array['order_date'] = date('Y-m-d');
		
		$this->create_order($order_array,$order_details,$result_array);

		$this->db->trans_complete();		
		return $this->db->trans_status();
	}

}// End Orders_m.php