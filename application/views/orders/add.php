<style>
.selected{
	/*background-color: #e2e0e0;*/
}

.custom_error{
	/*border-color:#a94442;*/
}

.help-block{
	/*color: #dd4b39;;*/
}
.custom_hide{
	margin-left: -107px;
	margin-right: 77px;
}
</style>

<?php
$editing_order	=	(isset($editing_order))?$editing_order:'';
$disable_or_not =	($editing_order)?"disabled = \"disabled\"":'';

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
	
	<form class="form-horizontal" method="post" action="<?php echo base_url('index.php/orders/add/');echo ($editing_order)?$editing_order['order_id']:''; ?>" enctype="multipart/form-data" id="client_order_form">
		<div class="box box-default box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Add Order</h3>				
			</div>
			<div class="box-body">
				<div class="row" style="margin-bottom: 20px;margin-left: 0px;font-size: 17px;margin-top: 2px;">
					<div class="col-sm-12">
						<a href="<?php echo base_url('index.php/clients/add'); ?>">
                            <i class="fa fa-plus"></i> 
                            Add Client
                        </a>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<div class="form-group <?php echo (form_error('client_id'))?'has-error':''; ?>">
							<label for="client_id" class="col-sm-2 col-md-2 control-label">Client<span style="color:red;">*</span></label>
							<div class="col-sm-10 col-md-8">
								<div class="input-group">
									<select class="form-control select2" data-placeholder="Select Client" id="client_id" name="client_id" style="width: 100%;" tabindex="1" <?php echo $disable_or_not; ?>>
									  <?php									
											$client_id = set_value('client_id',($editing_order)?$editing_order['client_id']:'');
											$selected = ($client_id)?"":"selected=\"selected\" ";
											echo "<option value=\"\" $selected>Select Client</option>";
											
											foreach($clients as $key=>$clientArr){
												$selected = ($client_id == $clientArr['client_id'])?"selected=\"selected\" ":"";
												echo "<option value=\"{$clientArr['client_id']}\" $selected>{$clientArr['first_name']} {$clientArr['last_name']}</option>";
											}
										?>
									</select>
									<span class="input-group-addon">
										<a href="javascript:void(0)" id="client_search" <?php echo $disable_or_not; ?>><i class="fa fa-search"></i></a>
									</span>
								</div>								
								<span class="help-block"><?php echo form_error('client_id'); ?></span>
							</div>
						 </div>
						<!-- <div class="form-group <?php echo (form_error('description'))?'has-error':''; ?>" style="display:none;">
							<label for="description" class="col-sm-2 col-md-2 control-label">Description</label>

							<div class="col-sm-10 col-md-8">
							  <textarea class="form-control" rows="3" id="description" name="description" placeholder="Product Description"><?php echo set_value('description',($editing_order)?$editing_order['description']:''); ?></textarea>
							  <span class="help-block"><?php echo form_error('description'); ?></span>
							</div>							
						</div>	 -->											
					</div>					
					<div class="col-sm-12 col-md-6">
						<div class="form-group <?php echo (form_error('order_date'))?'has-error':''; ?>">
							<label for="order_date" class="col-sm-2 col-md-2 control-label">Order Date<span style="color:red;">*</span></label>
							<div class="col-sm-10 col-md-8">
								<div class="input-group date">
								  <input type="text" class="form-control datepicker pull-right" id="order_date" readonly="readonly" name="order_date" value="<?php echo set_value('order_date',($editing_order)?$editing_order['order_date']:''); ?>" <?php echo $disable_or_not; ?>>
								  <span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </span>
								</div>
								<span class="help-block"><?php echo form_error('order_date'); ?></span>
							</div>
							<!-- /.input group -->
						</div>
					</div>

				</div>				
			</div>				
			<div class="box-footer">
				<div class="pull-right">
					<?php if($editing_order){
						echo "<button type=\"submit\" class=\"btn btn-primary\">Update</button>
						<input type=\"button\" class=\"btn btn-default\" value=\"Go Back\"  onclick=\"location.href='".base_url("index.php/orders/view_list")."';\" name=\"go_back\"/>";
					}else{
						echo "<button type=\"submit\" class=\"btn btn-primary\">Submit</button>
						<button type=\"reset\" class=\"btn btn-default\">Cancel</button>";
					}
					?>
				</div>
			</div>		
		</div>
	

</section>
<!-- /.content -->
</div>

