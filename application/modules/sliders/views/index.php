<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Module :: Sliders</h3>
              <?php 
              if(!isset($module)){ 
               // echo anchor(custom_constants::new_slider_url, 'New Slider', ['title'=>'New Slider', 'class'=>'btn btn-primary pull-right']);
              } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php //echo $this->router->fetch_class().'<br>'.$this->router->fetch_method(); ?>
              <table id="example2" class="table table-bordered table-striped example2" style="overflow-x: scroll;">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Name</th>
                  <th>Slider Code</th>
                  <th>Image Count</th>
                  <th>Css</th>
                  <th>Js</th>
                  <th>Active</th>
                  <th>Created</th>
                  <th>Modified</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                  /*echo '<pre>';
                  print_r($address);
                  echo '</pre>';*/
                  //$url = $module;
                  foreach($sliders as $key=> $v) { //print_r($v); ?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $v['name'] ;?></td>
                  <td><?php echo $v['slider_code'] ;?></td>
                  <td><?php echo $v['slide_count'];?></td>
                  <td><?php echo $v['css'] ;?></td>
                  <td><?php echo $v['js']; ?></td>
                  <td><i class="<?php echo (true==$v['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> </td>
                  <td><?php echo date('d F, Y h:i:s a', strtotime($v['created']));?></td>
                  <td><?php echo date('d F, Y h:i:s a', strtotime($v['modified']));?></td>
                  <td>
                   <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                       <span class="fa fa-caret-down" ></span>
                      </button>
                      <!-- <ul class="dropdown-menu">
                       <li>
                        <?php 
                        
                        echo anchor('sliders/adminedit/'.$v['id'], 'Edit', ['class'=>'']);  ?></li> 
                      </ul> -->
                    </div>
                  </td>  
                
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Name</th>
                    <th>Slider Code</th>
                    <th>Image Count</th>
                    <th>Css</th>
                    <th>Js</th>
                    <th>Active</th>
                    <th>Created</th>
                    <th>Modified</th>
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
  
