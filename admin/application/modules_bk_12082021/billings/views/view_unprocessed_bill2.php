<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Bill from <?php echo date('d F, Y', strtotime($start_date))." To ".date('d F, Y', strtotime($end_date)); ?></h3>
          
        </div>
        <!-- /.box-header -->
        <?php echo form_open('billings/process_courier_bill', ['class'=>'form-horizontal', 'id'=>'process_bill']); ?>
        <div class="box-body" style="overflow-x: scroll;">
          <input type="hidden" name="data[start_date]" value="<?php echo $start_date; ?>" placeholder="">
          <input type="hidden" name="data[end_date]" value="<?php echo $end_date; ?>" placeholder="">
          <input type="hidden" name="data[customer_id]" value="<?php echo implode(',', $customer_id); ?>" placeholder="">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped " style="overflow-x: scroll;">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Generate Bill</th>
              <th>Customer Name</th>
              <th>Site Name</th>
              <th>Invoice Number</th>
              <th>Service Charge</th>
              <?php 
              foreach ($products as $productKey => $product) {
                echo '<th colspan=8 align="center">'.$product['product'].'</th>';
              } ?>
              <th colspan="<?php echo count($products); ?>"></th>
              <th>Total</th>
            </tr>
            <tr>
              <th colspan="6"></th>
              <?php 
              foreach ($products as $productKey => $product) {
                echo '<th>Start Date</th>';
                echo '<th>End Date</th>';
                echo '<th>Consignment No</th>';
                echo '<th>Weight</th>';
                echo '<th>Mode</th>';
                echo '<th>Reference No</th>';
                echo '<th>Service Charge</th>';
                echo '<th>Amount</th>';
              } ?>
              <th>Total</th>
            </tr>
            </thead>
            <tbody>
              <?php 
              /*echo '<pre>';
              print_r($site_bills);
              echo '</pre>';*/
              $count = 0;
              $grandTotal = 0;
              foreach ($site_bills as $key => $bills) {
                $siteWiseTotal = 0;
                  foreach ($bills as $billKey => $bill) {
                    //echo '<pre>';
                    //echo $bill['service_charge'];
                    //echo '</pre>';
                    ?>
                  <tr>
                    <td>
                      <?php echo ++$count; ?>
                      <input type="hidden" name="data[customer_site_bills][<?php echo $count; ?>][customer_id]" value="<?php echo $key; ?>" placeholder="">
                    </td>
                    <td><input type="checkbox" name="data[customer_site_id][<?php echo $count; ?>]" value="<?php echo $bill['site_id'] ?>" checked="checked"></td>
                    <td><?php echo $bill['customer_name']; ?></td>
                    <td><?php echo $bill['site_name']; ?></td>
                    <td><?php echo 'MISS'.date('dmy').$count; ?></td>
                    <td><?php echo $bill['service_charge'];
                      echo ' '.($bill['service_charge_type']=='PERCENT')?' %':' Rs.'; 
                      ?></td>
                    <?php 
                    foreach ($products as $productKey => $product) {
                      if(isset($bill['service'][$product['id']])){
                      ?>
                      <td><?php echo date('d/m/Y', strtotime($bill['service'][$product['id']]['start_date'])); ?>
                      </td>
                      <td><?php echo date('d/m/Y', strtotime($bill['service'][$product['id']]['end_date'])); ?></td>
                      <td><?php echo $bill['service'][$product['id']]['consign_no']; ?></td>
                      <td><?php echo $bill['service'][$product['id']]['weight']; ?></td>
                      <td><?php echo ucfirst($bill['service'][$product['id']]['mode']); ?></td>
                      <td><?php echo $bill['service'][$product['id']]['ref_no']; ?></td>
                      <td align="right"><?php echo number_format($bill['service'][$product['id']]['cost'], 2); ?></td>
                      <td align="right">
                        <?php 
                        $nod = $bill['service'][$product['id']]['no_of_days'];
                        //$nos = $bill['service'][$product['id']]['no_of_shift'];
                        //$dsl = $bill['service'][$product['id']]['no_of_labour'];
                        //$nsl = $bill['service'][$product['id']]['night_shift_labour_count'];
                        $costperlabour = $bill['service'][$product['id']]['cost'];

                        $total = $costperlabour;///$nod)*$nod*($dsl+$nsl);
                          //$total = (($bill['service'][$product['id']]['no_of_shift'])*($bill['service'][$product['id']]['cost']/$bill['service'][$product['id']]['no_of_days']))*$bill['service'][$product['id']]['no_of_days']*$bill['service'][$product['id']]['no_of_labour'];
                          echo number_format($total,2);
                          $siteWiseTotal = $siteWiseTotal+$total;
                        ?>
                      </td>
                      <?php
                      }else{
                        ?>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td>-</td>
                        <td align="right">0</td>
                        <td align="right">0</td>
                        <?php
                      }
                    } ?>
                      <td>
                        <?php  
                        $grandTotal = $siteWiseTotal+$grandTotal+$total;
                        echo number_format($siteWiseTotal, 2); 
                        ?>
                        
                      </td>
                  
                    <?php
                  }
                echo '</tr>';
              }
              ?>
            </tbody>
            <tfoot>
              <tr>
                <th colspan="6"></th>
                <?php 
                foreach ($products as $productKey => $product) {
                  echo '<th>Start Date</th>';
                  echo '<th>End Date</th>';
                  echo '<th>Bill Days</th>';
                  echo '<th>Dayshift Manpower</th>';
                  echo '<th>Nightshift Manpower</th>';
                  echo '<th>Service Cost</th>';
                  echo '<th>Amount</th>';
                } ?>
                <th colspan=""></th>
              </tr>
              <tr>
                <th>Sr No</th>
                <th>Generate Bill</th>
                <th>Customer Name</th>
                <th>Site Name</th>
                <th>Invoice Number</th>
                <th>Service Charge</th>
                <?php 
                foreach ($products as $productKey => $product) {
                  echo '<th colspan=8 align="center">'.$product['product'].'</th>';
                } ?>
                <th colspan="<?php echo count($products); ?>"></th>
                <th>Total</th>
            </tr>
            
            </tfoot>
          </table>
          <button type="new_bill" class="btn btn-info pull-left">Process Bill</button>&nbsp;&nbsp;&nbsp;&nbsp;
                  <?php //echo nbs(3); ?>
          <button type="submit" class="btn btn-info">cancel</button>
        </div>
        <!-- /.box-body -->
        <?php echo form_close(); ?>
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

