<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$input['first_name'] = array(
						"name" => "data[login][first_name]",
						"placeholder" => "first name(s) *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "first_name",
					);

$input['surname'] = array(
						"name" => "data[login][surname]",
						"placeholder" => "surname *",
						"max_length" => "64",
						"required" => "required",
						"class" => "form-control",
						'id' => "surname"
					);

$input['middle_name'] = array(
							'name' => "data[login][middle_name]",
							'placeholder'=> "Middle Name(s) *",
							"max_length" =>"64",
							"required" =>"required",
							"class" =>"form-control",
							"id" => "middle_name",
							 );
$input['username'] =  array(
							"name" => "data[login][username]",
							"placeholder" => "Username *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
						
							 );



$input['email'] =  array(
							"type" => "email",
							"name" => "data[login][email]",
							"placeholder" => "Primary Email *",
							"max_length" => "100",
							"required" => "required",
							"class" => "form-control",
							"id" => "primary_email",
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
                      "class" => "form-control",
                      "id" => "repassword",
                      'type' => 'password'
                    );
$input['is_active'] = array(
						"name" => "data[login][is_active]",
						"id" => "is_active",
						'type' => 'checkbox'
					  );



// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{  //echo '<pre>'; print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{ //echo '<pre>'; print_r($post_value);
		if(is_array($post_value)){
			foreach ($post_value as $field_key => $field_value) {
				if(isset($input[$field_key]['type']) && $input[$field_key]['type']=="checkbox" && $field_value==true){
					$input[$field_key]['checked'] = "checked";
				}else{
					$input[$field_key]['value'] = $field_value;
				}
			}
		}
	}
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		Module :: login
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
		<li><?php echo anchor('login/admin_index_2', 'login', ['title'=>'login']) ?></li>
	</ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<?php 
			
           	if(NULL !== $this->session->flashdata('message') ) {
	            $msg = $this->session->flashdata('message');?>
	          	<div class = "<?php echo $msg['class'];?>">
	                <?php echo $msg['message'];?>
	          	</div>
        	<?php } ?>
        	<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					<li class="<?php if($tab=="personal_info"){echo "active";} ?>"><a href="#personal_info" data-toggle="tab">Personal Information</a></li>
					<li class="<?php if($tab=="roles"){echo "active";} ?>"><a href="#roles" data-toggle="tab">Role</a></li>
					<li class="pull-right">
						<?php echo anchor(custom_constants::admin_employee_view.'/'.$id, '<i class="fa fa-sticky-note"></i>', ['class'=>'text-muted', 'title'=>'View Details']);  ?>
					</li>
				</ul> 
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="personal_info"){echo "active";} ?>" id="personal_info">
						<?php echo form_open_multipart(custom_constants::user_edit_url ."/".$id, ['class'=>'form-horizontal', 'id'=>'admin_edit']); ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Existing User</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="first_name" class="col-sm-2 control-label">FirstName</label>
												<div class="col-sm-10">
													<?php echo form_input($input['first_name']); ?>
												      <?php echo form_error('data[login][first_name]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Surname</label>
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
												<label for="contact_1" class="col-sm-2 control-label">Username</label>
												<div class="col-sm-10">
													<?php echo form_input($input['username']); ?>
													<?php echo form_error('data[login][username]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="primary_email" class="col-sm-2 control-label">Primary Email</label>
												<div class="col-sm-10">
													<?php echo form_input($input['email']); ?>
													<?php echo form_error('data[login][email]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
									    <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="inputSecondaryEmail" class="col-sm-2 control-label">Password</label>
                            
                                              <div class="col-sm-10">
                                                <?php echo form_input($input['password']);?>
                                                <?php echo form_error('data[login][password]'); ?>
                                              </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                        	                <div class="form-group">
                        	                  <label for="inputSecondaryEmail" class="col-sm-2 control-label">Re-Enter Password</label>
                        
                        	                  <div class="col-sm-10">
                        	                    <?php echo form_input($input['repassword']);?>
                        	                    <?php echo form_error('data[password]'); ?>
                        	                  </div>
                        	                </div>
                        	            </div>
									</div><!-- /row -->
									<div class="row">
									
										<?php if(count($user_roles)>0){?>
									    <div class="col-md-6">
                                            <div class="form-group">
                                              <label for="inputSecondaryEmail" class="col-sm-2 control-label">Roles</label>
												<input type="hidden" name="data[user_roles][id]" value="<?=$user_roles[0]['id']?>"?>
                                              <div class="col-sm-10">
											
												<?php echo form_dropdown('data[user_roles][role_id]', $option['role'], $user_roles[0]['role_id'],['id'=>"role_id", 'required'=>"required", 'class'=>"form-control select2", 'style'=>"width:100%"]);?>
													
												      <?php echo form_error('data[user_roles][role_id]'); ?>
                                              </div>
                                            </div>
                                        </div><?php } ?>
                                        <div class="col-md-6">
											
                        	                <div class="form-group">
                        	                  <label for="inputSecondaryEmail" class="col-sm-2 control-label">Is Active</label>
                        
                        	                  <div class="col-sm-10">
											  <?php echo form_input($input['is_active']);?>
                        	                    <?php echo form_error('data[login][is_active]'); ?>
											 <!-- <input type="checkbox" name="data[login][is_active]" value="<?php //$values_posted['login']['is_active']?>" <?=($values_posted['login']['is_active'] == 1) ? 'checked' : ''?> > -->
                        	                  </div>
                        	                </div>
                        	            </div>
									</div><!-- /row -->

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
					<div class="tab-pane <?php if($tab=="roles"){echo "active";} ?>" id="roles">
					</div>
					
				</div>
			</div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

<script>
    var password = document.getElementById("password")
  , confirm_password = document.getElementById("repassword");

function validatePassword(){
  if(password.value != confirm_password.value) {
    confirm_password.setCustomValidity("Passwords Don't Match");
  } else {
    confirm_password.setCustomValidity('');
  }
}
password.onchange = validatePassword;
confirm_password.onkeyup = validatePassword;
</script>

