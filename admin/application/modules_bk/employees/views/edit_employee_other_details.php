<?php
$tab = "basic_detail";
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//echo $values_posted['data']['employees_salaries']['employment_end_date'];
//echo date('d/m/Y',strtotime($values_posted['data']['employees_salaries']['employment_end_date']));
$startDate = '';
if(isset($values_posted['data']['employees_salaries']['employment_start_date']))
$startDate = date('d/m/Y',strtotime($values_posted['data']['employees_salaries']['employment_start_date']));

$endDate = '';
if(isset($values_posted['data']['employees_salaries']['employment_end_date']) && $values_posted['data']['employees_salaries']['employment_end_date']!='0000-00-00 00:00:00')
	$endDate = date('d/m/Y',strtotime($values_posted['data']['employees_salaries']['employment_end_date']));
//echo "<div class = "for-group" >";
$input['employment_start_date'] = array(
						"name" => "data[employees_salaries][employment_start_date]",
						"placeholder" => "start Date *",
						"max_length" => "64",
						"required" => "required",
						"class"=> "form-control datepicker datemask",
						"id" => "employment_start_date",
						"value" => $startDate,
					);

$input['employment_end_date'] = array(
						"name" => "data[employees_salaries][employment_end_date]",
						"placeholder" => "End Date",
						"max_length" => "64",
						"class" => "form-control datepicker datemask",
						'id' => "employment_end_date",
						"value" => $endDate,
					);

$input['salary'] = array(
							'name' => "data[employees_salaries][salary]",
							'placeholder'=> "Salary *",
							"max_length" =>"64",
							"required" =>"required",
							"class" =>"form-control",
							"id" => "salary",
							'type' => 'number',
							 );

$input['provident_fund'] = array(
						"name" => "data[employees_salaries][provident_fund]",
						"placeholder" => "Provident Fund *",
						"required" => "required",
						"class" => "form-control",
						'id' => "emp_code",
						'type' => 'number',
						'min' => '0'
					);

$input['esic'] =  array(
							"name" => "data[employees_salaries][esic]",
							"placeholder" => "ESIC *",
							"max_length" => "12",
							"required" => "required",
							"class" => "form-control",
							"id" => "esic",
							'type' => 'number',
							 );

$input['professional_tax'] =  array(
							"name" => "data[employees_salaries][professional_tax]",
							"placeholder" => "Professional tax",
							"max_length" => "12",
							"class" => "form-control",
							"id" => "professional_tax",
							'type' => 'number',
							'required' => 'required',
							 );

$input['is_active'] = array(
						"name" => "data[employees_salaries][is_active]",
						"class" => "flat-red",
						"id" => "allow_login",
						"type" => "checkbox",
						"value" => true,
					);

if(isset($values_posted['data']['employees_salaries']['is_active']) && $values_posted['data']['employees_salaries']['is_active']==true){
	$input['is_active']['checked'] = "checked";
}
unset($values_posted['data']['employees_salaries']['employment_start_date']);
unset($values_posted['data']['employees_salaries']['employment_end_date']);
//print_r($input['employment_start_date']);exit;
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted) && !empty($values_posted['data']['employees_salaries']))
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

<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">
				
				<div class="tab-content">
					<div class="tab-pane <?php if($tab=="basic_detail"){echo "active";} ?>" id="basic_detail"> 
						<?php echo form_open_multipart('employees/edit_employee_other_details', ['class'=>'form-horizontal', 'id'=>'register_user']); 
							 ?>
							<input type="hidden" name="data[employees_salaries][id]" value="<?php echo !set_value('data[employees_salaries][id]')?$employeeOtherDetails['id']:set_value('data[employees_salaries][id]'); ?>">
							<input type="hidden" name="data[employees_salaries][employee_id]" value="<?php echo !set_value('data[employees_salaries][employee_id]')?$employee_id:set_value('data[employees_salaries][employee_id]'); ?>">
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">Employee Other Details</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="employment_start_date" class="col-sm-2 control-label">Start date</label>
												<div class="input-group date">
									                <div class="input-group-addon">
									                	<i class="fa fa-calendar"></i>
									                </div>
													<?php echo form_input($input['employment_start_date']); ?>
													<?php echo form_error('data[employees_salaries][employment_start_date]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">                          
												<label for="employment_end_date" class="col-sm-2 control-label">End Date</label>
												<div class="input-group date">
									                <div class="input-group-addon">
									                	<i class="fa fa-calendar"></i>
									                </div>
													<?php echo form_input($input['employment_end_date']); ?>
													<?php echo form_error('data[employees_salaries]employment_end_date'); ?>
												</div>
											</div>
										</div>
										
									</div><!-- /row -->
									<div class="row">
									<div class="col-md-6">
											<div class="form-group">
												<label for="salary" class="col-sm-2 control-label">Salary</label>
												<div class="col-sm-10">
													<?php echo form_input($input['salary']); ?>
													<?php echo form_error('data[employees_salaries][salary]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="provident_fund" class="col-sm-2 control-label">Provident Fund</label>
												<div class="col-sm-10">
													<?php echo form_input($input['provident_fund']); ?>
													<?php echo form_error('data[employees_salaries][provident_fund]'); ?>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="esic" class="col-sm-2 control-label">ESIC</label>
												<div class="col-sm-10">
													<?php echo form_input($input['esic']); ?>
													<?php echo form_error('data[employees_salaries][esic]'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
								                <label for="professional_tax"  class="col-sm-2 control-label">Professional Tax:</label>

								                <div class="col-sm-10">
								                  	<?php echo form_input($input['professional_tax']);?>
													<?php echo form_error('data[employees_salaries][professional_tax]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
									</div><!-- /row -->
									
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
								                <label for="allow_login" class="col-sm-2 control-label">Is Active</label>
								                <div class="col-sm-10">
								                  	<?php echo form_input($input['is_active']);?>
													<?php echo form_error('data[employees_salaries][is_active]'); ?>
								                </div>
								                <!-- /.input group -->
								            </div>
										</div>
										
									</div><!-- /row -->
								            
								<div class="box-footer">  
									<button type="submit" class="btn btn-info pull-left">Save</button> &nbsp;&nbsp;&nbsp;&nbsp;
									<?php //echo nsp(3); ?>
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

