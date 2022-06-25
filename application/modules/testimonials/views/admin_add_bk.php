<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$input['faq_category'] = array(
						"name" => "faques[faq_category]",
						"placeholder" => "FAQ Category *",
						"class"=> "form-control viewInput",
						"id" => "faq_category",

					);
//echo "<div class = "for-group" >";
// If form has been submitted with errors populate fields that were already filled
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
	    Module :: Testimonials
	</h1>
	<ol class="breadcrumb">
	    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
	    <li>
	      <?php echo anchor(custom_constants::admin_testimonial_listing_url, 'Testimonials', 'title="testimonials"'); ?>
	    </li>
	    <li>
	      <?php echo anchor(custom_constants::new_testimonial_url, 'New Testimonial'); ?>
	    </li>
	</ol>
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
						echo form_open_multipart(custom_constants::new_faq_url, ['class'=>'form-horizontal', 'id'=>'new_product']);
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> New Testimonial</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									
									<div class="box-haeder with-border">
										<h2 class="box-title">Testimonial</h2>
									</div>
									<div class="box-body" style="overflow-x:scroll">
										<table class="table" id="target">
											<thead>
												<tr>
													<th>Name</th>
													<th>Company</th>
													<th>Designation</th>
													<th>Comment</th>
													<th>Image</th>
													<th>Is Active</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody> 
												<tr id="0">
													<td><input type="text" name="company_testimonials[0][name]" class="form-control" id="name_0" placeholder="Enter Name"></td>
													<td><?php echo form_dropdown("company_testimonials[0][company]", '', '', "id='company_id_0' class='form-control select2 viewInput' style='width:100%'");?></td>
													<td><input type="text" name="company_testimonials[0][designation]" class="form-control" id="designation_0" placeholder="Enter Designation"></td>
													<td><input type="text" name="company_testimonials[0][comment]" class="form-control" id="comment_0" placeholder="Enter Comment"></td>
													<td><input type="file" name="company_testimonials[0][image]" id="image_0"></td>
													<td><input type="checkbox" name="input type="file" name="company_testimonials[0][is_active]" id="is_active_0"></td>
													<td><a href="#" class="removebutton calculate" data-id="0" data-link="testimonials/deleteTestimonialDetails" data-table="company_testimonials"></td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
											   		<td colspan="9"><button type="button" id="AddMoreTestimonials" class="btn btn-info pull-right AddMoreRow">Add More</button>
											   		</td>
											   	</tr>
											</tfoot>
										</table>
									</div>
									
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Add Testimonial</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
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

