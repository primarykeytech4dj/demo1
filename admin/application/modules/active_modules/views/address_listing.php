<!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><i class="fa fa-map-marker margin-r-5"></i> Address</h3>
              <?php if(!isset($module)){ ?>
                  <a href="<?php echo custom_constants::new_user_address_url; ?>" title="" class="btn btn-primary pull-right">New Address</a>
              <?php } ?>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="overflow-x: scroll;">
              <?php 
              foreach ($address as $key => $add) {
                # code...
              }
               ?>
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
  
