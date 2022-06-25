<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Customer Services</h3>
              <?php 
              if(!isset($module)){ 
                echo anchor(custom_constants::new_user_address_url, 'New Service', ['title'=>'New Address', 'class'=>'btn btn-primary pull-right']);
              } ?>
            </div>
            <!-- /.box-header -->
            <?php echo form_open('calls/admin_add', ['method'=>'get']); ?>
            <div class="box-body" style="overflow-x: scroll;">
              <?php //echo $this->router->fetch_class().'<br>'.$this->router->fetch_method(); ?>
              <table id="example2" class="table table-bordered table-striped example2" style="overflow-x: scroll;">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Customer</th>
                  <th>Brand</th>
                  <th>Product</th>
                  <th>Variation</th>
                  <th>Installation Address</th>
                  <th>Location</th>
                  <th>Sr. No</th>
                  <th>Model No.</th>
                  <th>Is Active</th>
                  <th>Create Call</th>
                  <th>Action</th> 
                </tr>
                </thead>
                <tbody>
                  <?php 
                if(count($products)>0){
                  foreach($products as $key=> $v) { 
                    //print_r($v);
                    $url2 = '';
                   ?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $v['customer_name'] ;?></td>
                  <td><?php echo $v['brand'] ;?></td>
                  <td><?php echo $v['product'];?></td>
                  <td><?php echo $v['variation'] ;?></td>
                  <td><?php echo $v['installation_address'];?></td>
                  <td><?php echo $v['location']; ?></td>
                  <td><?php echo $v['sr_no'];?></td>
                  <td><?php echo $v['model_no'] ;?></td>
                  <td>
                      <i class="<?php echo (true==$v['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                    
                    <?php if(empty($v['call_log_id'])){?>
                    <input type="hidden" name="customer_id" value="<?=$v['customer_id']?>">
                      <input type="checkbox" name="customer_service_id[<?php echo $key;?>]" value="<?php echo $v['id']; ?>" class=" check_0"><?php echo anchor("calls/admin_add?customer_service_id=".$v['id'].'&customer_id='.$v['customer_id'], 'Create Call', ['class'=>'load-ajax', 'data-model-size'=>'model-lg', 'data-modal-title'=>'Create Call', 'data-path'=>"calls/admin_add_call?customer_service_id=".$v['id'].'&customer_id='.$v['customer_id']]);
                    }
                    if(!empty($v['customer_service_id'])){?>
                      <input type="checkbox" name="call_customer_products[<?php echo $v['id'];?>]" value="<?php echo $v['id']; ?>" class="check_0"><?php echo anchor("dynamic_forms/add_question?type=backend&module=calls&call_log_id=".$v['call_log_id']."&customer_service_id=".$v['customer_service_id'], 'Add Questinaries', []);
                    }?>
                    <!-- <?php '<br/>';?>
                    <?php   ?> -->
                  </td>
                  <td>
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                       <span class="fa fa-caret-down" ></span>
                      </button>
                      <ul class="dropdown-menu">
                        <?php 
                        $userId = $v['customer_id'];
                        $url2 = $url.'&address_id='.$v['id'];
                        if($this->router->fetch_class()=='address')
                          $url2 = '#';
                      ?>
                       <li>
                          <?php if(!empty($v['customer_service_id'])){?>
                       <button type="button" class="btn btn-default load-ajax" data-path="add_question/add_question/<?=$v['id']?>" data-target="#modal-default" data-model-size="model-lg">Add Questionaries</button> 
                      <?php }

                       ?>
                        </li> 
                      </ul>
                    </div> 
                  </td>  
                
                </tr>
                
                <?php }
                ?>
                <tr><th colspan="12"><span class="pull-right"><input type="checkbox" id="checkAll" data-id="0" class="checkAll">Check All</span></th></tr>
                <?php
                }
                ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Customer</th>
                    <th>Brand</th>
                    <th>Product</th>
                    <th>Variation</th>
                    <th>Installation Address</th>
                    <th>Location</th>
                    <th>Sr. No</th>
                    <th>Model No.</th>
                    <th>Is Active</th>
                    <th>Create Call</th>
                    <th>Action</th> 
                  </tr>
                </tfoot>
                <tr>
                </tr>
              </table>
              <input type="submit" name="update" value="Create Call" class="btn btn-primary">
            <?=form_close();?>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  
