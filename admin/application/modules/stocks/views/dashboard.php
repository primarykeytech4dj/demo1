

<div class="row">
  <div class="col-xs-12">
    <!-- jQuery Knob -->
    <div class="box box-solid">
      <div class="box-header">
        <i class="fa fa-folder-open"></i>

        <h3 class="box-title">Stock Inwards</h3>

        <div class="box-tools pull-right">
          <button type="button" class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i>
          </button>
          <button type="button" class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i>
          </button>
        </div>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
              <div class="inner">
                <h3><?=$totalInward[0]['count']?></h3>

                <p>Current Year Inward</p>
              </div>
              <div class="icon">
                <i class="fa fa-folder-open"></i>
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
              <div class="inner">
                <h3><?=$currentMonthInward[0]['count']?><!-- <sup style="font-size: 20px">%</sup> --></h3>

                <p>Current Month Inward</p>
              </div>
              <div class="icon">
                <i class="fa fa-folder-open"></i>
                <!-- <span class="fa-user"></span> -->
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
              <div class="inner">
                <h3><?=$weeklyInward[0]['count']?></h3>

                <p>Week Inward</p>
              </div>
              <div class="icon">
                <i class="fa fa-folder-open"></i>
                <!-- <i class="ion ion-person-add"></i> -->
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
              <div class="inner">
                <h3><?=$todaysInward[0]['count']?></h3>

                <p>Today's Inward</p>
              </div>
              <div class="icon">
                <i class="fa fa-folder-open"></i>
                <!-- <i class="ion ion-pie-graph"></i> -->
              </div>
              <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <!-- /.row -->

        
        <!-- /.row -->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>


