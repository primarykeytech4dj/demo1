
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
   <span class="glyphicon glyphicon-shopping-cart"></span> <?=$title?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-shopping-cart margin-r-5"></i> Orders</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><?=$heading?></h3>
          <?php echo anchor(custom_constants::new_order_url, 'New Order (Project)', 'class="btn btn-primary pull-right"'); ?>
          
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <!-- <th>Customer</th> -->
              <th>Broker</th>
              <th>Invoice Number</th>
              <th>Invoice Date</th>
              <th>Product</th>
              <th>Grade</th>
              <th>Coil</th>
              <th>Thickness</th>
              <th>Width</th>
              <th>Length</th>
              <!-- <th>HSN Code</th> -->
              <th>Due On</th>
              <th>Quantity</th>
              <th>UOM</th>
              <!-- <th>Unit Price</th>
              <th>Amount Before Tax</th>
              <th>Amount After Tax</th> -->
              <th>Remark</th>
              <th>Is Active</th>
              <th>Action</th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($orderDetail as $key=> $v) {
                  //echo '<pre>';print_r($v);echo '</pre>';?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              
              <!-- <td><?php echo $v['customer_name'] ;?></td> -->
              <td><?php echo $v['broker_name'] ;?></td>
              <td><?php echo $v['order_code'] ;?></td>
              <td><?php echo ($v['order_date']!='0000-00-00')?date('d F, Y',strtotime($v['order_date'])):'NA'; ?></td>
              <td><?=$v['product']?></td>
              <td><?=$v['grade']?></td>
              <td><?=$v['coil_no']?></td>
              <td><?=$v['thickness']?></td>
              <td><?=$v['width']?></td>
              <td><?=$v['length']?></td>
              <!-- <td><?=$v['hsn_code']?></td> -->
              <td><?=$v['due_on']?></td>
              <td><?=$v['qty']?></td>
              <td><?=$v['uom']?></td>
              <!-- <td><?=$v['unit_price']?></td>
              <td><?php echo $v['amt_before_tax']; ?></td>
              <td><?php echo $v['amt_after_tax']; ?></td> -->
              <td><?php echo $v['remark']; ?></td>
              <td><i class="<?php echo ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                   <!-- <li><?php echo anchor(custom_constants::edit_order_url."/".$v['id'], 'Edit', ['class'=>'']);  ?></li> -->
                   <li><?php echo anchor('stocks/out?order_detail_id='.$v['id'], 'Dispatch', ['class'=>'']);  ?></li>
                  </ul>
                </div>
              </td>  
            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
              <th>Sr No</th>
              <!-- <th>Customer</th> -->
              <th>Broker</th>
              <th>Invoice Number</th>
              <th>Invoice Date</th>
              <th>Product</th>
              <th>Grade</th>
              <th>Coil</th>
              <th>Thickness</th>
              <th>Width</th>
              <th>Length</th>
              <!-- <th>HSN Code</th> -->
              <th>Due On</th>
              <th>Quantity</th>
              <th>UOM</th>
             <!--  <th>Unit Price</th>
              <th>Amount Before Tax</th>
              <th>Amount After Tax</th> -->
              <th>Remark</th>
              <th>Is Active</th>
              <th>Action</th>
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

