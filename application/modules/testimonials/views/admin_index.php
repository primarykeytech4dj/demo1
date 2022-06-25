
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Module :: Testimonials
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
    <li class="active"><i class="fa fa-user margin-r-5"></i> Testimonials</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <div class="box-header">
          <h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> Testimonials</h3>
          <?php echo anchor(custom_constants::new_testimonial_url, 'New Testimonials', 'class="btn btn-primary pull-right"'); ?>
        </div>
        <!-- /.box-header -->
        <div class="box-body" style="overflow-x: scroll;">
          <?php //print_r($this->session->userdata); ?>
          <table id="example2" class="table table-bordered table-striped example2">
            <thead>
            <tr>
              <th>Sr No</th>
              <th>Name</th>
              <th>Company</th>
              <th>Designation</th>
              <th>Comment</th>
              <th>Image</th>
              <th>Is Active</th>
              <th>Action</th>
              
            </tr>
            </thead>
            <tbody>
                <?php foreach($testimonials as $key=> $v) {
                	//print_r($v);?>
            <tr>
              <td><?php echo $key+1 ;?></td>
              <!-- <td><?php echo !empty($v['profile_img'])?'<img src="'.base_url().'assets/uploads/profile_images/'.$v['profile_img'].'" alt="'.$v['first_name'].'" height="80px" width="80px">':'';?></td> -->
              <td><?php echo $v['name'];?></td>
              <td><?php echo $v['company'];?></td>
              <td><?php echo $v['designation'];?></td>
              <td><?php echo word_limiter($v['comment'], 20);?></td>
              <td><?php echo !empty($v['image'])?'<img src="'.base_url().'assets/uploads/testimonials/'.$v['image'].'" alt="'.$v['name'].'" height="80px" width="80px">':'';?></td>
             <!--  <td><?php echo $v['featured_image'];?></td> -->
              <td><i class="<?php echo ($v['is_active']==true)?'alert-success fa fa-check-circle':'alert-danger fa fa-remove' ;?>"></i></td>
              <td>
               <!-- <?= anchor("Colleges/admin_Edit/".$v['id']);?>  -->
               <div class="input-group-btn">
                   <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                   <span class="fa fa-caret-down" ></span></button>
                 <ul class="dropdown-menu">
                  <!-- <li><?php echo anchor(custom_constants::admin_testimonial_view.'/'.$v['id'], 'View', ['class'=>'']);  ?></li> -->
                   <li><?php echo anchor(custom_constants::new_testimonial_url."/".$v['id'], 'New', ['class'=>'']); ?></li>
                  
                  </ul>
                </div>
              </td>  
              
             <!--  <td>
              <?= anchor("Colleges/admin_Edit/".$v['id']);?>
             
             </td> -->

            </tr>
            <?php }?>
            </tbody>
            <tfoot>
              <tr>
              	
	             <th>Sr No</th>
              <th>Name</th>
              <th>Company</th>
              <th>Designation</th>
              <th>Comment</th>
              <th>Image</th>
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

