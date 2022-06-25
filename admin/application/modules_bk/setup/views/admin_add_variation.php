<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$name  = array(
				'id' =>	'name',
				'required'	=>	'required',
				'class'	=>	'form-control select2 viewInput',
				'data-target' => 'name_input_0',
				'input-data-target' =>'name_input_0',
				'style' => 'width:100%',
				 /*data-target='faq_category_".$faquesKey."'*/
			 );

$color  = array(
				'id' =>	'color',
				'required'	=>	'required',
				'class'	=>	'form-control select2',
				'style' => 'width:100%',
				 /*data-target='faq_category_".$faquesKey."'*/
			 );
$input['value'] =  array(
							"name" => "data[variations][value]",
							"placeholder" => "Value *",
							"required" => "required",
							"class" => "form-control",
							"id" => "value",
							"tab-index" => 2,
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
	    Module :: Variations
	</h1>
	<ol class="breadcrumb">
	    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
	    <li>
	      <?php echo anchor(custom_constants::admin_product_listing_url, 'Variation', 'title="products"'); ?>
	    </li>
	    <li>
	      <?php echo anchor(custom_constants::new_product_url, 'New Variation'); ?>
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
						echo form_open_multipart('setup/admin_add_variation', ['class'=>'form-horizontal', 'id'=>'new_product']);
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> New Variation</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									

									<div class="box-body" style="overflow-x:scroll">
										<table class="table" id="target">
											<thead>
												<tr>
													<th>Select Variation</th>
													<th>Value</th>
													<th></th>
												</tr>
											</thead>
											<tbody>
												<tr id="0">

													<td><?php echo form_dropdown("variations[0][name]", $option['variation'],'',$name); ?>
														<input type="text" name="variations[0][name_input]" class="form-control input" id="name_input_0" placeholder='Variation Not found? Enter here'>
													</td>
													<td><input type="text" name="variations[0][value]" class="form-control" id="value_0"></td>
													<td></td>
												</tr>
											</tbody>
											<tfoot>
												<tr>
											   		<td colspan="9"><button type="button" id="AddMoreVariation" class="btn btn-info pull-right AddMoreRow">Add More</button>
											   		</td>
											   	</tr>
											</tfoot>
										</table>
									</div>
									
								<div class="box-footer">  
									<button type="new_variation" class="btn btn-info pull-left"><?=(count($variations)>0)?'Update':'Add'?> Variation</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
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

