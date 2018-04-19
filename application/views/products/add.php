<?php
// var_dump(validation_errors());

$editing_product	=	(isset($editing_product))?$editing_product:'';

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
}
?>
<div class="content-wrapper">
<!-- Content Header (Page header) -->


<!-- Main content -->
<section class="content">
	<?php echo $flashdata; ?>
	<form class="form-horizontal validateForm" method="post" action="<?php echo base_url('index.php/products/add/');echo ($editing_product)?$editing_product['product_id']:''; ?>" enctype="multipart/form-data" id="validateForm">
		<div class="box box-default box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Add Product</h3>
			</div>		
			<div class="box-body">			
				<div class="row">
					<div class="col-sm-12 col-md-6">
						<div class="form-group <?php echo (form_error('product_identifier'))?'has-error':''; ?>">
							<label for="product_identifier" class="col-sm-2 col-md-2 control-label">Product ID<span style="color:red;">*</span></label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control" id="product_identifier" name="product_identifier" placeholder="Product ID" value="<?php echo set_value('pro',($editing_product)?$editing_product['product_identifier']:'');?>" >
							  <span class="help-block"><?php echo form_error('product_identifier'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('name'))?'has-error':''; ?>">
							<label for="name" class="col-sm-2 col-md-2 control-label">Name<span style="color:red;">*</span></label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control" id="name" name="name" placeholder="Product Name" value="<?php echo set_value('name',($editing_product)?$editing_product['name']:'');?>" >
							  <span class="help-block"><?php echo form_error('name'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('description'))?'has-error':''; ?>">
							<label for="description" class="col-sm-2 col-md-2 control-label">Description</label>

							<div class="col-sm-10 col-md-8">
							  <textarea class="form-control" rows="3" id="description" name="description" placeholder="Product Description"><?php echo set_value('description',($editing_product)?trim($editing_product['description']):'');?></textarea>
							  <span class="help-block"><?php echo form_error('description'); ?></span>
							</div>
						</div>						
						<div class="form-group <?php echo (form_error('price'))?'has-error':''; ?>">
							<label for="price" class="col-sm-2 col-md-2 control-label">Price<span style="color:red;">*</span></label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control numberonly" id="price" name="price" placeholder="Product Price" value="<?php echo set_value('price',($editing_product)?$editing_product['price']:'');?>" >
							  <span class="help-block"><?php echo form_error('price'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('status'))?'has-error':''; ?>">
								<label for="status" class="col-sm-2 col-md-2 control-label">Status<span style="color:red;">*</span></label>
								<div class="col-sm-10 col-md-8">
									<select name="status" id= "status" class="form-control select2" data-placeholder="Select Status" style="width: 100%;" >
										<?php $status = set_value('status',($editing_product)?$editing_product['status']:''); ?>									
										<option value="" <?php echo ($status)?'':"selected=\"selected\"" ?>>Select Status</option>
										<option value="<?php echo ACTIVE; ?>" <?php echo ($status==ACTIVE)?"selected=\"selected\"":"" ?>>Active</option>
										<option value="<?php echo INACTIVE; ?>" <?php echo ($status==INACTIVE)?"selected=\"selected\"":"" ?>>Inactive</option>
									</select>
									<span class="help-block"><?php echo form_error('status'); ?></span>
								</div>
							 </div>
					</div>												
				</div>
			</div>
			<div class="box-footer">
				<div class="pull-right">
					<?php if($editing_product){
						echo "<button type=\"submit\" class=\"btn btn-primary\">Update</button>
						<input type=\"button\" class=\"btn btn-default\" name=\"go_back\"value=\"Go Back\"  onclick=\"location.href='".base_url("index.php/products/view_list")."';\"/>";
					}else{
						echo "<button type=\"submit\" class=\"btn btn-primary\">Submit</button>
						<button type=\"reset\" class=\"btn btn-default reset\">Cancel</button>";
					}
					?>
				</div>
			</div>		
		</div>
	</form>
	
</section>
<!-- /.content -->
</div>
<?php require_once APPPATH."/views/components/pluginImpots.php" ?>

<script type="text/javascript">
<?php
	$original_product_identifier_url = base_url('index.php/ajax/product_unique/');;
	if($editing_product){
		if(! is_null($editing_product['product_identifier'])){
			$original_product_identifier_url .= urlencode($editing_product['product_identifier']);
		}
	}	
?>

var rules		=	{
							product_identifier:	{
												required		: 	true,
												remote			:	{													
																		url 	: 	"<?php echo $original_product_identifier_url; ?>",
																		type 	: 	"post"			
																	}
											},
							name		:	{
												required		:	true,
												maxlength		:	'100',
												alphaSpaceCI	: 	"Product Name"
											},
							description	:	{
												// required		: 	true,
												maxlength		:	'300',
												address			: 	"Product Description"
											},
							price		:	{
												required		: 	true,
												maxlength		:	'6',
												number			: 	"true"
											},
							status 		: 	"required"			
						};

	var messages	=	{							
							product_identifier		:	{
												remote			:	"Product ID already Exists"
											}
						};

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