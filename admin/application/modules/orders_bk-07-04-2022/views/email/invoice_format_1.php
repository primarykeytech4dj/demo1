<style type="text/css">
	td,th{
		padding-left: 5px;
		padding-right: 5px
	}
	table tfoot{display:table-row-group;}
</style>
<?php //echo $customer['gst_no']." ".$company['gst_no'];
    $clientStateCode = substr($customer['gst_no'], 0,2);
	$companyStateCode = substr($company['gst_no'], 0,2); 
	//echo $clientStateCode. "-".$companyStateCode;
	$gst = ['cgst'=>['percent'=>0, 'amount'=>0], 'sgst'=>['percent'=>0, 'amount'=>0]];
	if(!empty($clientStateCode) && $companyStateCode!=$clientStateCode){
	    $gst = ['igst'];
	}
	
	$chkgst = 0;
	if(PHP_VERSION>=8){
	    $chkgst = str_starts_with($order['order_code'], '1');
	}else{
	    //echo substr( $order['order_code'], 0, 3 );
	    $chkgst = (substr( $order['order_code'], 0, 1 ) === "1")?TRUE:FALSE;
	}
	
	if($order['order_type']=='international'){
	    $gst = ['cgst'=>['percent'=>$this->session->userdata('application')['export_tax_rate']/2, 'amount'=>0], 'sgst'=>['percent'=>$this->session->userdata('application')['export_tax_rate']/2, 'amount'=>0]];
	}
	//echo '<pre>';print_r($gst);exit;
	//echo $chkgst;//exit;
	?>
