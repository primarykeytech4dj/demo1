<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Address</h3>
              <?php 
              if(!isset($module)){ 
                echo anchor(custom_constants::new_user_address_url, 'New Address', ['title'=>'New Address', 'class'=>'btn btn-primary pull-right']);
              } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php //echo $this->router->fetch_class().'<br>'.$this->router->fetch_method(); ?>
              <table id="example2" class="table table-bordered table-striped example2" style="overflow-x: scroll;">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Type</th>
                  <th>Short Name / Sites</th>
                  <th>Address 1, Address 2</th>
                  <th>FSSAI No</th>
                  <th>GST No</th>
                  <th>Remark</th>
                  <th>Area</th>
                  <th>City</th>
                  <th>Pincode</th>
                  <th>State</th>
                  <th>Country</th>
                  <th>is Default</th>
                  <th>Is Active</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  <?php 

                  /*echo '<pre>';
                  print_r($address);
                  echo '</pre>';*/
                  //$url = $module;
                  //echo '<pre>';
                  foreach($address as $key=> $v) { 
                    //print_r($v);
                    $url2 = '';
                   ?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $v['address_type'] ;?></td>
                  <td><?php echo $v['site_name'] ;?></td>
                  <td><?php echo $v['address_1'].", ".$v['address_2'];?></td>
                  
                  <td><?php echo $v['fssi'] ;?></td>
                  <td><?php echo $v['gst_no'] ;?></td>
                  
                  <td><?php echo $v['remark'] ;?></td>
                  <td><?php echo $v['area_name'] ;?></td>
                  <td><?php echo $v['city_name']; ?></td>
                  <td><?php echo $v['pincode'];?></td>
                  <td><?php echo $v['state_name'] ;?></td>
                  <td><?php echo $v['name'];?></td>
                  <td>
                      <i class="<?php echo (true==$v['is_default'])?'fa fa-check-circle alert-success':'fa fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                      <i class="<?php echo (true==$v['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                      <?php //print_r($user_detail); ?>
                   <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                       <span class="fa fa-caret-down" ></span>
                      </button>
                      <ul class="dropdown-menu">
                        <?php 
                        $userId = (isset($user_id))?$user_id:$v['user_id'];
                       // $url = '';
                        //$url = $this->router->fetch_class().'/'.$this->router->fetch_method().'/'.$v['user_id'].'?tab=address&address_id='.$v['id'];
                        $url2 = $url.'&address_id='.$v['id'];
                        ?>
                       <li>
                           <a class="btn dynamic-modal load-ajax" data-path="address/admin_edit_form?address_id=<?=$v['id']?>&module=<?=$module?>&user_id=<?=$userId?>&type=<?=$type?>&url=<?=$url?>" 'data-refill-target'="invoice_address_id" data-modal-title="Edit Address" data-model-size="modal-lg">Edit</a>
													
                        <?php 
                        
                        //echo anchor('#', 'Edit', ['class'=>'modal',  'data-path'=>$url2, 'data-refill-target'=>"invoice_address_id", 'data-modal-title'=>"Edit Address", 'data-model-size'=>"modal-lg"]);  ?>
                        </li> 
                        <li><?//=anchor('address/delete_address/'.$v['id'], 'Delete', []);?>
                          <a class="btn btn-default delete-address" data-id="<?=$v['id']?>" id="delete-address" data-path="address/delete_address" data-table="address">Delete</a></li>
                      </ul>
                    </div>
                  </td>  
                
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Type</th>
                    <th>Short Name / Sites</th>
                    <th>Address 1, Address 2</th>
                    <th>FSSAI No</th>
                    <th>GST No</th>
                    <th>Remark</th>
                    <th>Area</th>
                    <th>City</th>
                    <th>Pincode</th>
                    <th>State</th>
                    <th>Country</th>
                    <th>is Default</th>
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
  
