  <?php
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
			<h3 class="box-title">Product List</h3>
			<form method="post" action="<?php echo base_url("index.php/products/inactive_bulk/"); ?>" class="inline" style="display: none;" id="bulk_delete_form">
				<input type="hidden" name="table_name" id="table_name" value="products"/>
				<input type="hidden" id="msg" value="inactive {s} products"/>
				<input type="submit" value="Inactive Selected" class="pull-right btn btn-warning btn-xs" style="color:white;"/>
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
								<th>Product ID</th>
								<th>Name</th>
								<th>Price</th>
								<th>Description</th>
								<th>Status</th>
								<th  data-searchable="false" data-orderable="false" data-sortable="false">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($products as $key=>$productArr): ?>
								<tr>
									<td>
										<input type="checkbox" class="child_checkbox" name="child_checkbox[]" value="<?php echo $productArr['product_id']; ?>">
									</td>
									<td><?php echo $productArr['product_identifier']; ?></td>
									<td><?php echo $productArr['name']; ?></td>
									<td><?php echo $productArr['price']; ?></td>
									<td><?php echo $productArr['description']; ?></td>
									<td><?php echo ($productArr['status']==ACTIVE)?"<span class=\"user_flag badge bg_my_green\" style=\"min-width: 30px;\">ACTIVE</span>":"<span class=\"user_flag badge bg_my_red\" style=\"min-width: 30px;\">INACTIVE</span>"; ?>
										
									</td>
									<td>										
										<a href="<?php echo base_url("index.php/products/add/").$productArr['product_id']; ?>" class="btn btn-warning btn-xs" data-toggle="title" title="Edit"><i class="fa fa-edit"></i></a>
										<a href="<?php echo base_url("index.php/products/inactive/").$productArr['product_id']; ?>" class="btn btn-danger btn-xs inactive" data-toggle="title" title="Inactive" data-info_message_noun="Product"><i class="fa fa-times"></i></a>
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