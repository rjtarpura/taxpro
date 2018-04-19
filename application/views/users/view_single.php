<?php 
  $user_image_url   = base_url().'uploads/attachments/photos/no_image.jpg';
  if(isset($user_single)){
    $image      = $user_single['photo'];
    $user_image_path= $_SERVER['DOCUMENT_ROOT'].'/taxpro/uploads/attachments/photos/users/'.$image;
    // var_dump(file_exists($user_image_path));exit;
    if(($image) && (file_exists($user_image_path))){
      $user_image_url = base_url().'uploads/attachments/photos/users/'.$image;
    } 
  }
 ?>

<div class="content-wrapper">
<!-- Main content -->
<section class="content">
	<!--<div class="box box-default box-solid">
		<div class="box-header with-border">
			<h3 class="box-title">User Profile</h3>
		</div>
		<div class="box-body"> -->
			<div class="row">
				<div class="col-xs-12">
					<div class="box box-primary">
						<div class="box-header">
							<h3 class="box-title">User Profile</h3>
						</div>
						<div class="box-body">
							<img class="profile-user-img img-responsive img-circle" src="<?php echo $user_image_url;?>" alt="User profile picture" style="width:200px;">
								<h3 class="profile-username text-center">								
									<?php 
										echo ($user_single['gender']==MALE)?"Mr. ":"Ms. ";
										echo $user_single['first_name']." ".$user_single['last_name'];
									?>									
								</h3>
              				<p class="text-muted text-center">Member Since <?php echo date('d M, Y',strtotime($user_single['create_date'])); ?>
              					
              				</p>
						</div>
					</div>
				</div>	<!-- col end -->		
			</div> <!-- row end -->
			<div class="row">
				<div class="col-xs-12 col-sm-6">
					<div class="box box-primary">
						<div class="box-header">
							<h3 class="box-title">Account Details</h3>
						</div>
						<div class="box-body">
							<div class="row strip1">
								<div class="col-xs-12 col-sm-6">
									Date Of Birth
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php echo date('d M, Y',strtotime($user_single['dob'])); ?>
								</div>
							</div>
							<div class="row strip2">
								<div class="col-xs-12 col-sm-6">
									Username
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php echo $user_single['username'] ?>
								</div>
							</div>
							<div class="row strip1">
								<div class="col-xs-12 col-sm-6">
									Status
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php echo ($user_single['status']==ACTIVE)?"Active":"Inactive"; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6">
					<div class="box box-primary">
						<div class="box-header">
							<h3 class="box-title">Contact Details</h3>
						</div>
						<div class="box-body">
							<div class="row strip1">
								<div class="col-xs-12 col-sm-6">
									Email
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php echo $user_single['email'] ?>
								</div>				
							</div>
							<div class="row strip2">
								<div class="col-xs-12 col-sm-6">
									Contact
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php echo $user_single['contact'] ?>
								</div>				
							</div>
							<div class="row strip1">
								<div class="col-xs-12 col-sm-6">
									Address
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php
										if($user_single['street1']){
											echo $user_single['street1'].","."<br/>";
										}
										if($user_single['street2']){
											echo $user_single['street2'].","."<br/>";
										}
										echo $user_single['city_name'].", ".$user_single['state_name'],"<br/>";
										echo $user_single['country_name']. " - ". $user_single['zip'];
									?>
								</div>				
							</div>
						</div>
					</div>
				</div>
			</div>
		<!--</div>
	</div>-->
</section>
<!-- /.content -->
</div>
<?php require_once APPPATH."/views/components/pluginImpots.php" ?>
