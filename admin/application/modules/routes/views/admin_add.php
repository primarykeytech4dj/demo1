<?php 
// If form has been submitted with errors populate fields that were already filled
if(isset($values_posted['data']) && !empty($values_posted['data']))
{ //print_r($values_posted);
	foreach($values_posted['data'] as $post_name => $post_value)
	{ //print_r($post_value);
		foreach ($post_value as $field_key => $field_value) {
			# code...
			$input[$field_key]['value'] = $field_value;
		}
	}
}

?>
<div>
<?php 
	//print_r($this->session);
	if(!isset($module) && NULL!==$this->session->flashdata('message')) {
		$msg = $this->session->flashdata('message');?>
		<div class = "<?=$msg['class'];?>">
			<?php echo $msg['message'];?>
		</div>
	<?php } ?>

	<?php 
	/*$url = !isset($url)?'bank_accounts/edit_account/':$url;
	if(set_value('url'))
		$url = set_value('url');*/
	 ?>
	<input type="hidden" name="url" value="<?php echo !isset($url)?'routes/new_route/':$url; ?>">
	<input type="hidden" name="module" value="<?php echo !isset($module)?'routes':$module; ?>">
	<div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-pencil margin-r-5"></i> Routes Creation</h3>
		</div>
		<div class="box-body" style="overflow-x: scroll;">
		    <?php echo form_open_multipart('routes', ['class'=>'form-horizontal', 'id'=>'filter', 'method'=>'get']);  ?>
		    <div class="row">
		        <div class="col-md-3">
		            <label for="zone_no">Zone No</label>
		            <input type="text" name="zone_no" id="zone_no" class="form-control">
		        </div>
		        
		        <div class="col-md-3">
		            <label for="login_id">Sales person</label>
		            <?=form_dropdown('login_id', $option['employees'], '', ['class'=>'form-control select2', 'id'=>'loginid',]);?>
		        </div>
		        <div class="col-md-3 text-center">
		            <label for="submitbtn">&nbsp;</label>
		            <button type="submit" name="submitbtn" id="submitbtn" class="btn btn-primary btn-small">filter</button
		        </div>
		    </div>
		    
		    
		    <?php echo form_close(); ?> 
		</div>
		<!-- form start -->
		<div class="box-body">
		    <?php echo form_open_multipart('routes/route_listing', ['class'=>'form-horizontal', 'id'=>'new_route']);  ?>
			<table class="table" id="target">
				<thead>
					<tr>
					  
					  <th>Zone no</th>
					  <th>Route No</th>
					  <th>Route Name</th>
					  <th>Sales Person</th>
					  <th>Visiting Day</th>
					  <th>Action</th>
					  <th></th>
					</tr>
				</thead>
				<tbody>
				    <?php if(count($routes)>0){
						foreach($routes as $rKey=>$route){
							
				    ?>
				    <tr id=<?=$rKey?>>
				        <td >
				            <input type="hidden" name="id" id="id_<?=$rKey?>" value="<?=$route['id']?>">
				            <input type="text" name="zone_no" class="form-control" id="zone_no_<?=$rKey?>" value="<?=$route['zone_no']?>"></td>
				        <td><input type="text" name="route_no" class="form-control" id="route_no_<?=$rKey?>" value="<?=$route['route_no']?>"></td>
				        <td><input type="text" name="route_name" class="form-control" id="route_name_<?=$rKey?>" value="<?=$route['route_name']?>"></td>
				        <td>
				            <?=form_dropdown('login_id', $option['employees'], $route['login_id'], ['class'=>'form-control', 'id'=>'loginid_'.$rKey]);?>
				            
				        </td>
				        <td>
				            <?=form_dropdown('visiting_days', $option['days'],$route['visiting_days'], ['class'=>'form-control', 'id'=>'days_'.$rKey]);?>
				        </td>
				        <td><input type="button" class="btn primary-btn updaterow nochange" title="Update" id="updatebtn_<?=$rKey?>" name="upd_<?=$rKey?>" value="Update"></td>
				        <td></td>
				    </tr>
				    <?php
				        }
				    }else{
				    ?>
				    <tr id=0>
				        <td><input type="text" name="zone_no" class="form-control" id="zone_no_0"></td>
				        <td><input type="text" name="route_no" class="form-control" id="route_no_0"></td>
				        <td><input type="text" name="route_name" class="form-control" id="route_name_0"></td>
				        <td>
				            <?=form_dropdown('login_id', $option['employees'], '', ['class'=>'form-control select2', 'id'=>'loginid_0']);?>
				            
				        </td>
				        <td>
				            <?=form_dropdown('visiting_days', $option['days'], '', ['class'=>'form-control select2', 'id'=>'days_0']);?>
				        </td>
				        <td><input type="button" class="btn primary-btn updaterow nochange" name="upd_0" title="Update" id="updatebtn_0" value="Update"></td>
				        <td></td>
				    </tr>
				    <?php
				    
				    } ?>
				</tbody>  
				<tfoot>
					<tr>
				   		<td colspan="5"><button type="button" id="AddMoreProductAttributes" class="btn btn-info pull-right AddMoreRow">Add More</button>
				   		</td>
				   	</tr>
				</tfoot>
            </table>
			
			<?=form_close()?>
			<!-- s --> <!-- /box-body -->  
	    </div>              
		
		<!-- /.box-footer -->
	</div><!-- /box -->

</div>

<script>
    $(document).on('click', '.updaterow', function(e){
        e.preventDefault();
        var formId = $(this).closest('form').attr('id');
        var trId = $(this).closest('tr').attr('id');
        var formData = new FormData();
        $("#"+trId+" *").filter(':input').each(function(){
			// alert('sas');
            if(typeof $(this).attr('name')!='undefined'){
                if($(this).attr('type')=="checkbox"){
                    var val = 0;
                    if($(this).is(":checked")){
                       val = 1;
                    }
                    //alert($(this).attr('name')+" "+val);
                    formData.append($(this).attr('name'), val);
                }else if($(this).attr('type')=="button"){
                    
                }else{
                    
                    // alert(this.id+" "+$(this).attr('name')+" "+$(this).val());
                    formData.append($(this).attr('name'), $(this).val());
                }
            }
        })
        
		// alert('high');
        var action = $("#"+formId).attr('action');
        
        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: action,
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            timeout: 600000,
            success: function (data) {
    
                console.log(data);
                var data = $.parseJSON(data);
                Toast.fire({
                    icon: data.status,
                    title: data.message
                })
                
            }
        });
        console.log(formData);
        return false;
    })
    
</script>
