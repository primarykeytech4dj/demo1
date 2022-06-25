<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$value='';
// /echo $values_posted['news_categories']['dob'];exit;
$input['name'] = array(
					"name" => "data[temp_menu][name]",
					"placeholder" => "Menu *",
					"max_length" => "255",
					"required" => "required",
					"class"=> "form-control",
					"id" => "name",
					);
$input['class'] = array(
						"name" => "data[temp_menu][class]",
						"class" => "form-control select2 viewInput",
						'id' => "class",
						'data-target' => "class2"
					);

$input['class2'] = array(
						"name" => "data[temp_menu][class2]",
						"class" => "form-control",
						'id' => "class2",
						"required" => "required",
						"placeholder" => "Bootstrap Class",
						"value" => $values_posted['temp_menu']['class']
					);

$input['slug'] = array(
						'name' => "data[temp_menu][slug]",
						'placeholder'=> "Slug(s) *",
						"max_length" =>"255",
						"required" =>"required",
						"class" =>"form-control slugify",
						"id" => "slug",
							 );

$input['menu_id'] = array(
							"class" => "form-control select2 filter",
							"id" => "menu_id",
							"required" => 'required',
							'data-link' => 'menus/typeWiseMenuFilter',
							'data-target' => 'parent_id'
						);

$input['target'] = array(
							"class" => "form-control select2",
							"id" => "target",
							"required" => 'required'
						);

$input['is_custom_constant']  = array(
          "name" => "data[temp_menu][is_custom_constant]",
          "class" => "flat-red",
          "id" => "is_custom_constant",
          "type" => "checkbox",
          "value" => true,
        );

$input['priority'] =  array(
							"name" => "data[temp_menu][priority]",
							"placeholder" => "Priority *",
							"max_length" => "255",
							"required" => "required",
							"class" => "form-control",
							"id" => "priority",
							"type" => "number",
							"min" => 0,
							"value" => 0
						);

$input['is_active'] =  array(
							"name" => "data[temp_menu][is_active]",
							"type" => "checkbox",
							"class" => "flat-red",
							"id" => "is_active",
							"value" => true,
						);

