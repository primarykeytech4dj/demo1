<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

?>

         <section class="content-header">
	<h1>
	    Module :: Customers
	</h1>
	<ol class="breadcrumb">
	    <li><a href="#"><i class="fa fa-dashboard"></i>Export</a></li>
	    
	</ol>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<!-- Custom Tabs -->
			<div class="nav-tabs-custom">

				<div class="tab-content">
                <?php //echo form_open_multipart(custom_constants::new_user_url, ['class'=>'form-horizontal', 'id'=>'register_user']); 
							//print_r($this->session);
						echo form_open_multipart('customers/export', ['class'=>'form-horizontal', 'id'=>'new_product']);
							
							if(NULL!==$this->session->flashdata('message')) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
                            <div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> New Product</h3>
								</div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputCategory" class="col-sm-2 control-label">Zone</label>
                                        <div class="col-sm-10">
        									<?php 

            									echo form_dropdown('data[zone][]', $option['zone'],'',"id='zone' required='required' class='form-control select2 zone' multiple='multiple'"); 
            									echo form_error('data[zone]'); 
        									
        									?>
                                            </div>
                                        </div>
                                    </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="inputCategory" class="col-sm-2 control-label">Routes</label>
                                        <div class="col-sm-10">
        									<?php 

            									echo form_dropdown('data[routes][]', ['select Attributes'],'',["id"=>'routes', 'class'=>'form-control select2', 'multiple'=>'multiple']); 
            									echo form_error('data[routes]'); 
        									
        									?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="col-md-6  col-xs-6 text-center">
                                        <button class="btn btn-info" id="Exportbtn">Export</button>
                                    </div>
                                    <!-- <div class="col-md-6  col-xs-6 text-center">
                                        <button type="reset" class="btn btn-info">Reset</button>
                                    </div> -->
                                </div>
                            </div>

                </div>
            </div>
        </div>
    </div>
</section>