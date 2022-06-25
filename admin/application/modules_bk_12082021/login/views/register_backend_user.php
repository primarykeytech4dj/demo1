<?php
$input['first_name'] = array(
                          "name" => "data[login][first_name]",
                          "placeholder" => "first name(s) *",
                          "max_length" => "64",
                          "required" => "required",
                          "class" => "form-control",
                          "id" =>"first_name",
                          );

$input['middle_name'] = array(
                          "name" => "data[login][middle_name]",
                          "placeholder" => "middle name(s)",
                          "max_length" => "64",
/*                          "required" => "required",
*/                           "class" => "form-control",
                           "id" => "middle_name",
                           );

$input['surname'] = array(
                      "name" => "data[login][surname]",
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

$input['email'] = array(
                      "name" => "data[login][email]",
                      "placeholder" => "Email ID(s) *",
                      "max_length" => "100",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "primary_email",
                    );
$input['emp_code'] = array(
                      "name" => "data[emp_code]",
                      "placeholder" => "Employee code",
                      "max_length" => "100",
                      "class" => "form-control",
                      "id" => "emp_code",
                    );
/*$input['secondary_email'] = array(
                      "name" => "data[customers][secondary_email]",
                      "placeholder" => "secondary_email",
                      "max_length" => "100",
                      "class" => "form-control",
                      "id" => "secondary_email",
                    );*/


$input['contact_1'] = array(
                      "name" => "data[employees][contact_1]",
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
                      "id" => "password",
                      'type' => 'password'
                    );

$input['repassword'] = array(
                      "name" => "data[password]",
                      "placeholder" => "Password *",
                      "max_length" => "12",
                      "required" => "required",
                      "class" => "form-control",
                      "id" => "repassword",
                      'type' => 'password'
                    );


$input['profile_img'] =  array(
              "name" => "profile_img",
              "placeholder" => "profile_img *",
              "class" => "form-control",
              "id" => "profile_img",
              "value" =>  set_value('profile_img'),
               );
$input['company'] = [
  'id'=>'company_id', 
  'required'=>'required', 
  'class'=>'form-control select2 multiple required', 
  'multiple'=>'multiple'
];
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
        <li><?php echo anchor('admin', 'Dashboard'); ?>
        </li>
        <li><?php echo anchor (custom_constants::new_login_url, 'New User Account'); ?></li>
    </ul>

</div>

<div class="col-md-12">
    <div class="box">
        <?php 
        $formClass = ($this->input->is_ajax_request())?"form-horizontal submit-ajax":"form-horizontal";
        echo form_open_multipart(custom_constants::new_login_url, ['class'=>$formClass, 'id' => 'new_user']);
        //print_r($this->session->flashdata('message'));
        if($this->session->flashdata('message')!== FALSE) {
          $msg = $this->session->flashdata('message');?>
          <div class="<?php echo $msg['class'];?>">
            <?php echo $msg['message'];?>
          </div>
        <?php } ?>

        <div class="box box-info">
          <div class="box-header with-border">
          	<h3 class="box-title">New User</h3>
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputFirstName" class="col-sm-2 control-label">First Name</label>
                  <div class="col-sm-10">
                   <?php echo form_input($input['first_name']);?>
                   <?php echo form_error('data[login][first_name]');?>
                  </div>
                </div>
                </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputMiddleName" class="col-sm-2 control-label">Middle Name</label>
                  <div class="col-sm-10">
                  <?php echo form_input($input['middle_name']);?>
                  <?php echo form_error('data[login][middle_name]'); ?>
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
                <?php echo form_error('data[login][surname]'); ?>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="inputContact1" class="col-sm-2 control-label">Contact</label>
                  <div class="col-sm-10">
                    <?php echo form_input($input['contact_1']);?>
                    <?php echo form_error('data[employees][contact_1]'); ?>
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
            <div class="col-md-6">
              <div class="form-group">
                <label for="inputRole" class="col-sm-2 control-label">Role</label>
                <div class="col-sm-10">
                  <?php echo form_dropdown("data[user_roles][role_id][]", $option['role'],'',"id='user_type' required='required' class='form-control select2'"); ?>
                  <?php echo form_error('data[roles][role_name]'); ?>
                </div>
              </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                  <label for="inputEmp_code" class="col-sm-2 control-label">Code</label>
                  <div class="col-sm-10">
                <?php echo form_input($input['emp_code']);?>
                <?php echo form_error('data[emp_code]'); ?>
                  </div>
                </div>
              </div>
          </div><!-- /row -->
          <div class="row">
            <?php if($_SESSION['application']['multiple_company']){?>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="inputCompanyProduct" class="col-sm-2 control-label">Company</label>
                    <div class="col-sm-10">
                      <?php echo form_dropdown('data[companies_employees][company_id][]',$option['company'], set_value('data[companies_employees][company_id]'), $input['company']);?>
                      <?php echo form_error('data[companies_employees][company_id]');?>
                    </div>
                  </div>
                </div>
              <?php } ?>
          </div>
            
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
                  <?php echo form_input($input['email']);?>
                  <?php echo form_error('data[employees][primary_email]'); ?>
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
          <div class="response"></div>
          <button type="new_college" class="btn btn-info pull-left" id="Save">Save</button>
          <?php echo nbs(3); ?>
          <button type="reset" class="btn btn-info">cancel</button>
          <?php echo nbs(3); ?>
          <!-- <a href="#" data-toggle="modal" data-target="#login-modal"  class="btn btn-info">Login</a> -->
        </div>
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
    </div>
</div>