// If form has been submitted with errors populate fields that were already filled
//echo '<pre>';print_r($values_posted);echo '</pre>';exit;
if(isset($values_posted))
{ 
  //print_r($values_posted);

  foreach($values_posted as $post_name => $post_value)
  { 
    foreach ($post_value as $field_key => $field_value) {
      if(isset($input[$field_key]['type']) && $input[$field_key]['type']=="checkbox" && $field_value==true){
        $input[$field_key]['checked'] = "checked";
      }else{
        $input[$field_key]['value'] = $field_value;
      }
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
		<li>
			<?php echo anchor(custom_constants::admin_menu_listing_url, 'Menus', 'title="Menu Listing" id="temp_menu"'); ?>
		</li>
		
	</ol>
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<?php 
			
           	if($this->session->flashdata('message') !== FALSE) {
	            $msg = $this->session->flashdata('message');
	            //print_r($msg);?>
	          	<div class = "<?php echo $msg['class'];?>">
	                <?php echo $msg['message'];?>
	          	</div>
        	<?php } ?>
        	<div class="nav-tabs-custom">
				
				<div class="tab-content">
				
					<?php  
					echo form_open_multipart(custom_constants::edit_menu_url."/".$id, ['class'=>'form-horizontal', 'id'=>'menu'])
					?>
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> Edit Menu</h3>
							</div><!-- /box-header -->
							<!-- form start -->
							<div class="box-body">
								<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputMenu_id" class="col-sm-2 control-label">Menu Type</label>
												<div class="col-sm-10">
													<?php //print_r($option['menu']); ?>
													<?php echo form_dropdown('data[temp_menu][menu_id]', $option['menu'], $values_posted['temp_menu']['menu_id'], $input['menu_id']); ?>
													<?php echo form_error('data[temp_menu][menu_id]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="inputTarget" class="col-sm-2 control-label">Target</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[temp_menu][target]', $target, $values_posted['temp_menu']['target'], $input['target']); ?>
													<?php echo form_error('data[temp_menu][menu_id]'); ?>
												</div>
											</div>
										</div>
									</div>
								<div class ="row">
									<div class="col-md-6">
										<div class="form-group">
											<?php /*echo '<pre>';
											print_r($values_posted);	
											echo '</pre>';*/
											 ?>
											<label for="inputParent_id" class="col-sm-2 control-label">Parent</label>
											<div class="col-sm-10">
													<?php //print_r($option['parent']); ?>

												<?php 
												echo form_dropdown('data[temp_menu][parent_id]', $option['parent'], $values_posted['temp_menu']['parent_id'], 'class="form-control select2" id="parent_id" style="width:100%"');

												//echo form_dropdown('data[news_categories][parent_id]', $parents, $values_posted['news_categories']['parent_id'], "id='parent_id' class='form-control' required='required'"); ?>
												
													
												 <!--select name="data[news_categories][parent_id]" id='parent_id' class="form-control select2">
													<option value="0">Select Parent</option>
													<?php foreach($parents as $parentKey => $parent) {?>
													<option value="<?php echo $parent['id'];?>"<?php if($parent['id']== $values_posted['news_categories']['parent_id']/* && $parent['parent_id'] == $values_posted['news_categories']['parent_id']*/){ echo "selected='selected'";}?>><?php echo $parent['category_name'];?></option>
													<?php } ?>
												</select-->
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputCategoryName" class="col-sm-2 control-label">Category Name</label>
											<div class="col-sm-10">
												<?php echo form_input($input['name']); ?>
												
											      <?php echo form_error('name'); ?>
											</div>
										</div>
									</div>
								</div><!-- /row -->
								<div class="row">
									
									<div class="col-md-6">
										<div class="form-group">                          
											<label for="inputSlug" class="col-sm-2 control-label">Slug</label>
											<div class="col-sm-10">
												<?php echo form_input($input['slug']); ?>
												<?php echo form_error('slug'); ?>
											</div>
										</div>
									</div>
									<div class="col-md-6">
											<div class="form-group">
												<label for="inputClass" class="col-sm-2 control-label">Class</label>
												<div class="col-sm-10">
													<?php 
													echo form_dropdown('data[temp_menu][class]', [''=>'Select', 'cat-red'=>'cat-red', 'cat-blue'=>'cat-blue', 'cat-green'=>'cat-green', 'cat-cyan'=>'cat-cyan', 'cat-orange'=>'cat-orange', 'cat-violet'=>'cat-violet', 'cat-purple'=>'cat-purple'], isset($values_posted)?$values_posted['temp_menu']['class']:'', $input['class']);

													echo form_input($input['class2']);
													 ?>
													<?php echo form_error('data[temp_menu][class]'); ?>
												</div>
											</div>
										</div>
								</div><!-- /row -->
								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputRole" class="col-sm-2 control-label">Role</label>
											<div class="col-sm-10">
												<?php //print_r($values_posted['menu_roles']);?>
												<?php echo form_dropdown("data[menu_roles][role_id][]", $option['roles'],isset($values_posted['menu_roles'])?$values_posted['menu_roles']:'','id="temp_menu" class="form-control select2"  multiple="multiple" ');?>
												<?php echo form_error('data[temp_menu][role_id]');?>
											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputPriority" class="col-sm-2 control-label">Priority</label>
											<div class="col-sm-10">
												<?php echo form_input($input['priority']); ?>
												<?php echo form_error('data[temp_menu][priority]'); ?>
											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputIsDelete" class="col-sm-2 control-label">Is Mega Menu</label>
												<div class="col-sm-10">
													<?php echo form_input($input['is_custom_constant']);?>
													<?php echo form_error('data[temp_menu][is_custom_constant]');?>
												</div>
										</div>
				                    </div>
									<div class="col-md-6">
										<div class="form-group">
											<label for="inputIs_active" class="col-sm-2 control-label">Is Active</label>
											<div class="col-sm-10">
												<?php echo form_input($input['is_active']);?>
												<?php echo form_error('data[temp_menu][is_active]');?>
											</div>
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
					<!-- </div> -->
				</div>
			</div><!-- /nav-tabs-custom -->
        </div>
    </div>
</section>

