<?php 
$input['bank_name'] =  array(
							"name" => "data[bank_accounts][bank_name]",
							"placeholder" => "Bank Name*",
							"required" => "required",
							"class" => "form-control",
							"id" => "bank_name",
							"tab-index" => 3,
						);

$input['account_number'] =  array(
							"name" => "data[bank_accounts][account_number]",
							"placeholder" => "Account Number*",
							"required" => "required",
							"class" => "form-control",
							"id" => "account_number",
							"tab-index" => 4,
						);

$input['ifsc_code'] =  array(
							"name" => "data[bank_accounts][ifsc_code]",
							"placeholder" => "IFSC Code*",
							"required" => "required",
							"class" => "form-control",
							"id" => "ifsc_code",
							"tab-index" => 5,
						);
$input['branch'] =  array(
							"name" => "data[bank_accounts][branch]",
							"placeholder" => "branch*",
							"required" => "required",
							"class" => "form-control",
							"id" => "branch",
							"tab-index" => 6,
						);

$input['is_active'] = array(
						"name" => "data[bank_accounts][is_active]",
						"class" => "flat-red",
						"id" => "is_default",
						"type" => "checkbox",
						"value" => true,
					);

$input['is_default'] = array(
						"name" => "data[bank_accounts][is_default]",
						"class" => "flat-red",
						"id" => "is_default",
						"type" => "checkbox",
						"value" => true,
					);
if($bank_accounts['is_active'])
	$input['is_active']['checked'] = "checked";

if($bank_accounts['is_default'])
	$input['is_default']['checked'] = "checked";

// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ //print_r($values_posted);
	foreach($values_posted['data'] as $post_name => $post_value)
	{
		foreach ($post_value as $field_key => $field_value) {
			# code...
			$input[$field_key]['value'] = $field_value;
		}
	}
}
?>
<div>
<?php echo form_open_multipart('bank_accounts/edit_account/'.$bank_accounts['id'], ['class'=>'form-horizontal', 'id'=>'address']); 
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
	<input type="hidden" name="url" value="<?php echo !isset($url)?'bank_accounts/edit_account/'.$bank_accounts['id']:$url; ?>">
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
						<label for="user_type" class="col-sm-2 control-label">Account Belongs To</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[bank_accounts][user_type]', $option['typeLists'], $bank_accounts['user_type'], "id='user_type' required='required' class='form-control select2 filter' data-link='address/type_wise_user' data-target='user_id' tab-index=1");?>
							<?php echo form_error('user_type'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="type" class="col-sm-2 control-label">Select User</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[bank_accounts][user_id]', $users, $user_id, "id='user_id' required='required' class='form-control select2' tab-index=2");?>
							<?php echo form_error('account_number'); ?>
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
							<?php echo form_error('bank_name'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="account_number" class="col-sm-2 control-label">Account Number</label>
						<div class="col-sm-10">
							<?php echo form_input($input['account_number']); ?>
							<?php echo form_error('account_number'); ?>
						</div>
					</div>
				</div>
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="account_type" class="col-sm-2 control-label">Account Type</label>
						<div class="col-sm-10">
							<?php echo form_dropdown('data[bank_accounts][account_type]', $option['accountTypes'], $values_posted['data']['bank_accounts']['account_type'], "id='account_type' required='required' class='form-control select2'");?>
							<?php echo form_error('account_type'); ?>
							
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="ifsc_code" class="col-sm-2 control-label">IFSC Code</label>
						<div class="col-sm-10">
							<?php echo form_input($input['ifsc_code']); ?>
							<?php echo form_error('ifsc_code'); ?>
						</div>
					</div>
				</div>
				
			</div><!-- /row -->
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label for="branch" class="col-sm-2 control-label">Branch</label>
						<div class="col-sm-10">
							<?php echo form_input($input['branch']); ?>
							<?php echo form_error('branch'); ?>
						</div>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label for="is_default" class="col-sm-2 control-label">Set as Default Account</label>
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
						<label for="is_active" class="col-sm-2 control-label">Active / Inactive</label>
						<div class="col-sm-10">
							<?php echo form_input($input['is_active']); ?>
							<?php echo form_error('is_active'); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- /box-body -->  
	                  
		<div class="box-footer">  
			<button type="new_college" class="btn btn-info pull-left">Register</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
	</div><!-- /box -->
	</div>
	<?php echo form_close(); ?> 
</div>