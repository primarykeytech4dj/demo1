<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Order Payment Detail
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li>
      <?php echo anchor('#', 'Order Payment Detail', 'title="order_bill_payment"'); ?>
    </li>
    
  </ol>
</section>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-rupee margin-r-5"></i> Order Payment Detail</h3>
              
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php 
              /*echo '<pre>';
              print_r($categories);
              echo '</pre>';*/
               ?>
              <table id="example2" class="table table-bordered table-striped example2" style="overflow-x: scroll;">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Customer Name</th>
                  <th>Cash</th>
                  <th>Paytm</th>
                  <th>UPI</th>
                  <th>Cheque</th>
                  <th>NEFT</th>
                  <th>RTGS</th>
                  <th>Payment Date</th>
                  <th>Is Active</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                 
                  foreach($PaymentDetails as $key=> $detail) { ?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $detail['customer_name'] ;?></td>
                  
                  <td> <?php echo $detail['cash'] ;?></td>
                  <td><?php echo $detail['paytm_no'];?></td>
                  <td><?php echo $detail['upi_no'] ;?></td>
                  <td><?php echo $detail['cheque_no']; ?></td>
                  <td><?php echo $detail['neft'];?></td>
                  <td><?php echo $detail['rtgs'] ;?></td>
                  <td><?php echo $detail['payment_date'];?></td>
                  <td>
                      <i class="<?php echo (true==$detail['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                  <th>Sr No</th>
                  <th>Customer Name</th>
                  <th>Cash</th>
                  <th>Paytm</th>
                  <th>UPI</th>
                  <th>Cheque</th>
                  <th>NEFT</th>
                  <th>RTGS</th>
                  <th>Payment Date</th>
                  <th>Is Active</th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  
