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
<?php echo form_open_multipart('products/update_stock/'.$company_id, ['class'=>'form-horizontal', 'id'=>'update_stock']); 
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
	<input type="hidden" name="url" value="<?php echo !isset($url)?'products/update_stock/':$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'products':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-pencil margin-r-5"></i> Quick Product Updation</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body" style="overflow-x: scroll;">
			<table class="table" id="target">
				<thead>
					<tr>
					  <th>Sr No</th>
					  <th>Product</th>
					  <th>Base Price</th>
					  <th>Attributes</th>
					  
					  <th>In stock Qty</th>
					  <th>Show On Website/app</th>
					  <th>On Sale</th>
					  <th>Action</th>
					</tr>
				</thead>
				<tbody>
				 <?php //print_r($userDocuments);
				$count = count($products);
				if($count>0){
				foreach ($products as $productKey => $product) { 
					$input['show_on_website'] = ['type'=>'checkbox', 'name'=>'data[products]['.$productKey.'][show_on_website]', 'class'=>'flat-red'];
					if($product['show_on_website']==true){
						$input['show_on_website']['checked'] = 'checked';
					}

					$input['is_sale'] = ['type'=>'checkbox', 'name'=>'data[products]['.$productKey.'][is_sale]', 'class'=>'flat-red'];
					if($product['is_sale']==true){
						$input['is_sale']['checked'] = 'checked';
					}
					?>
					<tr id="<?=$productKey?>">
						<td><?=$productKey+1?></td>
						<td>
							<?=form_hidden(['data[products]['.$productKey.'][id]'=>$product['id']])?>
							<?=form_hidden(['data[product_details]['.$productKey.'][id]'=>$product['detail_id']])?>
							<?=$product['product']?>
						</td>
						<td><?=form_input(['name'=>'data[products]['.$productKey.'][base_price]', 'class'=>'form-control', 'value'=>$product['base_price'], 'placeholder'=>'Base price'])?></td>
						<td><?=form_dropdown('data[products]['.$productKey.'][base_uom]', $option['attributes'], $product['base_uom'], ['class'=>'form-control'])?></td>
						
						<td><?=form_input(['name'=>'data[product_details]['.$productKey.'][in_stock_qty]', 'class'=>'form-control', 'value'=>$product['in_stock_qty'], 'placeholder'=>'In Stock Qty'])?></td>
						<td><?=form_input($input['show_on_website'])?></td>
						<td><?=form_input($input['is_sale'])?></td>
						<td><button class="button primary-btn updaterow" title="Update" id="updatebtn_<?=$productKey?>"><i class="fa fa-edit"></i></button></td>
					</tr>
					<?php
					}
				}?>
				</tbody>  
				<tfoot>
					<tr>
					  <th>Sr No</th>
					  <th>Product</th>
					  <th>Base Price</th>
					  <th>Attributes</th>
					  <th>In stock Qty</th>
					  <th>Show On Website/app</th>
					  <th>On Sale</th>
					  <th>Action</th>
					</tr>
				</tfoot>
            </table>
			
			
			<!-- s --> <!-- /box-body -->  
	    </div>              
		<div class="box-footer">  
			<button type="submit" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
		<!-- /.box-footer -->
	</div><!-- /box -->
	<?php echo form_close(); ?> 
</div>

<script>
    $(document).on('click', '.updaterow', function(e){
        e.preventDefault();
        var formId = $(this).closest('form').attr('id');
        var trId = $(this).closest('tr').attr('id');
        var formData = new FormData();
        $("#"+trId+" *").filter(':input').each(function(){
            if(typeof $(this).attr('name')!='undefined'){
                if($(this).attr('type')=="checkbox"){
                    var val = 0;
                    if($(this).is(":checked")){
                       val = 1;
                    }
                    //alert($(this).attr('name')+" "+val);
                    formData.append($(this).attr('name'), val);
                }else{
                    //alert(this.id+" "+$(this).attr('name')+" "+$(this).val());
                    formData.append($(this).attr('name'), $(this).val());
                }
            }
        })
        
        var action = $("#"+formId).attr('action');
        
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: action,
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
    
                console.log(data);
                var data = $.parseJSON(data);
                Toast.fire({
                    icon: data.status,
                    title: data.message
                })
                
            }
        });
        console.log(formData);
        return false;
    })
</script>
