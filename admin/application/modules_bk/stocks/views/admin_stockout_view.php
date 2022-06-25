
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">
        <div class="tab-content">
          <div class="tab-pane active" id="basic_detail"> 
              <div class="box box-info">
                <div class="box-body">
                  <div class="row">
                    <div class="col-md-4">
                      
                        <b>Outward Date:</b>    <?php echo date('d/m/Y', strtotime($stock['outward_date']))?>
                    </div>

                    <div class="col-md-4">
                      <b>Order Code: </b>
                          <?php echo $stock['order_code']?>
                    </div>
                    <div class="col-md-4">
                      <b>Broker: </b><?php echo $broker['first_name']; ?>
                    </div>
                  </div><!-- /row -->
                  <div class="row">
                    <div class="col-md-4">
                      <b>Lorry No: </b><?php echo $stock['lorry_no'];?>    
                    </div>
                  </div>
            <!-- /box-body -->
                    <div class="box-header with-border">
                      <h2 class="box-title">Outward Details</h2>
                    </div>
                    <div class="box-body" style="overflow-x:scroll">
                      <table  id="datatable" class="table table-bordered table-striped example2">
                        <thead>
                          <tr>
                            <!-- <th>Product</th> -->
                            <th>Sr.no</th>
                            <th>Product</th>
                            <th>Coil No</th>
                            <th>thickness</th>
                            <th>width</th>
                            <th>length</th>
                            <th>Piece</th>
                            <th>Quantity</th>
                            <th>Is Active</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php foreach($stockoutDetails as $key=> $v) {  
                          ?>
                            <tr>
                              <td><?php echo $key+1 ;?></td>
                              <td><?php echo $v['product']?></td>
                              <td><?php echo $v['coil_no']?></td>
                              <td><?php echo $v['thickness']?></td>
                              <td><?php echo $v['width']?></td>
                              <td><?php echo $v['length']?></td>
                              <td><?php echo $v['piece']?></td>
                              <td><?php echo $v['qty']?></td>
                              <td><i id="status" class="<?php echo (true==$v['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i></td>
                              <td><?php if ($v['is_active'] == 0) 
                               {?>
                                <p>Deleted</p> <?php } else {?>
                                <button type="button" data-link="stocks/admin_delete" data-id = "<?=$v['id'];?>" data-table="stockout_details" class="btn btn-danger removebutton">Delete</button></td>
                              <?php }?>
                            </tr>
                          <?php }?>
                        </tbody>
                      </table>
                    </div>
                </div>
                <!-- /.box-footer -->
              </div><!-- /box -->
          </div><!-- /tab-pane -->
        </div><!-- /tab-content -->
      </div><!-- end of nav tab -->
    </div><!-- col-md-12 -->
  </div><!-- /nav-tabs-custom -->
</section> <!-- /section-->