<script src="<?=assets_url('admin_lte/plugins/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
<style type="text/css">
	td,th{
		padding-left: 5px;
		padding-right: 5px
	}
	table tfoot{display:table-row-group;}
</style>
<style>
    .content-wrapper{
        background-color: #fff;
    }
    .cart{
      box-shadow: 0 0 3px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
      border-radius: 15px;
      padding: 10px;
    }
    .text-gray{
        color: #848789 !important;
        font-weight: 500;
    }
    .cart-button{
        height: 34px;
        line-height: 32px;
        width: 32px;
        font-size: 32px;
        background: #e48021;
        border: 0;
        border-radius: 18px;
        color: #fff;
        font-weight: bolder;
    }

    .plus{
     }

    .minus{
     }
    .quantity{
        width: 32px;
        text-align: center;
        border: 0;
        height: 34px;
        line-height: 34px;
        font-size: 20px !important;
        color: #96514d;
        font-weight: 600;
    }
    .summary{
        color: #96514d;
    }
  </style>


<?php //print_r($order); ?>
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			Module :: Fieldmember
		</h1>
		<ol class="breadcrumb">
			<li><?=anchor(custom_constants::fieldmember_url, '<i class="fa fa-dashboard"></i> Dashboard', ['class'=>''])?></li>
			<li><?=anchor(custom_constants::fieldmember_url, 'My Delivery')?></li>
			<li class="active">Order Detail</li>
		</ol>
	</section>

  <!-- Main content -->
  <section class="content">
    <div class="row">
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title text-center">Order Detail</h3>
            <?php //echo anchor(custom_constants::new_role_url, 'New Role', 'class="btn btn-primary pull-right"'); ?>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
		  	<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
         <!--Grid row-->
		<?php 
		echo form_open_multipart(custom_constants::make_delivery.'/'.$orderId, ['class'=>'form-horizontal', 'id'=>'make_delivery']); 
		if(isset($form_error))
		{
			echo "<div class='alert alert-danger'>";
			echo $form_error;
			echo "</div>";
		}
		if($this->session->flashdata('message') !== FALSE) {
			$msg = $this->session->flashdata('message');?>
			<div class = "<?php echo $msg['class'];?>">
				<?php echo $msg['message'];?>
			</div>
		<?php } ?> 
    <div class="row">

     <!--Grid column-->
     <div class="col-lg-12">

      <!-- Card -->
      <div class="mb-1">
        <div class="pt-4">
          <ul class="list-group list-group-flush">
            <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
              Customer:
              <span class="font-italic"><?=strtoupper($order['order']['customer_name'])?></span>
            </li>
            <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
              Quotation No:
              <span class="font-italic"><?=strtoupper($order['order']['order_code'])?></span>
            </li>
            <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
              Delivery Address:
              <span class="font-italic"><?=strtoupper($order['order']['delivery_address'])?></span>
            </li>
            <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
              Delivery Remark:
              <span class="font-italic"><?=strtoupper($order['order']['delivery_address_remark'])?></span>
            </li>
            <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
              Order Total
              <span class="font-italic" id="amount_after_tax"><?=$order['order']['amount_after_tax']?></span>
            </li>
            <li class="pt-3 d-flex justify-content-between align-items-center px-0 text-gray">
              Shipping Charges: +<span style="color: #3f5b48;" class="font-italic" id="shipping_charge"><?=$order['order']['shipping_charge']?></span>
            </li>
          </ul>
		  <hr class="mb-2 mt-1" style="border-top:1px solid #7b9d6d;">
		<ul class="list-group list-group-flush">
			<li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">GROSS TOTAL: <span class="font-italic" class="gross-amount"><?=$order['order']['amount_after_tax']+$order['order']['shipping_charge']?></span> </li>
		</ul>

         
        </div>
      </div>
    </div>
    </div>
    <!--Grid column-->
	<?php foreach($order['orderDetails'] as $oKey=>$oValue){
	/*echo '<pre>';
	print_r($oValue);
	echo '</pre>';*/
	?>
    <div class="row ordered-products" id="<?=$oValue['id']?>">
        <!--Grid column-->
    	<div class="col-lg-12">
    		
    		<!-- Card -->
    		<div class="mb-3">
    			<div class="pt-2 wish-list">
    				<div class="row mb-4 cart">
    					<div class="col-md-2 col-lg-2 col-xl-2 col-xs-5 text-center">
    						<div class="view zoom overlay z-depth-1 rounded mb-3 mb-md-0 text-center">
    							<a href="#!">
    							<div class="mask text-center">
    							    <?=img(['src'=>content_url().'uploads/products/'.$oValue['image_name_1'], 'class'=>'img-fluid w-100', 'style'=>'max-height:100px'])?>
    								
    								<div class="mask rgba-black-slight"></div>
    							</div>
    							</a>
    						</div>
    					</div>
    					<div class="col-md-8 col-lg-8 col-xl-8 col-xs-7 p-0">
    						<div>
    							<div class="justify-content-between">
    								<div>
    									<h5 class="eclip"><?=$oValue['product'];?> </h5>
    									<!--<h6>Brand :</h6>-->
    								</div>
    								<div class="d-flex p-0">
    									<div class="col-5 p-0">
                                            <p class="mb-1 text-muted text-uppercase"><b><span id="qty_<?=$oValue['id']?>"><?=$oValue['qty_count'].'</span> &nbsp;&nbsp;X <span id="price_'.$oValue['id'].'">'. number_format($oValue['unit_price'], 2, '.', '');?></b></p>
                                            <p class="mb-3 text-muted text-uppercase small"><?=$oValue['uom'];?> Packing</p>
                        					<p class="mb-2 text-muted text-uppercase small">
                        					    <input type="hidden" id="id_<?=$oValue['id']?>" class="form-control" value="<?=$oValue['id']?>" name="order_details[<?=$oKey?>][id]" />
                                                <label>Return Qty :</label><input type="number" id="return_quantity_<?=$oValue['id']?>" class="form-control return_qty" value="0" name="order_details[<?=$oKey?>][return_quantity]" min="0" max="<?=$oValue['qty']?>" data="<?=$oValue['qty']?>" />
                                            </p>
                                            
                                            <p class="mb-3 text-muted text-uppercase small">Sub Total: <span class="subtotal" id="sub-total-<?=$oValue['id']?>"><?=number_format($oValue['qty_count']*$oValue['unit_price'], 2, '.', '');?></span></p>
    								    </div>
    								
            						</div>
            					</div>
            				</div>
            			</div>
            		</div>
            	</div>
        	</div>
        </div>
    </div>
      <!-- Card -->
     <?php } ?>
        <div class="row">
            <div class="col-lg-12">
    	        <div class="form-group">
					<label for="order_status_id" class="mb-3 control-label">Delivery Status</label>
					<div class="mb-9">
						<?php 
						echo form_hidden(['data[orders][id]'=>$order['order']['id']]);
						echo form_dropdown('data[orders][order_status_id]', [$order['order']['order_status_id']=>'Pending', 5=>'Delivered', '2'=>'Cancelled'], $order['order']['order_status_id'], ["id"=>'order_status_id', 'required'=>'required', 'class'=>'form-control', 'tab-index'=>1, 'style'=>'width:100%']);?>
						<?php echo form_error('data[orders][order_status_id]'); ?>
					</div>
				</div>
    	    </div>
    	</div>
    	
    	<div class="row orderstatus" id="orderstatus">
            <div class="col-lg-12">
    	        <div class="form-group">
					<label for="payment_mode" class="mb-3 control-label">Paid Through</label>
					<div class="mb-9">
					    <!--, 'cheque'=>'Cheque'-->
						<?php echo form_dropdown('data[order_payments][payment_mode]', ['cash'=>'Cash', 'online'=>'Online'], !set_value('data[order_payments][payment_mode]')?'cash':set_value('data[order_payments][payment_mode]'), ["id"=>'payment_mode', 'required'=>'required', 'class'=>'form-control', 'tab-index'=>1, 'style'=>'width:100%']);?>
						<?php echo form_error('data[order_payments][payment_mode]'); ?>
					</div>
				</div>
    	    </div>
    	</div>
    	<div class="row paid-through orderstatus" id="cheque-details"  style="display:none">
            <div class="col-lg-6">
    	        <div class="form-group">
					<label for="cheque_no" class="mb-3 control-label">Cheque No:</label>
					<div class="mb-6">
						<?php echo form_input(["name"=>'data[order_payments][cheque_no]', "id"=>'cheque_no', 'class'=>'form-control']);?>
						<?php echo form_error('data[order_payments][cheque_no]'); ?>
					</div>
				</div>
    	    </div>
    	    <div class="col-lg-6">
    	        <div class="form-group">
					<label for="cheque_date" class="mb-3 control-label">Cheque Date</label>
					<div class="mb-6">
						<?php echo form_input(["name"=>'data[order_payments][cheque_date]', "id"=>'cheque_date', 'class'=>'form-control datepicker']);?>
						<?php echo form_error('data[order_payments][cheque_date]'); ?>
					</div>
				</div>
    	    </div>
    	</div>
    	<div class="row paid-through orderstatus" id="online-details" style="display:none">
            <div class="col-lg-6">
    	        <div class="form-group">
					<label for="payment" class="mb-3 control-label">Via</label>
					<div class="mb-9">
						<?php echo form_dropdown('data[order_payments][tracker]', [''=>'Select Payment Media', 'paytm'=>'Paytm', 'google pay'=>'Google Pay', 'imps'=>'Imps', 'neft'=>'Neft', 'rtgs'=>'Rtgs', 'others'=>'others'], !set_value('data[order_payments][tracker]')?'':set_value('data[order_payments][tracker]'), ["id"=>'tracker', 'class'=>'form-control', 'tab-index'=>1, 'style'=>'width:100%']);?>
						<?php echo form_error('data[order_payments][tracker]'); ?>
					</div>
				</div>
    	    </div>
    	    <div class="col-lg-6">
    	        <div class="form-group">
					<label for="cheque_date" class="mb-3 control-label">Payment Date</label>
					<div class="mb-6">
						<?php echo form_input(["name"=>'data[order_payments][online_payment_date]', "id"=>'online_payment_date', 'class'=>'form-control datepicker', 'value'=>date('d/m/Y')]);?>
						<?php echo form_error('data[order_payments][online_payment_date]'); ?>
					</div>
				</div>
    	    </div>
    	</div>
    	<div class="row paid-through orderstatus" id="cash-details" style="display:block">
            <div class="col-lg-6">
    	        <div class="form-group">
					<label for="cash_paid_on" class="mb-3 control-label">Cash Paid On</label>
					<div class="mb-9">
						<?php echo form_input(["name"=>'data[order_payments][cash_paid_on]', "id"=>'cash_paid_on', 'class'=>'form-control datepicker', 'value'=>date('d/m/Y')]);?>
						<?php echo form_error('data[order_payments][cash_paid_on]'); ?>
					</div>
				</div>
    	    </div>
    	    
    	</div>
    	<div class="row">
            <div class="col-lg-12">
    	        <div class="form-group">
					<label for="remark" class="mb-3 control-label">Remark</label>
					<div class="mb-9">
						<?php echo form_input(["name"=>'data[orders][delivery_remark]', "id"=>'delivery_remark', 'class'=>'form-control']);?>
						<?php echo form_error('data[orders][delivery_remark]'); ?>
					</div>
				</div>
    	    </div>
    	    <div class="col-lg-12">
    	    <h2> TOTAL: <span class="gross-amount"><?=$order['order']['amount_after_tax']+$order['order']['shipping_charge']?></span></h2>
    	    </div>
    	</div>
    </div>
    <!--Grid column-->

   

  </div>
  <!-- Grid row -->
        <div class="box-footer">  
			<button type="submit" class="btn btn-info pull-left">Update Delivery</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="submit" class="btn btn-info">cancel</button>
		</div>
        <!-- /.row -->
        </form>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

		</div>
	  </div>
	</div>
	</section>
	<Script src="<?=assets_url('admin_lte/')?>dist/js/demo.js"></script>
