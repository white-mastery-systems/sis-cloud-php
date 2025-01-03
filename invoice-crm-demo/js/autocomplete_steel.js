

//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//autocomplete script
$(document).on('focus','.autocomplete_txtsteel',function(){
													
	type = $(this).data('type');
	//alert(type);
	if(type =='product_code' )autoTypeNo=0;
	if(type =='pro_name' )autoTypeNo=1; 	
	
	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'purchase_autocompletesteel.php',
				dataType: "json",
				method: 'post',
				data: {
				   name_startsWith: request.term,
				   type: type
				},
				 success: function( data ) {
					 response( $.map( data, function( item ) {
					 	var code = item.split("|");
						return {
							label: code[autoTypeNo],
							value: code[autoTypeNo],
							data : item
						}
					}));
				}
			});
		},
		autoFocus: true,	      	
		minLength: 0,
		select: function( event, ui ) {
			var names = ui.item.data.split("|");						
			id_arr = $(this).attr('id');
	  		id = id_arr.split("_");
			
			$('#itemDesc_'+id[1]).val(names[1]);
			$('#itemPrice_'+id[1]).val(names[2]);
			$('#itemQty_'+id[1]).val(1);
			$('#itemLineTotal_'+id[1]).val( 1*names[2] );
			$('#itemVat_'+id[1]).val(0);
			$('#details_'+id[1]).val(names[6]);
			$('#units_'+id[1]).val(names[7]);
			$('#sub_catname_'+id[1]).val(names[4]);
			$('#product_code_'+id[1]).val(names[0]);
			$('#cat_name_'+id[1]).val(names[3]);
			$('#brand_'+id[1]).val(names[5]);
			
			calculateTotal2();
		}		      	
	});
});

//price change

//price change
$(document).on('change keyup blur','.changesNo_st',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	quantity = $('#itemQty_'+id[1]).val();
	price = $('#itemPrice_'+id[1]).val();
	itemvat = $('#itemVat_'+id[1]).val();
	cal= (((parseFloat(price)*parseFloat(quantity)))*(parseFloat(itemvat) /100));
	//alert(cal);
	totalamt = ((parseFloat(price)*parseFloat(quantity))) + cal;
	if( quantity!='' && price !='' && itemvat !='' ) $('#itemLineTotal_'+id[1]).val(totalamt.toFixed(2));	
	calculateTotal2();
});

//total price calculation 
function calculateTotal2(){
	subTotal = 0 ; total = 0; 
	$('.totalLinePricest').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});
	$('#subTotal').val( subTotal.toFixed(2) );
	
}




//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}

