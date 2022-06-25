<?php 
if(!defined('BASEPATH')) exit('No direct script access allowed ');


//echo '<pre>';print_r($values_posted); echo '</pre>';
if(isset($values_posted)) {
  foreach ($values_posted as $post_name => $post_value) {
    foreach ($post_value as $field_key => $field_value) {
      $input[$field_key]['value'] = $field_value;
    }
  }
}
?>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Horizontal Form -->
      
      <?php 
      $formClass = ($this->input->is_ajax_request())?"form-horizontal submit-ajax":"form-horizontal";
      echo form_open_multipart('products/customer_products/'.$id, ['id'=>'customerproducts', 'class'=>$formClass]);
     ?>
     <?php
  if($this->session->flashdata('message')!== FALSE && !isset($module)) {
        $msg = $this->session->flashdata('message');?>
        <div class="ajaxresponsetext"></div>  

        <div class="<?php echo $msg['class'];?>">
          <?php echo $msg['message'];?>
        </div>
      <?php } ?>
        <div class="box box-info">
          <div class="box-header with-border">
            <h3 class="box-title">Customer Products</h3>
          </div><!-- /box-header -->
          <!-- form start -->
          <div class="box-body">
            
            <div class="row">
            <div class="col-md-12" style="overflow-x: scroll;">
              <table class="table table-bordered table-striped" style="overflow-x: scroll;">
                <thead>
                  <tr>
                    <th>Brand</th>
                    <th>Service</th>
                    <th>Variation</th>
                    <th>Installation Addres</th>
                    <th>Location</th>
                    <th>Sr. No.</th>
                    <th>Model No.</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if(count($values_posted['customer_services'])>0){?>
                     <?php //echo '<pre>';
                    foreach ($values_posted['customer_services'] as $key => $value) {
                      //print_r($value);?>
                      <tr id="<?=$key?>">
                      
                    <td><input type="hidden" name="customer_services[<?=$key?>][id]" value="<?=$value['id']?>" id="id_<?=$value['id']?>">
                      <?=form_dropdown('customer_services['.$key.'][manufacturing_brand_id]', $option['brand'], $value['manufacturing_brand_id'], ['id'=>'brand_'.$key.'', 'class'=>'form-control select2', 'required', 'style'=>'width:150px']);?></td>
                    <td>
                      <?php echo form_dropdown('customer_services['.$key.'][product_id]', $option['services'], $value['product_id'], ['id'=>'product_id_'.$key.'', 'class'=>'form-control select2 filter', 'required'=>'required', 'style'=>'width:150px;', 'data-link'=>'customer_sites/variationWiseProducts', 'data-target'=>'variation_id_'.$key.'']); ?>
                    </td>
                    <td><?php //echo $value['variation_id'];
                    echo form_dropdown('customer_services['.$key.'][variation_id]',  $option['variation'][$value['id']], $value['variation_id'], ['id'=>'variation_id_'.$key.'', 'class'=>'form-control select2', 'required'=>'required', 'style'=>'width:100px;']); ?>
                    </td>
                    <td>
                      <?//=print_r($option['address']);?>
                      <?php echo form_dropdown('customer_services['.$key.'][installation_address_id]', $option['address'], $value['installation_address_id'], ['class'=>'form-control select2 installation_address', 'id'=>'installation_address_id_'.$key.'', 'required'=>'required', 'style'=>'width:100px;']);?>
                      <?php echo form_error('customer_services['.$key.'][installation_address_id]'); ?>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_services['.$key.'][location]', 'class'=>'form-control', 'type'=>'text', 'id'=>'location_'.$key.'', 'required'=>'required', 'style'=>'width:100px;','value'=>$value['location']]);?>
                      <?php echo form_error('customer_services['.$key.'][location]'); ?>
                    </td>
                    <td><?php echo form_input(['name'=>'customer_services['.$key.'][sr_no]', 'class'=>'form-control', 'id'=>'sr_no_'.$key.'', 'style'=>'width:100px;', 'value'=>$value['sr_no']]); ?>
                      <?php echo form_error('customer_services['.$key.'][sr_no]');?>
                    </td>
                    <td><?php echo form_input(['name'=>'customer_services['.$key.'][model_no]', 'class'=>'form-control', 'id'=>'model_no_'.$key.'', 'style'=>'width:100px;', 'value'=>$value['model_no']]); ?>
                      <?php echo form_error('customer_services['.$key.'][model_no]');?></td>
                    <td>
                      <?php 
                      if($key!==0){
                        ?>
                        <a href="#" data-tr="<?=$key?>" class="removebutton"><span class="glyphicon glyphicon-remove"></span></a>
                        <?php
                      } ?>
                    </td>
                  </tr>
                    <?php }
                  }else{?>

                  <tr id="0">
                      
                    <td><?=form_dropdown('customer_services[0][manufacturing_brand_id]', $option['brand'], '', ['id'=>'brand_0', 'class'=>'form-control select2', 'required', 'style'=>'width:150px']);?></td>
                    <td>
                      <?php echo form_dropdown('customer_services[0][product_id]', $option['services'], '', ['id'=>'product_id_0', 'class'=>'form-control select2 filter', 'required'=>'required', 'style'=>'width:150px;', 'data-link'=>'customer_sites/variationWiseProducts', 'data-target'=>'variation_id_0']); ?>
                    </td>
                    <td><?php echo form_dropdown('customer_services[0][variation_id]',  ''/*$option['variation']*/, '', ['id'=>'variation_id_0', 'class'=>'form-control select2', 'required'=>'required', 'style'=>'width:100px;']); ?>
                    </td>
                    <td>
                      <?php echo form_dropdown('customer_services[0][installation_address_id]', $option['address'], '', ['class'=>'form-control select2 installation_address', 'id'=>'installation_address_id_0', 'required'=>'required', 'style'=>'width:100px;']);?>
                      <?php echo form_error('customer_services[0][installation_address_id]'); ?>
                    </td>
                    <td>
                      <?php echo form_input(['name'=>'customer_services[0][location]', 'class'=>'form-control', 'type'=>'text', 'id'=>'location_0', 'required'=>'required', 'style'=>'width:100px;']);?>
                      <?php echo form_error('customer_services[0][location]'); ?>
                    </td>
                    <td><?php echo form_input(['name'=>'customer_services[0][sr_no]', 'class'=>'form-control', 'id'=>'sr_no_0', 'style'=>'width:100px;']); ?>
                      <?php echo form_error('customer_services[0][sr_no]');?>
                    </td>
                    <td><?php echo form_input(['name'=>'customer_services[0][model_no]', 'class'=>'form-control', 'id'=>'model_no_0', 'style'=>'width:100px;']); ?>
                      <?php echo form_error('customer_services[0][model_no]');?></td>
                    <td></td>
                  </tr>
                    <?php }
                  ?>
                 

                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="10"><button type="button" id="AddMoreService" class="btn btn-info pull-right AddMoreRow">Add More</button>
                    </td>
                  </tr>
                </tfoot>
              </table>
                
            </div>
            </div>
            

        </div><!-- /box-body -->
        <div class="box-footer">  
          <button type="new_college" class="btn btn-info pull-left" id="Save">Save</button>
          <?php echo nbs(3); ?>
          <button type="reset" class="btn btn-info">cancel</button>
        </div>
        <!-- /.box-footer -->
      </div><!-- /box -->
      <?php echo form_close(); ?>
      <?php echo nbs(3); ?>
    </div>
  </div>
</section><!-- /.content -->
