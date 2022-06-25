<?php 
$input['address_1'] =  array(
							"name" => "data[address][address_1]",
							"placeholder" => "address 1*",
							"required" => "required",
							"class" => "form-control",
							"id" => "address_1",
							"rows" => "5",
							"tab-index" => 3,
						);

$input['address_2'] =  array(
							"name" => "data[address][address_2]",
							"placeholder" => "address 2*",
							"required" => "required",
							"class" => "form-control",
							"id" => "address_2",
							"tab-index" => 4,
							"rows" => "5",
						);
$country  = array(
				'id' =>	'country_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter',
				'data-link' => 'states/getCountrywiseStates',
				'data-target' =>'state_id',
				'style' => 'width:100%',
			 );

$state 	=	array(
				'id'	=>	'state_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter',
				'data-link' => 'cities/getStateWiseCities',
				'data-target' => 'city_id',
				'style' => 'width:100%',
				);

$city	= 	array(
				'id' => 'city_id',
				'required' => 'required',
				'class' => 'form-control select2 filter',
				'data-link' => 'areas/getCityWiseAreas',
				'data-target' => 'area_id',
				'style' => 'width:100%',
			);

$area  = array(
				'id' =>	'area_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter',
				'style' => 'width:100%',
			 );

$input['pincode'] = array(
						"name" => "data[address][pincode]",
						"placeholder" => "pincode*",
						"max_length" => "6",
						"required" => "required",
						"class" => "form-control",
						"id" => "pincode",
						"tab-index" => 9,
					);

$input['site_name'] = array(
						"name" => "data[address][site_name]",
						"placeholder" => "Address Short Name / Site Name*",
						"required" => "required",
						"class" => "form-control",
						"id" => "site_name",
						"tab-index" => 11,
					);

$input['add_to_site'] = array(
						"name" => "data[add_to_site]",
						"class" => "flat-red",
						"id" => "add_to_site",
						"tab-index" => 12,
						"value" => true,
						"type" => "checkbox",
					);

$input['is_default'] = array(
						"name" => "data[address][is_default]",
						"class" => "flat-red",
						"id" => "is_default",
						"type" => "checkbox",
						//"checked" => (TRUE == $address['is_default'])?"checked":'',
						"value" => true,
						"tab-index" => 10
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
	<div class="box box-info">
		<?php echo form_open_multipart('address/add', ['class'=>'form-horizontal', 'id'=>'address']); 
			//print_r($this->session);
			if(!isset($module) && $this->session->flashdata('message') !== FALSE) {
				$msg = $this->session->flashdata('message');?>
				<div class = "<?php echo $msg['class'];?>">
					<?php echo $msg['message'];?>
				</div>
			<?php } ?>
			<input type="hidden" name="url" value="<?php echo !isset($url)?'address/edit_address/':$url; ?>">
			<input type="hidden" name="module" value="<?php echo !isset($module)?'address':$module; ?>">
		<!-- <div class="box-header with-border">
			<h3 class="box-title">Address</h3>
		</div> --><!-- /box-header -->
		<!-- form start -->
		<div class="box-body">
			
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="address_1" class="col-sm-2 control-label">Address 1</label>
						<div class="col-sm-10">
							<?php echo form_textarea($input['address_1']); ?>
							<?php echo form_error('address_1'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="address_2" class="col-sm-2 control-label">Address 2</label>
						<div class="col-sm-10">
							<?php echo form_textarea($input['address_2']); ?>
							<?php echo form_error('address_2'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="country_id" class="col-sm-2 control-label">Country</label>
						<div class="col-sm-10">
							  <!-- <?php echo form_dropdown('data[address][country_id]', $option['countries'], '', "id='country_id'  required='required' class='form-control'"); ?>  -->
							  <?php echo form_dropdown('data[address][country_id]', $option['countries'], '', $country); ?>
							  <?php echo form_error('data[address][country_id]'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="state_id" class="col-sm-2 control-label">State</label>
							<div class="col-sm-10">
								
								<?php echo form_dropdown('data[address][state_id]',$option['states'], '',$state);?>
								<?php echo form_error('data[address][state_id]'); ?>
							</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="city_id" class="col-sm-2 control-label">City</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][city_id]', $option['cities'], '', $city); ?>
							<?php echo form_error('data[address][city_id]'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="area_id" class="col-sm-2 control-label">Area</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][area_id]', $option['areas'], '', $area); ?>
							<?php echo form_error('data[address][area_id]'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="pincode" class="col-sm-2 control-label">Pincode</label>
						<div class="col-sm-10">
							<?php echo form_input($input['pincode']); ?>
							<?php echo form_error('data[address][pincode]'); ?>
						</div>
					</div>
				</div>
				<!-- <div class="col-md-6">
					<div class="form-group">
						<label for="is_default" class="col-sm-2 control-label">Set as Default Address</label>
						<div class="col-sm-10">
							<?php echo form_input($input['is_default']); ?>
							<?php echo form_error('data[address][is_default]'); ?>
						</div>
					</div>
				</div> -->
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="site_name" class="col-sm-2 control-label">Address Short Name</label>
						<div class="col-sm-10">
							<?php echo form_input($input['site_name']); ?>
							<?php echo form_error('data[address][site_name]'); ?>
						</div>
					</div>
				</div>
				<!-- <div class="col-md-6">
					<div class="form-group">
						<label for="add_to_site" class="col-sm-2 control-label">Add To Site</label>
						<div class="col-sm-10">
							<?php echo form_input($input['add_to_site']); ?>
							<?php echo form_error('data[add_to_site]'); ?>
						</div>
					</div>
				</div> -->
			</div>


			<!-- s --> <!-- /box-body -->  
	                  
		<div class="box-footer">  
			<button type="submit" class="btn btn-info pull-left">Add My Address</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="reset" class="btn btn-info">Reset</button>
		</div>
		<!-- /.box-footer -->
	</div><!-- /box -->
	<?php echo form_close(); ?> 
	</div>
