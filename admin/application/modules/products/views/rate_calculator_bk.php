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
<?php echo form_open_multipart('products/rate_calculator', ['class'=>'form-horizontal', 'id'=>'rate_calculator']); 
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
	<input type="hidden" name="url" value="<?php echo !isset($url)?'products/rate_calculator/':$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'products':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-pencil margin-r-5"></i> Rate Calculator</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body" style="overflow-x: scroll;">
		    <?php //print_r($products); ?>
			<table class="table" id="target">
				<thead>
					<tr>
					  <th>Sr No</th>
					  <th>Category</th>
					  <th>Product</th>
					  <th>Base Price</th>
					  <th>Attributes</th>
					  <th>Current price</th>
					  <th>margin</th>
					  
					  <th>Selling Price</th>
					  <th>Remark</th>
					  
					</tr>
				</thead>
				<tbody>
				 <?php 
				$count = count($products);
				if($count>0){
				    $counter = 0;
				foreach ($products as $productKey => $product) { 
				    //print_r($product['attributes']);
					
					if(isset($product['attributes']) && count($product['attributes'])>0){
					    $baseUOM = explode(" ", $product['base_uom']);
					    ?>
					    <tr id="<?=$counter?>">
            					<td rowspan="<?=count($product['attributes'])?>"><?=$productKey+1?></td>
            					<td rowspan="<?=count($product['attributes'])?>"><?=nl2br($product['category_name'])?></td>
            					<td rowspan="<?=count($product['attributes'])?>"><?=$product['product']?></td>
            					<td rowspan="<?=count($product['attributes'])?>" class="base_price"><?=$product['base_price']?></td>
					    <?php
					    foreach($product['attributes'] as $aKey=>$attribute){
					        $requireUOM = explode(" ", $attribute['variant']);
                            
                            $unit = $this->pktlib->unit_convertion($requireUOM[1], $baseUOM[1]);
                            //echo $unit." <br>";
                            $margin = number_format(($attribute['price']/($requireUOM[0]*$unit))-$product['base_price'], 2, ".", "");
					        $str = 'checked="checked"';
					        if(FALSE===$attribute['is_active']){
					            $str = '';
					        }
					        ?>
            					<td><?=$attribute['variant']?>
            					    <?=form_input(['name'=>'products['.$counter.'][base_price]', 'value'=>$product['base_price'], 'type'=>'hidden', 'id'=>'base_price_'.$counter])?>
            					    <?=form_input(['name'=>'products['.$counter.'][base_uom]', 'value'=>$product['base_uom'], 'type'=>'hidden', 'id'=>'base_uom_'.$counter])?>
            					    <?=form_input(['name'=>'product_attributes['.$counter.'][uom]', 'value'=>$attribute['variant'], 'type'=>'hidden', 'id'=>'uom_'.$counter])?>
            					    <?=form_input(['name'=>'product_attributes['.$counter.'][product_id]', 'value'=>$attribute['product_id'], 'type'=>'hidden', 'id'=>'product_id_'.$counter])?>
            					    <?=form_input(['name'=>'product_attributes['.$counter.'][id]', 'value'=>$attribute['id'], 'type'=>'hidden', 'id'=>'product_attribute_id_'.$counter])?>
            					    </td>
            					<td><?=$attribute['price']?></td>
            					<td id="tdmargin_<?=$counter?>"><input type="text" style="width:120px" id="margin_<?=$counter?>" name="product_attributes[<?=$counter?>][per_unit_margin]" class="form-control margin" value="<?=($attribute['per_unit_margin']>0)?$attribute['per_unit_margin']:$margin?>"></td>
            					
            					<td><?=form_input(['name'=>'product_attributes['.$counter.'][price]',  'style'=>"width:120px", 'value'=>$attribute['price'], 'id'=>'price_'.$counter, "class"=>"form-control"])?></td>
            					<td><input type="text" name="product_attributes[<?=$counter?>][remarks]" class="form-control" id="remarks_<?=$counter?>" value="<?=$attribute['remarks']?>"></td>
            				</tr>
					        <?php
					        ++$counter;
					    }
					}
					
					?>
					
					<?php
					}
				}?>
				</tbody>  
				
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
<script type="text/javascript">
    $(document).on('blur', '.margin', function(){
        var trId = $(this).closest('tr').attr('id');
        var tdId = $(this).closest('td').attr('id');
        var counter = tdId.split('_');
        var product_id = $("#product_id_"+counter[1]).val();
        var product_attribute_id = $("#product_attribute_id_"+counter[1]).val();
        var uom = $("#uom_"+counter[1]).val();
        var margin = $("#margin_"+counter[1]).val();
        var base_uom = $("#base_uom_"+counter[1]).val();
        var base_price = $("#base_price_"+counter[1]).val();
        console.log(trId+" "+tdId+" "+product_id+" "+margin+" "+uom);
        
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url : base_url+'products/per_unit_calculation',
            data: {'product_id':product_id, 'uom':uom, 'margin':margin, 'id':product_attribute_id, 'base_price':base_price, 'base_uom':base_uom},
            success: function(response) {
              console.log(response.price);
              $("#price_"+counter[1]).val(response.price);
              //$('#'+formId+' #'+datatarget).select2('destroy').empty().select2({data : response});
            }
          
          });
    });
</script>