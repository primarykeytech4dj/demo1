<?php
$tab = "basic_detail";
//If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['title'] = array(
						"name" => "data[dyn_menu][title]",
						"placeholder" => "Title",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "title",
					);
$input['link_type'] = array(
						"name" => "data[dyn_menu][link_type]",
						"placeholder" => "link_type",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control",
						"id" => "link_type",
					);

$input['module'] = array(
						"placeholder" => "Module Name",
						"required" => "required",
						"class"=> "form-control select2",
						"id" => "module",
					);

$input['url'] = array(
						"name" => "data[dyn_menu][url]",
						"placeholder" => "url",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "url",
					);

$input['uri'] = array(
						"name" => "data[dyn_menu][uri]",
						"placeholder" => "uri",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "uri",
					);


$input['target'] = array(
						"name" => "data[dyn_menu][target]",
						"placeholder" => "target",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "target",
					);

$input['position'] = array(
						"name" => "data[dyn_menu][position]",
						"placeholder" => "position",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "position",
					);

$input['parent'] = array(
						"name" => "data[dyn_menu][parent_id]",
						"placeholder" => "parent",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "parent",
					);
$input['anchor_attribute'] = array(
						"name" => "data[dyn_menu][anchor_attribute]",
						"placeholder" => "anchor_attribute",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "anchor_attribute",
					);

$input['li_attribute'] = array(
						"name" => "data[dyn_menu][li_attribute]",
						"placeholder" => "li_attribute",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "li_attribute",
					);

$input['icon'] = array(
						"name" => "data[dyn_menu][icon]",
						"placeholder" => "icon",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "icon",
					);

$input['child_attribute'] = array(
						"name" => "data[dyn_menu][child_attribute]",
						"placeholder" => "child_attribute",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "form-control",
						"id" => "child_attribute",
					);

$input['show_menu'] = array(
						"name" => "data[dyn_menu][show_menu]",
						"max_length" => "64",
						//"required" => "required",
						"class"=> "flat-red",
						"id" => "show_menu",
						'type' => 'checkbox'
					);

$input['is_parent'] = array(
						"name" => "data[dyn_menu][is_parent]",
						"class" => "flat-red",
						"id" => "is_parent",
						"type" => "checkbox",
						"value" => true,
					);

$input['divider'] = array(
						"name" => "data[dyn_menu][divider]",
						"class" => "flat-red",
						"id" => "divider",
						"type" => "checkbox",
						"value" => true,
					);

$input['is_tab'] = array(
						"name" => "data[dyn_menu][is_tab]",
						"class" => "flat-red",
						"id" => "is_tab",
						"type" => "checkbox",
						"value" => true,
					);

$input['is_custom_constant'] = array(
						"name" => "data[dyn_menu][is_custom_constant]",
						"class" => "flat-red",
						"id" => "is_custom_constant",
						"type" => "checkbox",
						"value" => true,
					);


// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ //print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{
		foreach ($post_value as $field_key => $field_value) {
			# code...
			$input[$field_key]['value'] = $field_value;
		}
	}
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Menus
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li><a href="<?php echo custom_constants::admin_message_listing_url; ?>">Menus</a></li>
    <li class="active">New Menu</li>
  </ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				<!-- <ul class="nav nav-tabs">
					<li class="<?php if($tab=="basic_detail"){echo "active";} ?>"><a href="#basic_detail" data-toggle="tab">User</a></li>
					<li class="<?php if($tab=="address"){echo "active";} ?>"><a href="#address" data-toggle="tab">Address</a></li>
					<li class="<?php if($tab=="login"){echo "active";} ?>"><a href="#login" data-toggle="tab">Login</a></li>
				</ul>  -->
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="basic_detail"){echo "active";} ?>" id="basic_detail"> 
						<?php echo form_open_multipart(custom_constants::new_menu_url, ['class'=>'form-horizontal', 'id'=>'menus']); 
							//print_r($this->session);
							if(isset($form_error))
							{
								echo "<div class='alert alert-danger'>";
								echo $form_error;
								echo "</div>";
							}
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">New Menu</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<?php if(isset($err)){ ?>
									<div class="alert alert-danger alert-dismissible">
										<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
										<h4><i class="icon fa fa-ban"></i> Alert!</h4>
										<?php echo $this->session->flashdata('err'); ?>
									</div>
									<?php } ?>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Dyn Group</label>
												<div class="col-sm-10">
													<!-- <?php echo form_input($input['dyn_group']); ?> -->
													<?php echo form_dropdown('data[dyn_menu][dyn_group_id]',$option['dyn_group'],'',"id='dyn_group_id' required='required' class='form-control select2 filter'   tab-index=1 style='width:100%' data-link='menus/groupwise_menu' data-target=''parent_id");?>
													<?php echo form_error('data[menus][dyn_group]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Parent</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[dyn_menu][parent_id]',$option['parent'],'',"id='parent_id' required='required' class='form-control select2 filter'   tab-index=1 style='width:100%'");?>
													<?php echo form_error('data[dyn_menu][parent]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Title</label>
												<div class="col-sm-10">
													<?php echo form_input($input['title']); ?>
													<?php echo form_error('data[dyn_menu][title]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Link Type</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[dyn_menu][link_type]',$link_type,'',"id='link_type_id' required='required' class='form-control select2 filter' required='required' tab-index=1 style='width:100%'");?>
													<?php echo form_error('data[dyn_menu][link_type]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Module</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[dyn_menu][module_name]', $option['module'], '', $input['module']); ?>
													<?php echo form_error('data[dyn_menu][module]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">URL</label>
												<div class="col-sm-10">
													<?php echo form_input($input['url']); ?>
													<?php echo form_error('data[dyn_menu][url]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">URI</label>
												<div class="col-sm-10">
													<?php echo form_input($input['uri']); ?>
													<?php echo form_error('data[dyn_menu][uri]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Target</label>
												<div class="col-sm-10">
													<?php echo form_input($input['target']); ?>
													<?php echo form_error('data[dyn_menu][target]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Position</label>
												<div class="col-sm-10">
													<?php echo form_input($input['position']); ?>
													<?php echo form_error('data[dyn_menu][position]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Anchor Attribute</label>
												<div class="col-sm-10">
													<?php echo form_input($input['anchor_attribute']); ?>
													<?php echo form_error('data[dyn_menu][anchor_attribute]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">LI Attribute</label>
												<div class="col-sm-10">
													<?php echo form_input($input['li_attribute']); ?>
													<?php echo form_error('data[dyn_menu][li_attribute]'); ?>
												</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Show Menu</label>
												<div class="col-sm-10">
													<?php echo form_input($input['show_menu']); ?>
													<?php echo form_error('data[dyn_menu][surname]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Child Attribute</label>
												<div class="col-sm-10">
													<?php echo form_input($input['child_attribute']); ?>
													<?php echo form_error('data[dyn_menu][child_attribute]'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Icon Type</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[dyn_menu][icon_type]',$icon_type,'',"id='icon_type_id' required='required' class='form-control select2 filter'   tab-index=1 style='width:100%'");?>
													<?php echo form_error('data[dyn_menu][icon]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Icon</label>
												<div class="col-sm-10">
													<!-- <?php echo form_input($input['icon']); ?> -->
													<input type="file" name="icon_image" class="form-control" id="image_id_0" ></br>
													<?php echo form_input($input['icon']); ?>
													<?php echo form_error('data[dyn_menu][icon]'); ?>
												</div>
											</div>
										</div>
										
									</div>
									<div class="row">
									</div><!-- /row -->
									<div class="row">
										
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="surname" class="col-sm-2 control-label">Divider</label>
												<div class="col-sm-10">
													<?php echo form_input($input['divider']); ?>
													<?php echo form_error('data[menus][surname]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="allow_login" class="col-sm-2 control-label">Is Tab</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['is_tab']);?>
													<?php echo form_error('data[employees][allow_login]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
								                <label for="allow_login" class="col-sm-2 control-label">Is Custom Constant</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['is_custom_constant']);?>
													<?php echo form_error('data[employees][allow_login]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="allow_login" class="col-sm-2 control-label">Is Parent</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['is_parent']);?>
													<?php echo form_error('data[employees][allow_login]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									<div class="row">
									</div>
	
									<!-- s --> <!-- /box-body -->  
							                   
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Register</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php /*echo nbs(3);*/ ?>
									<button type="submit" class="btn btn-info">cancel</button>
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
							</div>
						<?php echo form_close(); ?> 
					</div><!-- /tab-pane -->
					
					
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

