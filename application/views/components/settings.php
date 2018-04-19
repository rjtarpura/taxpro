<style>
	fieldset.scheduler-border {
	    border: 1px groove #ddd !important;
	    padding: 0 1.4em 1.4em 1.4em !important;
	    margin: 0 0 1.5em 0 !important;
	    -webkit-box-shadow:  0px 0px 0px 0px #000;
	            box-shadow:  0px 0px 0px 0px #000;
	}

    legend.scheduler-border {
        font-size: 1.2em !important;
        font-weight: bold !important;
        text-align: left !important;
        width:auto;
        padding:0 10px;
        border-bottom:none;
    }
</style>
<!-- bootstrap slider -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap-slider/slider.css');?>">
<!-- bootstrap toggle -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap-toggle/bootstrap-toggle.css v2.2.0.css');?>">

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
    <!-- <section class="content-header">
      <h1>
        Settings
      </h1>
    </section> -->

    <!-- Main content -->
    <section class="content">
      <?php echo $flashdata; ?>
    	<div class="box box-default box-solid">
	      	<form class="form-horizontal" method="post" action="<?php echo base_url('index.php/settings/update');?>">
				<div class="box-header with-border">
					<h3 class="box-title">Settings</h3>				
				</div>			
				<div class="box-body">			
					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Contact Details</legend>
						<div class="row">
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="email">Email :</label>
							        <div class="col-sm-10 col-md-8">
									  <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $_settings['email']; ?>">
							        </div>
							    </div>						
							</div>
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="contact">Contact :</label>
							        <div class="col-sm-10 col-md-8">
									  <input type="text" class="form-control numberonly" id="contact" name="contact" placeholder="Contact Number"  value="<?php echo $_settings['contact']; ?>">
							        </div>
							    </div>						
							</div>
						</div>
					</fieldset>

					<fieldset class="scheduler-border">
						<legend class="scheduler-border">SMTP Details</legend>
						<div class="row">
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="smtp_email">SMTP Email :</label>
							        <div class="col-sm-10 col-md-8">
									  <input type="text" class="form-control" id="smtp_email" name="smtp_email" placeholder="SMTP Email"  value="<?php echo $_settings['smtp_email']; ?>">
							        </div>
							    </div>						
							</div>
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="smtp_pass">Password :</label>
							        <div class="col-sm-10 col-md-8">
									  <input type="text" class="form-control" id="smtp_pass" name="smtp_pass" placeholder="SMTP Password" value="<?php echo $_settings['smtp_pass']; ?>">
							        </div>
							    </div>						
							</div>
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="email_alias">Email Alias :</label>
							        <div class="col-sm-10 col-md-8">
									  <input type="text" class="form-control" id="email_alias" name="email_alias" placeholder="Email Alias"  value="<?php echo $_settings['email_alias']; ?>">
							        </div>
							    </div>						
							</div>
						</div>
					</fieldset>

					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Auto Logout (minutes)</legend>
						<div class="row">
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">							        
							        <div class="col-sm-2 col-md-4">
							        	<input type="checkbox" checked data-toggle="toggle" data-size="mini" id="autologout" name="autologout">
							    	</div>
							        <div class="col-sm-10 col-md-8">
									  <input type="text" value="" class="slider form-control"
									  id="autologout_mins" name="autologout_mins" data-slider-min="0" data-slider-max="60"				                         data-slider-step="1" data-slider-value="<?php echo $_settings['autologout_mins']; ?>" 
									  data-slider-orientation="horizontal"
				                      data-slider-selection="before" data-slider-tooltip="show" data-slider-id="red">
							        </div>
							    </div>
							</div>
						</div>
					</fieldset>

					<fieldset class="scheduler-border">
						<legend class="scheduler-border">File Upload</legend>
						<div class="row">
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="allowed_file_extensions">Extensions</label>
							        <div class="col-sm-10 col-md-8">
										<select id="allowed_file_extensions" name="allowed_file_extensions" class="form-control select2" data-placeholder="Select Extensions" multiple="multiple">
										<?php $allowed_file_extensions = explode("|",ALLOWED_PIC_EXTENSIONS);
										  foreach($allowed_file_extensions as $ext){
										  	echo "<option value=\"$ext\">$ext</option>";
										  }
										?>
										</select>
							        </div>
							    </div>
							</div>
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="file_upload_size_bytes">Size</label>
							        <div class="col-sm-10 col-md-8">
									  <input type="text" value="" class="slider form-control"
									  id="file_upload_size_bytes" name="file_upload_size_bytes" data-slider-min="0" data-slider-max="60"
				                         data-slider-step="1" data-slider-value="<?php echo $_settings['file_upload_size_bytes']; ?>" data-slider-orientation="horizontal"
				                         data-slider-selection="before" data-slider-tooltip="show" data-slider-id="blue">
							        </div>
							    </div>
							</div>
						</div>
					</fieldset>

					<fieldset class="scheduler-border">
						<legend class="scheduler-border">Profile Pic</legend>
						<div class="row">
							<div class="col-sm-12 col-md-4">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="profile_pic_extensions">Extensions</label>
							        <div class="col-sm-10 col-md-8">
										<select id="profile_pic_extensions" name="profile_pic_extensions" class="form-control select2" data-placeholder="Select Extensions" multiple="multiple">
										<?php $profile_pic_extensions = explode("|",PROFILE_PIC_EXTENSIONS);
										  foreach($profile_pic_extensions as $ext){
										  	echo "<option value=\"$ext\">$ext</option>";
										  }
										?>
										</select>
							        </div>
							    </div>
							</div>
							<div class="col-sm-12 col-md-6">
	    						 <div class="from-group">
							        <label class="col-sm-2 col-md-4 control-label" for="profile_pic_size_bytes">Size (kb)</label>
							        <div class="col-sm-10 col-md-8">
									  <input type="text" value="" class="slider form-control"
									  id="profile_pic_size_bytes" name="profile_pic_size_bytes" data-slider-min="0" data-slider-max="2000"
				                         data-slider-step="1" data-slider-value="<?php echo $_settings['profile_pic_size_bytes']/1000; ?>" data-slider-orientation="horizontal"
				                         data-slider-selection="before" data-slider-tooltip="show" data-slider-id="green">
							        </div>
							    </div>
							</div>
						</div>
					</fieldset>			
				</div>
			
				<div class="box-footer">
					<div class="pull-right">
						<input type="submit" class="btn btn-primary" value="Update">
					</div>
				</div>
			</form>
		</div>
      <!-- /.row -->
      
    </section>
    <!-- /.content -->
  </div>

<?php require_once APPPATH."/views/components/pluginImpots.php" ?>
<!-- Bootstrap toggle -->
<script src="<?php echo base_url('assets/plugins/bootstrap-toggle/bootstrap-toggle.js v2.2.0.js'); ?>"></script>
<!-- Bootstrap slider -->
<script src="<?php echo base_url('assets/plugins/bootstrap-slider/bootstrap-slider.js'); ?>"></script>

<script type="text/javascript">
	$(document).ready(function(){
		$('.slider').slider();
	});
</script>