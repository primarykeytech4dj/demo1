
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
                      
                        <b>Inward Date:</b>    <?php echo date('d/m/Y', strtotime($stock['inward_date']))?>
                    </div>

                    <div class="col-md-4">
                      <b>Stock Code: </b>
                          <?php echo $stock['stock_code']?>
                    </div>
                    <div class="col-md-4">
                      <b>Purchase Order: </b>
                          <?php echo $stock['po_no']?>
                    </div>
                    <div class="col-md-4">
                      <b>Vendor: </b><?php echo $vendor['company_name']; ?>
                    </div>
                  
                    <div class="col-md-4">
                      <b>Godown/Cutter: </b><?php echo $warehouse['warehouse'];?>    
                    </div>
                  </div>
            <!-- /box-body -->
                    <div class="box-header with-border">
                      <h2 class="box-title">Inward Details</h2>
                    </div>
                    <div class="box-body" style="overflow-x:scroll">
                      <table  id="datatable" class="table table-bordered table-striped example2">
                        <thead>
                          <tr>
                            <!-- <th>Product</th> -->
                            <th>Sr.no</th>
                            <th>Product</th>
                            <th>Variant</th>
                            <th>Batch No</th>
                            <th>HSN/SAC Code</th>
                            <th>Inward Qty</th>
                            <th>Barcode</th>
                            <th>Unit Price</th>
                            <th>Tax</th>
                            <th>Net Cost</th>
                            <th>Is Active</th>
                            <th>Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                          //echo getcwd();
                          foreach($stockDetails as $key=> $v) {
                            $text = $stock['stock_code'].''.$v['id'];
                         ?>
                            <tr>
                              <td><?php echo $key+1 ;?></td>
                              <td><?php echo $v['product']?></td>
                              <td><?php echo $v['uom']?></td>
                              <td><?php echo $v['lot_no']?></td>
                              <td><?php echo $v['hsn_sac_code']?></td>
                              <td><?php echo $v['qty']?></td>
                              <td class="barcode" id="barcode_<?=$v['id']?>">
                                <img src="<?=base_url()?>qrcode/phpqrcode/barcode.php?codetype=Code39&size=40&text=<?=$text?>&print=true'" height="50px">
                              </td>
                              <td><?php echo $v['unit_price']?></td>
                              <td><?php echo $v['tax']?></td>
                              <td><?php echo ($v['qty']*$v['unit_price'])+((($v['tax'])/100.00)*($v['qty']*$v['unit_price']))?></td>
                              <td><i id="status" class="<?php echo (true==$v['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i></td>
                              <td>
                                
                                <?php 
                                if($v['is_active'] == 0) 
                                {?>
                                <i class="fa fa-trash"></i> <?php } else {
                                  echo anchor('#', '<i class="fa fa-trash"></i>', ['data-link'=>'stocks/admin_delete', 'data-id'=>$v['id'], 'data-table'=>'stock_details', 'class'=>'removebutton']);
                                  ?>
                                </td>
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