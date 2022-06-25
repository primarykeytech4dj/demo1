
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Products
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-user margin-r-5"></i> Product Images</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> Product Images</h3>
          <?php //echo anchor(custom_constants::new_product_url, 'New Product', 'class="btn btn-primary pull-right"'); ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Product Image 1</th>
              <th>Product Image 2</th>
              <th>ProductId</th>
              <th>Featured Image</th>
              <th>Is Active</th>
              <th>Action</th>
              <th></th>
            </tr>
            </thead>
            <tbody>
                <?php foreach($productImages as $key=> $v) {
                	//print_r($v);?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <td><?php echo !empty($v['image_name_1'])?'<img src="'.base_url().'assets/uploads/products/'.$v['image_name_1'].'" alt="'.$v['image_name_1'].'" height="80px" width="80px">':'';?></td>
              <td><?php echo !empty($v['image_name_2'])?'<img src="'.base_url().'assets/uploads/products/'.$v['image_name_2'].'" alt="'.$v['image_name_2'].'" height="80px" width="80px">':'';?></td>
              <td><?php echo $v['product'] ;?></td>
              <td><i class="<?php echo ($v['featured_image']==true)?'alert-success fa fa-check-circle': 'alert-danger fa fa-remove' ;?>"></i></td>
              <td><i class="<?php echo ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               <!-- <?= anchor("Colleges/admin_Edit/".$v['id']);?>  -->
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                  <li><?php echo anchor(custom_constants::admin_product_view.'/'.$v['id'], 'View', ['class'=>'']);  ?></li>
                   <li><?php echo anchor(custom_constants::edit_product_url."/".$v['id'], 'Edit', ['class'=>'']); ?></li>
                  
                  </ul>
                </div>
              </td>  
              <td></td>
             <!--  <td>
              <?= anchor("Colleges/admin_Edit/".$v['id']);?>
             
             </td> -->

            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
	              <th>Sr No</th>
	              <th>Product Image 1</th>
	              <th>Product Image 2</th>
	              <th>Product ID</th>
	              <th>Featured Image</th>
	              <th>Is Active</th>
	              <th>Action</th>
	              <th></th>
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

