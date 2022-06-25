<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa margin-r-5"></i> Modules</h3>
              <?php if(!isset($module)){ ?>
                  <a href="<?php echo custom_constants::new_module_url; ?>" title="" class="btn btn-primary pull-right">New Module</a>
              <?php } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php //print_r($this->session->userdata); ?>
              <table id="example2" class="table table-bordered table-striped example2" style="overflow-x: scroll;">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Module</th>
                  <th>Version</th>
                  <th>Slug</th>
                  <th>Route</th>
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
                  foreach($modules as $key=> $v) { //print_r($v); ?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $v['module'] ;?></td>
                  <td><?php echo $v['version'] ;?></td>
                  <td><?php echo $v['slug'] ;?></td>
                  <td><?php echo $v['route']; ?></td>
                  <td>
                      <i class="<?php echo (true==$v['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                   <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                       <span class="fa fa-caret-down" ></span>
                      </button>
                      <ul class="dropdown-menu">
                        <?php 
                        
                        $url = custom_constants::edit_module_url."/".$v['id']; 
                        
                      ?>
                       <li>
                        <?php 
                        
                        echo anchor($url, 'Edit', ['class'=>'']);  ?></li> 
                      </ul>
                    </div>
                  </td>  
                
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Module</th>
                    <th>Version</th>
                    <th>Slug</th>
                    <th>Route</th>
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
  
