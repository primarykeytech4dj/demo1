<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Menus
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li>
      <?php echo anchor(custom_constants::admin_menu_listing_url, 'Menus', 'title="temp_category" id="temp_menu"'); ?>
    </li>
      
  </ol>
</section>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title pull-left"><i class="fa fa-shopping-bag margin-r-5"></i> Menus</h3>
              <?php echo anchor(custom_constants::new_menu_url, 'New Menu', 'title="temp_category" id="temp_menu" class="pull-right btn btn-primary"'); ?>

            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php 
              /*echo '<pre>';
              print_r($categories);
              echo '</pre>';*/
               ?>
              <table id="example2" class="table table-bordered table-striped example2" style="overflow-x: scroll;">
                <thead>
                <tr>
                  <th>Sr No</th>
                  <th>Menu Type</th>
                  <th>Parent</th>
                  <th>Image</th>
                  <th>Category</th>
                  <th>Priority</th>
                  <th>slug</th>
                  <th>Class</th>
                  <th>Access</th>
                  <th>Is Custom Constant</th>
                  <th>Is Active</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
                  <?php 
                 /* echo '<pre>';
                  print_r($categories);
                  echo '</pre>';*/
                  //$url = $module;
                  foreach($categories as $key=> $category) { //print_r($category); ?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $category['menu'] ;?></td>
                  <td><?php echo $category['parent'] ;?></td>
                  <td><?php echo !empty($category['image_name_1'])?img(['src'=>'uploads/temp/'.$category['image_name_1'], 'width'=>'100px', 'height'=>'100px']):img(['src'=>'uploads/temp/no-image.jpg', 'width'=>'100px', 'height'=>'100px']);
                  ?>
                  </td>
                  <td> <?php echo $category['name'] ;?></td>
                  <td> <?php echo $category['priority'] ;?></td>
                  <td><?php echo $category['slug'];?></td>
                  <td><?php echo $category['class'] ;?></td>
                  <td><?php echo $category['roles'] ;?></td>
                  <td>
                      <i class="<?php echo (true==$category['is_custom_constant'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                      <i class="<?php echo (true==$category['is_active'])?'fa fa-check-circle alert-success':' fa-remove alert-danger';?>"></i> 
                  </td>
                  <td>
                   <div class="input-group-btn"><?php echo $category['id']; ?>
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                       <span class="fa fa-caret-down" ></span>
                      </button>
                      <ul class="dropdown-menu">
                      	<li>
                        <?php 
                        
                        echo anchor(custom_constants::edit_menu_url.'/'.$category['id'], 'Edit', ['class'=>'']);  ?></li> 
                      </ul>
                    </div>
                  </td>  
                
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                  <th>Sr No</th>
                  <th>Menu Type</th>
                  <th>Parent</th>
                  <th>Image</th>
                  <th>Category</th>
                  <th>Priority</th>
                  <th>slug</th>
                  <th>Class</th>
                  <th>Access</th>
                  <th>Is Custom Constant</th>
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
  
