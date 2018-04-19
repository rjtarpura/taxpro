  <?php

  $user_image_url   = base_url().'uploads/attachments/photos/no_image.jpg';
  if(isset($user_profile)){
    $image      = $user_profile['photo'];
    $user_image_path= $_SERVER['DOCUMENT_ROOT'].'/taxpro/uploads/attachments/photos/users/'.$image;
    // var_dump(file_exists($user_image_path));exit;
    if(($image) && (file_exists($user_image_path))){
      $user_image_url = base_url().'uploads/attachments/photos/users/'.$image;
    } 
  }

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
    <section class="content-header">
      <h1>
        User Profile
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <?php echo $flashdata; ?>
      <div class="row">
        <div class="col-md-3">

          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <form method="post" id="upload_image" enctype="multipart/form-data" action="<?php echo base_url('index.php/users/image_update/');echo ($user_profile)?$user_profile['user_id']:''; ?>">
                <input type="file" name="photo" id="photo" accept=".jpg, .jpeg, .png" style="display:none"/>  
              </form>
              <img class="profile-user-img img-responsive img-circle" id="user_image" src="<?php echo $user_image_url; ?>" alt="<?php echo $user_profile['first_name']; ?> profile picture" style="max-width:150px;" onclick="trigger_file_upload()" data-toggle="title" title="Click to change Profile Pic">

              <h3 class="profile-username text-center">
              	<?php 
					echo ($user_profile['gender']==MALE)?"Mr. ":"Ms. ";
					echo $user_profile['first_name']." ".$user_profile['last_name'];
				?>
              </h3>

              <p class="text-muted text-center">
              	Member Since <?php echo date('d M, Y',strtotime($user_profile['create_date'])); ?>
              </p>
              <p>&nbsp;</p>
              <button class="btn btn-primary btn-block btn-flat btn-xs" style="display:none" id="upload_button" onclick="upload_file()">Upload</button>
              <ul class="list-group list-group-unbordered">
                <!-- <li class="list-group-item">
                  <b>Followers</b> <a class="pull-right">1,322</a>
                </li>                 -->
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="nav-tabs-custom">
            <?php

              if(isset($active_tab)){
                $active_personal_tab = ($active_tab==UPDATE_PERSONAL)?'active':''; 
                $active_contact_tab = ($active_tab==UPDATE_CONTACT)?'active':'';
                $active_password_tab = ($active_tab==UPDATE_PASSWORD)?'active':'';
              }else{
                $active_personal_tab = 'active';
                $active_contact_tab = '';
                $active_password_tab  = '';
              }
              // var_dump($active_tab);
            ?>
            <ul class="nav nav-tabs">
              <li class="<?php echo $active_personal_tab; ?>"><a href="#personal" data-toggle="tab">Personal</a></li>
              <li class="<?php echo $active_contact_tab; ?>"><a href="#contact" data-toggle="tab">Contact</a></li>
              <li class="<?php echo $active_password_tab; ?>"><a href="#password_tab" data-toggle="tab">Change Password</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane <?php echo $active_personal_tab; ?>" id="personal">
                <form class="form-horizontal" method="post" action="<?php echo base_url('index.php/users/profile_update/');echo ($user_profile)?$user_profile['user_id']:''; ?>" id="update_personal">
                  <input type="hidden" name="update_type" value="<?php echo UPDATE_PERSONAL; ?>">
                  <div class="form-group <?php echo (form_error('first_name'))?'has-error':''; ?>">
                    <label for="first_name" class="col-sm-2 control-label">First Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo set_value('first_name',($user_profile)?$user_profile['first_name']:'');?>">
                      <span class="help-block"><?php echo form_error('first_name'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('last_name'))?'has-error':''; ?>">
                    <label for="last_name" class="col-sm-2 control-label">Last Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo set_value('last_name',($user_profile)?$user_profile['last_name']:'');?>">
                      <span class="help-block"><?php echo form_error('last_name'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('dob'))?'has-error':''; ?>">
                    <label for="dob" class="col-sm-2 control-label">Dob</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control datepicker" id="dob" name="dob" readonly="readonly" placeholder="Birth Date" value="<?php echo set_value('dob',($user_profile)?$user_profile['dob']:'');?>">
                      <span class="help-block"><?php echo form_error('dob'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('gender'))?'has-error':''; ?>">
                    <label for="gender" class="col-sm-2 control-label">Gender</label>

                    <div class="col-sm-10">
                      <select name="gender" id= "gender" class="form-control select2" data-placeholder="Select Gender" style="width: 100%;">

                        <?php $gender = set_value('gender',($user_profile)?$user_profile['gender']:''); ?>                  
                        <option value="" <?php echo ($gender)?'':"selected=\"selected\"" ?>>Select Gender</option>
                        <option value="<?php echo MALE; ?>" <?php echo ($gender==MALE)?"selected=\"selected\"":"" ?>>Male</option>
                        <option value="<?php echo FEMALE; ?>" <?php echo ($gender==FEMALE)?"selected=\"selected\"":"" ?>>Female</option>

                      </select>
                      <span class="help-block"><?php echo form_error('gender'); ?></span>
                    </div>                    
                  </div>
                  <div class="form-group <?php echo (form_error('dob'))?'has-error':''; ?>">
                    <label for="username" class="col-sm-2 control-label">Username</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo set_value('username',($user_profile)?$user_profile['username']:'');?>">
                      <span class="help-block"><?php echo form_error('username'); ?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane <?php echo $active_contact_tab; ?>" id="contact">
                <form class="form-horizontal" method="post" action="<?php echo base_url('index.php/users/profile_update/');echo ($user_profile)?$user_profile['user_id']:''; ?>" id="update_contact">
                  <input type="hidden" name="update_type" value="<?php echo UPDATE_CONTACT; ?>">
                  <div class="form-group <?php echo (form_error('contact'))?'has-error':''; ?>">
                    <label for="contact" class="col-sm-2 control-label">Contact</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control numberonly" id="contact" name="contact" placeholder="Contact" maxlength="10" value="<?php echo set_value('contact',($user_profile)?$user_profile['contact']:'');?>">
                      <span class="help-block"><?php echo form_error('contact'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('email'))?'has-error':''; ?>">
                    <label for="email" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo set_value('email',($user_profile)?$user_profile['email']:'');?>">
                      <span class="help-block"><?php echo form_error('email'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('street1'))?'has-error':''; ?>">
                    <label for="street1" class="col-sm-2 control-label">Street 1</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="street1" name="street1" placeholder="Street 1" value="<?php echo set_value('street1',($user_profile)?$user_profile['street1']:'');?>">
                      <span class="help-block"><?php echo form_error('street1'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('street2'))?'has-error':''; ?>">
                    <label for="street2" class="col-sm-2 control-label">Street 2</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="street2" name="street2" placeholder="Street 2" value="<?php echo set_value('street2',($user_profile)?$user_profile['street2']:'');?>">
                      <span class="help-block"><?php echo form_error('street2'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('country_id'))?'has-error':''; ?>">
                    <label for="country_id" class="col-sm-2 control-label">Country</label>

                    <div class="col-sm-10">
                      <select name="country_id" id= "country_id" class="form-control country select2" data-placeholder="Select Country" style="width: 100%;" >                  
                        <?php
                        
                          $country_id = set_value('country_id',($user_profile)?$user_profile['country_id']:'');
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
                    <label for="state_id" class="col-sm-2 control-label">State</label>

                    <div class="col-sm-10">
                      <select name="state_id" id= "state_id" class="form-control state select2" data-placeholder="Select State" style="width: 100%;">
                        <?php
                          $state_id = set_value('state_id',($user_profile)?$user_profile['state_id']:'');

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
                    <label for="city_id" class="col-sm-2 control-label">City</label>

                    <div class="col-sm-10">
                      <select name="city_id" id= "city_id" class="form-control city select2" data-placeholder="Select City" style="width: 100%;" >                  
                        <?php
                          $city_id = set_value('city_id',($user_profile)?$user_profile['city_id']:'');

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
                    <label for="zip" class="col-sm-2 control-label">ZIP</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="zip" name="zip" placeholder="ZIP" maxlength="10" value="<?php echo set_value('zip',($user_profile)?$user_profile['zip']:'');?>">
                      <span class="help-block"><?php echo form_error('zip'); ?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane <?php echo $active_password_tab; ?>" id="password_tab">
                <form class="form-horizontal" method="post" action="<?php echo base_url('index.php/users/profile_update/');echo ($user_profile)?$user_profile['user_id']:''; ?>" id="update_password">
                  <input type="hidden" name="update_type" value="<?php echo UPDATE_PASSWORD; ?>">
                  <div class="form-group <?php echo (form_error('old_password'))?'has-error':''; ?>">
                    <label for="old_password" class="col-sm-2 control-label">Old Password</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old Password">
                      <span class="help-block"><?php echo form_error('old_password'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('password'))?'has-error':''; ?>">
                    <label for="password" class="col-sm-2 control-label">New Password</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="password" name="password" placeholder="New Password">
                      <span class="help-block"><?php echo form_error('password'); ?></span>
                    </div>
                  </div>
                  <div class="form-group <?php echo (form_error('new_password_confirm'))?'has-error':''; ?>">
                    <label for="new_password_confirm" class="col-sm-2 control-label">Confirm Password</label>

                    <div class="col-sm-10">
                      <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" placeholder="Confirm Password">
                      <span class="help-block"><?php echo form_error('new_password_confirm'); ?></span>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
          <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
      
    </section>
    <!-- /.content -->
  </div>