<!-- Page specific script -->
<script>
    $(document).on('change keyup', '.return_qty', function(){
        var id = this.id;
        var returnQty = $(this).val();
        
        var orderDetailId = $("#"+id).closest('div .ordered-products').attr('id');
        var orderQty = $("#"+id).attr('data');
        $('#qty_'+orderDetailId).text(orderQty-returnQty);
        
        var qty = $('#qty_'+orderDetailId).text();
        var price = $('#price_'+orderDetailId).text();
        //alert(price);
        //alert(qty*price);
        $("#sub-total-"+orderDetailId).text(qty*price);
       
        var sum = 0;
        $('.subtotal').each(function() {
          sum += +$(this).text()||0;
        });
        $("#subtotal").text(sum);
        //alert(sum);
        $("#amount_after_tax").text(parseFloat(sum));
        if($("#amount_after_tax").text()>0){
            $(".gross-amount").text(parseFloat(sum)+parseFloat($('#shipping_charge').text()));
        }else{
            $(".gross-amount").text('0.00');
        }
    });
    
    $(document).on('change', '#payment_mode', function(){
        $(".paid-through").hide();
       var value = $(this).val();
       //if(value!='cash'){
           //alert(value);
           $("#"+value+"-details").show();
       //}
       //alert(value);
    });
    
    $(document).on('change', '#order_status_id', function(){
       var value = $(this).val();
       if(value=='2'){
           //alert(value);
           $(".orderstatus").hide();
       }else{
           $("#orderstatus").show();
           $("#cash-details").show();
       }
       //alert(value);
    });
    
    $("form").on('submit', function(){
        if($("#payment_mode").val()=='online'){
            if($("#tracker").val()==''){
                alert("Please Select via the payment is made");
                $("#tracker").focus();
                return false;
            }
            if($("#online_payment_date").val()==''){
                alert("Please Enter Date");
                $("#online_payment_date").focus();
                return false;
            }
        }else if($("#payment_mode").val()=='cheque'){
            if($("#cheque_no").val().trim()==''){
                alert("Please Enter Cheque number");
                $("#cheque_no").focus();
                return false;
            }
            if($("#cheque_date").val().trim()==''){
                alert("Please Enter Cheque Date");
                $("#cheque_date").focus();
                return false;
            }
        }else if($("#payment_mode").val()=='cash'){
            if($("#cash_paid_on").val().trim()==''){
                alert("Please Enter Date");
                $("#cash_paid_on").focus();
                return false;
            }
        }
        
        return true;
    })
</script>


