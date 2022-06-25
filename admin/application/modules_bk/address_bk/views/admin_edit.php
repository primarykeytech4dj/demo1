<?php 
$input['address_1'] =  array(
							"name" => "data[address][address_1]",
							"placeholder" => "address 1*",
							"required" => "required",
							"class" => "form-control",
							"id" => "address_1",
							"rows" => "5",
							"tab-index" => 3,
							'autocomplete'=>'nope'
						);

$input['address_2'] =  array(
							"name" => "data[address][address_2]",
							"placeholder" => "address 2*",
							"required" => "required",
							"class" => "form-control",
							"id" => "address_2",
							"tab-index" => 4,
							"rows" => "5",
							'autocomplete'=>'nope'
						);
$input['fssi'] =  array(
							"name" => "data[address][fssi]",
							"placeholder" => "FSSI",
							"class" => "form-control",
							"id" => "fssi",
							'autocomplete'=>'nope'
						);
$input['gst_no'] =  array(
							"name" => "data[address][gst_no]",
							"placeholder" => "GST NO",
							"class" => "form-control",
							"id" => "gst_no",
							'autocomplete'=>'nope'
						);
$input['remark'] =  array(
							"name" => "data[address][remark]",
							"placeholder" => "Remark",
							"class" => "form-control",
							"id" => "remark",
							'autocomplete'=>'nope'
						);

$country  = array(
				'id' =>	'country_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter viewInput',
				'data-link' => 'states/getCountrywiseStates',
				'data-target' =>'state_id',
				'input-data-target' =>'country',
				'style' => 'width:100%',
				"tab-index" => 5,
			 );

$state 	=	array(
				'id'	=>	'state_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter viewInput',
				'data-link' => 'cities/getStateWiseCities',
				'data-target' => 'city_id',
				'input-data-target' =>'state',
				'style' => 'width:100%',
				"tab-index" => 6,
				);

$city	= 	array(
				'id' => 'city_id',
				'required' => 'required',
				'class' => 'form-control select2 filter viewInput',
				'data-link' => 'areas/getCityWiseAreas',
				'data-target' => 'area_id',
				'input-data-target' =>'city',
				'style' => 'width:100%',
				"tab-index" => 7,
			);

$area  = array(
				'id' =>	'area_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter viewInput',
				'style' => 'width:100%',
				'input-data-target' => 'area',
				"tab-index" => 8,
			 );

$input['pincode'] = array(
						"name" => "data[address][pincode]",
						"placeholder" => "pincode*",
						"max_length" => "6",
						"required" => "required",
						"class" => "form-control",
						"id" => "pincode",
						"tab-index" => 9,
						'autocomplete'=>'nope'
					);

$input['site_name'] = array(
						"name" => "data[address][site_name]",
						"placeholder" => "Address Short Name / Site Name*",
						"required" => "required",
						"class" => "form-control",
						"id" => "site_name",
						"tab-index" => 11,
						'autocomplete'=>'nope'
					);

$input['is_default'] = array(
						"name" => "data[address][is_default]",
						"class" => "flat-red",
						"id" => "is_default",
						"type" => "checkbox",
						"value" => true,
						"tab-index" => 10
					);
$input['is_active'] = array(
						"name" => "data[address][is_active]",
						"class" => "flat-red",
						"id" => "is_active",
						"type" => "checkbox",
						"value" => true,
						"tab-index" => 13
					);
/*if($address['is_default'])
	$input['is_default']['checked'] = "checked";*/

// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ //print_r($values_posted);
	foreach($values_posted as $post_name => $post_value)
	{
		foreach ($post_value as $field_key => $field_value) {
			if(isset($input[$field_key]['type']) && $input[$field_key]['type']=='checkbox' && $field_value==true){
				$input[$field_key]['checked'] = "checked";
			}else
			$input[$field_key]['value'] = $field_value;
		}
	}
}
?>
<div>
<?php echo form_open_multipart(custom_constants::edit_address_url.'/'.$address['id'], ['class'=>'form-horizontal submit-ajax', 'id'=>'address_'.time(), 'autocomplete'=>'nope']); 
	//print_r($this->session->flashdata('message'));
	if($this->session->flashdata('message') !== FALSE && !isset($module)) {
		$msg = $this->session->flashdata('message');?>
		<div class = "<?php echo $msg['class'];?>">
			<?php /*echo '<pre>';
			print_r($msg['message']);
			echo '</pre>';*/
			if(is_array($msg['message'])){
				echo '<pre>';
				foreach ($msg['message'] as $key => $msg) {
					echo $msg.'<br>';
				}
				echo '</pre>';
			}else{
				echo $msg['message'];
			}
			?>
		</div>
	<?php } ?>
	<input type="hidden" name="url" value="<?php echo !isset($url)?'address/edit_address/'.$address['id']:$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'address':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Address</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="address_type" class="col-sm-2 control-label">Address Belongs Tos</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][type]', $option['typeLists'], $address['type'], "id='address_type' required='required' class='form-control select2 filter' data-link='address/type_wise_user' data-target='user_id' tab-index=1 style='width:100%'");?>
							<?php echo form_error('address_type'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="type" class="col-sm-2 control-label">Select User</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][user_id]', $users, $user_id, "id='user_id' required='required' class='form-control select2' tab-index=2 style='width:100%'");?>
							<?php echo form_error('address_2'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="address_1" class="col-sm-2 control-label">Address_1</label>
						<div class="col-sm-10">
							<?php echo form_textarea($input['address_1']); ?>
							<?php echo form_error('address_1'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="address_2" class="col-sm-2 control-label">Address_2</label>
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
							  <?php echo form_dropdown('data[address][country_id]', $option['countries'], $address['country_id'], $country); ?>
							  <input type="text" name="data[countries][name]" class="form-control input" id="country" placeholder="Country Not found? Enter here" value="<?php isset($values_posted['address']['country_id'])?$values_posted['address']['country_id']:'';?>" style="display: <?php echo (empty($address['country_id']) || $address['country_id']==0)?'block':'none'; ?>" autocomplete="nope">
							  <?php echo form_error('country'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="state_id" class="col-sm-2 control-label">State.</label>
							<div class="col-sm-10">
								
								<?php echo form_dropdown('data[address][state_id]',$option['states'], $address['state_id'],$state);?>
								<input type="text" name="data[states][state_name]" class="form-control input" id="state" style="display: <?php echo (empty($address['state_id']) || $address['state_id']==0)?'block':'none'; ?>" placeholder="State Is Not Listed. Enter here" value="<?php isset($values_posted['address']['state_id'])?$values_posted['address']['state_id']:'';?>" autocomplete="nope">
								<?php echo form_error('state'); ?>
							</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="city_id" class="col-sm-2 control-label">City</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][city_id]', $option['cities'], $address['city_id'], $city); ?>
							<input type="text" name="data[cities][city_name]" class="form-control input" id="city" style="display: <?php echo (empty($address['city_id']) || $address['city_id']==0)?'block':'none'; ?>" placeholder="City Is Not Listed. Enter here" value="<?php isset($values_posted['address']['city_id'])?$values_posted['address']['city_id']:'';?>" autocomplete="nope">
							<?php echo form_error('city'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="area_id" class="col-sm-2 control-label">Area</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][area_id]', $option['areas'], $address['area_id'], $area); ?>
							<input type="text" name="data[areas][area_name]" class="form-control input" id="area" style="display: <?php echo (empty($address['area_id']) || $address['area_id']==0)?'block':'none'; ?>" placeholder="Area Is Not Listed. Enter here" value="<?php isset($values_posted['address']['area_id'])?$values_posted['address']['area_id']:'';?>" autocomplete="nope">
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
							<?php echo form_error('pincode'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="fssi" class="col-sm-2 control-label">FSSAI NO:</label>
							<div class="col-sm-10">
								
								<?php echo form_input($input['fssi']); ?>
								<?php echo form_error('data[address][fssi]'); ?>
							</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="gst_no" class="col-sm-2 control-label">GST NO:</label>
							<div class="col-sm-10">
								
								<?php echo form_input($input['gst_no']); ?>
								<?php echo form_error('data[address][gst_no]'); ?>
							</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="remark" class="col-sm-2 control-label">Remark:</label>
							<div class="col-sm-10">
								
								<?php echo form_input($input['remark']); ?>
								<?php echo form_error('data[address][remark]'); ?>
							</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="is_default" class="col-sm-2 control-label">Set as Default Address</label>
						<div class="col-sm-10">
							<?php echo form_input($input['is_default']); ?>
							<?php echo form_error('is_default'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="site_name" class="col-sm-2 control-label">Short Name <?=(isset($_SESSION['application']['tally']) && $_SESSION['application']['tally']==TRUE)?'/Ledger Name':''?></label>
						<div class="col-sm-10">
							<?php echo form_input($input['site_name']); ?>
							<?php echo form_error('site_name'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="is_default" class="col-sm-2 control-label">Is Active</label>
						<div class="col-sm-10">
							<?php echo form_input($input['is_active']); ?>
							<?php echo form_error('is_active'); ?>
						</div>
					</div>
				</div>
			</div>


			<!-- s --> <!-- /box-body -->  
	                  
		<div class="box-footer">  
			<div class="response"></div>
			<button type="submit" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
	</div><!-- /box -->
	</div>
	<?php echo form_close(); ?> 
</div>