    <!-- Content Header (Page header) -->
    <?php //echo var_dump($paymentMode);?>
<div id="printableArea_<?php //echo $id; ?>" class="printableArea">

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title">Issue Material</h3>
            </div>
          <!-- /.box-header -->
          <div class="box-body">
              <?php if(!in_array(6, $this->session->userdata('roles'))){ ?>
                <?php $formClass = ($this->input->is_ajax_request())?"form-horizontal submit-ajax":"form-horizontal";
              echo form_open_multipart('orders/issue_material', ['class'=>$formClass, 'id'=>'delivery_boy', 'autocomplete'=>'nope', 'method'=>'get']); ?>
              
                  <div class="row">
                      <!--<div class="col-md-4">
                          <input type="text" class="form-control datepicker" value="<?=!set_value('selected_date')?date('d/m/Y'):set_value('selected_date')?>" name="selected_date">
                      </div>-->
                      
                      <div class="col-md-4">
                          <?php echo form_dropdown('employee_id', $deliveryBoys['options'], $empId, ['class'=>'form-control select2', ]); ?>
                      </div>
                      
                      <div class="col-md-4">
                          <input type="submit" >
                      </div>
                  </div>
                <?php } ?> 
                  
                <?php echo form_close(); ?> 
                <?php if(count($productwiseOrder)>0){ ?>
            <form name="issue_material" method="post" action="orders/issue_material" id="issue_material">
                <input type="hidden" name="delivery_boy" value="<?=$empId?>">
                <table id="report" class="table table-bordered table-striped report">
                  <thead>
                    <tr>
                    <th class="text-center">Sr No</th>
                      <th class="text-center">Products</th>
                      <th class="text-center">In Stock<br><?=date('d/m/Y h:i:s a')?></th>
                      <th class="text-center">Issue Material</th>
                      
                      <th class="text-center">Total Order</th>
                    </tr>
                    
                  </thead>
                  <tbody>
                      <?php 
                      $count=0;
                      $oldTotal = 0;
                      $newTotal = 0;
                      $subTotal = 0;
                      foreach($productwiseOrder as $pKey=>$product):
                        //if($productwiseOrder['old'][$pKey]>0 || $productwiseOrder['new'][$pKey]>0){
                         //echo $productwiseOrder['old'][$pKey];
                        /*$total = 0;
                        $oldIn = 0;
                        $oldOut = 0;*/
                        $stockInHand = (isset($oldStock[$product['product_id']]['in'])?$oldStock[$product['product_id']]['in']:0)-(isset($oldStock[$product['product_id']]['out'])?$oldStock[$product['product_id']]['out']:0);
                        /*if(isset($oldStock[$product['product_id']])){
                            $oldIn = $oldStock[$product['product_id']]['in'];
                        }*/
                        
                        
                        /*if(isset($productwiseOrder['old'][$pKey])){
                            $total = $total+$productwiseOrder['old'][$pKey];
                        }
                            
                        if(isset($productwiseOrder['new'][$pKey])){
                            $total = $total+$productwiseOrder['new'][$pKey];
                        }
                            
                        if(isset($productwiseOrder['return'][$pKey])){
                            $total = $total-$productwiseOrder['return'][$pKey];
                        }*/
                        
                        $qty = $product['qty']-$stockInHand;
                        $total = $stockInHand+$qty;
                      ?>
                   <tr>
                       
                       <td><?=++$count?></td>
                       <td>
                           <input type="hidden" value="<?=$product['product_id']?>" name="daily_user_stocks[<?=$pKey?>][product_id]">
                           <input type="hidden" value="<?=$product['uom']?>" name="daily_user_stocks[<?=$pKey?>][uom]">
                           <?=$product['product']. ' ('.$product['uom'].')' ?></td>
                       <td><?=$stockInHand?></td>
                       <td><input type="text" value="<?=$qty?>"  name="daily_user_stocks[<?=$pKey?>][qty]" <?=($qty<=0)?'readonly':''?>><?=$product['uom']?></td>
                       
                       <td><?=$total?></td>
                       
                   </tr>
                   <?php //} ?>
                   <?php endforeach; ?>
                  </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5"><center></center><input type="submit" class="btn btn-primary" value="Issue Material"></center></td>
                        </tr>
                    </tfoot>
              
            </table>
                
            </form>
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
<script type="text/javascript">
    /*$(document).on("submit", '#issue_material', function(){
        alert("hiii");
        var formData = new FormData();
        console.log(formData);
        return false;
    })*/
    $("#issue_material").submit(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.
    
        var form = $(this);
        var url = form.attr('action');
        
        $.ajax({
               type: "POST",
               url: base_url+url,
               data: form.serialize(), // serializes the form's elements.
               success: function(data)
               {
                   var res = JSON.parse(data);
                   //alert(res.msg);
                   if(res.status=='success'){
                       alert(res.msg);
                       location.reload();
                   }else{
                       alert(res.msg);
                   }
                   //alert(data); // show response from the php script.
               }
             });
    
        
    });
</script>