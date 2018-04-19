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
			<h3 class="box-title">Order Processing</h3>
		</div>
			<div class="box-body">			
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<table class="table datatable" id="list" data-order="[[ 2, &quot;desc&quot; ]]">
							<thead>
								<tr>
									<th data-searchable="false" data-orderable="false" data-sortable="false">
									<input type="checkbox" class="parent_checkbox" id="parent_checkbox">
								</th>								
									<th>Order #</th>
									<th>Order Date</th>
									<th>Order Status</th>									
									<th>Order Document</th>
									<th>Assigned To</th>
									<th>Assign Date</th>
									<th  data-searchable="false" data-orderable="false" data-sortable="false">
									Process Order
									</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach($orders as $key=>$order_array): ?>
								<tr>
									<td>
										<input type="checkbox" class="child_checkbox" name="child_checkbox[]" value="<?php echo $order_array['order_id']; ?>">
									</td>
									<th><?php echo $order_array['order_id']; ?></th>
									<td><?php echo date('d-m-Y',strtotime($order_array['order_date'])); ?></td>
									<td><?php echo ($order_array['order_status']==ACTIVE)?"Active":"Inactive"; ?></td>
									
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
										<?php if($order_array['assigned_to']): ?>
											<p class="text-center">
												<a class="link" href="<?php echo base_url('index.php/users/view_single/').$order_array['assigned_to']; ?>">
												<?php echo $order_array['assigned_to_user']; ?>
												</a>
											</p>
										<?php endif; ?>
									</td>
									<td>
										<?php if($order_array['assign_date']): ?>
											<p class="text-center">
												<?php echo date('d-m-Y',strtotime($order_array['assign_date'])); ?>
											</p>
										<?php endif; ?>
									</td>
									<td class="text-center">
										<?php if(!$order_array['assigned_to']): ?>
										<a href="<?php echo base_url('index.php/orders/assign_to_me/').$order_array['order_id']; ?>" class="btn btn-flat btn-warning btn-xs assign_to_me" style="color:white;">Assign to me</a>
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
<script>
$(document).ready(function(){

	$(".assign_to_me").on("click",function(){

		var response = confirm("Are you sure you want to process order.");
		
		if(response){
			return true; //window.locaion.href=$(this).attr('href');
		}else{
			return false;
		}			
	});
});
</script>