<!--model -->
<div class="modal fade" id="client_modal">
  <div class="modal-dialog" style="width: 80%;">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Select Client</h4>
      </div>
      <div class="modal-body">
        <table class="table datatable" id="client_table" data-order="[[ 1, &quot;asc&quot; ]]">
			<thead>
				<tr>
					<th>First Name</th>
					<th>Last Name</th>
					<th>Email</th>
					<th>Contact No.</th>
					<th>Status</th>
					<!-- <th  data-searchable="false" data-orderable="false" data-sortable="false">Action</th> -->
				</tr>
			</thead>
			<tbody>
				<?php foreach($clients as $key=>$clientArr): ?>
				<tr data-client_id = <?php echo $clientArr['client_id']; ?>>
					<td><?php echo $clientArr['first_name']; ?></td>
					<td><?php echo $clientArr['last_name']; ?></td>
					<td><?php echo $clientArr['email']; ?></td>
					<td><?php echo $clientArr['contact']; ?></td>
					<td><?php echo ($clientArr['status']==ACTIVE)?"Active":"Inactive"; ?></td>
					<!-- <td>
						<a href="<?php echo base_url("index.php/clients/view_single/").$clientArr['client_id']; ?>" class="btn btn-success btn-xs" data-toggle="title" title="View"><i class="fa fa-eye"></i></a>
						<a href="<?php echo base_url("index.php/clients/add/").$clientArr['client_id']; ?>" class="btn btn-warning btn-xs" data-toggle="title" title="Edit"><i class="fa fa-edit"></i></a>
						<a href="<?php echo base_url("index.php/clients/delete/").$clientArr['client_id']; ?>" class="btn btn-danger btn-xs delete" data-toggle="title" title="Delete" data-info_message_noun="Client"><i class="fa fa-trash"></i></a>
					</td> -->
				</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
      </div>
      <!-- <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div> -->
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!--model end -->

<?php require_once APPPATH."/views/components/pluginImpots.php" ?>

<script type="text/javascript">
// var rules		=	{
// 							client_id		:	{
// 												required		:	true
// 											},
// 							'product_id[]'	:	{
// 												required		: 	true
// 											},
// 							order_date	:	{
// 												required		: 	true
// 											},
// 							description : 	"address"			
// 						};

// 	var messages	=	{							
// 							// product_identifier		:	{
// 							// 					remote			:	"Product ID already Exists"
// 							// 				}
// 						};

// $('#validateForm').validate({
// 	debug		:	true,
// 	rules		: 	rules,
// 	messages	:	messages,
// 	errorElement: "span",
// 	errorPlacement: function ( error, element ) {
// 		// console.log($(element).closest("div.has-error"));
// 		// error.addClass( "help-block" ).css("color","#a94442");
				
// 		$(element).next().text($(error)[0].innerHTML);
// 		// error.insertAfter( element );
// 	},
// 	highlight: function ( element, errorClass, validClass ) {
// 		// $( element ).parents( ".col-sm-10" ).addClass( "has-error" ).removeClass( "has-success" );
// 		$( element ).parents( ".form-group" ).addClass( "has-error" );
// 		$( element ).next().text('');

// 	},
// 	unhighlight: function (element, errorClass, validClass) {
// 		// $( element ).parents( ".col-sm-10" ).addClass( "has-success" ).removeClass( "has-error" );
// 		$( element ).parents( ".form-group" ).removeClass( "has-error" );
// 		$( element ).next().text('');
// 	},
// 	submitHandler: function(form) {
		
// 		form.submit();
// 	}
// });


</script>
<script type="text/javascript">
var add_row_flag = <?php echo ($editing_order)?json_encode($editing_order['product_id']):0; ?>;
$(document).ready(function() {

	// $.validator.setDefaults({
 //        ignore: [],        
 //    });

	$('#client_order_form').validate({
		debug		:	true,
		rules		: 	{
						client_id		:	{
											required		:	true
										},						
						order_date	:	{
											required		: 	true
										}
						},
		errorElement: "span",
		errorPlacement: function ( error, element ) {
			
			if($(element).hasClass("datepicker")){
				$(element).parent("div").next().text($(error)[0].innerHTML);
			}else if($(element).hasClass("select2")){
				if($(element).hasClass("slct")){
					$(element).closest(".form-group").children(":nth-last-child(2)").children("span.help-block").text($(error)[0].innerHTML);
				}else{
					$(element).closest("div.input-group").next().text($(error)[0].innerHTML)
				}
			}else if($(element).hasClass("attachment")){
				$(element).closest(".form-group").children(":last-child").find("span.help-block").text($(error)[0].innerHTML);
			}else{
				$(element).next().text($(error)[0].innerHTML);			
			}
		},
		highlight: function ( element, errorClass, validClass ) {
			if($(element).hasClass("datepicker")){
				$(element).parent("div").next().text('');
			}else if($(element).hasClass("select2")){
				if($(element).hasClass("slct")){
					// $(element).closest(".form-group").children(":last-child").find("span.help-block").text('');
					$(element).closest(".form-group").children(":nth-last-child(2)").children("span.help-block").text('');
				}else{
					$(element).closest("div.input-group").next().text('')
				}
			}else if($(element).hasClass("attachment")){
				$(element).closest(".form-group").children(":last-child").find("span.help-block").text('');
			}else{
				$( element ).next().text('');
			}

		},
		unhighlight: function (element, errorClass, validClass) {
			if($(element).hasClass("datepicker")){
				$(element).parent("div").next().text('');
			}else if($(element).hasClass("select2")){
				if($(element).hasClass("slct")){
					$(element).closest(".form-group").children(":nth-last-child(2)").children("span.help-block").text('');
				}else{
					$(element).closest("div.input-group").next().text('')
				}
			}else if($(element).hasClass("attachment")){
				$(element).closest(".form-group").children(":last-child").find("span.help-block").text('');
			}else{
				$( element ).next().text('');
			}
		},
		submitHandler: function(form) {
			// return false;

			form.submit();
		}
	});

	// Change cursor pointer of search link, while editing order.
	if(typeof $("#client_search").attr("disabled") != 'undefined'){		
		$("#client_search").css("cursor","not-allowed");
	}

});


</script>