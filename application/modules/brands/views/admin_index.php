
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Module :: Pages
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Pages</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Customer</h3>
              <?php echo anchor(custom_constants::new_page_url, 'New Page', 'class="btn btn-primary pull-right"'); ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php //print_r($this->session->userdata); ?>
              <table id="example2" class="table table-bordered table-striped example2">
                <thead>
                  <tr>
                    <th>Sr No</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Slug</th>
                    <th>Featured Image</th>
                    <th>Active/Inactive</th>
                    <th>Created</th>
                    <th>Modified</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($pages as $key => $page) {?>
                <tr>
                  <td><?php echo $key+1 ;?></td>
                  <td><?php echo $page['title'] ;?></td>
                  <td><?php echo word_limiter($page['content'], 50) ;?></td>
                  <td><?php echo $page['slug'] ;?></td>
                  <td><img src="<?php echo base_url(); ?>assets/uploads/featured_images/<?php echo $page['featured_image'] ;?>" alt="<?php echo $page['featured_image'];?>" height="80px" width="80px"></td>
                  <td><i class="<?php if($page['is_active']==1){ echo 'alert-success fa fa-check-circle'; }elseif($page['is_active']==0){ echo 'alert-danger fa fa-remove'; }elseif($page['is_active']==2){ echo 'alert-danger fa fa-thrash';} ?>"></i></td>
                  <td><?php echo $page['created']; ?></td>
                  <td><?php echo $page['modified']; ?></td>
                  <td>
                    <div class="input-group-btn">
                      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Action
                      <span class="fa fa-caret-down" ></span></button>
                      <ul class="dropdown-menu">
                        <li><?php echo anchor("pages/view/".$page['slug'], 'View', ['class'=>'', 'target'=>'_new']);  ?></li>
                        <li><?php echo anchor(custom_constants::edit_page_url."/".$page['id'], 'Edit', ['class'=>'']);  ?></li>
                      </ul>
                    </div>
                  </td>  
                </tr>
                <?php }?>
                </tbody>
                <tfoot>
                  <tr>
                    <th>Sr No</th>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Slug</th>
                    <th>Featured Image</th>
                    <th>Active/Inactive</th>
                    <th>Created</th>
                    <th>Modified</th>
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