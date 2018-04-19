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
			<h3 class="box-title">Users List</h3>
			<!-- <span class="pull-right"><a href="javascript:void(0)" class="btn btn-xs" id="all_delete" data-table_name="users" style="display:none"><i class="fa fa-trash"></i> | DEL</a></span> -->
			<form method="post" action="<?php echo base_url("index.php/users/inactive_bulk/"); ?>" class="inline" style="display: none;" id="bulk_delete_form">
				<input type="hidden" name="table_name" id="table_name" value="users"/>
				<input type="hidden" id="msg" value="inactive {s} users"/>
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
								<th>First Name</th>
								<th>Last Name</th>
								<th>Email</th>
								<th>Contact No.</th>
								<th>Status</th>
								<th  data-searchable="false" data-orderable="false" data-sortable="false">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($users as $key=>$userArr): ?>
							<tr>
								<td>
									<input type="checkbox" class="child_checkbox" name="child_checkbox[]" value="<?php echo $userArr['user_id']; ?>">
								</td>
								<td><?php echo $userArr['first_name']; ?></td>
								<td><?php echo $userArr['last_name']; ?></td>
								<td><?php echo $userArr['email']; ?></td>
								<td><?php echo $userArr['contact']; ?></td>
								<td><?php echo ($userArr['status']==ACTIVE)?"<span class=\"user_flag badge bg_my_green\" style=\"min-width: 30px;\">ACTIVE</span>":"<span class=\"user_flag badge bg_my_red\" style=\"min-width: 30px;\">INACTIVE</span>"; ?>									
								</td>
								<td>
									<a href="<?php echo base_url("index.php/users/view_single/").$userArr['user_id']; ?>" class="btn btn-success btn-xs" data-toggle="title" title="View"><i class="fa fa-eye"></i></a>
									<?php if($user_role_sess == ADMIN):?>
									<a href="<?php echo base_url("index.php/users/add/").$userArr['user_id']; ?>" class="btn btn-warning btn-xs" data-toggle="title" title="Edit"><i class="fa fa-edit"></i></a>									
									<a href="<?php echo base_url("index.php/users/inactive/").$userArr['user_id']; ?>" class="btn btn-danger btn-xs inactive" data-toggle="title" title="Inactive" data-info_message_noun="User"><i class="fa fa-times"></i></a>
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

<script type="text/javascript">

	$(document).ready(function(){

	});
	
</script>