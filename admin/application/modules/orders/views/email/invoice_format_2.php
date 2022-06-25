<style type="text/css">
	td,th{
		padding-left: 5px;
		padding-right: 5px
	}
	table tfoot{display:table-row-group;}
	.taxtable th:last-child,
    .taxtable td:last-child {
      border-top: 0;
      border-right: 0;
      border-bottom: 0;
    }
    
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
</style>

<?php //echo '<pre>';print_r($orderDetails);exit; ?>
<div id="printableArea_<?php echo $order['id']; ?>" class="printableArea">
    <table border="1" width="100%" style="border: 1px solid black;border-collapse: collapse; border-top:none">
        
            <tr><th colspan="9" align="center"><?=$company['company_name']?></th></tr>
            <tr>
                <th colspan="9" align="center">
                    <?=$company['address_1'].", ".$company['address_2']."".$company['area']." ".$company['city']."-".$company['pincode']." <b>Tel:</b> ".$company['contact_1']; ?><br>
                    B.O : Galano 1, Plot no 70, sector 19A, Vashi , Navi Mumbai 400705
                </th>
            </tr>
            <tr>
                <th colspan="3" align="left" style="border-right:none"><b>GST NO:</b> <?=$company['gst_no']?></th>
                <th style="border-left:none; border-right:none;">Tax Invoice</th>
                <th colspan="5" align="right" style="border-left:none"><b>FSSAI NO:</b> 11519019000012</th>
            </tr>
        
            <tr>
                <th colspan="4" align="left">Buyer (Bill To)</th>
                <td colspan=3 align="left">Invoice No<br><b>D-<?=str_replace("-", "", $order['fiscal_yr'])?>-<?=$order['invoice_no']?></b></td>
                <td colspan=2 align="left">Invoice Date<br><b><?=date('d F,Y', strtotime($order['created']))?></b></td>
            </tr>
            <tr>
                <td colspan="4" rowspan=2 align="left"><b><?php echo $customer['company_name']; ?></b><br>
		    		<?php echo nl2br($customer['address_1']),",<br> ".nl2br($customer['address_2']).",<br> ".$customer['area'].", ".$customer['city']."-".$customer['pincode']."<br>";?>
		    		GSTIN/Unique No: <?=$customer['gst_no']?><br>
		    		Contact No. : <?=$customer['contact_1']?><br>
		    		State: <?=$customer['state']."&nbsp;&nbsp;&nbsp;&nbsp; State Code: ".$customer['gst_state_code']; ?>
		    	</td>
                <td colspan=3>Order Date<br><b><?=date('d F,Y', strtotime($order['created']))?></b></td>
                <td colspan=2>Mode/Terms of Payment: <br><b>UPI/Cash On Delivery</b></td>
            </tr>
            
            <tr>
                <td colspan=5>
                <?=$order['ordercode_list']?>
                </td>
                
            </tr>
            <tr>
                <th width="5%">Sr No.</th>
                <th width="35%">Description Of Goods</th>
                <th width="5%">HSN/SAC</th>
                <th width="10%">PKG</th>
                <th width="10%">PCS</th>
                <th width="5%">QTY<br><small>(kgs)</small></th>
                <th width="10%">Rate<br><small>(Incl of tax)</small></th>
                <th width="10%">Rate<br>(per kg)</th>
                <th width="10%">Amount<br><small>Rs</small></th>
            </tr>
            <?php $totalAmount = 0;
            $pc = [];
            $kgs = [];
            $total = [];
            $gstAmount = [];
            foreach ($orderDetails as $key => $orderDetail) {
			    $pc[] = $orderDetail['pc'];
			    $kgs[] = $orderDetail['convert_qty'];
			    $total[] = $orderDetail['with_gst_rate'];
			    $gstAmount[] = $orderDetail['gst_amount'];
			    
			  ?>
			  <tr>
			      <td><?=$key+1?></td>
			      <td><?=$orderDetail['product']?></td>
			      <td align="center"><?=$orderDetail['hsn_sac_code']?></td>
			      <td align="center"><?=$orderDetail['pkg']?></td>
			      <td align="center"><?=$orderDetail['pc']?></td>
			      <td align="center"><?=$orderDetail['convert_qty']?></td>
			      <td align="right"><?=number_format($orderDetail['unit_price'],2)?></td>
			      <td align="right"><?=number_format($orderDetail['per_unit_rate_withoutgst'],2)?></td>
			      <td align="right"><?=number_format($orderDetail['with_gst_rate'],2)?></td>
			  </tr>
			  <?php
			}
			?>
			<tr>
			    <td colspan=2 rowspan=6 align="center">
			        <img src="<?=assets_url('emarkit-pay.png')?>" height="150px">
			    </td>
			    <td rowspan="1" style="border:none" colspan=2>
			        
			    </td>
			    
			    <th><?=array_sum($pc)?></th>
			    <th><?=array_sum($kgs)?></th>
			    <th colspan=2 align="right">Total &#x20b9;</th>
			    
			    <th align="right"><?=number_format(array_sum($total), 2)?></th>
			</tr>
			<tr>
			    <td rowspan="5" colspan=5>
			        <?php 
			        if(count($banks)>0){
    			        foreach($banks as $bKey=>$bank){
    			            echo "<b>Bank: </b>".$bank['bank_name']." <br><b>Account Name: </b> ".$bank['account_name']." <br><b>Account Number: </b> ".$bank['account_number']."<br><b>IFSC Code: </b>".$bank['ifsc_code'];
    			        }
			        }
			        ?>
			    </td>
			    
			    <th align="right">CGST</th>
			    <td align="right"><?=array_sum($gstAmount)/2?></td>
			</tr>
			<tr>
			    <th align="right">SGST</th>
			    <td align="right"><?=array_sum($gstAmount)/2?></td>
			</tr>
			<tr>
			    <th align="right">Roundoff</th>
			    <td align="right"></td>
			</tr>
			<tr>
			    <th align="right">Other Charges</th>
			    <td align="right"></td>
			</tr>
			<tr>
			    <th align="right">Total &#x20b9;</th>
			    <th align="right"><?=number_format(array_sum($total)+array_sum($gstAmount), 2)?></th>
			</tr>
			<tr>
			    <td colspan="8" align="left" style="border-right:none"><b>Amount (in words) :</b><?php echo ucwords($this->numbertowords->getIndianCurrency(array_sum($total)+array_sum($gstAmount))); ?></td>
			    <td align="right" style="border-left:none"><i>E. & O.E</i></td>
			</tr>
			
        
        
            <tr class="declaration">
			    <td rowspan="" colspan=5 valign="top">
			        <small>
			        <b><u>Declaration- </u></b><br>
			        <ul>
			            <li>We declare that this invoice shows the actual price of the goods described and that all particulars are true and correct.</li>
			            <li>We hereby certify that the food/foods mentioned in this Invoice is / are warranted to be of the nature and quality which it /these purports / purport to be</li>
			        </ul>
			        </small>
			     </td>
			     <td colspan=4 valign="top">
			         <small>
			        <b><u>Terms & Conditions</u></b>
			        <ul>
			            <li>Goods once sold will not be taken back under any circumstances</li>
			            <li>Our responsibility ceases on delivery of goods</li>
			            <li>Subject to Mumbai Jurisdiction</li>
			        </ul>
			        </small>
			    </td>
			   </tr>
			   <tr>
			       <td colspan=5>
			           <table width="100%" border=1 style="border-collapse: collapse;" class="taxtable">
			               <?php if(count($gstComputation)>0){ ?>
        			    <tr>
        			    <th  style="height:10px" valign="top"><small>HSN/SAC</small></th>
        			    <th  style="height:10px" valign="top"><small>Taxable Value</small></th>
        			    <th align="center" valign="top"><small>State Tax <br>Rate|Amt</small></th>
        			    <th align="center" valign="top"><small>Central Tax<br>Rate|Amt</small></th>
        			    <th align="center" valign="top" >Total Tax Amt</th>
        			    <?php } ?>
        			</tr>
        			<?php if(count($gstComputation)>0){ ?>
            			
            			<?php 
            			//echo '<pre>';print_r($gstComputation);echo'</pre>';
            			$totalTax = 0;
            			foreach($gstComputation as $gkey=>$compute){
            			    $totalTax = $totalTax+$compute['igst'];
            			?>
            			    <tr class="declaration">
            			        <td align="center"><?=$compute['hsn_sac_code']?></td>
            			        <td align="center"><?=$compute['taxable_value']?></td>
            			        <td align="center"><?=$compute['gst_rate']."|".$compute['sgst']?></td>
            			        <td align="center"><?=$compute['gst_rate']."|".$compute['cgst']?></td>
            			        <td align="right"><?=number_format($compute['igst'],2)?></td>
            			    </tr>
            			<?php
            			}
            			?>
            			<tr class="declaration"><th colspan=4> Total</th><th align="right"><hr><?=number_format($totalTax,2)?></th></tr>
            			<?php
        			}else{
        			    //echo "<tr><td rowspan=5 colspan=5>&nbsp;</td></tr>";
        			}
        			?>
        			           </table>
        			       </td>
        			       <th colspan="4" align="center" style="border-bottom:none">
            			        For <?=$company['company_name']?>
            			        <br><br><br><br>Authorised Signatory
            			   </th>
        			   </tr>
			   
			    
			<!--<tr class="declaration">
			    
			</tr>
			<tr class="declaration">
			    <th  colspan="9" rowspan=3 align="right" style="bottom:0;"><br><br><br>Authorised Signatory</th>
			</tr>-->
        
    </table>        
	
</div>
<style>
    tbody>tr.declaration>td, tbody>tr.declaration>th{
        font-size:11px;
    }
    
    @media screen /*--This is for Screen--*/
    {
        tbody>tr.declaration>td, tbody>tr.declaration>th{
            font-size:10px;
        }
    }
</style>
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
