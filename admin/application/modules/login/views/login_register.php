<?php
$input['first_name'] = array(
                          "name" => "data[customers][first_name]",
                          "placeholder" => "first name(s) *",
                          "max_length" => "64",
                          "required" => "required",
                          "class" => "form-control",
                          "id" =>"first_name",
                          );

$input['middle_name'] = array(
                          "name" => "data[customers][middle_name]",
                          "placeholder" => "middle name(s)",
                          "max_length" => "64",
/*                          "required" => "required",
*/                           "class" => "form-control",
                           "id" => "middle_name",
                           );

$input['surname'] = array(
                      "name" => "data[customers][surname]",
                      "placeholder" => "surname(s)",
                      "max_length" => "64",
/*                      "required" => "required",
*/                      "class" => "form-control",
                      "id" => "surname",
                    );

/*$input['company_name'] = array(
                      "name" => "data[customers][company_name]",
                      "placeholder" => "company_name(s) *",
                      "max_length" => "64",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "company_name",
                    );*/

$input['primary_email'] = array(
                      "name" => "data[customers][primary_email]",
                      "placeholder" => "Email ID(s) *",
                      "max_length" => "100",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "primary_email",
                    );

/*$input['secondary_email'] = array(
                      "name" => "data[customers][secondary_email]",
                      "placeholder" => "secondary_email",
                      "max_length" => "100",
                      "class" => "form-control",
                      "id" => "secondary_email",
                    );*/


$input['contact_1'] = array(
                      "name" => "data[customers][contact_1]",
                      "placeholder" => "Mobile Number *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "contact_1",
                    );
$input['password'] = array(
                      "name" => "data[login][password]",
                      "placeholder" => "Password *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "contact_1",
                      'type' => 'password'
                    );

$input['repassword'] = array(
                      "name" => "data[password]",
                      "placeholder" => "Password *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "contact_1",
                      'type' => 'password'
                    );

/*$input['contact_2'] = array(
                      "name" => "data[customers][contact_2]",
                      "placeholder" => "contact_2 *",
                      "max_length" => "12",
                      "class" => "form-control",
                      "id" => "contact_2",
                    );*/

/*$input['pan_no'] = array(
                      "name" => "data[customers][pan_no]",
                      "placeholder" => "Pan No",
                      "class" => "form-control",
                      "id" => "pan_no",
                    );

$input['emp_code'] = array(
                      "name" => "data[customers][emp_code]",
                      "placeholder" => "Client Code",
                      "class" => "form-control",
                      "id" => "emp_code",
                    );

$input['adhaar_no'] = array(
                      "name" => "data[customers][adhaar_no]",
                      "placeholder" => "Adhaar Number",
                      
                      "class" => "form-control",
                      "id" => "adhaar_no",
                    );

$blood_group = array(
                    "id" => "blood_group",
                    "class" => "form-control",
                    );*/


$input['profile_img'] =  array(
              "name" => "profile_img",
              "placeholder" => "profile_img *",
              "class" => "form-control",
              "id" => "profile_img",
              "value" =>  set_value('profile_img'),
               );

if(isset($value_posted)) {
  foreach ($value_posted as $post_name => $post_value) {
    foreach ($post_value as $field_key => $field_value) {
      $input[$field_key]['value'] = $field_value;
    }
  }
}
?>
<div class="col-md-12">

    <ul class="breadcrumb">
        <li><a href="#">Home</a>
        </li>
        <li>New account / Sign in</li>
    </ul>

</div>

<div class="col-md-7">
    <div class="box">
        <?php echo form_open_multipart(custom_constants::register_url, ['class'=>'form-horizontal', 'id' => 'register_customer']);
      
        if($this->session->flashdata('message')!== FALSE) {
          $msg = $this->session->flashdata('message');?>
          <div class="<?php echo $msg['class'];?>">
            <?php echo $msg['message'];?>
          </div>
        <?php } ?>

        <div class="box box-info">
          <div class="box-header with-border">
          	<h3 class="box-title">New Customer</h3>

	        <!-- <p class="lead">Not our registered customer yet?</p>
	        <p>With registration with us new world of fashion, fantastic discounts and much more opens to you! The whole process will not take you more than a minute!</p>
	        <p class="text-muted">If you have any questions, please feel free to <?php echo anchor('contact-us', 'Contact Us') ?>, our customer service center is working for you 24/7.</p> -->
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
                  <div class="col-sm-10">
                   <?php echo form_input($input['first_name']);?>
                   <?php echo form_error('data[customers][first_name]');?>
                  </div>
                </div>
                </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputMiddleName" class="col-sm-2 control-label">Middle Name</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['middle_name']);?>
                  <?php echo form_error('data[customers][middle_name]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputSurName" class="col-sm-2 control-label">Last Name</label>
                  <div class="col-sm-10">
                <?php echo form_input($input['surname']);?>
                <?php echo form_error('data[customers][surname]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputContact1" class="col-sm-2 control-label">Contact</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['contact_1']);?>
                    <?php echo form_error('data[customers][contact_1]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            

            <div class="row">
              
            <div class="col-md-6">
                <div class="form-group">
                  	<label for="inputCity" class="col-sm-2 control-label">Profile Image</label>
	                <div class="col-sm-10">
	                    <?php echo form_upload($input['profile_img']); ?>
	                    <?php echo form_error('profile_img'); ?>
	                </div>
                </div>
            </div> 
            </div><!-- /row -->
            
        </div><!-- /box-body -->
        <div class="box-header with-border">
          	<h3 class="box-title">Login Details</h3>
        </div><!-- /box-header -->
        <div class="box-body">
			<div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputAddress" class="col-sm-2 control-label">Primary Email</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['primary_email']);?>
                  <?php echo form_error('data[customers][primary_email]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputSecondaryEmail" class="col-sm-2 control-label">Password</label>

                  <div class="col-sm-10">
                    <?php echo form_input($input['password']);?>
                    <?php echo form_error('data[login][password]'); ?>
                  </div>
                </div>
              </div>
            </div><!-- /row -->
            <div class="row">
            	<div class="col-md-6">
	                <div class="form-group">
	                  <label for="inputSecondaryEmail" class="col-sm-2 control-label">Re-Enter Password</label>

	                  <div class="col-sm-10">
	                    <?php echo form_input($input['repassword']);?>
	                    <?php echo form_error('data[repassword]'); ?>
	                  </div>
	                </div>
	            </div>
            </div>
        </div>
        <div class="box-footer">  
          <button type="new_college" class="btn btn-info pull-left" id="Save">Save</button>
          <?php echo nbs(3); ?>
          <button type="reset" class="btn btn-info">cancel</button>
          <?php echo nbs(3); ?>
          <a href="#" data-toggle="modal" data-target="#login-modal"  class="btn btn-info">Login</a>
        </div>
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
    </div>
</div>

