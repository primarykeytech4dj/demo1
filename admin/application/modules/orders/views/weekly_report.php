<section class="content-header">
      <h1>
        Module :: Orders
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Order</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?=$formTitle?></h3>
              <?php echo form_open_multipart('orders/export_weekly_report', ['class'=>'form-vertical', 'id'=>'sales_filter']); ?>
                <div class="row">
                  <div class="col-md-3">
                  <label>Zone no</label>
                    <?php 
                    echo form_dropdown('zone[]', $option['zone'],'',"id='zone' class='form-control select2 zone' multiple='multiple'"); 
                    echo form_error('zone');  ?>
                  </div>
                  <div class="col-md-3">
                  <label>Routes </label>
                  <?php 

                  echo form_dropdown('routes[]', ['select Attributes'],'',["id"=>'routes', 'class'=>'form-control select2', 'multiple'=>'multiple']); 
                  echo form_error('routes'); 

                  ?>
                  </div>
            

                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="inputSalesPerson">Sales Person</label>
                        
                          <select name="sale_by" class="form-control select2" id="sale_by">
                              <option value="">-select-</option>
                              <?php foreach($salePersons as $sKey=>$sales){
                              echo '<option value="'.$sales['id'].'">'.$sales['person_name'].' | '.$sales['username'].'</option>';
                              }?>
                          </select>
                        
                      </div>
                    </div>
                  
				
                    <div class="col-md-3">
                      <div class="form-group">
                        <button type="submit" id="export" class="btn btn-primary" >Filter</button>
                      </div>
                    </div>
                  </div>
                
              <?php echo form_close(); ?>
            </div>
        </div>
      </div>
    </div>
</section>
<script type="text/javascript">

// $(document).on('click', '#export', function(){
//   var routes = JSON.stringify($('#routes').val());
//   var zone = JSON.stringify($('#zone').val());
//   var sale_by = $('#sale_by').val();
//   // alert(routes+' '+zone+' '+sale_by);
//   $.ajax({
//     type: 'POST',
//     url:'<?=base_url()?>orders/export_weekly_report',
//     dataType: 'json',
//     data: {'routes' : routes, 'zone' : zone, 'sale_by' : sale_by},
//     // processData: false,
//     // contentType: false,
//     // cache: false,
//     success:function(response) {

//     }
//   });
// })
  </script>