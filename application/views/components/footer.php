<?php
$page_url	=	$this->router->fetch_class()."/".$this->router->fetch_method();
?>

   <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      <!-- Anything you want -->
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="#"><?php echo $website_title; ?></a>.</strong> All rights reserved.
	
  </footer>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- COMMON JS SCRIPTS -->

<script type="text/javascript">

	// Variable Decleration		
		var page_url = "<?php echo $page_url; ?>";
		var idleTime = 0;	//minutes
		var settings = <?php echo $js_variables; ?>;
		var page_identity = null;

		// settings object structure (defined in My_Controller.php)
		// {
		// 	notification_display_duration_ms : 2000,	//miliseconds
		// 	allowed_file_extensions : "png|jpeg|jpg|gif|xls|xlsx|doc|docx|pdf",
		// 	file_upload_size_bytes : 70000,	//bytes
		// 	autologout : false,
		// 	autologout_mins : 1,		// mins
		//	profile_pic_size_bytes : "png|jpg|",
		//	profile_pic_size_bytes : 1000	//bytes
		// };

		// Holds list of selected options in 
		var selected_option_array = [];
		
		// Used while adding rows dynamically to add products in an order.
		var row_first = null;
		var row_second = null;
		var row_counter = 0;	// tracks no of rows selected using checkbox on listing pages.

	$(document).ready(function(){

		$('.my_dropdown_message').on('mouseout',function(){
			
		});

		// Initialize Select2 Elements
			$('.select2').select2({
				// allowClear:true,
				// closeOnSelect:false,
				// debug:true,
			}).on("change",function(){
				if(typeof $(this).rules() != 'undefined'){
					$(this).valid();
				}
			});

		// Initialize Datepicker
			$('.datepicker').datepicker({
				format		:	"yyyy-mm-dd",
				autoclose	:	true,
				todayBtn	:	"linked",
				clearBtn	:	true,
				endDate		: 	moment().format("YYYY-MM-DD"),
				// maxViewMode : 	2
			}).on("change",function(){
				if(typeof $(this).rules() != 'undefined'){
					$(this).valid();
				}
			});
			
		// Initialize Datatable
			var table = $('.datatable').DataTable({
				
			});	

		// Script to hide & show attachment list
			$('.dropbtn').on('click',function(){
				$('div.my_dropdown_content').addClass("hide").removeClass("show");
				$(this).next('div.my_dropdown_content').addClass("show").removeClass("hide");
			});
			$('body').on('click',function(e){		
				if(!$(e.target).hasClass('user_flag')){
					$('div.my_dropdown_content').addClass("hide").removeClass("show");
				}
			})

		// Setup time to auto hide alert notifications show on success or failure of an action.
			setTimeout(function() {
			    $(".alert").fadeTo(500, 0).slideUp(500, function(){
			        $(this).remove(); 
			    });
			}, settings.notification_display_duration_ms);

		// CONFIRM DIALOGUE BOX TO DELETE AND CANCEL class
			$(".delete").on("click",function(){
				
				var message = "Are you sure you want to delete this record.";

				if(typeof $(this).data("info_message_noun") != "undefined"){
					var msg = $(this).data("info_message_noun");
					if(msg.length > 0){
						message = "Are you sure you want to delete this "+msg+" ?";
					}
				}

				var response = confirm(message);
				
				if(response){
					return true; //window.locaion.href=$(this).attr('href');
				}else{
					return false;
				}			
			});

			$(".cancel").on("click",function(){

				var message = "Are you sure you want to cancel this record.";

				if(typeof $(this).data("info_message_noun") != "undefined"){
					var msg = $(this).data("info_message_noun");
					if(msg.length > 0){
						message = "Are you sure you want to cancel this "+msg+" ?";
					}
				}

				var response = confirm(message);
				
				if(response){
					return true; //window.locaion.href=$(this).attr('href');
				}else{
					return false;
				}			
			});

			$(".inactive").on("click",function(){

				var message = "Are you sure you want to inactive this record.";

				if(typeof $(this).data("info_message_noun") != "undefined"){
					var msg = $(this).data("info_message_noun");
					if(msg.length > 0){
						message = "Are you sure you want to inactive this "+msg+" ?";
					}
				}

				var response = confirm(message);
				
				if(response){
					return true; //window.locaion.href=$(this).attr('href');
				}else{
					return false;
				}			
			});

		// Change icon on header of box while show/hide filter options on reports
			$('.cs_report_fevicon').on('click',function(){
				var fevicon = $(this);

				if(!$('#collapseOne').hasClass('in')){
					fevicon.removeClass('fa-angle-right').addClass('fa-angle-down');
				}else{
					fevicon.removeClass('fa-angle-down').addClass('fa-angle-right');
				}
			});
		
		// Show/ Hide Bulk delete/cancel option on listings

			// Check/ Uncheck Child Checkboxes base on the state of parent checkbox
				$("#list thead").on('change','.parent_checkbox',function(e){

					var rows = table.rows({'search':'applied'}).nodes();
					
					$('input[type="checkbox"]', rows).prop('checked', this.checked);

					if($(this).is(':checked')){				
						// table.column(6).visible(false);
						row_counter=rows.length;
						if(row_counter > 0){
							$('#bulk_delete_form').show();
						}
					}else{
						$('#bulk_delete_form').hide();
						// table.column(6).visible(true);
						row_counter=0;
					}			
				});		
		
			// Increase/Decrease row_counter and based on row_counter show/hide bulk operation form.
				$("#list tbody").on('change','.child_checkbox',function(e){

					if($(this).is(':checked')){
						row_counter++;
					}else{
						row_counter--;
					}

					if(row_counter>1){
						$('#bulk_delete_form').show();
						// table.column(6).visible(false);
					}else{
						$('#bulk_delete_form').hide();	
						// table.column(6).visible(true);
					}
				});

				// $("#list td.client_name").on('contextmenu',function(e){
				// 	e.preventDefault();
				// 	alert();
				// });

			// Bulk operation form submit interception.
				$('#bulk_delete_form').on('submit',function(e){
					var msg = $(this).find("#msg");
					msg = (msg.length > 0 && msg.val().length >0)?msg.val():'proceed';				
					msg = msg.replace("{s}",row_counter);
					
					var response = confirm("Are you sure you want to "+msg+" ?");
					if(response){
						var that = $(this);
						 table.$('input[type="checkbox"]').each(function(){
						 	 if(this.checked){
						           // Create a hidden element 
						           $(that).append(
						              $('<input>')
						                 .attr('type', 'hidden')
						                 .attr('name', 'user_id_array[]')
						                 .val(this.value)
						           );
						        }
						 });
					}else{
						e.preventDefault();
					}
				});

				// $('#bulk_inactive_form').on('submit',function(e){
				// 	var msg = $(this).find("#msg");
				// 	msg = (msg.length > 0 && msg.val().length >0)?msg.val():'proceed';				
				// 	msg = msg.replace("{s}",row_counter);
					
				// 	var response = confirm("Are you sure you want to "+msg+" ?");
				// 	if(response){
				// 		var that = $(this);
				// 		 table.$('input[type="checkbox"]').each(function(){
				// 		 	 if(this.checked){
				// 		           // Create a hidden element 
				// 		           $(that).append(
				// 		              $('<input>')
				// 		                 .attr('type', 'hidden')
				// 		                 .attr('name', 'user_id_array[]')
				// 		                 .val(this.value)
				// 		           );
				// 		        }
				// 		 });
				// 	}else{
				// 		e.preventDefault();
				// 	}
				// });


		//Ajax Calls	
		
			// Change Values of State Combo box when Country Changes
				$("#country_id").on("change",function(){
					var value	=	parseInt($(this).val());
					var html	=	"<option value=''>Select State</option>";
					var state	=	$("#state_id");
					var city	=	$("#city_id");
					if(!isNaN(value)){
						$.ajax({
							type	:	"post",
							url		:	base_url+"ajax/get_state",
							data	:	{"country_id"	:	value},		//Other way to pass data - "country_id=value"
							datatype:	"json",
							success	:	function(result){
								$(result).each(function(index,value){
									html	+=	value;
								});					
							},
							error	:	function(xmlhttprequers,textStatus,error){
								console.log(xmlhttprequers.responseText);
							},
							complete:	function(){
								state.html(html);
								city.html("<option value=''>Select City</option>");
							}
						});
					}else{
						state.html(html);
						city.html("<option value=''>Select City</option>");
					}
				});
			
			// Change Values of City Combo box when State Changes
				$("#state_id").on("change",function(){
					var value	=	parseInt($(this).val());
					var html	=	"<option value=''>Select City</option>";
					var city	=	$("#city_id");
					if(!isNaN(value)){
						$.ajax({
							type	:	"post",
							url		:	base_url+"ajax/get_city",
							data	:	{"state_id"	:	value},		//Other way to pass data - "country_id=value"
							datatype:	"json",
							success	:	function(result){
								$(result).each(function(index,value){
									html	+=	value;
								});					
							},
							error	:	function(xmlhttprequers,textStatus,error){
								console.log(xmlhttprequers.responseText);
							},
							complete:	function(){
								city.html(html);
							}
						});
					}else{
						city.html(html);
					}
				});

		// Custom Validation base on class provided to element

			// Capitalize letter entered.
				$('.capitalize').on('keyup', function (e) {
					if (e.which >= 97 && e.which <= 122) {
						var newKey = e.which - 32;
						// I have tried setting those
						e.keyCode = newKey;
						e.charCode = newKey;
					}			
					$(this).val(($(this).val()).toUpperCase());
				});

			// Allows only numbers to be entered
				$('body').on('keypress', ".numberonly", function (key) {		
					if(key.charCode < 48 || key.charCode > 57) return false;			
							//* 42, + 43, - 45, . 46, / 47
							//[0-9] key code [48-57]
							//@64, #35, 
							//[A-Z] key code [65-90]		
							//[a-z] key code [97-122]
				});

			// Disable key press.
				$('.disablekey').on('keypress', function (key) {
					return false;
				});

		// Form Validation - Add Custom Rules

			// Reset Form Validation
				$('.reset').on('click',function(e){
					$(this).closest('form').validate().resetForm();
				});
			// Add Custom Validatoin Methods

				// Only allow alphabat and space
					$.validator.addMethod('alphaSpaceCI',function(value,element,params){			
						return this.optional(element) || /^[a-zA-Z][a-zA-Z ]*$/.test(value);
					},'Please enter valid {0}');

				// Only allow first character as alphnumeric after that allows alphanumeric, space -
					$.validator.addMethod('address',function(value,element,params){	
						return this.optional(element) || /^[a-zA-Z0-9][a-zA-Z0-9 \\-]*$/.test(value);
					},'Please enter valid {0}');

				// Makes product and quantity required
					$.validator.addMethod('my_required_rule',function(value,element,params){			
						return $(element).val().length != 0 && /^[1-9][0-9]*$/.test(parseInt($(element).parent().next().children().val()));
					},'Product and Quantity both are required.');

				// Validates uploading file size not to exceed specific file size in bytes.
					$.validator.addMethod('filesize',function(value,element,params){	
						var validation_status = true;
						$($(element)[0].files).each(function(i,e){
							if(e.size > settings.file_upload_size_bytes){
								validation_status = false;
							}
						});
						return this.optional(element) || validation_status;
					},'One of the attached file exceeds allowed file size (' +settings.file_upload_size_bytes/1000+' kb)');

			// Validate Form - Do Validation
				if(typeof rules != 'undefined'){	// Validate form only if rules is declared.

					message = (typeof messages == 'undefined')?'':messages;

					v =$('.validateForm').validate({
						
						// debug		:	true,

						rules		: 	rules,
						messages	:	messages,

						errorElement: "span",

						errorPlacement: function ( error, element ) {
							
							if($(element).hasClass("datepicker")){
								$(element).parent("div").next().text($(error)[0].innerHTML);
							}else if($(element).hasClass("select2")){
								if($(element).hasClass("slct")){
									console.log($(element).closest(".form-group").children(":nth-last-child(2)").children("span.help-block"));
									$(element).closest(".form-group").children(":nth-last-child(2)").children("span.help-block").text($(error)[0].innerHTML);
								}else{
									$(element).next().next().text($(error)[0].innerHTML);
								}
							}else if($(element).hasClass("attachment")){
								$(element).closest(".form-group").children(":last-child").find("span.help-block").text($(error)[0].innerHTML);
							}else{
								$(element).next().text($(error)[0].innerHTML);						
							}
						},

						highlight: function ( element, errorClass, validClass ) {
							$( element ).parents( ".form-group" ).addClass( "has-error" );
							if($(element).hasClass("datepicker")){
								$( element ).parent("div").next().text('');
							}else if($(element).hasClass("select2")){
								if($(element).hasClass("slct")){
									$(element).closest(".form-group").children(":nth-last-child(2)").children("span.help-block").text('');
								}else{
									$(element).next().next().text('');
								}
							}else if($(element).hasClass("attachment")){
								$(element).closest(".form-group").children(":last-child").find("span.help-block").text('');
							}else{
								$( element ).next().text('');
							}

						},

						unhighlight: function (element, errorClass, validClass) {
							$( element ).parents( ".form-group" ).removeClass( "has-error" );
							if($(element).hasClass("datepicker")){
								$( element ).parent("div").next().text('');
							}else if($(element).hasClass("select2")){
								if($(element).hasClass("slct")){
									$(element).closest(".form-group").children(":nth-last-child(2)").children("span.help-block").text('');
								}else{
									$(element).next().next().text('');
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
				}
		// AUTO LOGOUT (if user inactive for specific time, miliseconds)
			if(settings.autologout){
				page_identity = $.now();		
				$(window).on('focus', function () {
					// console.log('onFocus',page_identity);
					localStorage.setItem('active_tab', page_identity);
					localStorage.setItem('idleTime', 0);
				});
				$(window).on('load', function () {
					// console.log('onLoad',page_identity);
					localStorage.setItem('active_tab', page_identity);
					localStorage.setItem('idleTime', 0);
					localStorage.setItem('logout_done',false);
					// console.log(typeof localStorage.getItem('logout_done'),localStorage.getItem('logout_done'));
					
					var tab_count = localStorage.getItem('tab_count');
					
					if(tab_count){
						localStorage.setItem('tab_count', Number(tab_count)+1);						
					}else{
						localStorage.setItem('tab_count', 1);
					}		
					// console.log("total tabs",localStorage.getItem('tab_count'));
				});
				
				var idleInterval = setInterval("logout()",5000);	//miliseconds 1 minute = 60 sec = 60*1000  miliseconds
				
				$(this).on('mousemove keypress',function(){
					// idleTime = 0;
					if(localStorage.getItem('active_tab') == page_identity) {
						localStorage.setItem('idleTime', 0);
					}					
				});
			}			

		// Data table in model to select Client while placing order
			$('#client_search').on("click",function(e){
				if(typeof $(this).attr("disabled") == 'undefined'){
	    			$('#client_modal').modal({backdrop: 'static', keyboard: false});
	    		}
	    	});

			// Assign selected client's id to Client Combo box
	    	$('#client_table tbody').on( 'click', 'tr', function () {

		    		$('#client_id').val($(this).data('client_id')).trigger('change');
		    		
		    		$('#client_modal').modal("hide");   

		    	}).on('mouseover','tr',function(){	// change row color on hover in modal    	

			    	// if ( $(this).hasClass('selected') ) {
			     //        $(this).removeClass('selected');
			     //    }
			     //    else {
			     //        table.$('tr.selected').removeClass('selected');
			     //        $(this).addClass('selected');
			     //    }
		    });

		// Validate data in product row
			$("body").on("change",".attachment",function(){
	    		if(typeof $(this).rules() != 'undefined'){
	    			$(this).valid();
	    		}
	    	});

			$('body').on('blur','.quantity',function(){
				$(this).parent().prev().children(".slct").valid();
			});

		// Add / Remove product rows (Product rows are added and removed dynamically)

			// Add
		    	$('body').on('click','#btnOrderProdAdd',function(e){
			    	add_row();
			    });

	    	// Remove
		    	$('body').on('click','.close_this',function(e){

					var previous_option_value = $(this).closest(".form-group").children(":first-child").val();			

					$(e.target).closest("div.row").remove();

					if(previous_option_value){
						selected_option_array.splice( $.inArray(previous_option_value,selected_option_array), 1 );
						$(".slct option[value='"+previous_option_value+"'] ").prop("disabled",false);
					}
					$(".slct").select2();
			    });

		// Hide first row added dynamically on check/uncheck of checkbox	    
	    	$('#place_order_check').on('change',function(e){
	    		var v = $(this);
	    		hide_show_order_row_while_adding_client(v);
	    	});

    	// Add product row on page load by verifying that the view wants it to be added.

    		//add_row_flag is defined by the view to hind footer to execute this block of code or not.
			    if(typeof add_row_flag != 'undefined'){	

			    	var row = $("#template_row").html();

			    	row = $(row);	    	
			    	
			    	row_first = row.clone();
			    	row_second = row.clone();
			    	row.remove();
			    	
			    	row_second.find("button").removeAttr("id").removeClass("btn-warning").addClass("btn-default").addClass("close_this").find("span").removeClass("fa-plus").addClass("fa-minus");	    	 

			    	if(add_row_flag != 0){	// adds rows while editing order, as hinted by view file.

			    		$.each(add_row_flag,function(index,val){
			    			selected_option_array.push(val.product_id);
			    			add_row_while_editing(val.product_id,val.quantity);	  
			    		});
			    		
			    		var selected_option_array_except_current = [];

			    		$('.slct').each(function(i,e){

			    			var current_value = $(this).val();
			    			$(this).closest(".form-group").children(":first-child").val(current_value);

			    			selected_option_array_except_current = jQuery.grep(selected_option_array, function(n, i){
							  return (n != current_value);
							});

			    			$.each($(this).find("option"),function(index,va){

			    				if($.inArray($(this).val(),selected_option_array_except_current) != -1){
			    					$(this).prop("disabled",true);
			    				}// No neet fore else condition because these rows are automatically added while editing, so all rows are by default enabled.
			    			});
			    		});	

			    		$('.slct').select2();

			    	}else{
			    		add_row();
			    	}	    	
		    	}

	    	// Confirms that user can't select same product in multiple selects.
		    	$('body').on('change','.slct',function(){
					
					var id = $(this).attr('id');

					var selected_option = $(this).val();

					selected_option_array.push(selected_option);
					
					var hidden_input = $(this).closest(".form-group").children(":first-child");
					var previous_option_value = hidden_input.val();
								
					if(previous_option_value != ''){
						selected_option_array.splice( $.inArray(previous_option_value,selected_option_array), 1 );
					}

					$(".slct:not(#"+id+")").each(function(i,e){

		    			var current_value = $(this).val();

		    			selected_option_array_except_current = jQuery.grep(selected_option_array, function(n, i){
						  return (n != current_value);
						});

		    			$.each($(this).find("option"),function(index,va){

		    				if($.inArray($(this).val(),selected_option_array_except_current) != -1){	
		    					$(this).prop("disabled",true);
		    				}else{
		    					$(this).prop("disabled",false);	    					
		    				}
		    			});

		    			$(this).select2();
		    		});	 
					
					hidden_input.val(selected_option);
					
				});

		// Hides first product row added if client is editing.
	    	if(typeof hide_row_flag != 'undefined'){

	    		var custom_hide = $('.custom_hide');

	    		if(hide_row_flag == 0){
	    			custom_hide.hide().find(".form-control.slct").rules("remove","my_required_rule");
	    			custom_hide.hide().find('.attachment').rules("remove","extension");
	    			custom_hide.hide().find('.attachment').rules("remove","filesize");
	    			$('#place_order_checkbox').val(0);
	    		}
	    	}

	    // Sent email to clients

	    	$('.send_email').on('click',function(e){
		
				e.preventDefault();

				var uri = $(this).attr('href');
				var id = $(this).data('id');
				var message_type = $(this).data('message_type');
				var master_name = $(this).data('master_name');

				$.ajax({
					type	:	"post",
					url		:	uri,
					data	:	{
									"id"	:	id,
									"message_type" : message_type,
									"master_name"  : master_name
								},	

					datatype:	"json",

					success	:	function(result){

						var data = JSON.parse(result);
						
						if(data.status = 'success'){
							toastr["success"](data.message);
						}else{
							toastr["error"](data.message);
						}
					},
					error	:	function(xmlhttprequers,textStatus,error){
						console.log(xmlhttprequers.responseText);
					},
					complete:	function(){
						
					}
				});
			});

			// $('.my_dropdown_message').on('mouseleave',function(){
			// 	$(this).closest('.my_dropdown_group').removeClass('open');
			// });

	// AUTO LOCK
	// $('#lock_screen_form').on('submit',function(e){
	// 	e.preventDefault();

	// 	if($('#lock_password').val()){
	// 		$.ajax({
	// 			type	:	"post",
	// 			url		:	$(this).attr("action"),
	// 			data	:	{
	// 						"password" : $('#lock_password').val()
	// 						},
	// 			datatype:	"json",
	// 			success	:	function(result){
	// 				$('#lock_screen').hide();
	// 			},
	// 			error	:	function(xmlhttprequers,textStatus,error){
	// 				console.log(xmlhttprequers.responseText);
	// 				console.log(textStatus);
	// 				console.log(error);
	// 				$('#lock_screen_error').show();
	// 			},
	// 			complete:	function(){

	// 			}
	// 		});
	// 	}
	// });	

});// End of Documen.Ready

//FUNCTIONS	
	
	function logout(){
		//if autologout done redirect
		if(localStorage.getItem('logout_done')=='true'){
			
			var tab_count = Number(localStorage.getItem('tab_count'));
			
			if(tab_count>1){				
				localStorage.setItem('tab_count',tab_count-1);
				window.location.href = "<?php echo base_url('index.php/login/login') ?>";
			}else{
				localStorage.removeItem('tab_count');
				localStorage.removeItem('active_tab');
				localStorage.removeItem('idleTime');
				localStorage.removeItem('logout_done');
				window.location.href = "<?php echo base_url('index.php/login/login') ?>";
			}
			return;
		}
		
		time = Number(localStorage.getItem('idleTime'));
		
		if(localStorage.getItem('active_tab') == page_identity) {
			// idleTime += 1;
			time++;			
			localStorage.setItem('idleTime', time);						
		}
		console.log('Current Counter',time);
		
		// if(idleTime > 5){
		// 	$('#lock_screen').show();
		// }
		
		// if(idleTime >= settings.autologout_mins){
		if(time >= 20){	//20 seconds
			var tab_count = Number(localStorage.getItem('tab_count'));
			
			if(tab_count==1){
				localStorage.removeItem('tab_count');
				localStorage.removeItem('active_tab');
				localStorage.removeItem('idleTime');
				localStorage.removeItem('logout_done');
			}else{
				localStorage.setItem('logout_done',true);
				localStorage.setItem('tab_count',tab_count-1);
			}			
			window.location.href = "<?php echo base_url('index.php/login/logout') ?>";			
		}
	}

	// Remove user attachment files while editing user.

		function remove_attachment(element,table_name,attachment_id){

			var response = confirm("Are you sure you want to remove this file.");

			if(response){
				$.ajax({
					type	:	"post",
					url		:	base_url+"ajax/remove_attachment",
					data	:	{
								"id"	:	attachment_id,
								"table_name" : table_name
								},
					datatype:	"json",

					success	:	function(result){
						// console.log(result);
						$(element).closest('li').remove();
					},

					error	:	function(xmlhttprequers,textStatus,error){
						console.log(xmlhttprequers.responseText);
						console.log(textStatus);
						console.log(error);
					},

					complete:	function(){

					}
				});
			}
		}

	// Show user attachment in new tab while editing user.

		function show_attachment(folder_name,file_name){

			var path = "<?php echo base_url('/uploads/attachments/'); ?>";

			window.open(path+folder_name+"/"+file_name);		
		}

	// Function to add product row
		function add_row(product_id,quantity){
		
			var box_body = $(".box-body").append(get_row()).children(":last-child");
		
			var last_row = $(".box-body").children(":last-child");
			
			box_body.find(".form-control.slct").attr("id",row_counter).attr("name","order_description["+row_counter+"][product_id]").addClass("select2");//.select2();
			box_body.find(".form-control.quantity").attr("name","order_description["+row_counter+"][quantity]");
			
			box_body.find(".attachment").attr("name","attachment["+row_counter+"][]");		
			last_row.find(".form-control.slct").rules("add", "my_required_rule");
			last_row.find('.attachment').rules("add",{extension:settings.allowed_file_extensions});
			last_row.find('.attachment').rules("add",{filesize:settings.file_upload_size_bytes});	//bytes

			$.each(selected_option_array,function(index,val){
				$(".slct#"+row_counter+" option[value='"+val+"']").prop("disabled",true);
			});

			$(".slct").select2();
			
			row_counter++;
		}

		function add_row_while_editing(product_id,quantity){
			var box_body = $(".box-body").append(get_row()).children(":last-child");
			var last_row = $(".box-body").children(":last-child");
		
			box_body.find(".form-control.slct").attr("id",row_counter).attr("name","order_description["+row_counter+"][product_id]").addClass("select2").val(product_id);//.select2();
			box_body.find(".form-control.quantity").attr("name","order_description["+row_counter+"][quantity]").val(quantity);		
			
			box_body.find(".attachment").attr("name","attachment["+row_counter+"][]");		
			last_row.find(".form-control.slct").rules("add", "my_required_rule");
			last_row.find('.attachment').rules("add",{extension:settings.allowed_file_extensions});
			last_row.find('.attachment').rules("add",{filesize:settings.file_upload_size_bytes});	//bytes

			// $(".slct").select2();
			row_counter++;
		}

		function get_row(){
			return (row_counter == 0)?row_first.clone():row_second.clone();
		}

	// Hides first auto added product row on client add/edit page
		function hide_show_order_row_while_adding_client(chkbox){		
			var $this = chkbox;
			if($this.is(":checked")){
				$('.custom_hide').show().each(function(index,value){
					$(value).find(".form-control.slct").rules("add","my_required_rule");
					$(value).find('.attachment').rules("add",{extension:settings.allowed_file_extensions});
					$(value).find('.attachment').rules("add",{filesize:settings.file_upload_size_bytes});	//bytes
				});
				$('#place_order_checkbox').val(1);
			}else{
				$('.custom_hide').hide().each(function(index,value){
					$(value).find(".form-control.slct").rules("remove","my_required_rule");
					$(value).find('.attachment').rules("remove","extension");
					$(value).find('.attachment').rules("remove","filesize");	//bytes
				});
				$('#place_order_checkbox').val(0);
			}
		}

</script>

<!-- Template for product row -->
	<script type="text/html" id="template_row">
		<div class="row custom_hide">
			<div class="col-sm-12 col-md-12">
				<div class="form-group">
					<input type="hidden"/>
					<label for="" class="col-sm-2 col-md-2 control-label">Product<span style="color:red;">*</span></label>
					<div class="col-sm-10 col-md-3">					
						<select class="form-control slct" data-placeholder="Select Product" name="order_description[0][product_id]" style="width: 100%;">
						  <?php
								echo "<option value=\"\" selected=\"selected\">Select Product</option>";
								if(isset($products)){
									foreach($products as $key=>$productArr){
										echo "<option value=\"{$productArr['product_id']}\">{$productArr['name']}</option>";
									}
								}
							?>
						</select>					
					</div>
					<div class="col-sm-10 col-md-2">
						<input type="text" class="form-control quantity numberonly" name="order_description[0][quantity]" placeholder="Quantity" maxlength="3">
					</div>
					<div class="col-sm-10 col-md-3">
						<input type="file" class="form-control-file attachment" name="attachment[][]" placeholder="" multiple value="">
					</div>
					<div class="col-sm-10 col-md-2"><div class="pull-right">
						<button class="btn btn-warning btn-add" type="button" id="btnOrderProdAdd">
		                    <span class="fa fa-plus"></span>
		                </button></div>
					</div>
					<div class="col-sm-10 col-md-10 col-md-offset-2">
						<span class="help-block"></span>
					</div>
					<div class="col-sm-10 col-md-10 col-md-offset-2">
						<span class="help-block"></span>
					</div>
				</div>
			</div>
		</div>
	</script>

</body>
</html>
