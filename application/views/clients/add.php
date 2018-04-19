<style>
.help-block{
	color: #dd4b39;;
}
.custom_hide{
	margin-left: -107px;
	margin-right: 77px;
}
</style>
<?php
// var_dump(validation_errors());

$editing_client	=	(isset($editing_client))?$editing_client:'';

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
<style>
.upload_list{
	/*list-style:none;*/
	list-style-position: inline;
	list-style-type: none;
    margin: 0;
    padding: 0;
	border:1px solid #d2d6de;
	padding-top:12px;
	padding-bottom:12px;
	font-size:10px;
}

.upload_list li{
	padding-left:20px;
	/*margin-left:-40px;*/
}

.upload_list a{
	text-decoration: none;
	color: black;
}

.upload_list li:hover{
	background-color: #605ca845;
	/*border:1px solid black;*/
	font-size:18px;	
	cursor:pointer;	
	text-decoration: underline;
	border:1px solid #605ca8b0;

}
.upload_list i{
	position: absolute;
    left: 300px;
    padding-top: 3px;
    color:red;
}
</style>
<div class="content-wrapper">
<!-- Main content -->
<section class="content">
	<?php echo $flashdata; ?>
	<form class="form-horizontal validateForm" method="post" action="<?php echo base_url('index.php/clients/add/');echo ($editing_client)?$editing_client['client_id']:''; ?>" enctype="multipart/form-data" id="validateForm">
		<div class="box box-default box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Add Client</h3>
			</div>
			<div class="box-body">			
				<div class="row" style="margin-bottom: 15px;">
					<div class="col-sm-12 col-md-6">
						<div class="form-group <?php echo (form_error('first_name'))?'has-error':''; ?>">
							<label for="first_name" class="col-sm-2 col-md-2 control-label">First Name<span style="color:red;">*</span></label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo set_value('first_name',($editing_client)?$editing_client['first_name']:'');?>">
							  <span class="help-block"><?php echo form_error('first_name'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('last_name'))?'has-error':''; ?>">
							<label for="last_name" class="col-sm-2 col-md-2 control-label">Last Name</label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo set_value('last_name',($editing_client)?$editing_client['last_name']:'');?>">
							  <span class="help-block"><?php echo form_error('last_name'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('email'))?'has-error':''; ?>">
							<label for="email" class="col-sm-2 col-md-2 control-label">Email <span style="color:red;">*</span></label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo set_value('email',($editing_client)?$editing_client['email']:'');?>">
							  <span class="help-block"><?php echo form_error('email'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('contact'))?'has-error':''; ?>">
							<label for="contact" class="col-sm-2 col-md-2 control-label">Contact</label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control numberonly" id="contact" name="contact" placeholder="Contact" maxlength="10" value="<?php echo set_value('contact',($editing_client)?$editing_client['contact']:'');?>">
							  <span class="help-block"><?php echo form_error('contact'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('dob'))?'has-error':''; ?>">
							<label for="dob" class="col-sm-2 col-md-2 control-label">DOB <span style="color:red;">*</span></label>
							<div class="col-sm-10 col-md-8">
								<div class="input-group date">
								  <span class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </span>
								  
								  <input type="text" class="form-control datepicker pull-right" id="dob" name="dob" readonly="readonly" value="<?php echo set_value('dob',($editing_client)?$editing_client['dob']:'');?>">								  
								</div>
								<span class="help-block"><?php echo form_error('dob'); ?></span>
							</div>
							<!-- /.input group -->
						</div>								
						<div class="form-group <?php echo (form_error('gender'))?'has-error':''; ?>">
							<label for="gender" class="col-sm-2 col-md-2 control-label">Gender <span style="color:red;">*</span></label>
							<div class="col-sm-10 col-md-8">
								<select name="gender" id= "gender" class="form-control select2" data-placeholder="Select Gender" style="width: 100%;">

									<?php $gender = set_value('gender',($editing_client)?$editing_client['gender']:''); ?>									
									<option value="" <?php echo ($gender)?'':"selected=\"selected\"" ?>>Select Gender</option>
									<option value="<?php echo MALE; ?>" <?php echo ($gender==MALE)?"selected=\"selected\"":"" ?>>Male</option>
									<option value="<?php echo FEMALE; ?>" <?php echo ($gender==FEMALE)?"selected=\"selected\"":"" ?>>Female</option>

								</select>
								<span class="help-block"><?php echo form_error('gender'); ?></span>
							</div>
						 </div>							
					</div>
					<div class="col-sm-12 col-md-6">
						<div class="form-group <?php echo (form_error('street1'))?'has-error':''; ?>">
							<label for="street1" class="col-sm-2 col-md-2 control-label">Street 1 <span style="color:red;">*</span></label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control" id="street1" name="street1" placeholder="Street 1" value="<?php echo set_value('street1',($editing_client)?$editing_client['street1']:'');?>">
							  <span class="help-block"><?php echo form_error('street1'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('street2'))?'has-error':''; ?>">
							<label for="street2" class="col-sm-2 col-md-2 control-label">Street 2</label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control" id="street2" name="street2" placeholder="Street 2" value="<?php echo set_value('street2',($editing_client)?$editing_client['street2']:'');?>">
							  <span class="help-block"><?php echo form_error('street2'); ?></span>
							</div>
						</div>
						<div class="form-group <?php echo (form_error('country_id'))?'has-error':''; ?>">
							<label for="country_id" class="col-sm-2 col-md-2 control-label">Country <span style="color:red;">*</span></label>
							<div class="col-sm-10 col-md-8">
								<select name="country_id" id= "country_id" class="form-control country select2" data-placeholder="Select Country" style="width: 100%;" >									
									<?php
									
										$country_id = set_value('country_id',($editing_client)?$editing_client['country_id']:'');
										$selected = ($country_id)?"":"selected=\"selected\" ";
										echo "<option value=\"\" $selected>Select Country</option>";
										
										foreach($countries as $country=>$name){
											$selected = ($country_id == $country)?"selected=\"selected\" ":"";
											echo "<option value=\"$country\" $selected>$name</option>";
										}

									?>

								</select>
								<span class="help-block"><?php echo form_error('country_id'); ?></span>
							</div>
						 </div>
						<div class="form-group <?php echo (form_error('state_id'))?'has-error':''; ?>">
							<label for="state_id" class="col-sm-2 col-md-2 control-label">State <span style="color:red;">*</span></label>
							
							<div class="col-sm-10 col-md-8">								
								<select name="state_id" id= "state_id" class="form-control state select2" data-placeholder="Select State" style="width: 100%;">
									<?php
										$state_id = set_value('state_id',($editing_client)?$editing_client['state_id']:'');

										$selected = ($state_id)?"":"selected=\"selected\" ";
										echo "<option value=\"\" $selected>Select State</option>";
										
										if(isset($states)){
											foreach($states as $state=>$name){
												$selected = ($state_id == $state)?"selected=\"selected\" ":"";
												echo "<option value=\"$state\" $selected>$name</option>";
											}
										}
									?>
								</select>
								<span class="help-block"><?php echo form_error('state_id'); ?></span>
							</div>
						 </div>
						 <div class="form-group <?php echo (form_error('city_id'))?'has-error':''; ?>">
							<label for="city_id" class="col-sm-2 col-md-2 control-label">City <span style="color:red;">*</span></label>
							<div class="col-sm-10 col-md-8">
								<select name="city_id" id= "city_id" class="form-control city select2" data-placeholder="Select City" style="width: 100%;" >									
									<?php
										$city_id = set_value('city_id',($editing_client)?$editing_client['city_id']:'');

										$selected = ($city_id)?"":"selected=\"selected\" ";
										echo "<option value=\"\" $selected>Select City</option>";
										
										if(isset($cities)){
											foreach($cities as $city=>$name){
												$selected = ($city_id == $city)?"selected=\"selected\" ":"";
												echo "<option value=\"$city\" $selected>$name</option>";
											}
										}
									?>
								</select>
								<span class="help-block"><?php echo form_error('city_id'); ?></span>
							</div>
						 </div>						 
						 <div class="form-group <?php echo (form_error('zip'))?'has-error':''; ?>">
							<label for="zip" class="col-sm-2 col-md-2 control-label">ZIP Code <span style="color:red;">*</span></label>

							<div class="col-sm-10 col-md-8">
							  <input type="text" class="form-control numberonly" id="zip" name="zip" placeholder="ZIP Code" maxlength="10" value="<?php echo set_value('zip',($editing_client)?$editing_client['zip']:'');?>">
							  <span class="help-block"><?php echo form_error('zip'); ?></span>
							</div>
						</div>							
						<div class="form-group <?php echo (form_error('status'))?'has-error':''; ?>">
							<label for="status" class="col-sm-2 col-md-2 control-label">Status <span style="color:red;">*</span></label>
							<div class="col-sm-10 col-md-8">
								<select name="status" id= "status" class="form-control select2" data-placeholder="Select Status" style="width: 100%;" >
									<?php $status = set_value('status',($editing_client)?$editing_client['status']:''); ?>									
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
				<input type="hidden" value="0" name="place_order_checkbox" id="place_order_checkbox" />
				<div class="pull-right">
					<?php if($editing_client){
						echo "<button type=\"submit\" class=\"btn btn-primary\">Update</button>
						<input type=\"button\" class=\"btn btn-default\" name=\"go_back\" value=\"Go Back\"  onclick=\"location.href='".base_url("index.php/clients/view_list")."';\"/>";
					}else{						
						echo "<label  style=\"margin-right: 25px;\"><input type=\"checkbox\" id=\"place_order_check\" name=\"place_order_check\"> Place Order</label>";
						echo "&nbsp;&nbsp;&nbsp;";
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
var add_row_flag = 0;
var hide_row_flag = 0;


	$(document).ready(function(){
		$('#attachment').on('change',function(e){
			// console.log($(e.target.files)[0]);

		});
	});

	<?php
		$original_email_url = base_url('index.php/ajax/email_unique/');;
		if($editing_client){
			if(! is_null($editing_client['email'])){
				$original_email_url .= urlencode($editing_client['email']);
			}
		}

		$original_contact_url = base_url('index.php/ajax/contact_unique/');;
		if($editing_client){
			if(! is_null($editing_client['contact'])){
				$original_contact_url .= urlencode($editing_client['contact']);
			}
		}
		
	?>

	var rules		=	{
							first_name	:	{
												required		:	true,
												maxlength		:	'50',
												alphaSpaceCI	: 	"First Name"
											},
							last_name	:	{
												maxlength		:	'50',
												alphaSpaceCI	: 	"Last Name"
											},
							email		:	{
												required		: 	true,
												email			: 	true,
												remote			:	{													
																		url 	: 	"<?php echo $original_email_url; ?>",
																		type 	: 	"post",
																		// data 	: 	{
																		// 			sendVal 	: function(){
																		// 					return $('#email').val();
																		// 				}
																		// 			}
																	}
											},
							contact		:	{
												maxlength		: 	'10',
												remote			:	{
																		url 	: 	"<?php echo $original_contact_url; ?>",
																		type 	: 	"post",
																		// data 	: 	{
																					// sendVal 	: function(){
																					// 		return $('#contact').val();
																					// 	}
																					// }
																	}
											},
							dob 		: 	"required",
							gender		:	"required",							
							country_id	:	"required",
							state_id	:	"required",
							city_id		:	"required",
							street1		:	{
												required		: 	true,
												address			: 	'Street 1'
											},
							street2		:	{
												address			: 	'Street 2'
											},
							zip			:	{
												digits			: 	true,
												maxlength		:	10,
												required		:	true
											},
							status		:	"required",
						};

	var messages	=	{
							// first_name		: 	{
							// 						required	: 	'Please enter First Name',
							// 						maxlength	: 	'Max 50 characters allowed'
							// 					},
							email		:	{
												remote			:	"Email already Exists"
											},
							contact		:	{
												remote			:	"Contact already Exists"
											}
						};
</script>