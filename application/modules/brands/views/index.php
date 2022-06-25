    <!-- Main content -->
    <!-- <section class="content"> -->
      <div class="row content">
        <div class="col-md-1">
        </div>
        <div class="col-md-10">
          <div class="box pages">
            <?php if(!empty($pages['featured_image'])){ ?>
              <div class="box-header" style="border: none;
              padding: 25px;
              background-image: url('<?php echo base_url().'assets/uploads/featured_images/'.$pages['featured_image']; ?>'); background-repeat: no-repeat; background-size: 100% 100%;padding-left: 11em; padding-right: 20em; padding-bottom: 5em">
                <h3 class="box-title text-center"><?php //echo $pages['title']; ?></h3>
                <?php //echo anchor(custom_constants::new_customer_url, 'New Customer', 'class="btn btn-primary pull-right"'); ?>
              </div>
              <?php }else{
                ?>
              <div class="box-header">
                <h3 class="box-title text-center"><?php echo $pages['title']; ?></h3>
                <?php //echo anchor(custom_constants::new_customer_url, 'New Customer', 'class="btn btn-primary pull-right"'); ?>
              </div>
                <?php
              } ?>
            <!-- /.box-header -->
            <div class="box-body">
              <?php echo $pages['content']; ?>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <div class="col-md-1">
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    <!-- </section> -->
    <!-- /.content -->