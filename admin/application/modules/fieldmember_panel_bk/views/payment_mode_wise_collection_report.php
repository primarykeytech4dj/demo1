    <!-- Content Header (Page header) -->
    <?php //echo var_dump($paymentMode);?>
<div id="printableArea_<?php //echo $id; ?>" class="printableArea">

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">View Detail</h3>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
                <?php $formClass = ($this->input->is_ajax_request())?"form-horizontal submit-ajax":"form-horizontal";
	            echo form_open_multipart(custom_constants::payment_consolidation, ['class'=>$formClass, 'id'=>'address_'.time(), 'autocomplete'=>'nope']); ?>
              
                  <div class="row">
                      <div class="col-md-4">
                          <input type="text" class="form-control datepicker" value="<?=!set_value('selected_date')?date('d/m/Y'):set_value('selected_date')?>" name="selected_date">
                      </div>
                      <?php if(in_array(1, $this->session->userdata('roles')) || in_array(2, $this->session->userdata('roles'))){ ?>
                      <div class="col-md-4">
                          <?php echo form_dropdown('employee_id', $deliveryBoys['options'], set_value('employee_id'), ['class'=>'form-control select2', ]); ?>
                      </div>
                      <?php } ?>
                      <div class="col-md-4">
                          <input type="submit" >
                      </div>
                  </div>
                  
                  
                <?php echo form_close(); ?> 
                <?php if(count($paymentDetail)>0){ ?>
             <table id="report" class="table table-bordered table-striped report">
              <thead>
                <tr>
                  <th class="text-center">Sr No</th>
                  <th class="text-center">Delivery Boy | Code</th>
                  <th class="text-center">Party Name</th><th class="text-center">Order Code</th>
                  <?php foreach($paymentMode as $mode){?>
                  <th class="text-center"><?=ucfirst($mode)?></th>
                  <?php }?>
                </tr>
                
              </thead>
              <tbody>
                  <?php foreach($paymentDetail as $dKey=>$detail): ?>
               <tr>
                   <td><?=$dKey+1?></td>
                   <td><?=$detail['delivery_person']?></td>
                   <td><?=ucfirst($detail['first_name'].' '.$detail['middle_name'].' '.$detail['surname'])?></td>
                   <td><?=$detail['order_code']?></td>
                   <?php foreach($paymentMode as $mode){ ?>
                   <td style="text-align:right"><?=number_format($detail[$mode],2)?></td>
                  <?php }?>
                  
               </tr>
               <?php endforeach; ?>
              </tbody>
              <tfoot>
                  <tr>
                      <th colspan=4>Total</th>
                      <?php foreach($paymentMode as $mode){ ?>
                   <th style="text-align:right"><?=number_format($paymentTotal[$mode],2)?></th>
                  <?php }?>
                  </tr>
              </tfoot>
              
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