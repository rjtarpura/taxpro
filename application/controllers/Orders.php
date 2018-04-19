<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends My_Controller {
	
	//$data variable will be accessible in all controller extending MY_Controller. Use $this->data['']
	public function __construct(){
		parent::__construct();
	}
	
	public function index(){
		$this->data['clients']	=	$this->model->get("clients","status = ".ACTIVE);
		$this->data['products']	=	$this->model->get("products","status = ".ACTIVE);
		// $this->debug($this->data['products']);
		$this->data['view']	=	'orders/add';
		$this->load->view('components/layout',$this->data);
	}
	
	public function viewList(){
		$this->data['view']	=	'orders/viewList';
		$this->load->view('components/layout',$this->data);
	}
	
	public function add($order_id = NULL){
		
		$this->load->model("orders_m");

		if($order_id){

			$editing_order	=	$this->model->get("orders","order_id = $order_id");
			$editing_order	=	($editing_order)?$editing_order[0]:array();
			$this->data['editing_order'] = $editing_order;
			
			$product_id	=	$this->model->getField("orders_details","product_id,quantity","order_id={$editing_order['order_id']}");			
			// $product_id = array();
			// $this->debug($product_id);
			if($product_id){
				$this->data['editing_order']['product_id'] = $product_id;
				$this->data['products']	=	$this->model->get("products","status = ".ACTIVE. " OR product_id IN (".implode(',',array_column($product_id,'product_id')).")");
			}else{
				$this->data['editing_order']['product_id'] = array();
				$this->data['products']	=	$this->model->get("products","status = ".ACTIVE);
			}
			
			$this->data['clients']	=	$this->model->get("clients","status = ".ACTIVE. " OR client_id = {$editing_order['client_id']}");			

		}else{
			$this->data['clients']	=	$this->model->get("clients","status = ".ACTIVE);
			$this->data['products']	=	$this->model->get("products","status = ".ACTIVE);
		}

		if($this->input->server('REQUEST_METHOD') == "POST"){

			$this->form_validation->set_rules($this->get_order_validation_rule());
			
			if($this->form_validation->run() === true){

				// Uploading Files
				$key_mapping = $this->input->post("order_description");
				$result_array = $this->orders_m->upload_order_files("attachment",$key_mapping,true,null,"attachments/orders");
				
				$order_details = $this->input->post("order_description");
				
				$order_array = array();
				$order_array['client_id'] = $this->input->post('client_id');
				$order_array['order_date'] = $this->input->post('order_date');

				if(!$order_id){
					$order_array['create_by'] = $this->user_id_sess;
				}

				$where = ($order_id)?array("order_id" => $order_id):null;

				$status = $this->orders_m->create_order($order_array,$order_details,$result_array,$where);
				
				if($status){

					if($result_array['errors']['upload_errors']){

						$fileErrorArray = array();
						foreach($result_array['errors']['details'] as $k=>$val){
							$fileErrorArray[$k] = $val['error_msg']."(" .$val['file_name']. ")"." - [ Product - ".$val['product_name']."]";
						}
						
						$this->session->set_flashdata("fileerror",$fileErrorArray);	

					}else{

						if($order_id){
							$this->session->set_flashdata("success","Order updated.");
						}else{
							$this->session->set_flashdata("success","Order added.");
						}						
					}
					redirect('orders/view_list');
				}else{

					// Unlink files if transection fails
					foreach($result_array['file_names'] as $kk=>$vv){
						unlink($_SERVER['DOCUMENT_ROOT']."taxpro/uploads/".$vv);						
					}

					if($order_id){
						$this->session->set_flashdata("success","Order not updated.");
					}else{
						$this->session->set_flashdata("success","Order not added.");
					}
				}
			}
			// else{
			// 	$this->debug(validation_errors(),0);
			// 	$this->debug($_POST);
			// }
		}

		// $this->data['clients']	=	$this->model->get("clients");
		// $this->data['products']	=	$this->model->get("products");
		
		$this->data['view']	=	'orders/add';
		$this->load->view('components/layout',$this->data);
	}

	public function view_list(){
		
		$this->data['orders']	=	$this->model->get_order();

		// $this->debug($this->data['orders']);
		$this->data['view']	=	'orders/view_list';
		$this->load->view('components/layout',$this->data);
	}

	public function inactive($order_id){
		// $this->debug($order_id);
		if($order_id){
			$status = $this->model->update("orders",array("status"=>INACTIVE),"order_id = $order_id");
			if($status){
				$this->session->set_flashdata("success","Order Cancelled.");
			}else{
				$this->session->set_flashdata("error","Order not Cancelled.");
			}				
		}else{
			$this->session->set_flashdata("error","Internal Error.");
		}
		redirect('orders/view_list');
	}

	public function inactive_bulk(){	
		// $this->debug($_POST)	;	
		$order_id_array = $_POST['user_id_array'];
		$count = count($order_id_array);
		$order_id_list = implode(",",$order_id_array);
		$table_name = $_POST['table_name'];
		if($table_name=="orders"){
			if($affected_rows = $this->model->update($table_name,array("status"=>INACTIVE),"order_id in ($order_id_list)")){
				$this->session->set_flashdata('success',"$count orders inactived.");
			}else{
				$this->session->set_flashdata('error',"Records not inactiveated.");
			}
		}else{
			$this->session->set_flashdata('error',"Logic Error (Users.php).");
		}		

		redirect("orders/view_list");
	}

	public function get_order_validation_rule(){
		return	array(
						array(
									"field"	=>	"client_id",
									"label"	=>	"Client Id",
									"rules"	=>	"required"
							),
						array(
									'field'	=>	'order_date',
									'label'	=>	'Order Date',
									'rules'	=>	'required'
							),
					);
	}

	public function order_status_list(){
		$this->data['orders']	=	$this->model->get_order();
		// $this->debug($this->data['orders']);
		$this->data['view']	=	'orders/order_status_list';
		$this->load->view('components/layout',$this->data);
	}

	public function order_processing(){
		$this->data['orders']	=	$this->model->get_order();		//"`or`.`assigned_to` IS NULL"
		// $this->debug($this->data['orders']);
		$this->data['view']	=	'orders/order_processing';
		$this->load->view('components/layout',$this->data);
	}

	public function my_orders(){
		$this->data['orders']	=	$this->model->get_order("`or`.`assigned_to` = ".$this->user_id_sess);	//." AND `or`.`processing_status` != ".DONE
		// $this->debug($this->data['orders']);
		$this->data['view']	=	'orders/my_orders';
		$this->load->view('components/layout',$this->data);
	}

	public function assign_to_me($order_id=null){
		if($order_id){
			$data = array(
				"assigned_to"=>$this->user_id_sess,
				"assign_date"=>date("Y-m-d"),
				"processing_status"=>PROCESSING
			);

			$status = $this->model->update("orders",$data,"order_id = $order_id");

			if($status){
				$this->session->set_flashdata("success","Order assigned.");
			}else{
				$this->session->set_flashdata("error","Order not assigned.");
			}				
		}else{
			$this->session->set_flashdata("error","Internal Error. #1234");
		}
		redirect('orders/order_processing');
	}

	public function order_processed($order_id=null){
		if($order_id){
			$data = array(				
				"processing_status"=>DONE
			);

			$status = $this->model->update("orders",$data,"order_id = $order_id");

			if($status){
				$this->session->set_flashdata("success","Order Completed.");
			}else{
				$this->session->set_flashdata("error","Order not Completed.");
			}				
		}else{
			$this->session->set_flashdata("error","Internal Error. #1234");
		}
		redirect('orders/my_orders');
	}	
}
