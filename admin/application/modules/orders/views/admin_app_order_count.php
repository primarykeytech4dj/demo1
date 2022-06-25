
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->

          <div class="tab-pane active" id="basic_detail"> 
              <div class="box box-info">
                <div class="box-body">
                  <div class="row">
                    <div class="row">
                      <div class="col-md-6">
                          <b>Order Through App:</b>
                      </div>
                      <div class="col-md-6">
                        <?php echo $order_through_app; ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <b>Order Through App Version 2:</b> 
                      </div>
                      <div class="col-md-6">
                        <?php echo $order_through_app_v2; ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <b>Order Through App Version 3:</b>   
                      </div>
                      <div class="col-md-6">
                        <?php echo $order_through_app_v3; ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <b>POS:</b> 
                      </div>
                      <div class="col-md-6">
                        <?php echo $pos; ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <b>Salesman POS:</b>
                      </div>
                      <div class="col-md-6">
                        <?php echo $salesman_pos; ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <b>App:</b>   
                      </div>
                      <div class="col-md-6">
                        <?php echo $app; ?>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <b>Total Count:</b>  
                      </div>
                      <div class="col-md-6">
                      <?php echo $app +
                          $order_through_app +
                          $order_through_app_v2 +
                          $order_through_app_v3 +
                          $pos +
                          $salesman_pos; ?>
                      </div>
                    </div>
                  </div>
                
                <!-- /.box-footer -->
            </div><!-- /box -->
          
    </div><!-- col-md-12 -->
  </div><!-- /nav-tabs-custom -->
</section> <!-- /section-->