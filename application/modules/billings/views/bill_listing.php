
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
          <h3 class="box-title"><i class="fa fa-credit-card margin-r-5"></i> Billing</h3>
          <?php echo anchor('billings/areaWiseBill', 'Generate New Bill', ['class'=>"btn btn-primary pull-right", ]); ?>
        </div>
        <!-- /.box-header -->
        <?php echo form_open('billings/print_multiple_bills'); ?>
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Customer</th>
              <th>Company Name</th>
              <th>Site Name</th>
              <th>Invoice No</th>
              <th>Bill From</th>
              <th>Bill To</th>
              <th>Area</th>
              <th>Amount Before Tax</th>
              <th>Amount After Tax</th>
              <th>Print</th>
              <th>Is Active</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($bills as $key=> $bill) {?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo $bill['full_name']; ?></td>
              <td><?php echo $bill['company_name'] ;?></td>
              <td><?php echo $bill['site_name'] ;?></td>
              <td><?php echo (NULL==$bill['invoice_no'])?'MISS/IRB/GST/'.$bill['id']:$bill['invoice_no'] ;?></td>
              <td><?php echo date('d F, Y', strtotime($bill['bill_from_date'])); ?></td>
              <td><?php echo date('d F, Y', strtotime($bill['bill_to_date']));?></td>
              <td><?php echo $bill['area_name'] ;?></td>
              <td><?php echo $bill['amount_before_tax'];?></td>
              <td><?php echo $bill['amount_after_tax'];?></td>
              <td><input type="checkbox" name="data[bills][<?php echo $key ?>]" value="<?php echo $bill['id']; ?>"></td>
              <td><i class="<?php echo ($bill['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                   <!-- <li><?php echo anchor("employees/admin_employee_details/".$bill['id'], 'View', ['class'=>'']);  ?></li> -->
                   <!-- <li><?php echo anchor(custom_constants::edit_employee_url."/".$bill['id'], 'Edit', ['class'=>'']);  ?></li> -->
                   <li><?php echo anchor('billings/print_bill/'.$bill['id'], 'Print Bill', ['class'=>'', 'target'=>'_blank']);  ?></li> 
                  </ul>
                </div>
              </td>  
             <!--  <td>
              <?= anchor("Colleges/admin_Edit/".$bill['id']);?>
             
             </td> -->

            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
                <th>Sr No</th>
                <th>Customer</th>
                <th>Company Name</th>
                <th>Site Name</th>
                <th>Invoice No</th>
                <th>Bill From</th>
                <th>Bill To</th>
                <th>Area</th>
                <th>Amount Before Tax</th>
                <th>Amount After Tax</th>
                <th>Print</th>
                <th>Is Active</th>
                <th>Action</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <input type="submit" name="cmdprint" value="Print" class="btn btn-primary">
        <?php echo form_close(); ?>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<!-- /.content -->

