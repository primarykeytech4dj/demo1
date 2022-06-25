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


<?php //print_r($order);
$previous = "javascript:history.back()";
if(isset($_SERVER['HTTP_REFERER'])) {
    $previous = $_SERVER['HTTP_REFERER'];
} ?>
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
		  <a href="<?php echo $previous?>" class="btn"><i class="fa fa-arrow-left" style="color:black;font-size:18px"></i></a> <h3 class="box-title text-center">Order Detail</h3>
            <?php //echo anchor(custom_constants::new_role_url, 'New Role', 'class="btn btn-primary pull-right"'); ?>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
		  	<!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <?php /*echo '<pre>';
            print_r($this->session->userdata());
            echo '</pre>';*/
            ?>
         <!--Grid row-->
		<?php 
		echo form_open_multipart(custom_constants::make_delivery2.'/'.$orderId, ['class'=>'form-horizontal', 'id'=>'make_delivery']); 
		if(isset($form_error))
		{
			echo "<div class='alert alert-danger'>";
			echo $form_error;
			echo "</div>";
		}
		if(NULL!==$this->session->flashdata('message')) {
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
              <?php //echo '<pre>';print_r($order);exit; ?>
              <span class="font-italic"><?=strtoupper($order['customer_name'])?></span>
            </li>
            <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
              Invoice No:
              <span class="font-italic"><?=strtoupper($order['invoice_no'])?></span>
            </li>
            <!-- <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
              Delivery Address:
              <span class="font-italic"><?=strtoupper($order['order']['delivery_address'])?></span>
            </li>
            <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
              Delivery Remark:
              <span class="font-italic"><?=strtoupper($order['order']['delivery_address_remark'])?></span>
            </li> -->
            <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">
                <h2>
              Invoice Total:
              <span class="font-italic" id="amount_after_tax"><?=$order['amount_after_tax']?></span></h2>
            </li>
			<?php if((isset($_SESSION['delivery']['shipping_charges']) && $_SESSION['delivery']['shipping_charges']==1)){ ?>
             <li class="pt-3 d-flex justify-content-between align-items-center px-0 text-gray">
              Shipping Charges: +<span style="color: #3f5b48;" class="font-italic" id="shipping_charge">0.00<?//=$order['order']['shipping_charge']?></span>
            </li> 
			<?php }?>
          </ul>
		  <hr class="mb-2 mt-1" style="border-top:1px solid #7b9d6d;">
		<ul class="list-group list-group-flush">
			<!-- <li class="pt-3 d-flex justify-content-between align-items-center border-0 px-0 pb-0 text-gray">GROSS TOTAL: <span class="font-italic" class="gross-amount"><?=$order['order']['amount_after_tax']+$order['order']['shipping_charge']?></span> </li> -->
		</ul>

         
        </div>
      </div>
    </div>
    </div>
    <!--Grid column-->
	<?php //print_r($order);exit;
    foreach($order['orderDetail'] as $oKey=>$oValue){
	/*echo '<pre>';
	print_r($oValue);
	echo '</pre>';*/
	?>
    <div class="row ordered-products" id="<?=$oValue['id']?>">
        <!--Grid column-->
    	<div class="col-lg-4">
    		
    		<!-- Card -->
    		<div class="mb-3">
    			<div class="pt-2 wish-list">
    				<div class="row mb-4 cart">
						<?php if((isset($_SESSION['delivery']['product_image']) && $_SESSION['delivery']['product_image']==1)){ ?>
							<div class="col-md-4 col-lg-4 col-xl-4 col-xs-5 text-center">
								<div class="view zoom overlay z-depth-1 rounded mb-3 mb-md-0 text-center">
									<a href="#!">
									<div class="mask text-center">
										<?=img(['src'=>content_url().'uploads/products/'.$oValue['image_name_1'], 'class'=>'img-fluid w-100', 'style'=>'max-height:100px'])?>
										
										<div class="mask rgba-black-slight"></div>
									</div>
									</a>
								</div>
							</div>
						<?php } ?>
    					<div class="col-md-8 col-lg-8 col-xl-8 col-xs-7 p-0">
    						<div>
    							<div class="justify-content-between">
    								<div>
    								    <?php //echo '<pre>';print_r($oValue);echo '</pre>'; ?>
    									<h5 class="eclip"><?=($oValue['product_type']!=2)?($oValue['brand'].' '.$oValue['product']):'';?> <?=(isset($oValue['model'])?' '.$oValue['model']:'')?></h5>
    									<!--<h6>Brand :</h6>-->
    								</div>
    								<div class="d-flex p-0">
    									<div class="col-5 p-0">
                                            <p class="mb-1 text-muted text-uppercase"><b><span id="qty_<?=$oValue['id']?>"><?=$oValue['qty_count'].'pc ('.$oValue['uom'].' Pkg)</span> &nbsp;&nbsp;X  &nbsp;&nbsp;<span id="price_'.$oValue['id'].'" ><i class="fa fa-rupee"> </i> '. number_format($oValue['unit_price'], 2, '.', '');?></b></p>
                                            <!-- <p class="mb-3 text-muted text-uppercase small"><?=$oValue['uom'];?></p> -->
                        					<p class="mb-2 text-muted text-uppercase small">
                                                <input type="hidden" id="order_id_<?=$oValue['order_id']?>" class="form-control" value="<?=$oValue['order_id']?>" name="order_details[<?=$oKey?>][order_id]" />
                        					    <input type="hidden" id="id_<?=$oValue['id']?>" class="form-control" value="<?=$oValue['id']?>" name="order_details[<?=$oKey?>][id]" />
                                                <label>Return Qty :</label><input type="number" id="return_quantity_<?=$oValue['id']?>" class="form-control return_qty" value="0" name="order_details[<?=$oKey?>][return_quantity]" min="0" max="<?=$oValue['qty_count']?>" data="<?=$oValue['qty_count']?>" />
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
						//echo form_hidden(['data[orders][id]'=>$order['order']['id']]);
						echo form_hidden(['data[invoice][id]'=>$id]);
						echo form_dropdown('data[orders][order_status_id]', ['1'=>'Pending', '5'=>'Delivered', '2'=>'Cancelled'], /*$order['order']['order_status_id']*/'5', ["id"=>'order_status_id', 'required'=>'required', 'class'=>'form-control', 'tab-index'=>1, 'style'=>'width:100%']);?>
						<?php echo form_error('data[orders][order_status_id]'); ?>
					</div>
				</div>
    	    </div>
    	</div>

		<div class="row payment_type">
            <div class="col-lg-12">
    	        <div class="form-group">
					<label for="payment_type_id" class="" class="mb-3 control-label">Payment Type</label>
				<div class="mb-9">
						<?php 				
						echo form_dropdown('payment_type', ['1'=>'CASH', '2'=>'UPI', '3'=>'BOTH'], '1', ["id"=>'payment_type_id', 'required'=>'required', 'class'=>'form-control', 'tab-index'=>1, 'style'=>'width:100%']);?>
						<?php echo form_error('payment_type'); ?>
					</div>
					
				</div>
    	    </div>
    	</div>
    	
    	<div class="row orderstatus" id="orderstatus" style="display:block">
            <div class="col-lg-12">
    	        <div class="form-group">
					<!--<label for="payment_mode" class="mb-3 control-label">Paid Through</label>-->
					
			
					<div class="mb-9 cash">
					    <!--, 'cheque'=>'Cheque'-->
						<?php //echo form_dropdown('data[order_payments][payment_mode]', ['cash'=>'Cash', 'online'=>'Online'], !set_value('data[order_payments][payment_mode]')?'cash':set_value('data[order_payments][payment_mode]'), ["id"=>'payment_mode', 'required'=>'required', 'class'=>'form-control', 'tab-index'=>1, 'style'=>'width:100%']);?>
						<?php // echo form_error('data[order_payments][payment_mode]'); ?>
					    <label for="payment_mode" class="mb-3 control-label">Cash</label>
					    <?=form_hidden('data[order_payments][0][payment_mode]', 'cash'); ?>
						<input type="text" name="data[order_payments][0][amount]" id="amount_0"  class="form-control fill_amount rcvdpay">
						<?php echo form_error('data[order_payments][0][amount]'); ?>
					
					</div>
					<div class="mb-9 upi" style="display:none">
					    <label for="payment_mode" class="mb-3 control-label">UPI</label>
					    <?=form_hidden('data[order_payments][1][payment_mode]', 'paytm'); ?>
						<input type="text" name="data[order_payments][8][amount]" id="amount_8" class="form-control rcvdpay">
						<?php echo form_error('data[order_payments][8][amount]'); ?>
					
					</div>
					<!-- <div class="mb-9 upi" style="display:none">
					    <label for="payment_mode" class="mb-3 control-label">Google Pay</label>
					    <?=form_hidden('data[order_payments][2][payment_mode]', 'gpay'); ?>
						<input type="text" name="data[order_payments][2][amount]" id="amount_2" class="form-control  rcvdpay">
						<?php echo form_error('data[order_payments][2][amount]'); ?>
					
					</div>
					<div class="mb-9 upi" style="display:none">
					    <label for="payment_mode" class="mb-3 control-label">Phonepe</label>
					    <?=form_hidden('data[order_payments][3][payment_mode]', 'phonepe'); ?>
						<input type="text" name="data[order_payments][3][amount]"  id="amount_3" class="form-control  rcvdpay">
						<?php echo form_error('data[order_payments][3][amount]'); ?>
					
					</div>
					<div class="mb-9 cash">
					    <label for="payment_mode" class="mb-3 control-label">IMPS</label>
					    <?=form_hidden('data[order_payments][4][payment_mode]', 'imps'); ?>
						<input type="text" name="data[order_payments][4][amount]" id="amount_4" class="form-control  rcvdpay">
						<?php echo form_error('data[order_payments][4][amount]'); ?>
					
					</div>
					<div class="mb-9 cash">
					    <label for="payment_mode" class="mb-3 control-label">RTGS</label>
					    <?=form_hidden('data[order_payments][5][payment_mode]', 'rtgs'); ?>
						<input type="text" name="data[order_payments][5][amount]" id="amount_5" class="form-control  rcvdpay">
						<?php echo form_error('data[order_payments][5][amount]'); ?>
					
					</div>
					<div class="mb-9 cash">
					    <label for="payment_mode" class="mb-3 control-label">Neft</label>
					    <?=form_hidden('data[order_payments][6][payment_mode]', 'neft'); ?>
						<input type="text" name="data[order_payments][6][amount]" id="amount_6" class="form-control  rcvdpay">
						<?php echo form_error('data[order_payments][6][amount]'); ?>
					
					</div>
					<div class="mb-9 cash">
					    <label for="payment_mode" class="mb-3 control-label">Others</label>
					    <?=form_hidden('data[order_payments][7][payment_mode]', 'others'); ?>
						<input type="text" name="data[order_payments][7][amount]" id="amount_7" class="form-control">
						<?php echo form_error('data[order_payments][7][amount]'); ?>
					
					</div> -->
				</div>
    	    </div>
    	</div>
    <!--	<div class="row paid-through orderstatus" id="cheque-details"  style="display:none">
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
    	</div>-->
    	<!--<div class="row paid-through orderstatus" id="cash-details" style="display:block">-->
     <!--       <div class="col-lg-6">-->
    	<!--        <div class="form-group">-->
					<!--<label for="cash_paid_on" class="mb-3 control-label">Cash Paid On</label>-->
					<!--<div class="mb-9">-->
						<?php //echo form_input(["name"=>'data[order_payments][cash_paid_on]', "id"=>'cash_paid_on', 'class'=>'form-control datepicker', 'value'=>date('d/m/Y')]);?>
						<?php //echo form_error('data[order_payments][cash_paid_on]'); ?>
				<!--	</div>-->
				<!--</div>-->
    <!--	    </div>-->
    	    
    <!--	</div>-->
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
    	    <h2> TOTAL: <span class="gross-amount"><?=$order['amount_after_tax']?></span></h2>
    	    </div>
    	</div>
    </div>
    <!--Grid column-->

   

  </div>
  <!-- Grid row -->
        <div class="box-footer">  
			<button type="submit" class="btn btn-info pull-left">Receive Payment</button> &nbsp;&nbsp;&nbsp;&nbsp;
			<?php /*echo nbs(3);*/ ?>
			<button type="reset" class="btn btn-info">Reset</button>&nbsp;&nbsp;&nbsp;&nbsp;
			<a href="javascript:history.back()" class="btn btn-info">Back</a>
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

// $(document).ready(function () {

	// $('#make_delivery').validate({ // initialize the plugin
	// 	onkeyup: false,
   	// 	onclick: false,
	// 	rules: {
	// 		'data[orders][delivery_remark]': {
	// 			required: true,
	// 		}
	// 	},
	// 	message: {
	// 		'data[orders][delivery_remark]': {
	// 			required: 'Enter Remark',
	// 		}

	// 	},
		
	// });

// });
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
		   $(".payment_type").hide();
       }else{
           $("#orderstatus").show();
		   $(".payment_type").show();
           $("#cash-details").show();
       }
       //alert(value);
    });
	$(document).on('change', '#payment_type_id', function(){
       var value = $(this).val();
       if(value=='2'){
           //alert(value);
           $(".cash").hide();
		   $(".upi").show();
       }else if(value == '3'){
           $(".cash").show();
           $(".upi").show();
       }else{
		$(".upi").hide();
		$(".cash").show();
	   }
       //alert(value);
    });
    
    $("form").on('submit', function(){

		if($('#order_status_id').val() == '1' || $('#order_status_id').val() == '2'){
			if($('#delivery_remark').val() == '')
			{
				Toast.fire({
					icon: 'error',
					title: 'Please Enter Remark'
				})
				return false;
			}else{
				if(copyToClipboard($('#delivery_remark').val())){
					// alert('Remark Copied to Clipboard : '+$('#delivery_remark').val());
					if (confirm('Delivery Status change to '+$('#order_status_id option:selected').text()+'. Do you want to proceed')) {
						window.open("https://web.whatsapp.com/send?phone=9909838088", "_new");
					}else{
						return false;
					}
				}
			}
			
		}else{
			var rcvdPay = 0;
			var invAmount = parseFloat(<?=$order['amount_after_tax']?>);
			$('.rcvdpay').each(function(){
				//alert($(this).val());
				if($(this).val()!='')
				rcvdPay = rcvdPay+parseFloat($(this).val());
			})
			//alert(rcvdPay+" "+invAmount);
			if(invAmount!=rcvdPay){
				if(invAmount>rcvdPay){
					if (confirm('You are giving credit of '+((invAmount-rcvdPay).toFixed(2))+'. Do you want to proceed')) {
						return true;
						
					}else{
						return false;
					}
				}else if(invAmount<rcvdPay){
					if (confirm('Amount of '+((rcvdPay-invAmount).toFixed(2))+' is taken additional')) {
						return true;
					}else{
						return false;
					}
				}
			}else {
				return true;
			}
			
			return false;
		}
       
        
    })
	function copyToClipboard(text) {
		var sampleTextarea = document.createElement("textarea");
		document.body.appendChild(sampleTextarea);
		sampleTextarea.value = text; //save main text in it
		sampleTextarea.select(); //select textarea contenrs
		document.execCommand("copy");
		document.body.removeChild(sampleTextarea);
		return true;
	}
	// function WhatsAppRemark()
    // {

    //         window.open("https://web.whatsapp.com/send?phone=", "_new");

    // }
</script>


