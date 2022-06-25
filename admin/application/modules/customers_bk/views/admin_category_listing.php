<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <?=$title?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li>
      <?php echo anchor(custom_constants::admin_customer_category_listing_url, 'Customer Categories', 'title="customers" id="customer_categories"'); ?>
    </li>
    
  </ol>
</section>
<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?=$heading?></h3>
              <?php if(!isset($module)){ ?>
                <?php echo anchor(custom_constants::new_customer_category_url, 'New Category', 'class="btn btn-primary pull-right"'); ?>
                 <!--  <a href="<?php echo custom_constants::new_customer_category_url; ?>" title="" class="btn btn-primary pull-right">New Category</a> -->
              <?php } ?>
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
                  <th>Parent</th>
                  <!-- <th>Image</th> -->
                  <th>Category</th>
                  <th>slug</th>
                  <th>Description</th>
                  <!-- <th>Gst</th> -->
                  <th>Meta Title</th>
                  <th>Meta Keyword</th>
                  <th>Meta Description</th>
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
                  <td><?php echo $category['parent'] ;?></td>
                  <!-- <td><?php echo !empty($category['image_name_1'])?img(['src'=>'../content/uploads/vendor_categories/'.$category['image_name_1'], 'width'=>'100px', 'height'=>'100px']):img(['src'=>'../content/uploads/vendor_categories/no-image.jpg', 'width'=>'100px', 'height'=>'100px']);
                  ?>
                  </td> -->
                  <td> <?php echo $category['category_name'] ;?></td>
                  <td><?php echo $category['slug'];?></td>
                  <td><?php echo $category['description'] ;?></td>
                  <!-- <td><?php echo $category['gst']; ?></td> -->
                  <td><?php echo $category['meta_title'];?></td>
                  <td><?php echo $category['meta_keyword'] ;?></td>
                  <td><?php echo $category['meta_description'];?></td>
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
                        $url = custom_constants::edit_customer_category_url;
                        echo anchor($url."/".$category['id'], 'Edit', ['class'=>'']);  ?></li> 
                      </ul>
                    </div>
                  </td>  
                
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Parent</th>
                    <!-- <th>Image</th> -->
                    <th>Category</th>
                    <th>slug</th>
                    <th>Description</th>
                    <!-- <th>Gst</th> -->
                    <th>Meta Title</th>
                    <th>Meta Keyword</th>
                    <th>Meta Description</th>
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
  
