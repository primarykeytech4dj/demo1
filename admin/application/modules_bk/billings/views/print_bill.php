<?php //print_r($bill); ?>
<?php //print_r($billDetails); ?>
<!-- <input type="button" onclick="printDiv('printableArea_<?php echo $billId; ?>')" value="print a div!" /> -->
<div id="printableArea_<?php echo $billId; ?>" class="printableArea">
	<div style="min-height: 100px">
		
	</div>
	<table border="1" width="100%" style="border: 1px solid black;border-collapse: collapse;">
		<thead>
			<tr><th colspan="7"><h3>INVOICE</h3></th>
			
		</thead>
		<tbody>
			<tr>
				<td colspan="3" align="left"><b>Work Order No.:</b> </td>
				<td colspan="4" align="left"><b>Bill No.: <?php echo $bill['invoice_no']; ?></b></td>
			</tr>
			<tr>
				<td colspan="3" align="left"><b>Work Order Date.:</b> </td>
				<td colspan="4" align="left"><b>Bill Date:</b> <?php echo date('d/m/Y', strtotime($bill['created'])); ?></td>
			</tr>
			<tr>
				<td colspan="3" align="left"><b>Plant/Project Name:</b> <?php echo $bill['site_name']; ?></td>
				<td colspan="4" align="left"><b>Work Period:</b> <?php echo date('d/m/Y', strtotime($bill['bill_from_date'])).' To '.date('d/m/Y', strtotime($bill['bill_to_date'])); ?></td>
			</tr>
			<tr>
				<td colspan="7" align="left"><b>Name of Work:</b> <?php echo "Service given at ".$bill['address_1']." ".$bill['address_2']; ?></td>
			</tr>
			<tr>
				<td colspan="7" align="left"><b>Client Name:</b> <?php echo $bill['client_name']; ?></td>
			</tr>
			<tr>
				<td colspan="7" align="left"><b>Billing Address:</b> <?php echo $billingAddress['address_1'],", ".$billingAddress['address_2'].", ".$billingAddress['city_name'].", ".$billingAddress['area_name']." - ".$billingAddress['pincode']; ?></td>
			</tr>
			<tr>
				<td colspan="7" align="left"><b>PAN:</b> <?php echo $bill['pan_no']; ?></td>
			</tr>
			<tr>
				<td colspan="7" align="left"><b>GST NO:</b> <?php echo $bill['gst_no']; ?></td>
			</tr>
			<tr>
				<th align="center">Sr No</th>
				<th align="center">Description</th>
				<th align="center">Service Code</th>
				<th align="center">UOM (No of Duties)</th>
				<th align="center">Quantity</th>
				<th align="center">Unit Price</th>
				<th align="center">Amount (Rs.)</th>
			</tr>
			<?php $totalAmount = 0;
			foreach ($billDetails as $key => $billDetail) {
				?>
				<tr>
					<td align="center"><?php echo $key+1; ?></td>
					<td align="center"><?php echo $billDetail['product']; ?></td>
					<td align="center"><?php echo $billDetail['product_code']; ?></td>
					<td align="center"><?php echo ($billDetail['no_of_labour']+$billDetail['night_shift_labour_count'])*$billDetail['no_of_days']; ?></td>
					<td align="center"><?php echo $billDetail['no_of_labour']+$billDetail['night_shift_labour_count']; ?></td>
					<td align="right"><?php echo number_format($billDetail['cost'], 2); ?></td>
					<td align="right">
						<?php 
						$amount = ($billDetail['cost']/$billDetail['no_of_days'])*$billDetail['no_of_days']*($billDetail['no_of_labour']+$billDetail['night_shift_labour_count']);
						echo number_format($amount, 2);
						$totalAmount = $totalAmount+$amount;
					 ?></td>
				</tr>
				<?php
			} ?>
			<tr>
				<td colspan="4" rowspan="5"></td>
				<th colspan="2">Service Charge (<?php echo $bill['service_charge']; ?> <?php echo ($bill['service_charge_type']=='PERCENT')?'%':'Rs.'; ?>)</th>
				<td colspan="2" align="right"><?php echo number_format(($bill['amount_before_tax']-$totalAmount), 2); ?></td>
			</tr>
			<tr>
				<th colspan="2">Sub Total</th>
				<td colspan="2" align="right"><?php echo number_format($bill['amount_before_tax'], 2); ?></td>
			</tr>
			<tr>
				<!-- <td colspan="4"></td> -->
				<th colspan="2">CGST 9%</th>
				<td colspan="2" align="right"><?php echo number_format(($bill['amount_after_tax']-$bill['amount_before_tax'])/2, 2); ?></td>
			</tr>
			<tr>
				<!-- <td colspan="4"></td> -->
				<th colspan="2">SGST 9%</th>
				<td colspan="2" align="right"><?php echo number_format(($bill['amount_after_tax']-$bill['amount_before_tax'])/2, 2); ?></td>
			</tr>
			<tr>
				<!-- <td colspan="4"></td> -->
				<th colspan="2">Total</th>
				<td colspan="2" align="right"><?php echo number_format($bill['amount_after_tax'], 2); ?></td>
			</tr>
			<tr>
				<td colspan="7"><b>Amount In Words: </b><?php echo $this->numbertowords->getIndianCurrency($bill['amount_after_tax']); ?></td>
			</tr>
			<tr>
				<th colspan="4">Our GST No.:</th>
				<td colspan="3">27ACKPL3386N2ZV</td>
			</tr>
			<tr>
				<th colspan="4">Our PAN No.:</th>
				<td colspan="3">ACKPL3386N</td>
			</tr>
			<tr>
				<th colspan="4">Site Address:</th>
				<td colspan="3"><?php echo $bill['address_1']." ".$bill['address_2']; ?></td>
			</tr>
			<tr>
				<th colspan="4">Contact Person: Bharat Sable, Kisan Lokhande</th>
				<td colspan="3">7021269428 / 9225122112</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<th colspan="7" rowspan="5" valign="right" style="-moz-text-align-last: right; text-align-last: right"><br><br><br><br><br>Authorised Signatory.</th>
			</tr>
		</tfoot>
	</table>

</div>

<input type="button" onclick="printDiv('printableArea_<?php echo $billId; ?>')" value="print Bill!" />

<script type="text/javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>