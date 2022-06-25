<?php
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$input['from_date'] =  array(   
              "name" => "from_date",
              "placeholder" => "From Date *",
              "required" => "required",
              "class" => "col-xs-3 form-control datepicker datemask",
              "id"  => "from_date",
              "value" => date('d/m/Y')
               );
               
$input['from_time'] =  array(  
                "type"=>"time",
              "name" => "from_time",
              "placeholder" => "From Time *",
              "required" => "required",
              "class" => "col-xs-3 form-control",
              "id"  => "from_time",
              "value" => date('h:i:s a')
               );
//echo "<div class = "for-group" >";
$input['to_date'] =  array(   
              "name" => "to_date",
              "placeholder" => "To Date *",
              "required" => "required",
              "class" => "col-xs-3 form-control datepicker datemask",
              "id"  => "to_date",
              "value" => date('d/m/Y')
               );
$input['to_time'] =  array(  
                "type"=>"time",
              "name" => "to_time",
              "placeholder" => "To Time *",
              "required" => "required",
              "class" => "col-xs-3 form-control",
              "id"  => "to_time",
              "value" => date('h:i:s a')
               );
if(isset($values_posted))
{ //print_r($values_posted);
  /*foreach($values_posted as $post_name => $post_value)
  {*/
    foreach ($values_posted as $field_key => $field_value) {
      # code...
      if(isset($input[$field_key]['type']) && $input[$field_key]['type']=="checkbox" && $field_value==true){
        $input[$field_key]['checked'] = "checked";
      }else{
        $input[$field_key]['value'] = $field_value;
      //}
    }
  }
}
?>
<!-- Main content -->
<section class="content">
  

<div class="row">
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Ordered Products</h3>

        <!-- <div class="text-center">
          <input type="button" value="Export" class="btn btn-info text-center" onclick="exportToExcel('report', 'ordered_products_<?php echo date('dmyhis'); ?>');">
        </div> -->

      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <?php echo form_open_multipart('orders/orderwisereport', ['class'=>'form-horizontal', 'id' => 'stock_inward_report']);
        if($this->session->flashdata('message')!== FALSE) {
          $msg = $this->session->flashdata('message');?>
          <div class="<?php echo $msg['class'];?>">
            <?php echo $msg['message'];?>
          </div>
        <?php } ?>
        <div class="row">
            
          <div class="col-md-2">
            <div class="form-group">
              <label for="inputFromdate" class="col-sm-2 control-label">From Date</label>
              <div class="col-sm-10">
               <?php echo form_input($input['from_date']);?>
               <?php echo form_error('data[orders][from_date]');?>
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label for="inputFromtime" class="col-sm-2 control-label">From Time</label>
              <div class="col-sm-10">
               <?php echo form_input($input['from_time']);?>
               <?php echo form_error('data[orders][from_time]');?>
              </div>
            </div>
          </div>
          <div class="col-md-2">
            <div class="form-group">
              <label for="inputFromdate" class="col-sm-2 control-label">To Date</label>
              <div class="col-sm-10">
               <?php echo form_input($input['to_date']);?>
               <?php echo form_error('data[orders][to_date]');?>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-sm-hide">
            <div class="form-group">
              <label for="inputTotime" class="col-sm-2 control-label">To Time</label>
              <div class="col-sm-10">
               <?php echo form_input($input['to_time']);?>
               <?php echo form_error('data[orders][to_time]');?>
              </div>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <button type="view_report" class="btn btn-info pull-left" id="View_Report">View Report</button>&nbsp;&nbsp;
              <input type="button" value="Export To Excel" class="btn btn-info text-center" onclick="exportToExcel('report', 'ordered_product_<?php echo date('dmyhis'); ?>.xls');">
            </div>
          </div>
        </div><!-- /row -->
        <?=form_close();?>
        <div class="col-md-12" id="dvData" style="overflow-x:auto">
            <?php //echo '<pre>'; print_r($orders);echo '</pre>'; ?>
            <style>
                td, th{
                    border:1px;
                }
            </style>
          <table id="report" class="table" border=1>
            <thead>
              <tr>
                <th>Category</th>
                <th>Product</th>
                <?php foreach($orders as $key=>$order){
                echo '<th>'.$order['order_code'].' ('.$order['customer_name'].')</th>';
                }
                ?>
                <th>Total</th>
              </tr>
            </thead>
            <tbody>

              <?php if(count($products)>0){

              
                foreach($products as $key => $v){
                    $uom=explode(" ", $v['base_uom']);
                    $total=0;
                    foreach($orders as $okey=>$order){
                        if(isset($orderDetails[$order['order_code']][$v['id']])){
                            $total=$total+array_sum($orderDetails[$order['order_code']][$v['id']]['qty']);
                            //echo '<td>'.$orderDetails[$order['order_code']][$v['id']]['qty'].'</td>';
                        }
                    }
                    if($total==0)
                        continue;
                ?>
                  <tr>
                    <td><?=$v['category_name']?></td>
                    <td><?=$v['product']?></td>
                    <?php //if(isset($orderDetails[$v['id']])){ ?>
                    <?php 
                    $total=0;
                    foreach($orders as $okey=>$order){
                        if(isset($orderDetails[$order['order_code']][$v['id']])){
                            $total=$total+array_sum($orderDetails[$order['order_code']][$v['id']]['qty']);
                            echo '<td>'.array_sum($orderDetails[$order['order_code']][$v['id']]['qty']).'</td>';
                        }else{
                            echo '<td></td>';
                        }
                    }
                    echo '<td>'.$total.'</td>';
                    
                    
               }//end foreach
                
              }else{?>
                <h1>no Data Found</h1>
              <?php }?>
            </tbody>
          </table>
        </div>
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
<script>
    function exportToExcel(tableID, filename = ''){
    var downloadLink;
    var dataType = 'application/vnd.ms-excel';
    var tableSelect = document.getElementById(tableID);
    var tableHTML = tableSelect.outerHTML.replace(/ /g, '%20');
    
    // Specify file name
    filename = filename?filename+'.xls':'excel_data.xls';
    
    // Create download link element
    downloadLink = document.createElement("a");
    
    document.body.appendChild(downloadLink);
    
    if(navigator.msSaveOrOpenBlob){
        var blob = new Blob(['\ufeff', tableHTML], {
            type: dataType
        });
        navigator.msSaveOrOpenBlob( blob, filename);
    }else{
        // Create a link to the file
        downloadLink.href = 'data:' + dataType + ', ' + tableHTML;
    
        // Setting the file name
        downloadLink.download = filename;
        
        //triggering the function
        downloadLink.click();
    }
}

</script>
