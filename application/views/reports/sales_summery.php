<div class="content-wrapper">
<!-- Content Header (Page header) -->


<!-- Main content -->
<section class="content">	
	<div class="box-group" id="accordion" >
        <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
        <div class="panel box box-default box-solid cs_report_title">
          <div class="box-header with-border">
            <h4 class="box-title">
              Sales Summery 
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
						<div class="col-sm-4 col-md-6">
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
					</div>
				<!-- </form> -->
            </div>
          </div>
        </div>
    </div>
	<div class="box box-default box-solid cs_report_body">		
			<div class="box-body">			
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<table class="table" id="sales_summery"  data-order="[[ 3, &quot;desc&quot; ]]">
							<thead>
								<tr>
									<th  data-searchable="false" data-orderable="false" data-sortable="false">#</th>
									<th>Sales Person</th>
									<th>Product</th>									
									<th>Product Sale</th>
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

	$(document).ready(function(){

		// Get referenct to required elements
			from_date = $("#from_date");
			to_date = $("#to_date");
			from_date_help = $('#from_date_help');
			to_date_help = $('#to_date_help');
			to_date_help_2 = $('#to_date_help_2');

		// Data table generating and getting data from ajax source
		var counter=0;
			var sales_summery_tbl = $('#sales_summery').DataTable({

				"processing": true,
	        	"serverSide": true,
	        	"searchDelay": 800,
	        	// "stateSave" : true,	        	
				ajax : {
						"url" : "<?php echo base_url('index.php/ajax/sales_summery_ajax'); ?>",
						"type" : "post",
						"data" : function ( d ) {console.log(counter++);
							      return $.extend( {}, d, {
							        "date_filter": {							        	
							        	"from_date" : from_date.val(),
							        	"to_date" 	: to_date.val()	
							        }
							      } );
							    }
					},
				columns :	[			
								{"data" : "sr"},
								{ "data": "sales_person",
									"render":{
										"display":function(sales_person,x,data){											
											return "<a class=\"link\" href=\""+base_url+"users/view_single/"+data['sales_person_id']+"\">"+sales_person+"</a>";	
										}
									}
								},
								{ "data": "product" },
								{ "data": "product_sale" }
							]			
			});

		// Supress to Show error when from_date or to_date changes
			$("#from_date,#to_date").on('change',function(e){		
				validate();
			});

		// Process this code block when 'Filter' button clicked.
			$('#dt_filter_btn').on('click',function(){
				if(!validate()){
					sales_summery_tbl.draw();
				}
			});

		// Reset all searching elements when "Reset" button clicked.
			$('#dt_reset_btn').on('click',function(){
				
				from_date.val('');		
				to_date.val('');
				from_date_help.hide();
				to_date_help.hide();
				to_date_help_2.hide();

				sales_summery_tbl.search( '' ).columns().search( '' ).draw();

			});
			// sales_summery_tbl.search( '' ).columns().search( '' ).draw();
	});

	// Validation Function
		function validate(){

			// Hide all errors initially.
			from_date_help.hide();
			to_date_help.hide();
			to_date_help_2.hide();

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