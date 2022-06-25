    <!-- Content Header (Page header) -->
    
<div id="printableArea_<?php echo $id; ?>" class="printableArea">

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">View Detail</h3>
            <?php //echo anchor(custom_constants::new_role_url, 'New Role', 'class="btn btn-primary pull-right"'); ?>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <?php //print_r($this->session->userdata); ?>
            <table id="report" class="table table-bordered table-striped report">
              <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Product</th>
                  <th>Quantity</th>
                  <th>UOM</th>
                  <th>Price</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach($orderDetails as $key => $v) { ?>
              <tr>
                <td><?php echo $key+1 ;?></td>
                <td><?=$v['product']?></td>
                <td><?=$v['qty']?></td>
                <td><?php $variation = json_decode($v['variation'],0);
                echo (isset($variation->attribute->product_attribute_id) && $variation->attribute->product_attribute_id!=0 && $v['variation'])?$attribute[$variation->attribute->product_attribute_id]:$v['base_uom'];?></td>
                <td><?=number_format((float)$v['unit_price'], 2)?></td>

              </tr>
              <?php }?>
              </tbody>
              <tr><th colspan="4"><p class="pull-right">Amount</p></th>
                <th><?=number_format((float)$v['amount_after_tax'], 2)?></th>
              </tr>
              <tr><th colspan="4"><p class="pull-right">Delivery Charge</p></th>
                <th><?=number_format((float)$v['shipping_charge'], 2)?></th>
              </tr>
              <tr><th colspan="4"><p class="pull-right">Total Amount</p></th>
                <th><?=number_format((float)$v['amount_after_tax']+$v['shipping_charge'], 2)?></th>
              </tr>
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
</div>

<?=anchor('orders/mail_invoice_admin/'.$id, 'Print Bill', ['class'=>'btn btn-default new_window', 'target' => '_blank']);?>