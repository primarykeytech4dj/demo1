$(document).ready(function() { 
	/*place jQuery actions here*/ 
	var link = base_url;
	$(".cart-product").submit(function() {
		//alert("added to cart");
		var id = $(this).find('input[name=product_id]').val();
		//var qty = $(this).find('input[name=quantity]').val();
		var qty = 1;
		$.post(link + "cart/add_cart_item", { product_id: id, quantity: qty, ajax: '1' },
				function(data){	
					console.log(data);
		 			// Interact with returned data
		 			if(data == 'true'){
 		 			
    					$.get(link + "cart/show_cart", function(cart){ // Get the contents of the url cart/show_cart
  							$("#cart_content").html(cart); // Replace the information in the div #cart_content with the retrieved data
						}); 		 
						alert("Item Added To Cart");
										
 		 			}else{
 		 				alert("Product does not exist");
 		 			}
			 });
	//alert('ID:' + id + '\n\rQTY:' + qty);

		return false; // Stop the browser of loading the page defined in the form "action" parameter.
	});

	$(".empty").on('live', "click", function(e){
		e.preventDefault();
    	$.get(link + "cart/empty_cart", function(){
    		$.get(link + "cart/show_cart", function(cart){
  				$("#cart_content").html(cart);
			});
		});
		
		return false;
    });
});

$('.variation').on('click onchange',function(){
	//alert($(this).attr('data-value'));
	var id = this.id;
	var dataTarget = $(this).attr('data-target'); 
	var datavalue = $(this).attr('data-value');
	if(typeof datavalue === 'undefined'){
		//alert(id);
		//console.log($('#'+id+' option:selected').text());
		$('#'+dataTarget).val($('#'+id+' option:selected').text());
	}else{
		$('.filter-color-box').css('width', '35px').css('height', '35px').css('margin-bottom', '0px');
		$('#'+dataTarget).val(datavalue);
		$(this).css('width','25px').css('height', '25px').css('margin-bottom', '10px');
	}
	//alert('hii');
	return false;
});