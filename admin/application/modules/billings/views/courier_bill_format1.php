<style type="text/css">
	td,th{
		padding-left: 5px;
		padding-right: 5px
	}
	table tfoot{display:table-row-group;}
</style>
<div id="printableArea_<?php echo $customer_bills['id']; ?>" class="printableArea">
	<table border="1" width="100%" style="border: 1px solid black;border-collapse: collapse;">
		<thead>
			<tr>
				<th colspan="6">
					<p align="left">
						<img src="<?php echo content_url().'uploads/profile_images/'.$company['logo']; ?>" height="auto">
					</p>
				</th>
				<th colspan="2">
					<p align="left">
						<h1><b><?php echo $company['company_name']; ?></b></h1>
						<?php echo $company['address_1']."<br> ".$company['address_2'].",<br> ".$company['city'].", ".$company['area'].", ".$company['state'].", ".$company['country']; ?>
					</p>
				</th>
			</tr>
			<tr><th colspan="8"><h3>INVOICE</h3></th></tr>
			
		</thead>
		<tbody>
			<tr>
				<td colspan="4" align="left"><b>Invoice No.:</b> </td>
				<td colspan="4" align="left"><b>Bill No.: <?php echo $customer_bills['invoice_no']; ?></b></td>
			</tr>
			<tr>
				<td colspan="4" align="left"><b>Work Order Date.:</b> <?php echo date('d/m/Y', strtotime($customer_bills['bill_to_date'])); ?></td>
				<td colspan="4" align="left"><b>Bill Date:</b> <?php echo date('d/m/Y', strtotime($customer_bills['bill_to_date'])); ?></td>
			</tr>
			<tr>
				<td colspan="4" align="left"><b>Company:</b> <?php echo $customer['company_name']; ?></td>
				<td colspan="4" align="left"><b>Bill Period:</b> <?php echo date('d/m/Y', strtotime($customer_bills['bill_from_date'])).' To '.date('d/m/Y', strtotime($customer_bills['bill_to_date'])); ?></td>
			</tr>
			
			<tr>
				<td colspan="8" align="left"><b>Client Name:</b> <?php echo $customer['first_name']." ".$customer['middle_name']." ".$customer['surname']; ?></td>
			</tr>
			<tr>
				<td colspan="8" align="left"><b>Billing Address:</b> <?php echo $customer['address_1'],", ".$customer['address_2'].", ".$customer['city'].", ".$customer['area']." - ".$customer['pincode'].", ".$customer['state'].", ".$customer['country']; ?></td>
			</tr>
			<tr>
			    <td colspan="8" align="left"><b>HSN Code:</b>996812</td>
			</tr>
			<tr>
				<td colspan="8" align="left"><b>PAN:</b> <?php echo $customer['pan_no']; ?></td>
			</tr>
			<tr>
				<td colspan="8" align="left"><b>GST NO:</b> <?php echo $customer['gst_no']; ?></td>
			</tr>
			<tr>
				<th align="center">Sr No</th>
				<th align="center">Tracking No/Consignment No</th>
				<th align="center">Date</th>
				<th align="center">Destination</th>
				<th align="center">Receiver</th>
				<th align="center">Weight</th>
				<th align="center">Mode</th>
				<th align="center">Cost</th>
			</tr>
			<?php $totalAmount = 0;
			foreach ($customer_bill_details as $key => $billDetail) {
				?>
				<tr>
					<td align="center"><?php echo $key+1; ?></td>
					<td align="center"><?php echo $billDetail['consign_no']; ?></td>
					<td align="center"><?php echo date('d/m/Y', strtotime($billDetail['date'])); ?></td>
					<td align="center"><?php echo $billDetail['site_name'] ?></td>
					<td align="center"><?php echo $billDetail['delivery_person']; ?></td>
					<td align="center"><?php echo $billDetail['weight']; ?></td>
					<td align="center"><?php echo $billDetail['mode']; ?></td>
					<td align="right"><?php echo number_format($billDetail['cost'], 2); ?></td>
					
				</tr>
				<?php
			} ?>
			<tr>
				<th colspan="7" align="right">Sub Total</th>
				<td colspan="1" align="right"><?php echo number_format($customer_bills['amount_before_tax'], 2); ?></td>
			</tr>
			<tr>
				<th colspan="7" align="right">Fuel Sur Charge @ <?php echo $customer_bills['fuel_surcharge']; ?></th>
				<td colspan="1" align="right"><?php echo number_format($customer_bills['amount_before_tax']*($customer_bills['fuel_surcharge']/100), 2); ?></td>
			</tr>
			
			<tr>
				<th colspan="7" align="right">CGST 9%</th>
				<td colspan="1" align="right"><?php echo number_format((($customer_bills['service_charge_type']=='PERCENT')?$customer_bills['amount_before_tax']*($customer_bills['service_charge']/100):$customer_bills['service_charge'])/2, 2); ?></td>
			</tr>
			<tr>
				<th colspan="7" align="right">SGST 9%</th>
				<td colspan="1" align="right"><?php echo number_format((($customer_bills['service_charge_type']=='PERCENT')?$customer_bills['amount_before_tax']*($customer_bills['service_charge']/100):$customer_bills['service_charge'])/2, 2); ?></td>
			</tr>
			<tr>
				<th colspan="7" align="right">Total</th>
				<td colspan="1" align="right"><?php echo number_format($customer_bills['amount_after_tax'], 2); ?></td>
			</tr>
			<tr>
				<td colspan="8"><b>Amount In Words: </b><?php echo $this->numbertowords->getIndianCurrency($customer_bills['amount_after_tax']); ?></td>
			</tr>
			<tr>
				<th colspan="4">Our GST No.:</th>
				<td colspan="4"><?php echo $company['gst_no']; ?></td>
			</tr>
			<tr>
				<th colspan="4">Our PAN No.:</th>
				<td colspan="4"><?php echo $company['adhaar_no']; ?></td>
			</tr>
			<tr>
				<th colspan="4">Our Address:</th>
				<td colspan="4"><?php echo $company['address_1'].", ".$company['address_2'].", ".$company['city'].", ".$company['area'].", ".$company['state'].", ".$company['country']; ?></td>
			</tr>
			<tr>
				<th colspan="4">Contact Person: <?php echo $company['first_name']." ".$company['middle_name']." ".$company['surname']; ?></th>
				<td colspan="4"><?php echo $company['contact_1'] ?></td>
			</tr>
			<tr>
				<td colspan="4"><h3><b>Bank Account Details:</b></h3></td>
				<td colspan="4"><b>Bank : </b> <?=$company['bank_name']." <br>
					<b>Account Number : </b>".$company['account_number']."<br><b>IFSC Code : </b>".$company['ifsc_code']." <br><b>Account Type : </b>".$company['account_type']?>

				</td>
			</tr>
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
<input type="button" onclick="printDiv('printableArea_<?php echo $customer_bills['id']; ?>')" value="print Bill!" />
<script type="text/javascript">
  function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
</script>
