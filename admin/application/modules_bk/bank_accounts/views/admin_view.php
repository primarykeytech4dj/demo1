<div>
<?php echo form_open_multipart(custom_constants::address_url, ['class'=>'form-horizontal', 'id'=>'address']); 
							//print_r($this->session);
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
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
													<?php echo form_dropdown('data[address][country_id]', $option['countries'], '', "id='country_id'  required='required' class='form-control'"); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="state_id" class="col-sm-2 control-label">State.</label>
													<div class="col-sm-10">
														<?php echo form_dropdown('data[address][state_id]',$option['states'], '', "id='state_id' required='required' class='form-control'");
														?>
														<?php //echo form_error('state'); ?>
													</div>
											</div>
										</div>
									</div><!-- /row -->
									<div class="row">
										<div class="col-md-6">
											<div class="form-group">
												<label for="city_id" class="col-sm-2 control-label">City</label>
												<div class="col-sm-10">
													<?php //$city = array('mumbai' ,'delhi' ); ?>
													<?php //$js = 'id = "city" class ="form-control"'; ?>
													<?php echo form_dropdown('data[address][city_id]', $option['cities'], '', "id='city_id'  required='required' class='form-control'"); ?>
													<?php //echo form_error('city'); ?>
												</div>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group">
												<label for="area_id" class="col-sm-2 control-label">Area</label>
												<div class="col-sm-10">
													<?php echo form_dropdown('data[address][area_id]', $option['areas'], '', "id='area_id'  required='required' class='form-control'"); ?>
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
									</div><!-- /row -->


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
</div>