<?php //echo '<pre>';print_r($customer);exit; ?>
<div id="printableArea_<?php echo $order['id']; ?>" class="printableArea">
	<table border="1" width="100%" style="border: 1px solid black;border-collapse: collapse; border-top:none">
		<thead style="border-left:none">
		    
			<tr>
				<th colspan="1"  style="border:none;border-left:none">
					<img src="<?php echo content_url().'uploads/profile_images/'.$company['logo']; ?>">
				</th>
				
				<th colspan="5"  style="border:none">
					<p style="color:#F90DFF; font-size:38px"><b><?php echo strtoupper($company['company_name']); ?></b></p>
				</th>
				
				
				<th colspan="1" style="border:none; text-align:right;">
					<img src="<?php echo content_url().'uploads/profile_images/'.$company['logo']; ?>">
					
				</th>
			</tr>
			
			<tr>
			    <th colspan="7"><?php echo $company['address_1'].", ".$company['address_2'].", ".$company['city'].", ".$company['area'].", ".$company['state'].", ".$company['country']; ?></th>
			</tr>
			<tr><th colspan="7" style="text-align:center; border-bottom:none;"><h3>TAX INVOICE<br>GST NO: <?=$company['gst_no']?></h3></th></tr>
		</thead>
		<tbody>
			<tr>
				<td colspan="4" align="left">Details of Receiver (Billed To / Consignee):<br>
		    		M/S <?php echo $customer['company_name']; ?> <?=!empty($customer['first_name'])?"(".$customer['first_name']." ".$customer['middle_name']." ".$customer['surname'].")":''?><br>
		    		<?php echo $customer['address_1'],",<br> ".$customer['address_2'].",<br> ".$customer['area'].", ".$customer['city']."-".$customer['pincode']."<br>State: ".$customer['state']."&nbsp;&nbsp;&nbsp;&nbsp; State Code: ".$customer['gst_state_code']; ?><br>
		    		GSTIN/Unique No: <?=$customer['gst_no']?><br>
		    		Contact No. : <?=$customer['contact_1']?>
				</td>
				<td colspan="3" align="left">
				    <b>Invoice No.: <?php echo (($bill_type==1)?'#':'').$order['invoice_no']; ?></b><br><b>Date :</b> <?=date('d/m/Y', strtotime($order['date']))?><br>
				    Transportation Mode :- <br>
				    Vehicle No. :- <br>
				    Date & Time of Supply : <?=date('d/m/Y', strtotime($order['date']))?><br>
				    <?=$company['area']?><br>
				    Tax is Payable on Reverse Charge : (YES / NO )
				</td>
			</tr>
			
			<tr>
				<th align="center" width="5%">Sr No</th>
				<th align="left" width="20%">Description Of Goods</th>
				<!--<th align="center"  width="10%">MRP</th>-->
				<th align="center" width="10%">HSN CODE</th>
				<th align="center" width="10%">SP</th>
				<th align="center">Tax(%)</th>
				<th align="center">Quantity</th>
				<!-- <th align="center">Date</th> -->
				<!-- <th align="center">Destination</th>
				<th align="center">Receiver</th>
				<th align="center">Weight</th>
				<th align="center">Mode</th> -->
				<th align="center">Price</th>
			</tr>
			<?php $totalAmount = 0;
			foreach ($orderDetails as $key => $orderDetail) {
			    /*echo '<pre>';
			    print_r($orderDetail);
			    echo '</pre>';*/
				?>
				<tr>
					<td align="center"><?php echo $key+1; ?></td>
					<td align="left"><?php echo (isset($this->session->userdata('application')['product_has_multiple_models']) && $this->session->userdata('application')['product_has_multiple_models'])?$orderDetail['model']:$orderDetail['product'];//($orderDetail['product_type']==2)?$orderDetail['model']:$orderDetail['brand']." ".$orderDetail['product']." ".$orderDetail['model']; ?> 
					<!--<small>(<?=(isset($orderDetail['hsn_sac_code']))?$orderDetail['hsn_sac_code']:''?>)</small>--></td>
					
					<!--td align="right"><?php echo $orderDetail['mrp']; ?></td-->
					<td><?=(isset($orderDetail['hsn_sac_code']))?$orderDetail['hsn_sac_code']:''?></td>
					<td align="right"><?php echo $orderDetail['unit_price']; ?></td>
					<td align="center"><?php echo $orderDetail['tax']; ?></td>
					<td align="center"><?php echo $orderDetail['qty']; ?></td>
					
					
					<td align="right" colspan=2>&#8377; <?php echo number_format($orderDetail['unit_price']*$orderDetail['qty'], 2); ?></td>
					
				</tr>
				<?php
			} ?>
			    <tr>
    			       <td colspan="4" rowspan="2" style="cellspacing:0px; padding:0px">
			    
			    <?php if(count($banks)>0){
			        ?>
			        <!--table width="100%" border="1px" style="border-collapse: collapse; border-top:none">
			        <?php
    			    foreach($banks as $bKey=>$bank){
    			        
    			    ?>
    			    <tr>
    			        <td><b>Bank: </b> <?=$bank['bank_name']." <b>Branch:</b> ".$bank['branch']."<br>
    					<b>Account Number: </b>".$bank['account_number']." | <b>IFSC Code: </b>".$bank['ifsc_code']." <br><b>Account Type: </b>".$bank['account_type']?>
    					</td>
    				</tr>
    				<?php 
    				//echo ((!count($banks)-1)!=$bKey)?'<hr>':'';
				    }
				    ?>
				    </table-->
				    <?php
				} ?>
			    </td>
    		</tr>
			<tr>	
				<th colspan="2" align="right" valign="top">Total</th>
				<td colspan="1" align="right" valign="top">&#8377; <?php echo number_format($order['amount_before_tax'], 2); ?></td>
			</tr>
			<tr>
				<td colspan="4" rowspan="<?=(($chkgst)?3:2)+count($gst)?>"><b>Amount In Words: </b><?php echo ucfirst($this->numbertowords->getIndianCurrency($order['amount_after_tax']+$order['shipping_charge'])); ?></td>
				<!--<td colspan="3">-->
				    <?php 
				    if($order['amount_before_tax']!=$order['amount_after_tax']){
				        echo '<th colspan=2 align="right">GST Amount: </th><td align="right">'.($order['amount_after_tax']-$order['amount_before_tax']).'</td>';
        				
        				?>
        				
        				
        				<?php
        			}else{
            			if($chkgst){
            			    ?>
            			    
            			    <?php
            			}
        			} ?>
        			
			</tr>
			<tr>
			    <td colspan=2>Sub Round Off.</td><td>&nbsp;</td>
			</tr>
			<tr>
			    <td colspan=2>Round Off.</td><td>&nbsp;</td>
			</tr>
			<tr>
			    <td colspan=2>Packaging Charges</td><td<?php $order['amount_after_tax'] = $order['amount_after_tax']+$order['shipping_charge']; ?>
				    <?php echo $order['shipping_charge']; ?></td>
			</tr>
			
			<tr>
				<th colspan="2" align="right"><span style="font-size:28px;">Grand Total</span></th>
				<td colspan="1" align="right"><span style="font-size:20px;">&#8377; <?php echo number_format($order['amount_after_tax'], 2); ?></span></td>
			</tr>
			<?php if(count($jobWork)>0){
			    
			?>
			
			<tr>
			    <th colspan=4 align="center">Job Work Description</th>
			</tr>
    			            <tr>
    			                <th>Job Work</th><th>Delivery Date</th><th>Cost</th><th>Unit</th><!--th>Tax</th><th>Discount</th><th>Total Price</th-->
    			                
    			            </tr>
			            
			            <?php foreach($jobWork as $job){
			                $AmtwithTax = $job['unit_price']*$job['qty']+(($job['tax']/100.00)*$job['unit_price']*$job['qty']);
    			            ?>
    			            <tr>
    			                <td><?=$job['comment']?></td>
    			                <td><?=date('d/m/Y h:i:s a', strtotime($job['delivery_date']))?></td>
    			                <td><?=$job['unit_price']?></td>
    			                <td><?=$job['qty']?></td>
    			                <!--td><?=$job['tax']?></td>
    			                <td><?=$job['discount'].' '.(($job['discount_type']=='percent')?'%':'&#8377')?></td>
    			                <td style="text-align:right"><?=number_format($AmtwithTax,2)?></td-->
    			            </tr>
			            
			            <?php
			            }
			            ?>
			        
			<?php
			}
			?>
			<tr>
				<td colspan="4" rowspan="6">
					<b>Terms & Condition :</b>
					<p align="left">1. Goods once sold will not be taken back on any account.<br>
					    2. If payment will not made within 7 days interest @ 18% p.a. from invoice date will be charged on Amount not Paid.<br>
					    3. No complaints will be entertain after 4 months from Invoice date.<br>
					    4. All Subject to Telangana Jurisdiction Only.<br>
    					Certified that the Particulars given above are true and correct.<br>
					    Amount of Tax Subject To Reverse Charge.</p>
					
				</td>
				<td colspan="4" rowspan="6" valign="right" style="-moz-text-align-last: center; text-align-last: center">
					For : <?=$company['company_name']?><br><br>
					<!--img src="<?php echo assets_url().'sign.png'; ?>" width="30%"-->
					<br><br><br>Authorised Signatory.</td>
			</tr>
			
		</tbody>
		
	</table>
</div>
<input type="button" onclick="printDiv('printableArea_<?php echo $order['id']; ?>')" value="print Bill!" />
<script type="text/javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
