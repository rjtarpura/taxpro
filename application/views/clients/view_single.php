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
							<h3 class="box-title">Client Profile</h3>
						</div>
						<div class="box-body">
							<!-- <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url('assets/user4-128x128.jpg');?>" alt="User profile picture" style="width:200px;"> -->
								<h3 class="profile-username text-center">								
									<?php 
										echo ($client_single['gender']==MALE)?"Mr. ":"Ms. ";
										echo $client_single['first_name']." ".$client_single['last_name'];
									?>									
								</h3>
              				<p class="text-muted text-center">Member Since <?php echo date('d M, Y',strtotime($client_single['create_date'])); ?>
              					
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
									<?php echo date('d M, Y',strtotime($client_single['dob'])); ?>
								</div>
							</div>							
							<div class="row strip2">
								<div class="col-xs-12 col-sm-6">
									Status
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php echo ($client_single['status']==ACTIVE)?"Active":"Inactive"; ?>
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
									<?php echo $client_single['email'] ?>
								</div>				
							</div>
							<div class="row strip2">
								<div class="col-xs-12 col-sm-6">
									Contact
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php echo $client_single['contact'] ?>
								</div>				
							</div>
							<div class="row strip1">
								<div class="col-xs-12 col-sm-6">
									Address
								</div>
								<div class="col-xs-12 col-sm-6">
									<?php
										if($client_single['street1']){
											echo $client_single['street1'].","."<br/>";
										}
										if($client_single['street2']){
											echo $client_single['street2'].","."<br/>";
										}
										echo $client_single['city_name'].", ".$client_single['state_name'],"<br/>";
										echo $client_single['country_name']. " - ". $client_single['zip'];
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
