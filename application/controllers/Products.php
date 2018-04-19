<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends My_Controller {
	
	//$data variable will be accessible in all controller extending MY_Controller. Use $this->data['']
	public function __construct(){
		parent::__construct();
	}
	
	public function index()
	{
		$this->data['view']	=	'products/add';
		$this->load->view('components/layout',$this->data);
	}
	
	public function viewList(){
		$this->data['view']	=	'products/viewList';
		$this->load->view('components/layout',$this->data);
	}
	
	public function add($product_id=NULL){
		// $this->debug($_POST);exit;
		if($product_id){

			$editing_product	=	$this->model->get("products","product_id = $product_id");
			$editing_product	=	($editing_product)?$editing_product[0]:array();
			$this->data['editing_product'] = $editing_product;
			// $this->debug($editing_product);
		}

		if($this->input->server('REQUEST_METHOD') == "POST"){
			// $this->debug($_POST);
			$this->form_validation->set_rules($this->get_product_validation_rule());
			
			if($this->form_validation->run() === true){
				$dataToAdd["product_identifier"]=	$this->input->post("product_identifier");
				$dataToAdd["name"]	=	$this->input->post("name");
				$dataToAdd["description"]		=	($this->input->post("description"))?$this->input->post("description"):null;
				$dataToAdd["price"]	=	$this->input->post("price");
				$dataToAdd["status"]	=	$this->input->post("status");
				
				if($product_id){
					$rs2		=	$this->model->update("products",$dataToAdd,"product_id = $product_id");
					if($rs2){
						$this->session->set_flashdata("success","Product updated.");
						redirect('products/view_list');					
					}else{
					
						$this->session->set_flashdata("error","Product not updated. Please try again.");						
					}
				}else{
					$rs2		=	$this->model->add("products",$dataToAdd);
					if($rs2){
						$this->session->set_flashdata("success","Product added.");
						redirect('products/view_list');					
					}else{
					
						$this->session->set_flashdata("error","Product not added. Please try again.");						
					}
				}
			}
		}		

		$this->data['view']	=	'products/add';
		$this->load->view('components/layout',$this->data);
	}

	public function view_list(){

		$products		=	$this->model->get("products");
		$this->data["products"]	=	$products;

		$this->data['view']	=	'products/view_list';
		$this->load->view('components/layout',$this->data);
	}

	public function delete($product_id){

		if($this->model->delete("products","product_id = $product_id")){
			$this->session->set_flashdata("success","Deleted Successfully");
		}else{
			$this->session->set_flashdata("error","Unable to delete at this moment");
		}
		redirect("products/view_list");
		
	}

	public function inactive($product_id){

		if($this->model->update("products",array("status" =>INACTIVE),"product_id = $product_id")){
			$this->session->set_flashdata("success","Deleted Successfully");
		}else{
			$this->session->set_flashdata("error","Unable to delete at this moment");
		}
		redirect("products/view_list");
		
	}

	public function delete_bulk(){	
		// $this->debug($_POST)	;	
		$product_id_array = $_POST['user_id_array'];
		$count = count($product_id_array);
		$product_id_list = implode(",",$product_id_array);
		$table_name = $_POST['table_name'];
		if($table_name=="products"){
			$pk = "product_id";
			$table_name = "products";
		}else{
			$this->session->set_flashdata('error',"Logic Error (Products.php).");
			redirect("products/view_list");
		}

		if($affected_rows = $this->model->delete_bulk($table_name,$product_id_list,$pk)){
			$this->session->set_flashdata('success',"$count records deleted.");
		}else{
			$this->session->set_flashdata('error',"Records not deleted.");
		}

		redirect("products/view_list");
	}

	public function inactive_bulk(){

		$product_id_array = $_POST['user_id_array'];
		$count = count($product_id_array);
		$product_id_list = implode(",",$product_id_array);
		// $table_name = $_POST['table_name'];
		// if($table_name=="products"){
		// 	$pk = "product_id";
		// 	$table_name = "products";
		// }else{
		// 	$this->session->set_flashdata('error',"Logic Error (Products.php).");
		// 	redirect("products/view_list");
		// }

		// if($affected_rows = $this->model->inactive_bulk($table_name,$product_id_list,$pk)){
		if($affected_rows = $this->model->update("products",array("status"=>INACTIVE),"product_id in ($product_id_list)")){
			$this->session->set_flashdata('success',"$count records inactive.");
		}else{
			$this->session->set_flashdata('error',"Records not inactive.");
		}

		redirect("products/view_list");
	}

	public function get_product_validation_rule(){
		return	array(
						array(
									"field"	=>	"product_identifier",
									"label"	=>	"Product Id",
									"rules"	=>	"required"
							),
						array(
									'field'	=>	'name',
									'label'	=>	'Product Name',
									'rules'	=>	'required'
							),
						array(
									'field'	=>	'price',
									'label'	=>	'Price',
									'rules'	=>	'required'
							),
						array(
									'field'	=>	'description',
									'label'	=>	'Description',
									'rules'	=>	'callback_alpha_dash_space'
							)
					);
	}

	public function alpha_dash_space($str)
	{
		if(!preg_match('/^[a-z0-9 _-]+$/i',$str)){
			$this->form_validation->set_message("alpha_dash_space","Invalid Input");
			return false;
		}else{
			return true;
		}
	}

	public function product_status_master($product_id=null){
		$where = ($product_id)?"`or`.`product_id`=$product_id":"";
		if($product_id){
			$this->data['editing_product'] = $this->model->getField("products","product_id,name","product_id=$product_id")[0];
		}

		$this->data['products']	=	$this->model->get("products",$where);
		
		$this->data['view']	=	'products/product_status_master';
		// $this->debug($this->data['products']);
		$this->load->view('components/layout',$this->data);
	}

	public function product_processing_status_message(){
		echo "<xmp>";
		$this->debug($_POST);
	}
}
