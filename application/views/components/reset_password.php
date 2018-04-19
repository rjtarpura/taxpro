<!-- <?php
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
?> -->

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
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href=""><?php echo $logo_lg; ?></a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Please enter new password</p>

    <form action="<?php echo base_url("index.php/login/forgot/").$this->uri->segment(3); ?>" method="post" id="reset_form">
      <input type="hidden" name="reset_hash" value="<?php echo $reset_hash; ?>">
      <div class="form-group has-feedback <?php echo (form_error('password'))?'has-error':''; ?>">
        <input type="password" class="form-control" placeholder="Password" name="password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span class="help-block"><?php echo form_error('password'); ?></span>
      </div>
      <div class="form-group has-feedback <?php echo (form_error('password_confirm'))?'has-error':''; ?>">
        <input type="password" class="form-control" placeholder="Confirm Password" name="password_confirm" id="password_confirm">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        <span class="help-block"><?php echo form_error('password_confirm'); ?></span>
      </div>
      <div class="row" style="margin-top: 10px;">
        <div class="col-xs-12">
          <button type="submit" name="submit" class="btn btn-primary btn-flat pull-left">Submit</button>
          <button type="reset" name="reset" class="btn btn-default btn-flat pull-right">Reset</button>
        </div>
        <!-- /.col -->
      </div>
    </form>

	<p>&nbsp;</p>
    <span class="help-block">
      <div id="flashdiv"></div>
    </span>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 3 -->
<script src=" <?php echo base_url('assets/plugins/jquery/dist/jquery.min.js'); ?>"></script>
<!-- Bootstrap 3.3.7 -->
<script src="<?php echo base_url('assets/plugins/bootstrap/dist/js/bootstrap.min.js'); ?>"></script>
<script>
$(document).ready(function(){
  $('#reset_form').on('submit',function(e){
    // e.preventDefault();
    // alert();
  });
});
</script>
</body>
</html>
