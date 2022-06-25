<?php 
$input['bank_name'] =  array(
							"name" => "data[bank_accounts][bank_name]",
							"placeholder" => "Bank Name*",
							"required" => "required",
							"class" => "form-control",
							"id" => "bank_name",
							"tab-index" => 3,
						);

$input['account_name'] =  array(
							"name" => "data[bank_accounts][account_name]",
							"placeholder" => "Account Name*",
							"required" => "required",
							"class" => "form-control",
							"id" => "account_name",
							"tab-index" => 4,
						);

$input['account_number'] =  array(
							"name" => "data[bank_accounts][account_number]",
							"placeholder" => "Account Number*",
							"required" => "required",
							"class" => "form-control",
							"id" => "account_number",
							"tab-index" => 5,
						);
$input['ifsc_code'] =  array(
							"name" => "data[bank_accounts][ifsc_code]",
							"placeholder" => "IFSC Code*",
							"required" => "required",
							"class" => "form-control",
							"id" => "ifsc_code",
							"tab-index" => 6,
						);
$input['branch'] =  array(
							"name" => "data[bank_accounts][branch]",
							"placeholder" => "branch*",
							"required" => "required",
							"class" => "form-control",
							"id" => "branch",
							"tab-index" => 7,
						);
$input['is_default'] = array(
						"name" => "data[bank_accounts][is_default]",
						"class" => "flat-red",
						"id" => "is_default",
						"type" => "checkbox",
						"value" => true,
						"tab-index" => 8,
					);
$input['is_active'] = array(
						"name" => "data[bank_accounts][is_active]",
						"class" => "flat-red",
						"id" => "is_active",
						"type" => "checkbox",
						"value" => true,
						"tab-index" => 9
					);
/*$userId  = array(
				'id' =>	'user_id',
				'required'	=>	'required',
				'class'	=>	'form-control select2',
				"tab-index" => 2,
			 );

$userType 	=	array(
				'id'	=>	'user_type',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter',
				'data-link' => 'bank_accounts/type_wise_user',
				'data-target' => 'city_id',
				"tab-index" => 1,
				);

$accountType 	=	array(
				'id'	=>	'account_type',
				'required'	=>	'required',
				'class'	=>	'form-control select2 filter',
				"tab-index" => 1,
				);*/

//print_r($values_posted);
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted['data']) && !empty($values_posted['data']))
{ //print_r($values_posted);
	foreach($values_posted['data'] as $post_name => $post_value)
	{ //print_r($post_value);
		foreach ($post_value as $field_key => $field_value) {
			if(isset($input[$field_key]['type']) && $input[$field_key]['checkbox']){
				$input[$field_key]['checked'] = 'checked';
		}else
			$input[$field_key]['value'] = $field_value;
		}
	}
}
?>
<div>
<?php echo form_open_multipart(custom_constants::new_bank_account_url, ['class'=>'form-horizontal', 'id'=>'bank_accounts']); 
	//print_r($this->session);
	if(!isset($module) && $this->session->flashdata('message') !== FALSE) {
		$msg = $this->session->flashdata('message');?>
		<div class = "<?php echo $msg['class'];?>">
			<?php echo $msg['message'];?>
		</div>
	<?php } ?>

	<?php 
	/*$url = !isset($url)?'bank_accounts/edit_account/':$url;
	if(set_value('url'))
		$url = set_value('url');*/
	 ?>
	<input type="hidden" name="url" value="<?php echo !isset($url)?'bank_accounts/edit_account/':$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'bank_accounts':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-institution margin-r-5"></i> Bank Account</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body">
			
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="account_belongs_to" class="col-sm-2 control-label">Account Belongs To</label>
						<div class="col-sm-10">
							<?php 
							$addressType = $type;
							if(set_value('data[bank_accounts][user_type]'))
								$addressType = set_value('data[bank_accounts][user_type]');
							echo form_dropdown('data[bank_accounts][user_type]', $option['typeLists'], $addressType, "id='account_belongs_to' required='required' class='form-control select2 filter' data-link='bank_accounts/type_wise_user' data-target='account_user_id' tab-index=1 style='width:100%'");?>
							<?php echo form_error('data[bank_accounts][user_type]'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="user_id" class="col-sm-2 control-label">Select User</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[bank_accounts][user_id]', $users, $user_id, "id='account_user_id' required='required' class='form-control select2' style='width:100%'");?>
							<?php echo form_error('data[bank_accounts][user_id]'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="bank_name" class="col-sm-2 control-label">Bank Name</label>
						<div class="col-sm-10">
							<?php echo form_input($input['bank_name']); ?>
							<?php echo form_error('data[bank_accounts][bank_name]'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="account_name" class="col-sm-2 control-label">Account Name</label>
						<div class="col-sm-10">
							<?php echo form_input($input['account_name']); ?>
							<?php echo form_error('data[bank_accounts][account_name]'); ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="account_number" class="col-sm-2 control-label">Account Number</label>
						<div class="col-sm-10">
							<?php echo form_input($input['account_number']); ?>
							<?php echo form_error('data[bank_accounts][account_number]'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="account_type" class="col-sm-2 control-label">Account Type</label>
						<div class="col-sm-10">
							<?php 
							echo form_dropdown('data[bank_accounts][account_type]', $option['accountTypes'], isset($values_posted['data']['bank_accounts']['account_type'])?$values_posted['data']['bank_accounts']['account_type']:'', "id='account_type' required='required' class='form-control select2' style='width:100%'");?>
							<?php echo form_error('data[bank_accounts][account_type]'); ?>
							
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="ifsc_code" class="col-sm-2 control-label">IFSC Code</label>
						<div class="col-sm-10">
							<?php echo form_input($input['ifsc_code']); ?>
							<?php echo form_error('data[bank_accounts][ifsc_code]'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="branch" class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-10">
							<?php echo form_input($input['branch']); ?>
							<?php echo form_error('data[bank_accounts][branch]'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="is_default" class="col-sm-2 control-label">Set as Default Account</label>
						<div class="col-sm-10">
							<?php echo form_input($input['is_default']); ?>
							<?php echo form_error('data[bank_accounts][is_default]'); ?>
						</div>
					</div>
				</div>
				
				<div class="col-md-6">
					<div class="form-group">
						<label for="branch" class="col-sm-2 control-label">Is active</label>
						<div class="col-sm-10">
							<?php echo form_input($input['is_active']); ?>
							<?php echo form_error('data[bank_accounts][is_active]'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<!-- s --> <!-- /box-body -->  
	    </div>              
		<div class="box-footer">  
			<button type="new_college" class="btn btn-info pull-left">Register</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
	</div><!-- /box -->
	
	<?php echo form_close(); ?> 
</div>