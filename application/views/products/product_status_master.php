 <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); ?>">
<style>
	#my_div{
		border: 1px solid #dddddd;
		padding: 10px;
	}
	#my_div div.form-group{
		margin-bottom:0px;
	}
	.form-group label.my_label{
		text-align: left;
	}
</style>

 <?php
 $editing_product	=	(isset($editing_product))?$editing_product:'';
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
	<form class="form-horizontal" action="<?php echo base_url('index.php/products/product_processing_status_message/'); ?>" method="post" id="order_status_master" name="order_status_master">
		<div class="box box-default box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Status Master</h3>			
			</div>		
			<div class="box-body">
				<div class="row">
					<div class="col-md-9">
	    				<textarea id="text_template" class="textarea" name="desc" placeholder="Place some text here" style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
	    			</div>
			        <div class="col-md-3"><div id="my_div">
						<div class="form-group <?php echo (form_error('product_id'))?'has-error':''; ?>" style="display:none">
							<label for="product_id" class="col-sm-2 col-md-4 control-label my_label">Product</label>
							<div class="col-sm-10 col-md-8">
								<select name="product_id" id= "product_id" class="form-control select2" data-placeholder="Select Order" style="width: 100%;">
									<?php
									
										// $product_id = set_value('product_id',($editing_product)?$editing_product['product_id']:'');
										// $selected = ($product_id)?"":"selected=\"selected\" ";
										echo "<option value=\"\" $selected>Select Order</option>";
										
										foreach($products as $key=>$array){
											// $selected = ($product_id == $array['product_id'])?"selected=\"selected\" ":"";
											echo "<option value=\"{$array['product_id']}\">{$array['product_id']} - {$array['name']}</option>";
										}
									?>
								</select>
								<!-- <span class="help-block"><?php echo form_error('product_id'); ?></span> -->
							</div>
						</div>					 						
					
						<div class="form-group <?php echo (form_error('processing_status'))?'has-error':''; ?>">
							<label for="processing_status" class="col-sm-2 col-md-4 control-label my_label">Processing Status</label>
							<div class="col-sm-10 col-md-8">
								<select name="processing_status" id= "processing_status" class="form-control select2" data-placeholder="Processing Status" style="width: 100%;">
									<?php $processing_status = set_value('processing_status',($editing_product)?$editing_product['processing_status']:''); ?>
									<option value="" <?php echo ($processing_status)?'':"selected=\"selected\"" ?>>Processing Status</option>
									<option value="<?php echo PENDING; ?>" <?php echo ($processing_status==PENDING)?"selected=\"selected\"":"" ?>> Pending</option>
									<option value="<?php echo PROCESSING; ?>" <?php echo ($processing_status==PROCESSING)?"selected=\"selected\"":"" ?>> Processing</option>
									<option value="<?php echo DONE; ?>" <?php echo ($processing_status==DONE)?"selected=\"selected\"":"" ?>> Done</option>
								</select>
								<span class="help-block"><?php echo form_error('processing_status'); ?></span>
							</div>
						</div>	

						<div class="form-group <?php echo (form_error('status'))?'has-error':''; ?>" style="display:none;">
							<label for="status" class="col-sm-2 col-md-4 control-label my_label">Status</label>
							<div class="col-sm-10 col-md-8">
								<select name="status" id= "status" class="form-control select2" data-placeholder="Status" style="width: 100%;">
									<!-- <?php $status = set_value('processing_status',($editing_product)?$editing_product['status']:''); ?>
									<option value="" <?php echo ($status)?'':"selected=\"selected\"" ?>>Processing Status</option>
									<option value="<?php echo ACTIVE; ?>" <?php echo ($status==ACTIVE)?"selected=\"selected\"":"" ?>> Active</option>
									<option value="<?php echo INACTIVE; ?>" <?php echo ($status==INACTIVE)?"selected=\"selected\"":"" ?>> Inactive</option>		 -->							
								</select>
								<span class="help-block"><?php echo form_error('status'); ?></span>
							</div>
						</div>						
			        </div></div>		        
	    		</div>
			</div>
			<div class="box-footer">
				<div class="pull-right">
					<?php
						if(false){
							echo "<button type=\"submit\" class=\"btn btn-primary\">Update</button>
							<input type=\"button\" class=\"btn btn-default\" name=\"go_back\" value=\"Go Back\"  onclick=\"location.href='".base_url("index.php/users/view_list")."';\"/>";
						}else{
							echo "<button type=\"submit\" class=\"btn btn-primary\">Submit</button>
							<button type=\"reset\" class=\"btn btn-default\">Cancel</button>";
						}
					?>
				</div>
			</div>
			<div style="background-color: pink; min-height: 200px;display:none;" id="output">
			</div>
		</div>
	</form>
	<?php echo $flashdata; ?>
</section>
<!-- /.content -->
</div>
<?php require_once APPPATH."/views/components/pluginImpots.php" ?>
<!-- Bootstrap WYSIHTML5 -->
<script src="<?php echo base_url('assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js'); ?>"></script>
<script text="text/javascript">	

	// var str = "<!DOCTYPE html>";
	// str += "<html><head><meta charset=\"utf-8\"><meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">";
	// str += "<meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">";
	// str += "<body>";

$(document).ready(function(){

	// var output = $('#output');
	// output.show();
	// https://github.com/jhollingworth/bootstrap-wysihtml5
	// var v = $('#text_template').wysihtml5({
	// 	"html": true,
	// 	"color":true,
	// 	"events" : {
	// 			"change" : function(e){
	// 				var st = str;
	// 				st += $($(this)[0].editableElement).val();
	// 				st += "</body></html>";
	// 				$('#output').html(st);
	// 			},
	// 	}
	// });

	var editor = new wysihtml5.Editor("text_template");
	
	function onChange() { alert("The content of the editor has changed");console.log($(this)) };
function onLoad() { alert("Go!"); };
editor.on("change", onChange);
editor.on("load", onLoad);

	$('#order_status_master').validate();	// Compulsory to work with Select2 valid() metehod in footer.php

	$('#processing_status').on('change',function(){
		// console.log(v.val("rakesh"));
	});
});
</script>