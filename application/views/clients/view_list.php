<style>
</style>

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
			<h3 class="box-title">Client List
				<!-- <span>
				<a href="<php echo base_url('index.php/clients');?>" style="margin-left: 15px;cursor: pointer;color:#3c8dbc;" title="Add Client"><i class="fa fa-plus"></i></a>
				</span> -->
			</h3>			
			<form method="post" action="<?php echo base_url("index.php/clients/inactive_bulk/"); ?>" class="inline" style="display: none;" id="bulk_delete_form">
				<input type="hidden" name="table_name" id="table_name" value="clients"/>
				<input type="hidden" id="msg" value="inactive {s} clients"/>
				<input type="submit" value="Inactive Selected" class="pull-right btn btn-warning btn-xs" style="color:white;"/>
			</form>
		</div>
		<div class="box-body">
			<!-- <div class="row">
				<div class="col-sm-12">
					<span><a href="#" class="btn btn-lg" ><i class="fa fa-plus"></i></a></span>
				</div>
			</div> -->
			<div class="row">
				<div class="col-sm-12 col-md-12">

					<table class="table datatable" id="list" data-order="[[ 1, &quot;asc&quot; ]]">
						<thead>
							<tr>
								<th data-searchable="false" data-orderable="false" data-sortable="false">
									<input type="checkbox" class="parent_checkbox" id="parent_checkbox">
								</th>
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th>Contact No.</th>
								<th>Status</th>
								<th  data-searchable="false" data-orderable="false" data-sortable="false">Action</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($clients as $key=>$clientArr): ?>
							<tr>
								<td>
									<input type="checkbox" class="child_checkbox" name="child_checkbox[]" value="<?php echo $clientArr['client_id']; ?>">
								</td>
								<td>
									<a class="link" href="<?php echo base_url('index.php/clients/view_single/').$clientArr['client_id']; ?>">
									<?php echo $clientArr['first_name']; ?></a>
								</td>
								<td><?php echo $clientArr['last_name']; ?></td>
								<td><?php echo $clientArr['email']; ?></td>
								<td><?php echo $clientArr['contact']; ?></td>
								<td><?php echo ($clientArr['status']==ACTIVE)?"<span class=\"user_flag badge bg_my_green\" style=\"min-width: 30px;\">ACTIVE</span>":"<span class=\"user_flag badge bg_my_red\" style=\"min-width: 30px;\">INACTIVE</span>"; ?>
									
								</td>
								<td>
									<div class="btn btn-default btn-xs my_dropdown_group">
										<a href="#" class="" data-toggle="dropdown" aria-expanded="true" style="text-decoration:none;" title="Ping Client">
											<i class="fa fa-comment"></i>
										</a>
										<ul class="dropdown-menu my_dropdown_message">
											<li class="dropdown-header"> SEND MESSAGE </li>
											<li>
												<a href="<?php echo base_url('index.php/ajax/send_email');?>" 
													data-id="<?php echo $clientArr['client_id'];?>"
													data-message_type = "<?php echo MESSAGE_PROCESSING;
													?>"
													data-master_name = "clients"
													class="send_email"	 
												>
													<i class="fa fa-circle text-orange"></i>
													&nbsp;
													<span class="text-orange">Processing</span>
												</a>
											</li>
											<li>
												<a href="<?php echo base_url('index.php/ajax/send_email');?>" 
													data-id="<?php echo $clientArr['client_id'];?>"
													data-message_type = "<?php echo MESSAGE_DONE; ?>"
													data-master_name = "clients"
													class="send_email"
												>
													<i class="fa fa-circle text-green"></i>
													&nbsp;
													<span class="text-green">Done</span>
												</a>
											</li>
										</ul>
									</div>

									<a href="<?php echo base_url("index.php/clients/view_single/").$clientArr['client_id']; ?>" class="btn btn-success btn-xs" data-toggle="title" title="View"><i class="fa fa-eye"></i></a>
									<a href="<?php echo base_url("index.php/clients/add/").$clientArr['client_id']; ?>" class="btn btn-warning btn-xs" data-toggle="title" title="Edit"><i class="fa fa-edit"></i></a>
									
									<?php if($clientArr['status']==ACTIVE):?>	<!--ACTIVE-->
									<a href="<?php echo base_url("index.php/clients/inactive/").$clientArr['client_id']; ?>" class="btn btn-danger btn-xs inactive" data-toggle="title" title="Inactive" data-info_message_noun="Client"><i class="fa fa-times"></i></a>
									<?php endif;?>									
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

<script type="text/javascript">
$(document).ready(function(){
	
});
</script>