<?php require_once APPPATH."/views/components/pluginImpots.php" ?>
<script type="text/javascript">

  var settings = <?php echo $js_variables; ?>;
  var allowed = settings.profile_pic_extensions.split("|");

  $(document).ready(function(){
    <?php
    $original_email_url = base_url('index.php/ajax/email_unique/');;
    if($user_profile){
      if(! is_null($user_profile['email'])){
        $original_email_url .= urlencode($user_profile['email']);
      }
    }

    $original_contact_url = base_url('index.php/ajax/contact_unique/');;
    if($user_profile){
      if(! is_null($user_profile['contact'])){
        $original_contact_url .= urlencode($user_profile['contact']);
      }
    }
    $original_username_url = base_url('index.php/ajax/username_unique/');;
    if($user_profile){
      if(! is_null($user_profile['username'])){
        $original_username_url .= urlencode($user_profile['username']);
      }
    }
    ?>

    $('#update_personal').validate({
        debug   : true,
        rules   :   {
                      first_name  : {
                                required    : true,
                                maxlength   : '50',
                                alphaSpaceCI  :   "First Name"
                              },
                      last_name : {
                                maxlength   : '50',
                                alphaSpaceCI  :   "Last Name"
                              },              
                      dob     :   "required",
                      gender    : "required",
                      username    : {
                                required    : true,
                                maxlength   :   '10',
                                remote      : {
                                            url   :   "<?php echo $original_username_url; ?>",
                                            type  :   "post"
                                          }
                              }              
                    },
        messages  : {
                      username  : {
                        remote      : "username already Exists"
                      }
                    },
        errorElement: "span",
        errorPlacement: function ( error, element ) {
          // console.log($(element).closest("div.has-error"));
          // error.addClass( "help-block" ).css("color","#a94442");
              
                  
          if($(element).hasClass("datepicker")){
            $(element).parent("div").next().text($(error)[0].innerHTML);
          }else if($(element).hasClass("select2")){
            $(element).next().next().text($(error)[0].innerHTML);
          }else{
            $(element).next().text($(error)[0].innerHTML);
          }
          // error.insertAfter( element );
        },
        highlight: function ( element, errorClass, validClass ) {
          // $( element ).parents( ".col-sm-10" ).addClass( "has-error" ).removeClass( "has-success" );
          $( element ).parents( ".form-group" ).addClass( "has-error" );
          // $( element ).next().text('');
          if($(element).hasClass("datepicker")){
            $( element ).parent("div").next().text('');
          }else if($(element).hasClass("select2")){
            $(element).next().next().text('');
          }else{
            $( element ).next().text('');
          }

        },
        unhighlight: function (element, errorClass, validClass) {
          // $( element ).parents( ".col-sm-10" ).addClass( "has-success" ).removeClass( "has-error" );
          $( element ).parents( ".form-group" ).removeClass( "has-error" );
          // $( element ).next().text('');
          if($(element).hasClass("datepicker")){
            $( element ).parent("div").next().text('');
          }else if($(element).hasClass("select2")){
            $(element).next().next().text('');
          }else{
            $( element ).next().text('');
          }
        },
        submitHandler: function(form) {
          form.submit();
        }
      });
 
    $('#update_contact').validate({
        debug   : true,
        rules   :   {
                      email   : {
                                required  :   true,
                                email     :   true,
                                remote      : {                         
                                            url   :   "<?php echo $original_email_url; ?>",
                                            type  :   "post"
                                          }
                              },
                      contact   : {
                                maxlength   :   '10',
                                remote      : {
                                            url   :   "<?php echo $original_contact_url; ?>",
                                            type  :   "post"
                                          }
                              },                      
                      country_id    : "required",
                      state_id      : "required",
                      city_id     : "required",
                      street1   : {
                                required    :   true,
                                address     :   'Street 1'
                              },
                      street2   : {
                                address     :   'Street 2'
                              },
                      zip     : {
                                digits      :   true,
                                maxlength   : 10,
                                required    : true
                              }
                    },
        messages  : {
                      email   : {
                                remote      : "Email already Exists"
                              },
                      contact   : {
                                remote      : "Contact already Exists"
                              }
                    },
        errorElement: "span",
        errorPlacement: function ( error, element ) {
          // console.log($(element).closest("div.has-error"));
          // error.addClass( "help-block" ).css("color","#a94442");
              
                  
          if($(element).hasClass("datepicker")){
            $(element).parent("div").next().text($(error)[0].innerHTML);
          }else if($(element).hasClass("select2")){
            $(element).next().next().text($(error)[0].innerHTML);
          }else{
            $(element).next().text($(error)[0].innerHTML);
          }
          // error.insertAfter( element );
        },
        highlight: function ( element, errorClass, validClass ) {
          // $( element ).parents( ".col-sm-10" ).addClass( "has-error" ).removeClass( "has-success" );
          $( element ).parents( ".form-group" ).addClass( "has-error" );
          // $( element ).next().text('');
          if($(element).hasClass("datepicker")){
            $( element ).parent("div").next().text('');
          }else if($(element).hasClass("select2")){
            $(element).next().next().text('');
          }else{
            $( element ).next().text('');
          }

        },
        unhighlight: function (element, errorClass, validClass) {
          // $( element ).parents( ".col-sm-10" ).addClass( "has-success" ).removeClass( "has-error" );
          $( element ).parents( ".form-group" ).removeClass( "has-error" );
          // $( element ).next().text('');
          if($(element).hasClass("datepicker")){
            $( element ).parent("div").next().text('');
          }else if($(element).hasClass("select2")){
            $(element).next().next().text('');
          }else{
            $( element ).next().text('');
          }
        },
        submitHandler: function(form) {
          form.submit();
        }
      });

    $('#update_password').validate({
        debug   : true,
        rules   :   {                      
                      old_password   : {
                                required  : true
                              },
                      password   : {
                                required  : true
                              },
                      new_password_confirm     : {
                                required  : true,
                                equalTo   : "#password"
                              }
                    },
        // messages  : {
                      
        //               contact   : {
        //                         remote      : "Contact already Exists"
        //                       }
        //             },
        errorElement: "span",
        errorPlacement: function ( error, element ) {
          // console.log($(element).closest("div.has-error"));
          // error.addClass( "help-block" ).css("color","#a94442");
              
                  
          if($(element).hasClass("datepicker")){
            $(element).parent("div").next().text($(error)[0].innerHTML);
          }else if($(element).hasClass("select2")){
            $(element).next().next().text($(error)[0].innerHTML);
          }else{
            $(element).next().text($(error)[0].innerHTML);
          }
          // error.insertAfter( element );
        },
        highlight: function ( element, errorClass, validClass ) {
          // $( element ).parents( ".col-sm-10" ).addClass( "has-error" ).removeClass( "has-success" );
          $( element ).parents( ".form-group" ).addClass( "has-error" );
          // $( element ).next().text('');
          if($(element).hasClass("datepicker")){
            $( element ).parent("div").next().text('');
          }else if($(element).hasClass("select2")){
            $(element).next().next().text('');
          }else{
            $( element ).next().text('');
          }

        },
        unhighlight: function (element, errorClass, validClass) {
          // $( element ).parents( ".col-sm-10" ).addClass( "has-success" ).removeClass( "has-error" );
          $( element ).parents( ".form-group" ).removeClass( "has-error" );
          // $( element ).next().text('');
          if($(element).hasClass("datepicker")){
            $( element ).parent("div").next().text('');
          }else if($(element).hasClass("select2")){
            $(element).next().next().text('');
          }else{
            $( element ).next().text('');
          }
        },
        submitHandler: function(form) {
          form.submit();
        }
      });

    $('#photo').on('change',function(event){

      var filename = $('#photo').val();

      if(filename.length >0){
        
        var path = URL.createObjectURL(event.target.files[0]);
      
        if(validate_file(filename) >= 0){ //do validation of file here

          if(event.target.files[0].size < settings.profile_pic_size_bytes){
            $('#user_image').attr('src',path);
            $('#upload_button').show();
          }else{
            toastr["error"]("Filesize must not exceeds "+settings.profile_pic_size_bytes);
          }                                   
        }else{
          toastr["error"]("Invalid file type<br/>"+"Allowed File types are : <br/>"+allowed.join(", "));
        }
      }                             
    });

  });

