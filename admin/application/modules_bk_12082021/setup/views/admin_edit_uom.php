<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*$name  = array(
				'id' =>	'name',
				'required'	=>	'required',
				'class'	=>	'form-control select2 viewInput',
				'data-target' => 'name_input_0',
				'input-data-target' =>'name_input_0',
				'style' => 'width:100%',
				 //data-target='faq_category_".$faquesKey."'
			 );*/





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
	    Module :: Setup
	</h1>
	<ol class="breadcrumb">
	    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
	    <li>
	      <?php echo anchor(custom_constants::admin_product_listing_url, 'UOM', 'title="UOM"'); ?>
	    </li>
	    <li>
	      <?php echo anchor(custom_constants::new_product_url, 'New UOM'); ?>
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
					<?php echo form_open_multipart('setup/admin_uom_setup', ['class'=>'form-horizontal', 'id'=>'new_product']);
						
						if($this->session->flashdata('message') !== FALSE) {
							$msg = $this->session->flashdata('message');?>
							<div class = "<?php echo $msg['class'];?>">
								<?php echo $msg['message'];?>
							</div>
						<?php } ?>
						<div class="box box-info">
							<div class="box-header with-border">
								<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> New UOM</h3>
							</div><!-- /box-header -->
							<!-- form start -->
							<div class="box-body">
								

								<div class="box-body" style="overflow-x:scroll">
									<table class="table" id="target">
										<thead>
											<tr>
												<th>UOM</th>
												<th>Short Code</th>
												<th>Active</th>
												<th></th>
											</tr>
										</thead>
										<tbody>
										<?php if(count($values_posted)>0){
											foreach ($values_posted as $key => $uom) {
		
														?>
													<tr id="<?=$key?>">
													
													<td><input type="hidden" name="uom[<?=$key?>][id]" value="<?=isset($uom['id'])?$uom['id']:''?>" id="id_<?=$key?>" class="form-control">
														<input type="text" name="uom[<?=$key?>][uom]" class="form-control" id="uom_<?=$key?>" value="<?=$uom['uom']?>"placeholder='UOM Not found? Enter here'>
													</td>
													<td><input type="text" name="uom[<?=$key?>][short_code]" class="form-control" value="<?=$uom['short_code']?>" required id="value_<?=$key?>" ></td>
													<td><input type="checkbox" name="uom[<?=$key?>][is_active]" class="flat-red" id="is_active_<?=$key?>" 
														<?=($uom['is_active'])?'checked="checked"':''?>></td>
													<td></td>
												</tr>	
													<?php }

												}else{?>
												<tr id="0">
													
													<td>
														<input type="text" name="uom[0][uom]" class="form-control" id="uom_0" placeholder='UOM Not found? Enter here'>
													</td>
													<td><input type="text" name="uom[0][short_code]" class="form-control" id="short_code_0"></td>
													<td><input type="checkbox" name="uom[0][is_active]" class="flat-red" id="is_active_0"></td>
													<td></td>
												</tr>
												<?php } ?>
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
									<button type="new_variation" class="btn btn-info pull-left"><?=(count($uom)>0)?'Update':'Add'?> Uom</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
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

