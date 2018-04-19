 <?php
  $path_to_attachment = base_url("uploads/attachments/orders/");
  $path_to_attachment_disk = $_SERVER['DOCUMENT_ROOT']."/".PROJECT_ROOT."/uploads/attachments/orders/";
  $flashdata="";
  if($fd = $this->session->flashdata('success')){
    $flashdata  = "<div class='alert alert-success'>";
    $flashdata  .=  "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
    $flashdata  .=  $fd;
    $flashdata  .=  "</div>";
  }elseif($fd = $this->session->flashdata('error')){
    $flashdata  = "<div class='alert alert-danger'>";
    $flashdata  .=  "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
    $flashdata  .=  $fd;
    $flashdata  .=  "</div>";
  }elseif($fd = $this->session->flashdata('fileerror')){
    $flashdata  = "<div class='alert alert-danger'>";
    $flashdata  .=  "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>";
    $flashdata  .=  "Following errors are related to uploaded files: <br/><ul>";
    foreach($fd as $name=>$error){
      $flashdata    .=  "<li>$name - $error</li>";
    }
    $flashdata  .=  "</ul></div>";    
  }
?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->


<!-- Main content -->
<section class="content">
	<?php echo $flashdata; ?>
	<div class="box box-default box-solid">
		<div class="box-header with-border">
			<h3 class="box-title">Orders List</h3>
			<form method="post" action="<?php echo base_url("index.php/orders/inactive_bulk/"); ?>" class="inline" style="display: none;" id="bulk_delete_form">
			<input type="hidden" name="table_name" id="table_name" value="orders"/>
			<input type="hidden" id="msg" value="cancel {s} orders"/>
			<input type="submit" value="Cancel Selected Orders" class="pull-right btn btn-warning btn-xs" style="color:white;"/>
		</form>
		</div>		
		<div class="box-body">				
			<div class="row">
				<div class="col-sm-12 col-md-12">
					<table class="table datatable" id="list" data-order="[[ 4, &quot;asc&quot; ]]">
						<thead>
							<tr>
								<th data-searchable="false" data-orderable="false" data-sortable="false">
									<input type="checkbox" class="parent_checkbox" id="parent_checkbox">
								</th>
								<th>Client Name</th>
								<th>Email</th>
								<th>Contact No.</th>
								<!-- <th>Order Date</th> -->
								<th>Products</th>
								<!-- <th>Status</th> -->
								<th>Documents</th>
								<th  data-searchable="false" data-orderable="false" data-sortable="false">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($orders as $key=>$order_array): ?>
								<tr>
									<td>
										<input type="checkbox" class="child_checkbox" name="child_checkbox[]" value="<?php echo $order_array['order_id']; ?>">
									</td>
									<td>
										<a class="link" href="<?php echo base_url('index.php/clients/view_single/').$order_array['client_id']; ?>">
											<?php echo $order_array['client_name']; ?></a>
									</td>
									<td><?php echo $order_array['email']; ?></td>
									<td><?php echo $order_array['contact']; ?></td>
									<!-- <td><php echo date('d-m-Y',strtotime($order_array['order_date'])); ?></td> -->
									<td>
										<?php
											// $product_name_arr = explode(",",$order_array['product_name_list']);
											// echo "<ul style=\"list-style:none;\">";
											// foreach($product_name_arr as $name){
											// 	echo "<li>",$name,"</li>";
											// }
											// echo "</ul>";
											echo $order_array['product_name_list'];
										?>
									</td>
									<!-- <td><?php echo ($order_array['order_status']==ACTIVE)?"Active":"Inactive"; ?></td>			 -->						
									<td>
									<?php
										$file_name_arr = explode(",",$order_array['file_name_list']);

										$file_name_disk_arr = explode(",",$order_array['file_name_disk_list']);
										$file_count = ($order_array['file_name_disk_list'] != '')?count($file_name_disk_arr):0;

										if($file_count){
											echo "<div class=\"my_dropdown\">";
												echo "<div class=\"dropbtn user_flag\">";
											
											echo "<i class=\"user_flag fa fa-files-o fa-sm\" style=\"margin-right:15px;    vertical-align: text-bottom;\"></i>";
											echo "<span class=\"user_flag badge bg-yellow\" style=\"min-width: 30px;\">".$file_count."</span>";
											
											echo "</div>";
											echo "<div class=\"my_dropdown_content\">";

											foreach($file_name_disk_arr as $k=>$value){
												if($value && $file_name_arr[$k]){
													$path = $path_to_attachment.$value;
													echo "<a href=\"".$path."\" target=\"_blank\" class=\"\">".$file_name_arr[$k]."</a>  ";
												}
											}
											echo "</div></div>";
										}
									?>									
									</td>
									<td>
									<?php if($order_array['order_status']==ACTIVE): ?>
										<?php
											switch($order_array['processing_status']){
												case PENDING:
													echo "PENDING";
													break;		
												case PROCESSING:
													// echo "PROCESSING";
	echo "<a href=\"".base_url('index.php/orders/order_processed').'/'.$order_array['order_id']."\" class=\"btn btn-flat btn-warning btn-xs done_order\" style=\"color:white;\">Done</a>";
													break;
												case DONE:		
													echo "COMPLETE";
													break;
											}
										?>
									<?php else: ?>
										<?php
											switch($order_array['processing_status']){
												case PENDING:
													echo "INACTIVE & PENDING";
													break;		
												case PROCESSING:
													echo "INACTIVE & PROCESSING";
													break;
												case DONE:		
													echo "INACTIVE & DONE";
													break;
											}
										?>
									<?php endif; ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>												
			</div>
		</div>
		<div class="box-footer">
		</div>
	</div>
	
</section>
<!-- /.content -->
</div>
<?php require_once APPPATH."/views/components/pluginImpots.php" ?>

<script text="text/javascript">

$(document).ready(function(){

	$('.done_order').on('click',function(){
		var response = confirm("Are you sure to complete this order?");
			
		if(response){
			return true;
		}else{
			return false;
		}
	});

});
</script>