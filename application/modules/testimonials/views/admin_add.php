<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$input['company'] = array(
						"name" => "company_testimonials[company]",
						"placeholder" => "Company *",
						"class"=> "form-control viewInput",
						"id" => "company",

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
	      <?php echo anchor(custom_constants::admin_testimonial_listing_url, 'Testimonials', 'title="products"'); ?>
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
						echo form_open_multipart(custom_constants::new_testimonial_url, ['class'=>'form-horizontal', 'id'=>'new_testimonial']);
							
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
												<?php //print_r($faques);?>
												<?php if(count($testimonials>0)) {
													// count($testimonials);
													//echo "greater than 0";
													foreach ($testimonials as $testimonialKey => $testimonial) {
													//print_r($testimonial['is_active']); ?>
													<tr id="<?php echo $testimonialKey;?>">
														<td><input type="hidden" name="company_testimonials[<?php echo $testimonialKey;?>][id]" value="<?php echo $testimonial['id'];?>" id="id_<?php echo $testimonialKey;?>">
															<input type="text" name="company_testimonials[<?php echo $testimonialKey;?>][name]" class="form-control" id="name_<?php echo $testimonialKey;?>" value="<?php echo $testimonial['name'];?>" ></td>
														<td><input type="text" name="company_testimonials[<?php echo $testimonialKey;?>][company]" class="form-control" id="company_<?php echo $testimonialKey;?>" value="<?php echo $testimonial['company'];?>" >
														</td>
														<td><input type="text" name="company_testimonials[<?php echo $testimonialKey;?>][designation]" class="form-control" id="designation_<?php echo $testimonialKey;?>" value="<?php echo $testimonial['designation'];?>"></td>
														<td>
															<input type="text" name="company_testimonials[<?php echo $testimonialKey;?>][comment]" class="form-control" id="comment_<?php echo $testimonialKey;?>" value="<?php echo $testimonial['comment'];?>" ></td>
															<td><!-- <input type="file" name="company_testimonials[<?php echo $testimonialKey;?>][image]", id="image_<?php echo $testimonialKey;?>", class="form required"> -->
																<input type="file" name="company_testimonials[<?php echo $testimonialKey; ?>][image]", id="image_<?php echo $testimonialKey; ?>", class="form required">
																		<input type="hidden" name="company_testimonials[<?php echo $testimonialKey; ?>][image_2]" id="image_2_<?php echo $testimonialKey; ?>", value= "<?php echo $testimonial['image'];?>"class="form required">
																		<img src="<?php echo !empty($testimonial['image'])?base_url().'assets/uploads/testimonials/'.$testimonial['image']:base_url().'assets/uploads/testimonials/no_image.jpg'; ?>" height="80px" width="80px">
															</td>
															<td><!-- <input type="checkbox" name="company_testimonials[<?php echo $testimonialKey;?>][is_active]" value="true" id="is_active_<?php echo $testimonialKey;?>" class="" > -->
																<input type="checkbox" name="company_testimonials[<?php echo $testimonialKey; ?>][is_active]" value="true" id="is_active_<?php echo $testimonialKey; ?>" class="" <?php if($testimonial['is_active']){ echo "checked=checked";} ?>>
															</td>
														<td></td>
													</tr>
															<?php } 
												}else { echo "hi";?>
												<tr id="0">
														<td><input type="text" name="company_testimonials[0][name]" class="form-control" id="name_0" ></td>
														<td><input type="text" name="company_testimonials[0][company]" class="form-control" id="company_0" >
														</td>
														<td><input type="text" name="company_testimonials[0][designation]" class="form-control" id="designation_0" ></td>
														<td>
															<input type="text" name="company_testimonials[0][comment]" class="form-control" id="comment_0" ></td>
															<td><input type="file" name="company_testimonials[0][image]", id="image_0", class="form required">
															</td>
															<td><input type="checkbox" name="company_testimonials[0][is_active]" value="true" id="is_active_0" class="" ></td>
														<td></td>
													</tr>
												<?php }?>
											</tbody>
											<tfoot>
												<tr>
											   		<td colspan="9"><button type="button" id="AddMoreTestimonial" class="btn btn-info pull-right AddMoreRow">Add More</button>
											   		</td>
											   	</tr>
											</tfoot>
										</table>
									</div>
									
								<div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Add Testimonials</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
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

