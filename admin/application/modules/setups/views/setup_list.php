<?php 
	
	if(!isset($module) && NULL!==$this->session->flashdata('message')) {
		$msg = $this->session->flashdata('message');?>
		<div class = "<?  echo $msg['class'];?>">
			<?php echo $msg['message'];?>
		</div>
	<?php } ?>

    <div class="box box-info">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-pencil margin-r-5"></i> Routes Creation</h3>
		</div>
		<div class="box-body" style="overflow-x: scroll;">
		    <?php echo form_open_multipart('setups', ['class'=>'form-horizontal', 'id'=>'filter', 'method'=>'get']);  ?>
		    <div class="row">
		        <!-- <div class="col-md-3">
		            <label for="zone_no">Zone No</label>
		            <input type="text" name="zone_no" id="zone_no" class="form-control">
		        </div> -->
		        <?php // print_r($option);exit; ?>
  
									
		        <div class="col-md-3">
		            <label for="name">Module Name</label>
                    <select name="name" class="form-control select2  filter" id="name" >
                        <option value="">-Select Module Name-</option>
                        <?php
                            foreach($option as $sKey=>$options){
                            ?>
                            <option value="<?=$options->module_name?>" ><?=$options->module_name?></option>
                            <?php
                        } ?>	
                    </select>
		          
                  
		        </div>
		        <div class="col-md-3 text-center">
		            <label for="submitbtn">&nbsp;</label>
		            <button type="submit" name="submitbtn" id="submitbtn" class="btn btn-primary btn-small" >filter</button>
		        </div>
		    </div>
            <?php echo form_close(); ?> 
            
		    
		</div>

        <div class="box-body">
		    <?php echo form_open_multipart('setups/setups_listing', ['class'=>'form-horizontal', 'id'=>'new_setup']);  ?>
			<table class="table" id="target">
				<thead>
					<tr>
					  
					  <th>Module Name</th>
					  <th>Parameter</th>
					  <th>Datatype</th>
					  <th>Value</th>
                      <th>Priority</th>
					  <th>Action</th>
					  <th></th>
					</tr>
				</thead>
				<tbody>
				    <?php if(count($setups)>0 ){
						foreach($setups as $rKey=>$setup){
                            
                            ?>
				    <tr id=<?=$rKey?>>
				        <td >
                            <input type="hidden" name="id" id="id_<?=$rKey?>" value="<?=$setup['id']?>">
                            <select name="module_name" class="form-control select2" style="width:100%;" id="module_name_<?=$rKey?>" >                                <!-- <option value="">-Select State-</option> -->
                                <?php
                                
                                foreach($option as $sKey=>$modules){
                                    ?>
                                    <option value="<?=$modules->module_name?>" <?php echo (strtolower($setup['module_name']) === strtolower($modules->module_name)) ? 'selected = "selected"' : ''; ?>><?=$modules->module_name?></option>
                                    <?php
                                } ?>	
                            </select></td>
                            <td><input type="text" name="parameter" id="parameter_<?=$rKey?>" value="<?php echo $setup['parameter'];?>" class="form-control"></td>
                            <td><select name="datatype" class="form-control datatype select2" style="width:100%;"  id="datatype_<?=$rKey?>" >
                                    <option value="">-Select Datatype-</option>
                                    <option value="file" <?=($setup['datatype'] == "file") ? 'selected' : ''?>>File</option>
                                    <option value="checkbox" <?=($setup['datatype'] == "checkbox")? 'selected' : ''?>>Check Box</option>
                                    <option value="boolean" <?=($setup['datatype'] == "boolean")? 'selected' : ''?>>Boolean</option>
                                    <option value="value" <?=($setup['datatype'] == "value")? 'selected' : ''?>>Value</option>
                                    <option value="DATE" <?=($setup['datatype'] == "DATE")? 'selected' : ''?>>Date</option>
                                    <option value="INT" <?=($setup['datatype'] == "INT")? 'selected' : ''?>>INT</option>
                                       
                                </select></td>
				        <td class='tdvalue' data-value="<?=$setup['value']?>">
                            <?php if($setup['datatype'] == "boolean"){?>
                                
                                <input type="checkbox" name="value" id="value_<?=$rKey?>" <?=($setup['value'] == "true" ) ? 'checked' :''?> value="<?=$setup['value']?>">
				            <?php }else if($setup['datatype'] == "INT"){ ?>
                                <input type="number" name="value" class="form-control"  id="value_<?=$rKey?>" value="<?=$setup['value']?>">
                            <?php }else if($setup['datatype'] == "DATE"){?>
                                <input type="date" name="value" id="value_<?=$rKey?>" class="form-control" value="<?=date('Y-m-d',strtotime($setup['value']))?>">
                            <?php }else if($setup['datatype'] == "value"){?>
                                <input type="text" name="value" class="form-control"  id="value_<?=$rKey?>" value="<?=$setup['value']?>">
                            <?php }else if($setup['datatype'] == "checkbox"){?>
                                <input type="checkbox" name="value" id="value_<?=$rKey?>" <?=($setup['value'] == 1 ) ? 'checked' :''?> value="<?=$setup['value']?>">
                            <?php }else if($setup['datatype'] == "file"){ ?>
                                <input type="file" name="value" class="form-control"  id="value_<?=$rKey?>" value="<?=$setup['value']?>">
                            <?php }else{ ?>
                                <input type="text" name="value" class="form-control"  id="value_<?=$rKey?>" value="<?=$setup['value']?>">
                            <?php } ?>
				        </td>
                        <td>
                        <input type="text" name="priority" id="priority_<?=$rKey?>" value="<?php echo $setup['priority'];?>"  class="form-control">
				            
				        </td>
				        <td><input type="button" class="btn primary-btn updaterow nochange" title="Update" id="updatebtn_<?=$rKey?>" name="upd_<?=$rKey?>" value="Update"></td>
				        <td></td>
				    </tr>
				    <?php
				        }
				    }else{
				    ?>
				    <tr id=0>
				        <td><select name="module_name" class="form-control select2" id="module_name_0" >
                                <option value="">-Select State-</option>
                                <?php
                                
                                    foreach($option as $sKey=>$modules){
                                    ?>
                                    <option value="<?=$modules->module_name?>"><?=$modules->module_name?></option>
                                    <?php
                                } ?>	
                            </select></td>
				        <td><select name="datatype" class="form-control select2  " id="datatype_0" >
                                <option value="">-Select State-</option>
                                <option value="file">File</option>
                                <option value="checkbox">Check Box</option>
                                <option value="boolean">Boolean</option>
                                <option value="value">Value</option>
                                <option value="DATE">Date</option>
                                <option value="INT">INT</option>
                               	
                            </select></td>
				        <td><input type="text" name="parameter" id="parameter_0" class="form-control"></td>
				        <td>
                        <input type="text" name="value" id="value_0" class="form-control">
				        </td>
                        <td>
                        <input type="text" name="priority" id="priority_0" class="form-control">
				            
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
		
       

<script type="text/javascript">

  function filter(){
        
        $('#ajaxLoader').DataTable().ajax.reload();
    }

    

    $(document).on('click', '.updaterow', function(e){
        e.preventDefault();
        var formId = $(this).closest('form').attr('id');
        var trId = $(this).closest('tr').attr('id');
        // alert(trId);
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
        });
        
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
    });
    
    

    // $(document).on('click','#datatype', function(e){
    //     e.preventDefault();
    //     alert(trId);
    //     var trId = $(this).closest('datatype').attr('id');
    // });

    $(document).on('change','.datatype', function() {

        var trId = $(this).closest('tr').attr('id');
        var type = $(this).val();
        // alert(trId+type);
        // alert($("#"+trId+" .tdvalue").attr('data-value'));
        if(type == 'checkbox')
        {
            var checked = "";
            if($("#"+trId+" .tdvalue").attr('data-value') == 1)
            {
                checked = 'checked';
            }
            $("#"+trId+" .tdvalue").html(' <input type="checkbox" name="value" id="value_'+trId+'" value="'+$("#"+trId+" .tdvalue").attr('data-value')+'" '+checked+'  >');
        }else if(type == "file")
        {
            $("#"+trId+" .tdvalue").html(' <input type="file" class="form-control"  name="value" id="value_'+trId+'" value="'+$("#"+trId+" .tdvalue").attr('data-value')+'"   >');

        }else if(type == "boolean")
        {
            var checked = "";
            if($("#"+trId+" .tdvalue").attr('data-value') == "true")
            {
                checked = 'checked';
            }
            $("#"+trId+" .tdvalue").html(' <input type="checkbox" name="value" id="value_'+trId+'" value="'+$("#"+trId+" .tdvalue").attr('data-value')+'" '+checked+'  >');
            // $("#"+trId+" .tdvalue").html(' <input type="radio" name="value" id="value_'+trId+'" value="'+$("#"+trId+" .tdvalue").attr('data-value')+'" '+checked +'>');
        }else if(type == "value")
        {
            $("#"+trId+" .tdvalue").html(' <input type="text" class="form-control" name="value" id="value_'+trId+'" value="'+$("#"+trId+" .tdvalue").attr('data-value')+'"   >');
        }
        else if(type == "DATE")
        {
            $("#"+trId+" .tdvalue").html(' <input type="date" class="form-control" name="value" id="value_'+trId+'" value="'+$("#"+trId+" .tdvalue").attr('data-value')+'"   >');
        }
        else if(type == "INT")
        {
            $("#"+trId+" .tdvalue").html(' <input type="number" name="value" class="form-control" id="value_'+trId+'" value="'+$("#"+trId+" .tdvalue").attr('data-value')+'"   >');
        }
        else{
            $("#"+trId+" .tdvalue").html(' <input type="text" name="value" class="form-control" id="value_'+trId+'" value="'+$("#"+trId+" .tdvalue").attr('data-value')+'"   >');
        }
    });
    
</script>