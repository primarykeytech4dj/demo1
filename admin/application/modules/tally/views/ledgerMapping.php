<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<?php

// If access is requested from anywhere other than index.php then exit
if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$input['faq_category'] = array(
						"name" => "faques[faq_category]",
						"placeholder" => "FAQ Category *",
						"class"=> "form-control viewInput",
						"id" => "faq_category",

					);

$input['module'] = array(
						"name" => "faques[module]",
						"placeholder" => "Module Name *",
						"class"=> "form-control viewInput",
						"id" => "module",

					);
if(isset($values_posted))
{	
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
	    Module :: Tally
	</h1>
	<ol class="breadcrumb">
	    <li><a href="#"><i class="fa fa-dashboard"></i>Dashboard</a></li>
	    <li>
	      <?php echo anchor('tally/adminindex', 'Tally', 'title="Tally"'); ?>
	    </li>
	    <li>
	      <?php echo anchor('tally/ledgerMapping', 'Mapp ledgers'); ?>
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
					
						<?php 
						echo form_open_multipart("tally/ledgerMapping", ['class'=>'form-horizontal submit-ajax-tally', 'id'=>'new_ledger_mapping']);
							
							if($this->session->flashdata('message') !== FALSE) {
								$msg = $this->session->flashdata('message');?>
								<div class = "<?php echo $msg['class'];?>">
									<?php echo $msg['message'];?>
								</div>
							<?php } ?>
							<div class="box box-info">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-shopping-bag margin-r-5"></i> Ledger Mapping</h3>
								</div><!-- /box-header -->
								<!-- form start -->
								<div class="box-body">
									
									<div class="box-haeder with-border">
										<h2 class="box-title">Ledgers</h2>
									</div>
									<div class="box-body" style="overflow-x:scroll">
										<table class="table" id="target">
											<thead>
												<tr>
													<th class="col-md-1">Sr No</th>
													<th class="col-md-3">Tally Ledgers</th>
													<th class="col-md-2">Ledger type</th>
													<th class="col-md-3">ERP Users</th>
													<th class="col-md-1">Is Active</th>
													<th class="col-md-1">Action</th>
												</tr>
											</thead>
											<tbody> 
											<?php 

											if(count($ledgers)>0) {
												$counter = 0;
												//echo $start_from;
												foreach ($ledgers as $ledgerKey => $ledger) {

													if(in_array($ledger, $ledgerAccounts) || (($ledgerKey+1)<=$start_from)){
														continue;
													}
													?>
													<tr id="<?php echo $ledgerKey;?>">
														<td><?=++$counter?></td>
														<td><input type="hidden" name="data[tally_ledger][<?=$ledgerKey?>][ledger_name]" id="ledger_name_<?=$ledgerKey?>" value="<?=$ledger?>"><?=$ledger?></td>
														<td>
															<?php echo form_dropdown('data[tally_ledger]['.$ledgerKey.'][module]', $option['ledgerTypes'], set_value('data[tally_ledger]['.$ledgerKey.'][module]'), ['class'=>'form-control select2 filter', 'data-link'=>'address/type_wise_user', 'data-target'=>'user_id_'.$ledgerKey, 'style'=>'width:100%', 'id'=>'module_'.$ledgerKey]); ?>
														</td>
														<td>
															<?php echo form_dropdown('data[tally_ledger]['.$ledgerKey.'][user_id]', $option['user_id'], $option['user_id'], ["id"=>'user_id_'.$ledgerKey, "required"=>'required', 'class'=>'form-control select2 addqc', 'style'=>'width:80%']);?>
															<button class="btn dynamic-modal load-ajax-tally" type="button" data-refill-target='user_id_<?=$ledgerKey?>'><i class="fa fa-plus"></i></button>
														</td>
														<td>
															<input type="checkbox" name="data[tally_ledger][<?=$ledgerKey?>][is_active]" id="is_active_<?=$ledgerKey?>">
														</td>
														<td>
															<input type="button" class="btn btn-primary submit-btn" id="btn_<?=$ledgerKey?>" value="Save">
															<div id="response_<?=$ledgerKey?>"></div>
														</td>
													</tr>
													<?php
														if($counter==30){
															break;
														}
													}
												} ?>
											</tbody>
											<tfoot>
												<tr>
													<td colspan="2">
														Totat Records : <?=$totalRecords?>
													</td>
											   		<td colspan="2">
											   			<?php 
											   			if($page!=1){
											   				echo anchor('tally/ledgerMapping?page='.($page-1), 'Previous', ['class'=>'btn btn-default']);
											   			} 
											   			?>
											   			
											   		</td>
											   		<td colspan="2">
											   			<?php echo anchor('tally/ledgerMapping?page='.($page+1), 'Next', ['class'=>'btn btn-default']); ?>
											   		</td>
											   	</tr>
											</tfoot>
										</table>
									</div>
									
								<!-- <div class="box-footer">  
									<button type="new_college" class="btn btn-info pull-left">Add FAQ</button> &nbsp;&nbsp;&nbsp;&nbsp;
									
								</div> -->
								<!-- /.box-footer -->
							</div><!-- /box -->
							</div>
						<?php echo form_close(); ?> 
				</div><!-- /tab-content -->
			</div><!-- end of nav tab -->
		</div><!-- col-md-12 -->
	</div><!-- /nav-tabs-custom -->
</section> <!-- /section-->

