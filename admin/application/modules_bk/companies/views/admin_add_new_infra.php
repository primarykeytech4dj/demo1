<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$input['comment'] =  array(
							"name" => "data[company_infrastructures][comment]",
							"placeholder" => "Comment *",
							"required" => "required",
							"class" => "form-control textarea",
							"id" => "comment",
							"rows" => "5",
							"tab-index" => 3,
						);

// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{	
	//print_r($values_posted);

	foreach($values_posted as $post_name => $post_value)
	{ 
		foreach ($post_value as $field_key => $field_value) {
		//print_r($field_value);
			# code...
			$input[$field_key]['value'] = $field_value;
		}
	}
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Add Infrastructure
  </h1>
  
</section>
<!--Main content -->
<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">

				<div class="tab-content">
					
						<?php //echo form_open_multipart(custom_constants::new_user_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
							//print_r($this->session);
						echo form_open_multipart(custom_constants::new_infra_url, ['class'=>'form-horizontal', 'id'=>'new_company']);
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title">New Comment</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									<div class="row">
										<div class="col-md-12">
											<div class="col-md-6">
												<div class="form-group">
													<label for="inputCompany" class="col-sm-2 control-label">Company</label>
													<div class="col-sm-10">
														<?php echo form_dropdown('data[company_infrastructures][company_id]',$option['company'], $company_id," id='company_id_0' class='form-control select2 viewInput' style='width:100%' data-target='media_typeinput_id_0'");?>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group">
												<label for="inputComment" class="col-sm-1 control-label">Comment</label>
												<div class="col-sm-11">
													<?php echo form_textarea($input['comment']); ?>
													<?php echo form_error('comment'); ?>
												</div>
											</div>
										</div>
									</div>
									<hr>
									<div class="box-haeder with-border">
										<h2 class="box-title">Infrastructure Media</h2>
									</div>
									<div class="box-body" style="overflow-x:scroll">
										<table class="table" id="target">
											<thead>
												<tr>
													<th>Media Type</th>
													<th>Image</th>
													<th>Text</th>
													<th>Description</th>
													<th>Is Active</th>
													<th>Action</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												
													

												<tr id="0">
													<td>
														<input type="hidden" name="infrastructure_medias[0][id]" class="form-control" id="id_0" >
														<!-- <input type="textarea" name="company_infrastructures[0][comment]", id="comment_0", class="form required textarea" value=""> -->
														<?php echo form_dropdown('infrastructure_medias[0][media_type]',$media_type, ''," id='media_type_id_0' class='form-control select2 viewInput' style='width:100%' data-target='media_typeinput_id_0'");?>
														
													</td>
													<td><input type="file" name="infrastructure_medias[0][image]" class="form-control" id="image_id_0" >
													</td>
													<td><input type="text" name="infrastructure_medias[0][text]" class="form-control" id="text_id_0" ></td>
													<td><input type="text" name="infrastructure_medias[0][description]" class="form-control" id="description_id_0" ></td>
													
													<td><input type="checkbox" name="infrastructure_medias[0][is_active]" id="is_active_0" value="true" class="form required"></td>
													<td>
														
													</td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
											   		<td colspan="9"><button type="button" id="AddMoreProductImages" class="btn btn-info pull-right AddMoreRow">Add More</button>
											   		</td>
											   	</tr>
											</tfoot>
										</table>
									</div>
								<div class="box-footer">  
									<button type="submit" class="btn btn-info pull-left">New Infrastructure</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
								</div>
								<!-- /.box-footer -->
							</div><!-- /box -->
							</div>
						<?php echo form_close(); ?> 
					
					
					
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

