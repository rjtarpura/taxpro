<div class="content-wrapper">
<!-- Content Header (Page header) -->


<!-- Main content -->
<section class="content">	
	<div class="box-group" id="accordion" >
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-default box-solid cs_report_title" style="">
          <div class="box-header with-border" style="">
            <h4 class="box-title">
              Sales Performance 
              <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" style="margin-left: 10px;" class="">
                <i class="fa fa-angle-down cs_report_fevicon"></i>
              </a>
            </h4>
          </div>

          <div id="collapseOne" class="panel-collapse collapse in">
            <div class="box-body">
            	<!-- <form method="post" action="" class="form-horizontal" style="" id=""> -->
                    <div class="row">
						<div class="col-sm-4 col-md-3">
							<div class="form-group">
								<div class="col-sm-12 col-md-12">
								  <div class="input-group">
									  <span class="input-group-addon">
										<i class="fa fa-calendar"></i> From
									  </span>
									  
									  <input type="text" class="form-control datepicker pull-right" id="from_date" name="from_date" readonly="readonly" value="">
									</div>
									<span class="my_help-block" id="from_date_help">From date is required</span>
								</div>
							</div>									
						</div>	
						<div class="col-sm-4 col-md-3">
							<div class="form-group">
								<div class="col-sm-12 col-md-12">
								  <div class="input-group">
									  <span class="input-group-addon">
										<i class="fa fa-calendar"></i> To
									  </span>
									  
									  <input type="text" class="form-control datepicker pull-right" id="to_date" name="to_date" readonly="readonly" value=""> 
									</div>
									<span class="my_help-block" id="to_date_help">To date is required</span>
									<span class="my_help-block" id="to_date_help_2">'To' date must by grater then or equal to 'From' Date</span>
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-md-3">
							<div class="form-group">
								<div class="col-sm-12 col-md-12">
								  <div class="input-group">
									  <span class="input-group-addon">
										<i class="fa fa-user"></i>
									  </span>											  
									  <select class="form-control select2" id="user_id" name="user_id" multiple="multiple" data-placeholder="Select User">
									  	<?php foreach($users_list as $k=>$arr){
									  	 	echo "<option value=\"{$arr['user_id']}\">{$arr['sales_person']}</option>";
									  	} ?>
									  </select>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-4 col-md-3">
							<div class="form-group">
							<div class="col-sm-12 col-md-12">
								<div class="pull-right">
									<div class="btn-group" role="group" aria-label="Basic example">
									  <button type="button" class="btn btn-warning" id="dt_filter_btn">Filter</button>
									  <button type="button" class="btn btn-secondary" id="dt_reset_btn">Reset</button>
									</div>								
								</div>
							</div>
							</div>
						</div>	
						<!-- <div class="col-sm-4 col-md-3 col-md-offset-9">
							<div class="form-group">
								<div class="col-sm-12 col-md-12">
									<div class="my_table_tools">
						                <ul class="scopes my_table_tools_segmented_control">
						                  <li class="selected">
						                    <a href="" class="table_tools_button">
						All                      <span class="count">(300)</span>
						                    </a>
						                  </li>
						                  <li class="">
						                    <a href="" class="table_tools_button">
						In Progress                      <span class="count">(26)</span>
						                    </a>
						                  </li>						                  
						                </ul>
						              </div>
								</div>
							</div>
						</div> -->
					</div>
				<!-- </form> -->
            </div>
          </div>
        </div>
    </div>
	<div class="box box-default box-solid cs_report_body" style="">		
			<div class="box-body">			
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<table class="table" id="sales_performance"  data-order="[[ 3, &quot;desc&quot; ]]">
							<thead>
								<tr>
									<!-- <th  data-searchable="false" data-orderable="false" data-sortable="false">#</th> -->
									<th>Order #</th>
									<th>Sales Person</th>									
									<th>Client Name</th>
									<th>Order Date</th>
									<th>Processing Status</th>
								</tr>
							</thead>							
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

	// Globally declare Variables to store reference of elements
	var from_date,to_date,from_date_help,to_date_help,to_date_help_2;	
	var user_ids = [];	// Stores user_id selected in ComboBox

	$(document).ready(function(){

		// Get referenct to required elements
			from_date = $("#from_date");
			to_date = $("#to_date");
			from_date_help = $('#from_date_help');
			to_date_help = $('#to_date_help');
			to_date_help_2 = $('#to_date_help_2');

		// Data table generating and getting data from ajax source
			var sales_performance_tbl = $('#sales_performance').DataTable({

				"processing": true,
	        	"serverSide": true,

				ajax : {
						"url" : "<?php echo base_url('index.php/ajax/sales_performance_ajax'); ?>",
						"type" : "post",
						"data" : function ( d ) {
							      return $.extend( {}, d, {
							        "date_filter": {
							        	"from_date" : from_date.val(),
							        	"to_date" 	: to_date.val()	
							        },
							        "user_ids": user_ids
							      } );
							    }
					},
				columns :	[
								{ "data": "order_id" },
								{ "data": "sales_person",
										"render":{
											"display":function(sales_person,x,data){
												return "<a class=\"link\" href=\""+base_url+"users/view_single/"+data['sales_person_id']+"\">"+sales_person+"</a>";
											}
										}
								},
								{ "data": "client_name",
									"render":{
										"display":function(client_name,x,data){
											return "<a class=\"link\" href=\""+base_url+"clients/view_single/"+data['client_id']+"\">"+client_name+"</a>";
										}
									}
								},
								{ "data": "order_date" },
								{ "data": "processing_status",
										"render":{
											"display" :function(processing_status){
												var status = '-';										
												switch(parseInt(processing_status)){
													case <?php echo PENDING; ?>:
														status = "Pending";
														break;
													case <?php echo PROCESSING; ?> :
														// status = "Processing";
														status = "<span class=\"user_flag badge bg_my_red\" style=\"min-width: 30px;\">PROCESSING</span>";
														break;
													case <?php echo DONE; ?> :
														// status = "Done";
														status = "<span class=\"user_flag badge bg_my_green\" style=\"min-width: 30px;\">COMPLETE</span>";
														break;
												}
												return status;
											}
										}
								}
							],
						// "initComplete": function(settings, json) {							
						// 	console.log(json);
						//     alert( 'DataTables has finished its initialisation.' );
						//   }
			});

		// Supress to Show error when from_date or to_date changes
			$("#from_date,#to_date").on('change',function(e){		
				validate();
			});

		// Process this code block when 'Filter' button clicked.
			$('#dt_filter_btn').on('click',function(){
				if(!validate()){
					sales_performance_tbl.draw();
				}
			});

		// Reset all searching elements when "Reset" button clicked.
			$('#dt_reset_btn').on('click',function(){
				
				from_date.val('');		
				to_date.val('');
				from_date_help.hide();
				to_date_help.hide();
				to_date_help_2.hide();
				$('#user_id').val('').trigger('change');

				sales_performance_tbl.search( '' ).columns().search( '' ).draw();

			});

		// Change user_ids array, with the values selected in Combo Box, each time of change.	
			$('#user_id').on('change',function(){
				user_ids =$(this).val();
			});
	});

	// Validation Function
		function validate(){

			// Hide all errors initially.
			from_date_help.hide();
			to_date_help.hide();
			to_date_help_2.hide();

			if(from_date.val() == '' && to_date.val() == ''){
				return false;
			}
			
			// Start Validating fields.
			if(from_date.val() == ''){
				from_date_help.show();
				return true;
			}else{
				from_date_help.hide();
			}

			if(to_date.val() == ''){
				to_date_help.show();
				return true;
			}else{
				to_date_help.hide();
			}

			from_moment = moment(from_date.val());
			to_moment = moment(to_date.val());

			if(to_moment.isBefore(from_moment)){
				to_date_help_2.show();
				return true;
			}else{
				to_date_help_2.hide();					
			}

			return false;
		}
</script>