    <!-- Content Header (Page header) -->
    <?php //echo var_dump($paymentMode);?>
<div id="printableArea_<?php //echo $id; ?>" class="printableArea">

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Morning Stock Report</h3>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
              <?php if(!in_array(6, $this->session->userdata('roles'))){ ?>
                <?php $formClass = ($this->input->is_ajax_request())?"form-horizontal submit-ajax":"form-horizontal";
              echo form_open_multipart(custom_constants::morning_stock_report, ['class'=>$formClass, 'id'=>'address_'.time(), 'autocomplete'=>'nope']); ?>
              
                  <div class="row">
                      <!--<div class="col-md-4">
                          <input type="text" class="form-control datepicker" value="<?=!set_value('selected_date')?date('d/m/Y'):set_value('selected_date')?>" name="selected_date">
                      </div>-->
                      
                      <div class="col-md-4">
                          <?php echo form_dropdown('employee_id', $deliveryBoys['options'], set_value('employee_id'), ['class'=>'form-control select2', ]); ?>
                      </div>
                      
                      <div class="col-md-4">
                          <input type="submit" >
                      </div>
                  </div>
                <?php } ?> 
                  
                <?php echo form_close(); ?> 
                <?php if(count($products)>0){ ?>
             <table id="report" class="table table-bordered table-striped report">
              <thead>
                <tr>
                <th class="text-center">Sr No</th>
                  <th class="text-center">Products</th>
                  <th class="text-center">Old Stock<br><?=date('d/m/Y', strtotime($date. ' -1 day'))?></th>
                  <th class="text-center">New Stock<br><?=date('d/m/Y', strtotime($date))?></th>
                  
                  <th class="text-center">Total</th>
                </tr>
                
              </thead>
              <tbody>
                  <?php 
                  $count=0;
                  $oldTotal = 0;
                  $newTotal = 0;
                  $subTotal = 0;
                  foreach($products as $pKey=>$product):
                    if($productwiseOrder['old'][$pKey]>0 || $productwiseOrder['new'][$pKey]>0){
                     //echo $productwiseOrder['old'][$pKey];
                    $total = 0;
                    if(isset($productwiseOrder['old'][$pKey])){
                        $total = $total+$productwiseOrder['old'][$pKey];
                    }
                        
                    if(isset($productwiseOrder['new'][$pKey])){
                        $total = $total+$productwiseOrder['new'][$pKey];
                    }
                        
                    if(isset($productwiseOrder['return'][$pKey])){
                        $total = $total-$productwiseOrder['return'][$pKey];
                    }
                    
                  ?>
               <tr>
                   
                   <td><?=++$count?></td>
                   <td><?=$product['product']. ' ('.$product['base_uom'].')' ?></td>
                   <td><?=(isset($productwiseOrder['old'][$pKey]))?$productwiseOrder['old'][$pKey]:0?></td>
                   <td><?=(isset($productwiseOrder['new'][$pKey]))?$productwiseOrder['new'][$pKey]:0?></td>
                   
                   <td><?=$total?></td>
                   
               </tr>
               <?php } ?>
               <?php endforeach; ?>
              </tbody>
              
              
            </table>
            <?php }else{
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <h2>No Data Found</h2>
                    </div>
                </div>
                <?php
            }
            ?>
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