<?php
  
// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//echo "<div class = "for-group" >";
$input['name'] = array(
            "name" => "data[sliders][name]",
            "placeholder" => "Name(s) *",
            "max_length" => "255",
            "required" => "required",
            "class"=> "form-control",
            "id" => "name",
          );

$input['slider_code'] = array(
            "name" => "data[sliders][slider_code]",
            "placeholder" => "Slider Code *",
            "max_length" => "255",
            "class" => "form-control",
            'id' => "slider_code"
          );

$input['js'] = array(
            "name" => "data[sliders][js]",
            "placeholder" => "js",
            "class" => "form-control",
            'id' => "js",
            'rows'=> 4
          );

$type   = array(
        'id'  =>  'type',
        //'required'  =>  'required',
        'class' =>  'form-control select2 showInput',
        'style' => 'width:100%',
        );
$filterText   = array(
        'id'  =>  'filter_text_0',
        //'required'  =>  'required',
        'class' =>  'form-control select2',
        'style' => 'width:100%',
        );

$input['css'] = array(
            "name" => "data[sliders][css]",
            "placeholder" => "CSS ",
            "class" => "form-control",
            'id' => "css",
            'rows' => 4
          );

$input['title_1'] = array(
          "name" => "slider_details[0][title_1]",
          "placeholder" => "title 1 *",
          "max_length" => "64",
          "class" => "form-control",
          "id" => "title_1",
          );

$input['title_2']  = array(
          "name" => "slider_details[0][title_2]",
          "placeholder" => "title 2 *",
          "max_length" => "64",
          "class" => "form-control",
          "id" => "title_2",
        );

$input['short_description']  = array(
          "name" => "slider_details[0][short_description]",
          "placeholder" => "Description",
          "max_length" => "255",
          "class" => "form-control",
          "id" => "short_description",
        );

$input['link']  = array(
          "name" => "slider_details[0][link]",
          "placeholder" => "Link *",
          "max_length" => "64",
          "class" => "form-control",
          "id" => "link",
        );
$input['priority']  = array(
          "name" => "slider_details[0][priority]",
          "placeholder" => "Priority *",
          "max_length" => "64",
          "class" => "form-control",
          "id" => "priority",
          "type" => 'number'
        );

// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted))
{ 
  //print_r($values_posted);

  foreach($values_posted as $post_name => $post_value)
  { 
    foreach ($post_value as $field_key => $field_value) {
      if(isset($input[$field_key]['type']) && $input[$field_key]['type']=="checkbox" && $field_value==true){
        $input[$field_key]['checked'] = "checked";
      }else{
        $input[$field_key]['value'] = $field_value;
      }
    }
  }
}

?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
      Module :: Sliders
  </h1>
  <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
      <li>
        <?php echo anchor(custom_constants::admin_slider_listing_url, 'Slider', ['title'=>'Slider']); ?>
      </li>
      <li>
        <?php echo anchor(custom_constants::new_slider_url, 'New Slider'); ?>
      </li>
  </ol>
</section>
<!--Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <!-- Custom Tabs -->
      <div class="nav-tabs-custom">

        <div class="tab-content">
          
            <?php //echo form_open_multipart(custom_constants::new_user_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
              //print_r($this->session);
            echo form_open_multipart(custom_constants::new_slider_url, ['class'=>'form-horizontal', 'id'=>'new_slider']);
              
              if($this->session->flashdata('message') !== FALSE) {
                $msg = $this->session->flashdata('message');?>
                <div class = "<?php echo $msg['class'];?>">
                  <?php echo $msg['message'];?>
                </div>
              <?php } ?>
              <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> New Slider</h3>
                </div><!-- /box-header -->
                <!-- form start -->
                <div class="box-body">
                  
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputName" class="col-sm-2 control-label">Name</label>
                        <div class="col-sm-10">
                          <?php echo form_input($input['name']); ?>
                          <?php echo form_error('data[sliders][name]'); ?>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputCode" class="col-sm-2 control-label">Code</label>
                        <div class="col-sm-10">
                                    <?php echo form_input($input['slider_code']);?>
                          <?php echo form_error('data[sliders][slider_code]');?>
                                </div>
                      </div>
                    </div>
                  </div>
                 
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputJS" class="col-sm-2 control-label">JS</label>
                        <div class="col-sm-10">
                                    <?php echo form_textarea($input['js']);?>
                          <?php echo form_error('data[sliders][js]');?>
                                </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="inputCSS" class="col-sm-2 control-label">CSS</label>
                        <div class="col-sm-10">
                                    <?php echo form_textarea($input['css']);?>
                          <?php echo form_error('data[sliders][css]');?>
                                </div>
                      </div>
                    </div>
                  </div>
                  <hr>

                  <div class="box-haeder with-border">
                    <h2 class="box-title">Slider Images</h2>
                  </div>
                  <div class="box-body" style="overflow-x:scroll">
                    <table class="table" id="target">
                      <thead>
                        <tr>
                            <th>Filter On</th>
                          <th>Type</th>
                          <th>Title 1</th>
                          <th>Title 2</th>
                          <th>Description</th>
                          <th>Image/Youtube url</th>
                          <th>Priority</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr id="0">
                            <td><?php echo form_dropdown('slider_details[0][filter_text]',['default'=>'Default', 'old app'=>'Old app'], 'default',$filterText); ?></td>
                          <td><?php echo form_dropdown('slider_details[0][type]',$imageType, '',$type); ?></td>
                          <td><?php echo form_input($input['title_1']);?>
                            <?php echo form_error('data[slider_details][title_1]');?>
                          </td>
                          <td><?php echo form_input($input['title_2']);?>
                            <?php echo form_error('data[slider_details][title_2]');?></td>
                            <td><?php echo form_input($input['short_description']);?>
                            <?php echo form_error('data[slider_details][short_description]');?></td>
                         
                          <td id="text"><input type="file" name="slider_details[0][image]", id="image_0", class="form-control"></td>
                          <td id="video" style="display: none"><input type="text" name="slider_details[0][image]", id="video_0", class="form-control"></td>
                          <td><?php echo form_input($input['priority']);?>
                            <?php echo form_error('data[slider_details][priority]');?></td>
                          <td></td>
                        </tr>
                      </tbody>
                      <tfoot>
                        <tr>
                            <td colspan="9"><button type="button" id="AddMoreProductImages" class="btn btn-info pull-right AddMoreRow">Add More</button>
                            </td>
                          </tr>
                      </tfoot>
                    </table>
                  </div>                <div class="box-footer">  
                  <button type="new_college" class="btn btn-info pull-left">Submit</button> &nbsp;&nbsp;&nbsp;&nbsp;
                  
                </div>
                <!-- /.box-footer -->
              </div><!-- /box -->
              </div>
            <?php echo form_close(); ?> 
          
          
          
        </div><!-- /tab-content -->
      </div><!-- end of nav tab -->
    </div><!-- col-md-12 -->
  </div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

