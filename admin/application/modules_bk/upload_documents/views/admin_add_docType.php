<?php 
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted['data']) && !empty($values_posted['data']))
{ //print_r($values_posted);
	foreach($values_posted['data'] as $post_name => $post_value)
	{ //print_r($post_value);
		foreach ($post_value as $field_key => $field_value) {
			# code...
			$input[$field_key]['value'] = $field_value;
		}
	}
}

?>
<div>
<?php echo form_open_multipart(custom_constants::new_document_type_url, ['id'=>'new_doc_type']); 
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
	<input type="hidden" name="url" value="<?php echo !isset($url)?'upload_documents/new_document/':$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'upload_documents':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-book margin-r-5"></i> Document Upload</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body" style="overflow-x: scroll;">
			<table class="table" id="target">
				<thead>
					<tr>
					  <th>Document Type</th>
					  <th>Document</th>
					  <th>IS Active</th>
					  <th>Action</th>
					</tr>
				</thead>
				<tbody>
				 <?php //print_r($userDocuments);
				$count = count($documents);
				if($count>0){
				foreach ($documents as $documentKey => $document) { ?>
					<tr id="<?php echo $documentKey; ?>">
						<td>
							<input type="hidden" name="documents[<?php echo $documentKey; ?>][id]" value="<?php echo $document['id'];?>" id="id_<?php echo $documentKey;?>">
							<input type="text" name="documents[<?php echo $documentKey;?>][doc_type]" id="doc_type_<?php echo $documentKey; ?>" value="<?php echo $document['doc_type'] ?>">
							
						
						</td>
						<td>
							<input type="text" name="documents[<?php echo $documentKey; ?>][name]" id="name_<?php echo $documentKey; ?>" value="<?php echo $document['name']; ?>">
						</td>
						
						<td>
							<input type="checkbox" name="documents[<?php echo $documentKey; ?>][is_active]" value="true" id="is_active_<?php echo $documentKey; ?>" class="" <?php if($document['is_active']){ echo "checked=checked";} ?>>
						</td>
						<td>
						<?php 
						if($documentKey>0){ ?>
							<a href="#button" class="removebutton" data-id="<?php echo $document['id']; ?>" data-link="upload_documents/deactivate_document/<?php echo $document['id']; ?>"><span class="glyphicon glyphicon-trash"></span></a>
						<?php 
						} ?>
						</td>
					</tr>
					<?php
					}
					}else{ ?>
					<tr id="0">
						<td>
							<input type="text" name="documents[0][doc_type]" id="doc_type_0">
						</td>
						<td>
							<input type="text" name="documents[0][name]" id="name_0">
							
						</td>
						<td><input type="checkbox" name="documents[0][is_active]" value="true" id="is_active_0" checked="checked"></td>
						<td></td>
					</tr>
				<?php } ?>
				</tbody>  
				<tfoot>
				   	<tr>
				   		<td colspan="7"><button type="button" id="AddMoreDocument" class="btn btn-info pull-right AddMoreRow">Add More</button>
				   		</td>
				   	</tr>
				</tfoot>
            </table>
			
			
			<!-- s --> <!-- /box-body -->  
	    </div>              
		<div class="box-footer">  
			<button type="submit" class="btn btn-info pull-left">Submit</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
	</div><!-- /box -->
	<?php echo form_close(); ?> 
</div>
