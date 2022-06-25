$(document).on('keyup', '.dispatchqty', function(){
	//alert("hii");
	var id = this.id;
	var trId = $(this).closest('tr').attr('id');
	var qty = $('#'+id).val();
	var balanceQty = $("#balance_qty_"+trId).val();
	balanceQty = parseFloat(balanceQty.match(/[-+]?([0-9]*\.[0-9]+|[0-9]+)/));
	balanceQty = balanceQty+1;
	console.log(id+' '+trId+' '+qty+' '+parseFloat(balanceQty));
	if(qty>balanceQty){
		alert("Quantity cannot be greater than "+balanceQty);
		$("#qty_"+trId).val('');
	}
});

$('#stock_report').on('submit', function(e){
	var formData = $(this).serializeArray();
	e.preventDefault();
	var dataPath =  'orders/admin_order_form_2';
	var modalId = 'modal-'+new Date().getTime();
	var modalTitle = 'Create New Sales Order';
	$.ajax({
	type: 'GET',
	dataType: 'html',
	url : base_url+dataPath,
	data:formData,
	success: function(response) {
		console.log(response);
	$("#modal-handler").append("<div class=modal id='"+modalId+"'><div class='modal-dialog modal-lg'><div class='modal-content'><div class='modal-header'><button type='button' class='close' data-dismiss='modal-default' aria-label='Close' id='close_"+modalId+"'><span aria-hidden='true'>&times;</span></button><h4 class='modal-title'>"+modalTitle+"</h4></div><div class='modal-body' style='overflow-y: auto;'>"+response+"</div><div class='modal-footer' id='response_"+modalId+"'></div></div></div></div>");
	$('#'+modalId).modal();
	init();
	}
	});
	return false;
});

$(document).on('keyup change', '.calculate', function(){
    var tr = $('.details').find('tbody').find('tr');
    var amtAfterTax = 0.00;
    var amtBeforeTax = 0.00;
    $.each(tr, function(key, val){
        var qty = $('#qty_'+key).val();
        var rate = $('#unit_price_'+key).val();
        var tax = $('#tax_'+key).val();
        var price = qty*rate;
        amtBeforeTax = amtBeforeTax+price;
        var taxAmount = (tax/100.00)*price;
        //console.log(taxAmount);
        amtAfterTax = amtAfterTax+price+taxAmount;
        //console.log(amtAfterTax);
    })
    $('#amount_before_tax').val(amtBeforeTax.toFixed(2));
    $('#amount_after_tax').val(amtAfterTax.toFixed(2));
    var othercharges = $("#other_charges").val();
    if(othercharges==='')
        othercharges = 0.00;
    var amtwithotc = parseFloat(amtAfterTax)+parseFloat(othercharges);
    //console.log("other charges-"+amtwithotc);
    
    var discountType = $("input[name='discountType']:checked"). val();
    //console.log("discounttype="+discountType);
    if(discountType=="value"){
        var discount = $("#discountamt").val();
        var discountpercent = (discount/amtAfterTax)*100.00;
        $("#discount").val(discountpercent);
        //console.log("dis="+discount);
        $("#grand_total").html((amtwithotc-discount).toFixed(2));
    }else{
        var discount = $("#discount").val();
        discountAmount = (discount/100.00)*amtAfterTax.toFixed(2);
        $("#discountamt").val(discountAmount);
        $("#grand_total").html((amtwithotc-discountAmount).toFixed(2));
    }
    
    
    
    //console.log(tr);
});

$("input[name='discountType']").on('change', function(){
    var discountType = $("input[name='discountType']:checked").val();
    //console.log("reached here="+discountType);
    if(discountType==="value"){
        $('#discount').prop('readonly', true);
        $('#discountamt').prop('readonly', false);
    }else{
        $('#discount').prop('readonly', false);
        $('#discountamt').prop('readonly', true);
    }
});

$(document).on('keyup change', '.calculate2', function(){
    var tr = $('.details').find('tbody').find('tr');
    var amtAfterTax = 0.00;
    var amtBeforeTax = 0.00;
    $.each(tr, function(key, val){
        //alert($('#is_active_'+key+':checked').length);
        var qty = $('#qty_'+key).val();
        var rate = $('#unit_price_'+key).val();
        var tax = $('#tax_'+key).val();
        var price = qty*rate;
        var taxAmount = (tax/100.00)*price;
        $('#total_'+key).val(price+taxAmount);
        if($('#is_active_'+key+':checked').length > 0){
            
            amtBeforeTax = amtBeforeTax+price;
            amtAfterTax = amtAfterTax+price+taxAmount;
        }
        //console.log(amtAfterTax);
    })
    $('#amount_before_tax').val(amtBeforeTax.toFixed(2));
    $('#amount_after_tax').val(amtAfterTax.toFixed(2));
    var othercharges = $("#other_charges").val();
    if(othercharges==='')
        othercharges = 0.00;
    var amtwithotc = parseFloat(amtAfterTax)+parseFloat(othercharges);
    //console.log("other charges-"+amtwithotc);
    
    var discountType = $("input[name='discountType']:checked"). val();
    //console.log("discounttype="+discountType);
    if(discountType=="value"){
        var discount = $("#discountamt").val();
        var discountpercent = (discount/amtAfterTax)*100.00;
        $("#discount").val(discountpercent);
        //console.log("dis="+discount);
        $("#grand_total").html((amtwithotc-discount).toFixed(2));
    }else{
        var discount = $("#discount").val();
        discountAmount = (discount/100.00)*amtAfterTax.toFixed(2);
        $("#discountamt").val(discountAmount);
        $("#grand_total").html((amtwithotc-discountAmount).toFixed(2));
    }
    
    
    
    //console.log(tr);
})
