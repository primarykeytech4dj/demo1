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
/*echo '<pre>';
print_r($invoiceorders);
exit;*/
?>
<section class="content-header hideInPrint">
    <div class="row">
            <div class="col-md-12">
                <h1>
                    Module :: Order
                </h1>
                <ol class="breadcrumb pull-right">
                <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
                <li class="active">Order</li>
                <li class="active"><input type='button' id='btn' value='Print' onclick='printDiv();'></li>
                </ol>
            </div>
    </div>
</section>
<div id='DivIdToPrint'>
<?php echo form_open_multipart('orders/admin_invoice_orders', ['class'=>'form-horizontal', 'id'=>'admin_invoice_orders']); 
	//print_r($this->session);
	if(!isset($module) && NULL!==$this->session->flashdata('message')) {
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
	<input type="hidden" name="url" value="<?php echo !isset($url)?'orders/admin_invoice_orders/':$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'orders':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border hideInPrint">
			<h3 class="box-title"><i class="fa fa-pencil margin-r-5"></i> Order Invoices</h3>
			<br>Last Invoice Number was: <b><?=(count($last_invoice)>0)?$last_invoice[0]['invoice_no']:0?></b>
		</div><!-- /box-header -->
		<!-- form start -->
		<div class="box-body" style="overflow-x: scroll;">
		    <?php //print_r($products); ?>
			<table class="table" id="target">
				<thead>
					<tr>
					  <th>Sr No</th>
					  <th>Customer</th>
					  <th>Area</th>
					  <th>Order Code</th>
					  <th>Detail</th>
					  <th>Order Amount</th>
					  <th>Invoice Amount</th>
					  <th class="hideInPrint">Generate Auto Invoice</th>
					  <th class="hideInPrint">Manual Invoice No</th>
					  <!-- <th>Remark</th> -->
					  
					</tr>
				</thead>
				<tbody>
				 <?php 
				$count = count($invoiceorders);
				if($count>0){
				    $counter = 0;
				    $cnt = 0;
				    $idSr = 0;
				foreach ($invoiceorders as $invoiceKey => $invoice) { 
					//print_r($invoice);?>
					<tr id="<?=$counter?>">
						<td rowspan="<?=count($invoice['orders'])?>"><?=$counter+1?></td>
						<td rowspan="<?=count($invoice['orders'])?>"><?=$invoice['customer']?></td>
						<td rowspan="<?=count($invoice['orders'])?>"><?=$invoice['area']?></td>
						<?php $counter2=0;?>
						 <?php $invAmt = 0;
						 
						 foreach ($invoice['orders'] as $orderKey => $order) {
						 	$invAmt = $invAmt+$order['order_amount'];
						 	++$idSr;
						 	if($counter2!=0){
						 		echo '<tr>';
						 	}
						 	?>
							<td><?=$orderKey?></td>

							<td><input type="hidden" name="invoice_orders[<?=$counter?>][order_code][]" value="<?=$orderKey?>" id="order_code_<?=$idSr?>" class="order_codes_<?=$counter?>">
								<?=nl2br($order['orderdetail'])?></td>
							<td><?=$order['order_amount']?></td>
							<?php //echo $counter2.' '.count($invoice['orders']);?>
							<?php if($counter2==0){?>

							<td><?=$invoice['invoice_amt']?></td>
							<td class="hideInPrint">
								<input type="checkbox" name="invoice_orders[<?=$counter?>][invoice_no]"  class="invoice_no_checkbox" id="invoice_checkbox_<?=$idSr?>" value="">
							</td>
							<td class="hideInPrint">
								<input type="text" name="invoice_orders[<?=$counter?>][invoice_no]"  class="form-control invoice_no" id="invoice_no_<?=$idSr?>" style="width:120px" value="">
							</td>
            					
							<?php 
							    
							    
							}else{
								echo '<td></td><td></td>';
							} ?> 
							
						</tr>
						<?php 
						
						++$counter2;} 
						++$cnt;
						?> 
						
						
					</tr>
					
					
					
					
					<?php
					$counter = $counter+1;
					}
				}?>
				</tbody>  
				
            </table>
			
			
			<!-- s --> <!-- /box-body -->  
	    </div>              
		<div class="box-footer">  
			<div class="row">
				<div class="col-md-4">
					<?=form_dropdown('fiscal_yr', $option['fiscal_yr'], $this->pktlib->get_fiscal_year(), ['class'=>'form-control ', 'id'=>'fiscal_yr'])?>
					
				</div>
				<div class="col-md-4">
					<button type="submit" class="btn btn-info pull-left">Update</button> &nbsp;&nbsp;&nbsp;&nbsp;
					
				
					<button type="reset" class="btn btn-info">cancel</button>
				</div>
			</div>
			<?php /*echo nbs(3);*/ ?>
		</div>
		<!-- /.box-footer -->
	</div><!-- /box -->
	<?php echo form_close(); ?> 
</div>

<script type="">
    $(document).on('blur', '.invoice_no', function(){
        var id = this.id;
        var invno = $("#"+id).val();
        var fiscalyr = $("#fiscal_yr").val();
        const myarray = [];
        $('.invoice_no').each(function(index, val){
           console.log($(this).attr('id'));
           var inId = $(this).attr('id');
           if($("#"+inId).val()!=''){
               if($.inArray($("#"+inId).val(), myarray) !== -1){
                   $("#"+id).val('');
                   alert("Duplicate invoice");
                   return false;
               }else{
                   myarray.push($("#"+inId).val());
                   
               }
           }
        });
        
        $.ajax({
            type: 'POST',
            url: base_url+'orders/check_invoice',
            dataType: 'json',
            data: {'invoice':invno, 'fiscalyr':fiscalyr},
            success:function(response) {
                console.log(response);
                if(response.status == 'success'){
                    $("#"+id).val('').focus();
                    alert(response.msg);
                }
               
            } 
        });
        return false;
    });
    
    $(document).on("change", ".invoice_no_checkbox", function(){
        //alert(this.id); 
        var id = this.id;
        if( $(this).is(":checked") ){
            if (confirm('Do you want to generate invoice? Process cannot be rolled back')) {
                var trId = $("#"+id).closest('tr').attr('id');
                //alert("trid="+trId);
                const orderCodes = [];
                /*$("#"+trId+'>td>input').each(function(index,val){
                    alert("hii");
                    formData[$(this).attr('name')]
                   console.log($(this).attr('name')); 
                   console.log($(this).attr('value')); 
                });*/
                $(".order_codes_"+trId).each(function(index,value){
                    //console.log(this.id);
                    var ordereleId = this.id;
                    console.log($("#"+ordereleId).val());
                    orderCodes.push($("#"+ordereleId).val()); 
                    //formData['order_code'][index] = value.attr('value');
                })
                
                $.ajax({
                    type: 'POST',
                    dataType: 'json',
                    url : base_url+'orders/generatenmapinvoice/',
                    data: {'order_code':orderCodes},
                    success: function(response) {
                        console.log(response);
                        if(response.status=="success"){
                            alert("Invoice Number "+response.invoice_no+" created successfully");
                            $("#"+trId).remove();
                        }else{
                            alert("Error occurred while generating Invoice");
                        }
                    }
                
                });
            } else {
                $("#"+id).prop('checked', false);
            }
        }
    });
    
    
</script>

