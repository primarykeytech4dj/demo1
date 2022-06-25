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
<?php echo form_open_multipart('orders/admin_assign_delivery', ['class'=>'form-horizontal', 'id'=>'admin_invoice_orders']); 
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
	<input type="hidden" name="url" value="<?php echo !isset($url)?'orders/admin_assign_delivery/':$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'orders':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-pencil margin-r-5"></i> Assign Delivery</h3>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body" style="overflow-x: scroll;">
		    <?php //print_r($products); ?>
			<table class="table summary" id="target">
				<thead>
					<tr>
					  <th colspan="2">Sr No</th>
					  <th colspan="2">Teritorry</th>
					  <th colspan="2">KG</th>
					  <th>No of. Invoice</th>
					  <?php foreach ($users as $key => $user) {?>
					  	<th><?=$user['name']?></th>
					  <?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php $count = count($invoiceArray);
					if($count>0){
					    $counter = 0;
					foreach ($invoiceArray as $invoiceKey => $invoice) { 
					    //echo '<pre>';print_r($invoice);echo '</pre>';
						//tr id area_namme
						$qty = 0;
						foreach($invoice as $in){
						    $qty = $qty+$in['qty'];
						}
						?>
						<tr id="<?=$counter?>">
							<td colspan="2"><?=$counter+1?></td>
							<td colspan="2"><?=$invoiceKey?></td>
							<td colspan="2"><?=$qty?></td>
							<td><?=count($invoice)?></td>
							
								<?php foreach($users as $userKey => $user){
										//id=user_id
									$area1 = strtr($invoiceKey,"(","a");
									$area = str_replace(' ', '', $area1); 
									?>

									<td><input type="checkbox" class="<?=str_replace(')', '', $area).'_'.$user['id']?>_ singleCheck1"  id="<?=$user['id']?>" data-id="<?=$user['id']?>"><br></td>
								<?php }?>
							
						</tr>
						<?php
						$counter = $counter+1;
						}
					}?>
				</tbody>  
				<hr>
			</table>
			<table class="table summary_detail" id="target">
				<thead>
					<tr>
					  <th>Sr No</th>
					  <th>Date</th>
					  <th>Shop Name</th>
					  <th>Teritorry</th>
					  <th>KG</th>
					  <th>Invoice No</th>
					  <th>Invoice Amount</th>
					  <?php foreach ($users as $key => $user) {?>
					  	<th><?=$user['name']?></th>
					  <?php } ?>
					</tr>
				</thead>
				<tbody>
					<?php $counter = 0;
					$counter1 = 0;
					//echo '<pre>';
					foreach ($invoiceArray as $invKey => $invoice) {
					    /*echo '<pre>';
					    echo $invKey;
					    print_r($invoice);echo '</pre>';*/
						$counter2 = 0;

						foreach ($invoice as $key => $value) {
							//print_r($value);

							?>
							<tr id="<?=$counter?>">
							<td><?=$counter1+1?></td>
							<td><?=date('d/m/Y', strtotime($value['date']))?></td>
							<td><?=$value['customer']?></td>
							<td><?=$value['area_name']?></td>
							<td><?=$value['qty']?></td>
							<td><?=$value['invoice_no']?></td>
							<td><?=$value['amount_after_tax']?></td>
							<?php foreach ($users as $key => $user) {
								$area1 = strtr($value['area_name'],"(","a");
								$area = str_replace(' ', '', $area1); ?>
								<td><input type="hidden" name="deliveryboy_orders[<?=$counter1?>][invoice_no]" value="<?=$value['invoice_no']?>">
									<input type="checkbox" name="deliveryboy_orders[<?=$counter1?>][employee_id]" class="<?=str_replace(')', '', $area).'_'.$user['id']?> summery_detail_singleCheck1 <?=str_replace(')', '', $area)?>" value="<?=$user['id']?>"></td>
					  		<?php } ?>
						</tr>
						<?php
						$counter2 = $counter2+1;
						$counter1 = $counter1+1;
						 }
						++$counter;
					} ?>
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
    $(document).on('click', '.singleCheck1', function(){
    	var trId  = $(this).closest('tr').attr('id');
        var elementId = $(this).attr('id');
	    var className = $(this).attr('class');
	    $("#"+trId+" .singleCheck1").prop('checked', false);
	    //console.log('elementId = '+elementId+"trId = "+trId);
	    $('#'+trId+" #"+elementId).prop('checked', true);
	    //console.log('classname = '+className);
	    var classvalue = className.split('_');
	    //console.log(classvalue); 
	    //$('.'+classvalue[0]+"_"+classvalue[1]).prop('checked', true); 
	   	$('.summary_detail > tbody  > tr').each(function() {
	   		var tr = $(this).closest('tr').attr('id');
	   		console.log(classvalue[0]);
	   		$('.summary_detail #'+tr+' .'+classvalue[0]).prop('checked', false);
	   		$('.summary_detail .'+classvalue[0]+"_"+classvalue[1]).prop('checked', true);
	   		console.log('reached  in summary_detail '+classvalue[0]+' trid '+tr);
	   	});
    });


</script>