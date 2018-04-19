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
	<div class="box box-default box-solid">
		<div class="box-header with-border">
			<h3 class="box-title">Orders List</h3>
			<form method="post" action="<?php echo base_url("index.php/orders/inactive_bulk/"); ?>" class="inline" style="display: none;" id="bulk_delete_form">
			<input type="hidden" name="table_name" id="table_name" value="orders"/>
			<input type="hidden" id="msg" value="cancel {s} orders"/>
			<input type="submit" value="Cancel Selected Orders" class="pull-right btn btn-primary btn-xs" style="color:white;"/>
		</form>
		</div>		
		<div class="box-body">				
			<div class="row">
				<div class="col-sm-12 col-md-12">					 
					<table class="table datatable" id="list" data-order="[[ 1, &quot;asc&quot; ]]">
						<thead>
							<tr>
								<th data-searchable="false" data-orderable="false" data-sortable="false">
									<input type="checkbox" class="parent_checkbox" id="parent_checkbox">
								</th>
								<th>Order #</th>
								<th>Client Name</th>
								<!-- <th>Email</th>
								<th>Contact No.</th>
								<th>Order Date</th>
								<th>Products</th> -->
								<th>Status</th>
								<th>Active/ Deactive</th>
								<!-- <th>Documents</th> -->
								<th  data-searchable="false" data-orderable="false" data-sortable="false">File Details</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($orders as $key=>$order_array): ?>
								<tr>									
									<td>
										<input type="checkbox" class="child_checkbox" name="child_checkbox[]" value="<?php echo $order_array['order_id']; ?>">
									</td>
									<th><?php echo $order_array['order_id']; ?></th>
									<td><?php echo $order_array['client_name']; ?></td>
									<!-- <td><?php echo $order_array['email']; ?></td>
									<td><?php echo $order_array['contact']; ?></td>
									<td><?php echo date('d-m-Y',strtotime($order_array['order_date'])); ?></td> -->
									<td>**</td>
									<td><?php echo ($order_array['order_status']==ACTIVE)?"Active":"Inactive"; ?></td>
									<td>										
										***
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
	<?php echo $flashdata; ?>
</section>
<!-- /.content -->
</div>
<?php require_once APPPATH."/views/components/pluginImpots.php" ?>

<script text="text/javascript">
$(document).ready(function(){
	
});
</script>