<?php 
							
$input['comment'] =  array(
							"name" => "data[company_infrastructures][comment]",
							"placeholder" => "Comments ",
							"required" => "required",
							"class" => "form-control textarea",
							"id" => "comment",
							"rows" => "5",
							"tab-index" => 3,
						);
?>
<div class="box box-info">
<?php echo form_open_multipart('companies/admin_edit_infrastructure/'.$id, ['class'=>'form-horizontal', 'id'=>'edit_infrastructure']);?>
		<!-- <input type="hidden" name="product_id" value="<?php echo $id; ?>"> -->
		<div class="box-header with-border">
			<h3 class="box-title">Existing Company's Infrastructure</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body">
			
			<div class="box-haeder with-border">
				<h2 class="box-title">Company's Infrastructure</h2>
			</div>
			<div class="box-body" style="overflow-x:scroll">
				<table class="table" id="target">
					<thead>
						<tr>
							<th>Comment</th>
							<th>Is Active</th>
							<th>Action</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php //print_r($values_posted['company_infrastructures']);?>
							<?php foreach($infrastructures as $infraKey =>$infra) {
								?>

						<tr id="<?php echo $infraKey; ?>">
							<td>
								<input type="text" name="company_infrastructures[<?php echo $infraKey;?>][id]" class="form-control" id="id_<?php echo $infraKey;?>" value="<?php echo $infra['id']; ?>">
								<input type="textarea" name="company_infrastructures[<?php echo $infraKey; ?>][comment]", id="comment_<?php echo $infraKey; ?>", class="form required textarea" value="<?php echo $infra['comment']; ?>">
							</td>
							
							<td><input type="checkbox" name="company_infrastructures[<?php echo $infraKey; ?>][is_active]" id="is_active_<?php echo $infraKey; ?>", <?php if($infra['is_active']){echo "checked='checked'";}?> class="form required"></td>
							<td><?php if($infraKey>0) {?>
																	<a href="#" class="removebutton calculate" data-id="<?php echo $infra['id']; ?>" data-link='companies/deleteInfraDetails' data-table='company_infrastructures'>
																	 <span class="glyphicon glyphicon-trash"></span>
																</a>
																<?php }?></td>
						</tr>
							<?php } ?>
					</tbody>
					<tfoot>
						<tr>
					   		<td colspan="9"><button type="button" id="AddMoreProductImages" class="btn btn-info pull-right AddMoreRow">Add More</button>
					   		</td>
					   	</tr>
					</tfoot>
				</table>
			</div>
			<hr>
			<!-- <?php echo form_open_multipart('companies/admin_edit_medias/'.$id, ['class'=>'form-horizontal', 'id'=>'edit_product_images']);?> -->

			<div class="box-haeder with-border">
				<h2 class="box-title">Infrastructure Media</h2>
			</div>
			<div class="box-body" style="overflow-x:scroll">
				<table class="table" id="target">
					<thead>
						<tr>
							<th>Media Type</th>
							<th>Text</th>
							<th>Description</th>
							<th>Is Active</th>
							<th>Action</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php //print_r($values_posted['company_infrastructures']);?>
							<!-- <?php foreach($infrastructures as $infraKey =>$infra) {
								?> -->

						<tr id="0">
							<td>
								<input type="text" name="infrastructure_medias[0][id]" class="form-control" id="id_0" value="">
								<select name="infrastructure_medias[0][media_type]"" id="media_type_0" class="form-control" required="required">
									<option value="0">Select Media Type</option>
									<option value="1">Image</option>
									<option value="2">Link</option>
									<option value="3">YouTube Url</option>

								</select>
							</td>
							<td><input type="text" name="infrastructure_medias[0][text]" id="text_0" class="form-control" value=""></td>
							<td><input type="text" name="infrastructure_medias[0][description]" id="description_0" class="form-control" value=""></td>

							<td><input type="checkbox" name="company_infrastructures[0][is_active]" id="is_active_0", sclass="form required"></td>
							<td></td>
						</tr>
							<!-- <?php } ?> -->
					</tbody>
					<tfoot>
						<tr>
					   		<td colspan="9"><button type="button" id="AddMoreProductImages" class="btn btn-info pull-right AddMoreRow">Add More</button>
					   		</td>
					   	</tr>
					</tfoot>
				</table>
			</div>
			<!-- <?php echo form_close(); ?>  -->

		</div><!-- /box body-->
		<div class="box-footer">  
			<button type="new_product" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
<?php echo form_close(); ?> 
</div>