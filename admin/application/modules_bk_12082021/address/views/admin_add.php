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
							//"required" => "required",
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
				 /*data-target='faq_category_".$faquesKey."'*/
			 );

$state 	=	array(
				'id'	=>	'state_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter viewInput',
				'data-link' => 'cities/getStateWiseCities',
				'data-target' => 'city_id',
				'input-data-target' =>'state',
				'style' => 'width:100%',
				);

$city	= 	array(
				'id' => 'city_id',
				'required' => 'required',
				'class' => 'form-control select2 filter viewInput',
				'data-link' => 'areas/getCityWiseAreas',
				'data-target' => 'area_id',
				'input-data-target' =>'city',
				'style' => 'width:100%',
			);

$area  = array(
				'id' =>	'area_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter viewInput',
				'style' => 'width:100%',
				'input-data-target' => 'area',
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
						"class" => "default_site",
						"id" => "is_default",
						"type" => "checkbox",
						//"checked" => (TRUE == $address['is_default'])?"checked":'',
						"value" => true,
						"tab-index" => 10
					);

$input['country_input'] = array(
						"name" => "data[address][country_input]",
						"placeholder" => "Country *",
						"class"=> "form-control viewInput",
						"id" => "country_input",
                        "autocomplete"=>'off'
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
<div>
<?php 
    $formClass = ($this->input->is_ajax_request())?"form-horizontal submit-ajax":"form-horizontal";
	echo form_open_multipart(custom_constants::new_address_url, ['class'=>$formClass, 'id'=>'address_'.time(), 'autocomplete'=>'nope']); 
	//print_r($this->session);
	if(!isset($module) && $this->session->flashdata('message') !== FALSE) {
		$msg = $this->session->flashdata('message');?>
		<div class = "<?php echo $msg['class'];?>">
			<?php echo $msg['message'];?>
		</div>
	<?php } ?>
	<input type="hidden" name="url" value="<?php echo !isset($url)?'address/edit_address/':$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'address':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title">Address</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body">
			<!-- <?php if(isset($err)){ ?>
			<div class="alert alert-danger alert-dismissible">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
				<h4><i class="icon fa fa-ban"></i> Alert!</h4>
				<?php echo $this->session->flashdata('err'); ?>
			</div>
			<?php } ?>
			<div class="box-header with-border">
				<h3 class="box-title">Address</h3>
			</div> -->
			<?php if(isset($type) && $type!='' && isset($user_id) && $user_id!=''){ ?>
			<input type="hidden" name="data[address][type]" value="<?=$type?>" id="address_type">
			 <input type="hidden" name="data[address][user_id]" value="<?=$user_id?>" id="user_id">
			 <?php  }else{ ?>
			<div class="row">
			    
				<div class="col-md-6">
					<div class="form-group">
						<label for="address_type" class="col-sm-2 control-label">Address Belongs To</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][type]', $option['typeLists'], $type, "id='address_type' required='required' class='form-control select2 filter' data-link='address/type_wise_user' data-target='user_id' tab-index=1 style='width:100%'");?>
							<?php echo form_error('address_type'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="type" class="col-sm-2 control-label">Select User</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][user_id]', $users, $user_id, "id='user_id' required='required' class='form-control select2' style='width:100%'");?>
							<?php echo form_error('address_2'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<?php } ?>
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
							  <?php echo form_dropdown('data[address][country_id]', $option['countries'], '1', $country); ?>
							  <!--<input type="text" name="data[countries][name]" class="form-control input" id="country" placeholder="Country Not found? Enter here" autocomplete="nope">-->
							  <?php echo form_error('data[address][country_id]'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="state_id" class="col-sm-2 control-label">State.</label>
							<div class="col-sm-10">
								
								<?php echo form_dropdown('data[address][state_id]',$option['states'], '1',$state);?>
								<!--<input type="text" name="data[states][state_name]" class="form-control input" id="state" style="display:block;?>" placeholder="State Is Not Listed. Enter here" autocomplete="nope">-->
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
							<?php echo form_dropdown('data[address][city_id]', $option['cities'], '1', $city); ?>
							<!--<input type="text" name="data[cities][city_name]" class="form-control input" id="city" style="display:block;" placeholder="City Is Not Listed. Enter here" autocomplete="nope">-->
							<?php echo form_error('data[address][city_id]'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="area_id" class="col-sm-2 control-label">Area</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[address][area_id]', $option['areas'], '', $area); ?>
							<input type="text" name="data[areas][area_name]" class="form-control input" id="area" style="display:block;" placeholder="Area Is Not Listed. Enter here" autocomplete="nope">
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
							<?php echo form_error('data[address][is_default]'); ?>
						</div>
					</div>
				</div>
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
				<?php if($isCustomerSiteModuleExists && !$this->input->is_ajax_request()){ ?>
				<div class="col-md-6">
					<div class="form-group">
						<label for="add_to_site" class="col-sm-2 control-label">Add To Site</label>
						<div class="col-sm-10">
							<?php echo form_input($input['add_to_site']); ?>
							<?php echo form_error('data[add_to_site]'); ?>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>


			<!-- s --> <!-- /box-body -->  
	                  
		<div class="box-footer">  
			<div class="response"></div>
			<button type="submit" class="btn btn-info pull-left">Register</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
	</div><!-- /box -->
	</div>
	<?php echo form_close(); ?> 
</div>

<script type="text/javascript">
	$('#is_default').on('click', function(){
		//alert("hello");
		if($('#is_default:checkbox:checked').length > 0){
			$("#site_name").val($('#company_name').val());
		}else{
			$("#site_name").val('');
		}
	});
</script>