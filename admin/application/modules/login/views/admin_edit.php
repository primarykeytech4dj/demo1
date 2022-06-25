<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//echo $login['id'];//exit;
/*echo '<pre>';
echo "hiii";
echo "</pre>";exit;*/
// If access is requested from anywhere other than index.php then exit

//echo "<div class = "for-group" >";
$value='';
// /echo $values_posted['login']['dob'];exit;

$input['username'] = array(
            "name" => "data[login][username]",
            "placeholder" => "Username(s) *",
            "max_length" => "64",
            "required" => "required",
            "class"=> "form-control",
            "id" => "username",
            //'value' => set_values('username', $login['username']),
          );

$input['first_name'] = array(
            "name" => "data[login][first_name]",
            "placeholder" => "First Name",
            "max_length" => "64",
            "required" => "required",
            "class"=> "form-control",
            "id" => "first_name",
            //'value' => set_values('username', $login['username']),
          );

$input['surname'] = array(
            "name" => "data[login][surname]",
            "placeholder" => "Surname",
            "required" => "required",
            "class"=> "form-control",
            "id" => "surname",
            //'value' => set_values('username', $login['username']),
          );
                            

$input['password_hash'] = array(
            "name" => "data[login][password_hash]",
            "placeholder" => "New Password *",
            "max_length" => "64",
            "required" => "required",
            "class" => "form-control",
            'id' => "New Password",
            "type" => "password"
          );

$input['confirm_password'] = array(
            "name" => "data[login][confirm_password]",
            "placeholder" => "Confirm Password *",
            "max_length" => "64",
            "required" => "required",
            "class" => "form-control",
            'id' => "confirm_password",
            "type" => "password"
          );

// If form has been submitted with errors populate fields that were already filled
unset($values_posted['login']['password_hash']);

//print_r($values_posted);
if(isset($values_posted))
{   //echo '<pre>';print_r($values_posted);echo '</pre>';
  foreach($values_posted as $post_name => $post_value)
  {
    foreach ($post_value as $field_key => $field_value) {
      $input[$field_key]['value'] = $field_value;
    }
  }
}

?>
<!--Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <?php 
      
            if($this->session->flashdata('message') !== FALSE && !isset($module)) {
              $msg = $this->session->flashdata('message');?>
              <div class = "<?php echo $msg['class'];?>">
                  <?php echo $msg['message'];?>
              </div>
          <?php } ?>

          <div class="nav-tabs-custom">
        <div class="tab-content">
            <?php //echo form_open_multipart(custom_constants::edit_employee_url ."/".$id, ['class'=>'form-horizontal', 'id'=>'admin_edit']); 
            echo form_open_multipart('login/admin_edit' ."/".$id, ['class'=>'form-horizontal', 'id'=>'admin_edit']);?>
              <input type="hidden" name="url" value="<?php echo !isset($url)?'login/admin_edit/'.$id:$url; ?>">
              <input type="hidden" name="module" value="<?php echo !isset($module)?'employees':$module; ?>">
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Existing login</h3>
                </div><!-- /box-header -->
                <!-- form start -->
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputFirstname" class="col-sm-2 control-label">First Name</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['first_name']); ?>
                          <?php echo form_error('data[login][first_name]'); ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputSurname" class="col-sm-2 control-label">Surname</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['surname']); ?>
                          <?php echo form_error('data[login][surname]'); ?>
                        </div>
                      </div>
                    </div>
                  </div><!-- /row -->
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="Username" class="col-sm-2 control-label">Username</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['username']); ?>
                          <?php echo form_error('data[login][username]'); ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="NewPassword" class="col-sm-2 control-label">New Password</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['password_hash']); ?>
                          <?php echo form_error('data[login][password_hash]'); ?>
                        </div>
                      </div>
                    </div>
                  </div><!-- /row -->
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                          <label for="ConfirmPassword" class="col-sm-2 control-label">Confirm Password</label>
                          <div class="col-sm-10">
                            <?php echo form_input($input['confirm_password']);?>
                             <?php echo form_error('data[login][confirm_password]'); ?>
                          </div>
                                <!-- /.input group -->
                      </div>
                    </div>
                  </div>
                <div class="box-footer">  
                  <button type="new_college" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
                  <?php /*echo nbs(3);*/ ?>
                  <button type="submit" class="btn btn-info">cancel</button>
                </div>
                <!-- /.box-footer -->
              </div><!-- /box -->
              </div>
            <?php echo form_close(); ?> 
        </div>
      </div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

