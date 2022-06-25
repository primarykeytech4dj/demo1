
<!-- Content Header (Page header) -->
<!-- <section class="content-header">
  <h1>
    Module :: Billing
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-credit-card margin-r-5"></i> Billing</li>
  </ol>
</section> -->

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-credit-card margin-r-5"></i> GST Summary</h3>
        </div>
        <!-- /.box-header -->
        
        <div class="box-body" style="overflow-x: scroll;">
          <table id="gst_summary" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th colspan="14" style="text-align: center">GSTR-1 Summary</th>
              </tr>
              <tr>
                <th>Sr No</th>
                <th>Receiver GSTIN</th>
                <th>HSN Code</th>
                <th>Description of Goods/Services</th>
                <th>Total Qty</th>
                <th>Receiver Name</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Net Taxable</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>IGST</th>
                <th>Total Invoice Value</th>
                <th>Place of Supply</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($bills as $key=> $bill) {
                $cgst = 0;
                $sgst = 0;
                $igst = 0;

                if((substr(trim($bill['gst_no']), 0, 2)==substr(trim('27BAPPG0863B1ZW'), 0, 2)) || empty($bill['gst_no'])){
                  $cgst = ($bill['amount_before_tax']*(18/100))/2;
                  $sgst = ($bill['amount_before_tax']*(18/100))/2;
                }else{
                  $igst = $bill['amount_before_tax']*(18/100);
                }  ?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo strtoupper($bill['gst_no']); ?></td>
              <td>996812</td>
              <td>Courier Service</td>
              <td><?=$bill['annexure_count']?></td>
              <td><?=$bill['company_name'].' ('.$bill['company_name'].')'?></td>
              <td><?=$bill['invoice_no']?></td>
              <td><?php echo date('d/m/Y', strtotime($bill['bill_to_date']));?></td>
              <td align="right"><?php echo $bill['amount_before_tax'];?></td>
              <td align="right"><?php echo $cgst;?></td>
              <td align="right"><?php echo $sgst;?></td>
              <td align="right"><?php echo $igst;?></td>
              <td align="right"><?=$bill['amount_after_tax']?></td>
              <td>Maharashtra</td>
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>Receiver GSTIN</th>
                <th>HSN Code</th>
                <th>Description of Goods/Services</th>
                <th>Total Qty</th>
                <th>Receiver Name</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Net Taxable</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>IGST</th>
                <th>Total Invoice Value</th>
                <th>Place of Supply</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <div class="box-body" style="overflow-x: scroll;">
          <table id="gst_summary" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th colspan="14" style="text-align: center">Credit Note Summary</th>
              </tr>
              <tr>
                <th>Sr No</th>
                <th>Receiver GSTIN</th>
                <th>HSN Code</th>
                <th>Description of Goods/Services</th>
                <th>Total Qty</th>
                <th>Receiver Name</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Net Taxable</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>IGST</th>
                <th>Total Invoice Value</th>
                <th>Place of Supply</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach($creditNote as $key=> $bill) {
                $cgst = 0;
                $sgst = 0;
                $igst = 0;

                if(substr(trim($bill['gst_no']), 0, 2)==substr(trim('27BAPPG0863B1ZW'), 0, 2)){
                  $cgst = ($bill['credit_note']*(18/100))/2;
                  $sgst = ($bill['credit_note']*(18/100))/2;
                }else{
                  $igst = $bill['credit_note']*(18/100);
                }  ?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo $bill['gst_no']; ?></td>
              <td>996812</td>
              <td>Courier Service</td>
              <td><?=$bill['annexure_count']?></td>
              <td><?=$bill['company_name'].' ('.$bill['company_name'].')'?></td>
              <td><?=$bill['invoice_no']?></td>
              <td><?php echo date('d/m/Y', strtotime($bill['bill_to_date']));?></td>
              <td align="right"><?php echo $bill['amount_before_tax'];?></td>
              <td align="right"><?php echo $cgst;?></td>
              <td align="right"><?php echo $sgst;?></td>
              <td align="right"><?php echo $igst;?></td>
              <td><?=$bill['amount_before_tax']+$cgst+$sgst+$igst?></td>
              <td>Maharashtra</td>
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>Receiver GSTIN</th>
                <th>HSN Code</th>
                <th>Description of Goods/Services</th>
                <th>Total Qty</th>
                <th>Receiver Name</th>
                <th>Invoice No</th>
                <th>Invoice Date</th>
                <th>Net Taxable</th>
                <th>CGST</th>
                <th>SGST</th>
                <th>IGST</th>
                <th>Total Invoice Value</th>
                <th>Place of Supply</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <input type="submit" name="cmdprint" value="Print" class="btn btn-primary">
        
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

