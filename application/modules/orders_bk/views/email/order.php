<style type="text/css">
	td,th{
		padding-left: 5px;
		padding-right: 5px
	}
	table tfoot{display:table-row-group;}
</style>
<?php //print_r($order); ?>
<div id="printableArea_<?php echo $order['id']; ?>" class="printableArea">
	<table border="1" width="100%" style="border: 1px solid black;border-collapse: collapse;">
		<thead>
			<tr>
				<th colspan="3">
					<p align="left">
						<img src="<?php echo content_url().'uploads/profile_images/'.$company['logo']; ?>" height="100px">
					</p>
				</th>
				<th colspan="3">
					<p align="left">
						<h1><b><?php echo $company['company_name']; ?></b></h1>
						<?php echo $company['address_1']."<br> ".$company['address_2'].",<br> ".$company['city'].", ".$company['area'].", ".$company['state'].", ".$company['country']; ?>
					</p>
				</th>
			</tr>
			<tr><th colspan="6"><h3>INVOICE</h3></th></tr>
			
		</thead>
		<tbody>
			<tr>
				<td colspan="3" align="left"><b>Invoice No.: <?php echo $order['order_code']; ?></b> </td>
				<td colspan="3" align="left"><b>Bill No.: <?php echo $order['order_code']; ?></b></td>
			</tr>
			<tr>
				<td colspan="3" align="left"><b>Work Order Date.:</b> <?php echo date('d/m/Y', strtotime($order['created'])); ?></td>
				<td colspan="3" align="left"><b>Order Date:</b> <?php echo date('d/m/Y', strtotime($order['date'])); ?></td>
			</tr>
			<!-- <tr>
				<td colspan="4" align="left"><b>Company:</b> <?php echo $customer['company_name']; ?></td>
				<td colspan="4" align="left"><b>Bill Period:</b> <?php echo date('d/m/Y', strtotime($order['bill_from_date'])).' To '.date('d/m/Y', strtotime($order['bill_to_date'])); ?></td>
			</tr> -->
			
			<tr>
				<td colspan="6" align="left"><b>Client Name:</b> <?php echo $customer['first_name']." ".$customer['middle_name']." ".$customer['surname']; ?></td>
			</tr>
			<tr>
				<td colspan="6" align="left"><b>Billing Address:</b> <?php echo $customer['address_1'],", ".$customer['address_2'].", ".$customer['city'].", ".$customer['area']." - ".$customer['pincode'].", ".$customer['state'].", ".$customer['country']; ?>&nbsp;&nbsp;&nbsp;Phone : <?=$customer['contact_1']?></td>
			</tr>
			<tr>
				<td colspan="6" align="left"><b>PAN:</b> <?php echo $customer['pan_no']; ?></td>
			</tr>
			<tr>
				<td colspan="6" align="left"><b>GST NO:</b> <?php echo $customer['gst_no']; ?></td>
			</tr>
			<tr>
				<th align="center">Sr No</th>
				<th align="center">Product</th>
				<th align="center">Variation</th>
				<th align="center">Unit Price</th>
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
			    //$variations = json_decode($orderDetail['variation'],0);
			    //$variations = array($variations);
			   //print_r($variations[0]->attribute->product_attribute_id);exit;
			   // print_r($attribute[$variations->attribute->product_attribute_id]);exit;
				?>
				<tr>
					<td align="center"><?php echo $key+1; ?></td>
					<td align="center"><?php echo $orderDetail['product']; ?></td>
					<td>
						<?php //print_r($orderDetail['variation']);exit;
						if($orderDetail['variation']!=NULL){
							$variations = json_decode($orderDetail['variation'],true);
							//$variations = array($variations);
							//print_r($variations);exit;
							if(isset($variations['attribute']['product_attribute_id']) && $variations['attribute']['product_attribute_id']!=0){
							    echo $attribute[$variations['attribute']['product_attribute_id']];
							}else{
							    echo $orderDetail['base_uom'];
							}
							/*foreach ($variations as $variationKey => $variation) {
							    //echo $variationKey;
							    //print_r($variation->attribute->product_attribute_id);exit;
								if($variationKey){
								    
								echo (isset($variation->attribute->product_attribute_id) && $variation->attribute->product_attribute_id!=0 && $v['variation'])?$attribute[$variation->attribute->product_attribute_id]:$orderDetail['base_uom'];
								//echo ucfirst($variationKey)." : ".$variation."<br/>";
								}
							}*/
							//echo json_decode($orderDetail['variation']);
						} ?>
					</td>
					<td align="center"><?php echo $orderDetail['unit_price']; ?></td>
					<td align="center"><?php echo $orderDetail['qty']; ?></td>
					
					<td align="right"><?php echo number_format($orderDetail['unit_price']*$orderDetail['qty'], 2); ?></td>
					
				</tr>
				<?php
			} ?>
			<tr>
				<th colspan="5" align="right">Sub Total</th>
				<td colspan="1" align="right"><?php echo number_format($order['amount_before_tax'], 2); ?></td>
			</tr>
			<!-- <tr>
				<th colspan="7" align="right">Fuel Sur Charge @ <?php echo $order['fuel_surcharge']; ?></th>
				<td colspan="1" align="right"><?php echo number_format($order['amount_before_tax']*($order['fuel_surcharge']/100), 2); ?></td>
			</tr> -->
			<?php if($order['amount_before_tax']!=$order['amount_after_tax']){
				$difAmt = $order['amount_after_tax']-$order['amount_before_tax'];
				$percent = ($difAmt/$order['amount_after_tax'])*100.00;
				//echo $difAmt." ".$percent;
				?>
				<tr>
					<th colspan="5" align="right">Tax<!-- CGST 9% --></th>
					<td colspan="1" align="right"><?php echo number_format($difAmt, 2); ?></td>
				</tr>
				<!-- <tr>
					<th colspan="5" align="right">SGST 9%</th>
					<td colspan="1" align="right"><?php echo number_format((($order['service_charge_type']=='PERCENT')?$order['amount_before_tax']*($order['service_charge']/100):$order['service_charge'])/2, 2); ?></td>
				</tr> -->
				<?php
			} 
			
			?>
			<tr>
				<th colspan="5" align="right">Other Charges</th>
				<td colspan="1" align="right">
				    <?php $order['amount_after_tax'] = $order['amount_after_tax']+$order['shipping_charge']; ?>
				    <?php echo $order['shipping_charge']; ?></td>
			</tr>
			<tr>
				<th colspan="5" align="right">Total</th>
				<td colspan="1" align="right"><?php echo number_format($order['amount_after_tax'], 2); ?></td>
			</tr>
			<tr>
				<td colspan="6"><b>Amount In Words: </b><?php echo ucfirst($this->numbertowords->getIndianCurrency($order['amount_after_tax'])); ?></td>
			</tr>
			<tr>
				<th colspan="3">Our GST No.:</th>
				<td colspan="3"><?php echo $company['gst_no']; ?></td>
			</tr>
			<tr>
				<th colspan="3">Our PAN No.:</th>
				<td colspan="3"><?php echo $company['adhaar_no']; ?></td>
			</tr>
			<tr>
				<th colspan="3">Our Address:</th>
				<td colspan="3"><?php echo $company['address_1'].", ".$company['address_2'].", ".$company['city'].", ".$company['area'].", ".$company['state'].", ".$company['country']; ?></td>
			</tr>
			<tr>
				<th colspan="3">Contact Person: <?php echo $company['first_name']." ".$company['middle_name']." ".$company['surname']; ?></th>
				<td colspan="3"><?php echo $company['contact_1'] ?></td>
			</tr>
			<!--<tr>
				<td colspan="4"><h3><b>Bank Account Details:</b></h3></td>
				<td colspan="4"><b>Bank : </b> <?=$company['bank_name']." <br>
					<b>Account Number : </b>".$company['account_number']."<br><b>IFSC Code : </b>".$company['ifsc_code']." <br><b>Account Type : </b>".$company['account_type']?>

				</td>
			</tr>-->
			<tr>
				<td colspan="4" rowspan="6">
					<b>Terms & Condition :</b>
					<p align="left">1. All Dispute are subject to MUMBAI Jurisdiction only.</p>
					<p align="left">2. Payment Due on receipt of this Bill.</p>
					
				</td>
				<td colspan="4" rowspan="6" valign="right" style="-moz-text-align-last: right; text-align-last: right">
					For : <?=$company['company_name']?>
					<br><br><br><br><br>Authorised Signatory.</td>
			</tr>
			
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8">
					As the tax is to be deposited by the 15th of following month so we request you to clarify the bill before that. No correction would be entertained after 10th of following month.
				</td>
				
			</tr>
		</tfoot>
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