function trigger_file_upload(){
  $('#photo').trigger('click');
}

function validate_file(filename){  
  var extension = filename.substring(filename.lastIndexOf('.')+1);
  return $.inArray(extension,allowed);        
}

function upload_file(){
  var action = $('#upload_image').attr('action');
  var data = new FormData();
  data.append('photo',$('#photo')[0].files[0]);
  $.ajax({
      type    : 'POST',
      url     : action,
      data    : data, //$("#upload_image").serialize(),
      contentType : false,
      cache   : false,
      processData : false, 
      dataType  : "json",           
      success   : function(result){
        // console.log(result);
        // console.log(typeof result.message);
        var flash = "";
        if(result.status=='success'){
          toastr["success"](result.message);
        }else if(result.status=='error'){
          var msg = '';
          if(typeof result.message == 'string'){
            msg = result.message;            
          }else{
            msg = "<ul>";
            $.each(result.message,function(index,value){
            msg += value;
          });            
            msg += "</ul>";
          }
          toastr["error"](msg);          
        }
        $('#upload_button').hide();
        $('.profile_pic').attr('src',$('#user_image').attr('src'));
        // profile_pic #user_header_image
      },
      error   : function(xmlhttpreq,textStatus,errorThrown){
        console.log(xmlhttpreq.responseText);
      }
      });
}

</script>