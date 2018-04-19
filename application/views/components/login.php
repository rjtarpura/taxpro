<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $website_title; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/bootstrap/dist/css/bootstrap.min.css');?>"	>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/font-awesome/css/font-awesome.min.css'); ?>"	>
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/Ionicons/css/ionicons.min.css'); ?>"	>  
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/plugins/toastr/toastr.min.css'); ?>" >
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/dist/css/AdminLTE.min.css'); ?>"	>
  
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition login-page" style="background-image:url(<?php echo base_url('/assets/2.jpg');?>);background-repeat: no-repeat; background-size: 100% 100%;height: auto">
<div class="login-box">
  <div class="login-logo">
    <a href=""><img src="<?php echo base_url("/assets/").$logo_lg; ?>"/></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <form action="<?php echo base_url('index.php/login/login'); ?>" method="post" id="login_form">
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" name="username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-flat pull-left">Sign In</button>
		  <button type="reset" class="btn btn-default btn-flat pull-right">Cancel</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
	
	<form action="<?php echo base_url('index.php/login/forgot'); ?>" method="post" id="forgot_form" class="hide">
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" name="email" id="email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>      
      <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12">
          <button type="submit" class="btn btn-primary btn-flat pull-left" id="forgot_button">Submit</button>
          <div id="progress_icon" style="display:none;"><i class="fa fa-spinner fa-spin" style="font-size:24px;"></i> &nbsp;&nbsp;Processing...</div>
		      <!-- <button type="reset" class="btn btn-default btn-flat pull-right">Cancel</button> -->
        </div>
        <!-- /.col -->
      </div>
  </form>
	<p>&nbsp;</p>
    <a href="javascript:void(0);" id="forgot_link">Forgot Password</a><br>

    <span class="help-block">
      <!-- <div id="div_error" style="color:#dd4b39;"></div> -->
      <div id="div_success" style=""></div>
    </span>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src=" <?php echo base_url('assets/plugins/jquery/dist/jquery.min.js'); ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/plugins/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
<!-- toastr -->
<script src="<?php echo base_url('assets/plugins/toastr/toastr.min.js'); ?>"></script>
<script>
$(document).ready(function(){
	$('#forgot_link').on('click',function(){

    $('#div_success').html('');
    $('#login_form')[0].reset();
    $('#forgot_form')[0].reset();

		if($('#login_form').hasClass('hide')){

			$(this).text('Forgot Password');

		}else{

			$(this).text('Back to Login');

		}

		$('#login_form').toggleClass('hide');
		$('#forgot_form').toggleClass('hide');
	});

  //function for forgot password

      $('#forgot_form').on('submit',function(e){
        e.preventDefault();

        var forgot_button = $('#forgot_button');
        var progress_icon = $('#progress_icon');
        var forgot_link   = $('#forgot_link');
        
        forgot_button.hide();
        forgot_link.hide();
        progress_icon.show();


        $.ajax({
          type    : 'POST',
          url     : $(this).attr('action'),
          data    : $(this).serialize(),            
          dataType  : "json", 
          // async    : false,
          success   : function(result){
            // console.log(result);
            if(result.status==='success'){                
              $('#div_success').html(result.message).css("color","green");;
              // window.location.href = "<?= base_url() ?>";
            }else if(result.status=='error'){               
              $('#div_success').html(result.message).css("color","#dd4b39");;
            }else{                
              var flash = "";
              $.each(result, function (i, v) {
                flash += v+"<br/>";                 
              });
              $('#div_success').html(flash).css("color","#dd4b39");
            }
          },
          error   : function(xmlhttpreq,textStatus,errorThrown){
            console.log(errorThrown);
          },
          complete  : function(){
            // $('#forgot_button').removeAttr("disabled");              
            forgot_button.show();
            forgot_link.show();
            progress_icon.hide();
            $('#email').val("");
          }
          });           
      });

toastr.options = {
  "closeButton": true,
  "debug": false,
  "newestOnTop": false,
  "progressBar": true,
  "positionClass": "toast-bottom-right",
  "preventDuplicates": false,
  "onclick": null,
  "showDuration": "300",
  "hideDuration": "1000",
  "timeOut": "5000",
  "extendedTimeOut": "1000",
  "showEasing": "swing",
  "hideEasing": "linear",
  "showMethod": "fadeIn",
  "hideMethod": "fadeOut"
}

  <?php
    if($fde = $this->session->flashdata('error')){
      echo "toastr[\"error\"](\"$fde\")";
    }
  ?>

  <?php
    if($fde = $this->session->flashdata('success')){
      echo "toastr[\"success\"](\"$fde\")";
    }
  ?>
  
});
</script>
</body>
</html>
