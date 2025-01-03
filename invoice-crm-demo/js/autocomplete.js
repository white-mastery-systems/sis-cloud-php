


//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});




//autocomplete script
$(document).on('focus','.autocomplete_txt',function(){
	type = $(this).data('type');
	//alert(type);
	if(type =='product_code' )autoTypeNo=0;
	if(type =='pro_name' )autoTypeNo=1; 	
	
	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'purchase_wautocomplete.php',
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
		//minLength: 0,
		select: function( event, ui ) {
			var names = ui.item.data.split("|");						
			id_arr = $(this).attr('id');
	  		id = id_arr.split("_");
			$('#itemCode_'+id[1]).val(names[0]);
			
			$('#itemDesc_'+id[1]).val(names[1]);
			$('#itemHeight_'+id[1]).val(names[3]);
			$('#itemWidth_'+id[1]).val(names[4]);
			//$('#ed').val(names[8]);
			//$('#st').val(names[9]);
			//$('#vat').val(names[7]);
			$('#itemQty_'+id[1]).val(1);
			$('#itemBasicm_'+id[1]).val(names[5]);
			$('#itemTotalm_'+id[1]).val( 1*names[5] );
			$('#itemBasici_'+id[1]).val(names[6]);
			$('#itemTotali_'+id[1]).val( 1*names[6] );
			$('#itemBrand_'+id[1]).val(names[7]);
			$('#itemUnit_'+id[1]).val(names[8]);
			$('#itemLineTotal_'+id[1]).val( 1*((+names[5])+ (+names[6])));
			
			calculateTotal();
		}		      	
	});
});

//price change
$(document).on('change keyup blur','.changesNo',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	quantity = $('#itemQty_'+id[1]).val();
	
	itembasicm = $('#itemBasicm_'+id[1]).val();
	itembasici = $('#itemBasici_'+id[1]).val();
	
	if( quantity!='' && itembasicm !='' ) $('#itemTotalm_'+id[1]).val( (parseFloat(itembasicm)*parseFloat(quantity)).toFixed(2) );
	if( quantity!='' && itembasici !='' ) $('#itemTotali_'+id[1]).val( (parseFloat(itembasici)*parseFloat(quantity)).toFixed(2) );
	itembasicmt =  $('#itemTotalm_'+id[1]).val();
	itembasicit =  $('#itemTotali_'+id[1]).val();
	if( itembasici!='' && itembasicm !='' ) $('#itemLineTotal_'+id[1]).val((parseFloat(itembasicmt)+parseFloat(itembasicit)).toFixed(2) );
	calculateTotal();
});

$(document).on('change keyup blur','#ed',function(){
	calculateTotal();
});


//total price calculation 
function calculateTotal(){
	subTotal = 0 ; total = 0; 
	 
	total1 = 0;
	total2 = 0;
	total3 = 0;
	subTotal1 = 0; 
	subTotal2 = 0;taxAmount1 =0;
	
	
	
	$('.totalLinePrice').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});
	$('.totalLinePrice1').each(function(){
		if($(this).val() != '' )subTotal1 += parseFloat( $(this).val() );
	});
	$('.totalLinePrice2').each(function(){
		if($(this).val() != '' )subTotal2 += parseFloat( $(this).val() );
	});
	$('#subTotal').val(subTotal.toFixed(2) );
	//$('#totalAftertax').val(subTotal.toFixed(2) );
	$('#basictotal').val( subTotal1.toFixed(2) );
	$('#gt').val( subTotal1.toFixed(2) );
	$('#itotal').val( subTotal2.toFixed(2) );
	$('#gt1').val( subTotal2.toFixed(2) );
	tax = $('#ed').val();
    
   
	if(tax != '' && typeof(tax) != "undefined" ){
		taxAmount = subTotal1 * ( parseFloat(tax) /100 );
		$('#taxAmount').val(taxAmount.toFixed(2));
		total = subTotal + taxAmount;
		total1 = subTotal1 + taxAmount;
		$('#totalAftertax').val( total.toFixed(2) );
	$('#gt').val( total1.toFixed(2) );
	}else{
		$('#taxAmount').val(0);
		total = subTotal;
		total1 = subTotal1;
		$('#totalAftertax').val( total.toFixed(2) );
	$('#gt').val( total1.toFixed(2) );
	}
	
	calculateAmountDue();
	calculatevat();
	calculatest();
}

$(document).on('change keyup blur','#vat',function(){
	calculatevat();
});


$(document).on('change keyup blur','#vatdr',function(){
	calculateTotal3();
});

//total price calculation 
function calculateTotal3(){
	subTotal = 0 ; total = 0; 
	$('.totalLinePrice').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});
	$('#subTotal').val( subTotal.toFixed(2) );
	tax = $('#vatdr').val();
	if(tax != '' && typeof(tax) != "undefined" ){
		taxAmount = subTotal * ( parseFloat(tax) /100 );
		$('#taxAmountdr').val(taxAmount.toFixed(2));
		total = subTotal + taxAmount;
	}else{
		$('#taxAmountdr').val(0);
		total = subTotal;
	}
	$('#totalAftertaxdr').val( total.toFixed(2) );
}



$(document).on('change keyup blur','#tp1',function(){
	calculateAmountDue();
});






//due amount calculation
function calculateAmountDue(){
	
	amountPaid = $('#tp').val();
	subTotal = $('#subTotal').val();
	basictotal = $('#basictotal').val();
	taxAmount = $('#taxAmount').val();
	//total1 = $('#gt').val();
	if(amountPaid != '' && typeof(amountPaid) != "undefined" ){
		 $('#tp1').val(amountPaid);
		amountDue1 = parseFloat(basictotal) + parseFloat( amountPaid ) + parseFloat( taxAmount )+parseFloat( taxAmount1 );
		totaltax = parseFloat(subTotal) + parseFloat( amountPaid ) + parseFloat( taxAmount )+parseFloat( taxAmount1 );
		
		$('#gt').val( amountDue1.toFixed(2) );
		$('#totalAftertax').val(totaltax.toFixed(2) );
	}else{
		$('#tp1').val(0);
	amountDue1 = parseFloat(basictotal)  + parseFloat( taxAmount )+parseFloat( taxAmount1 );
		totaltax = parseFloat(subTotal) +  parseFloat( taxAmount )+parseFloat( taxAmount1 ) ;
		
		$('#gt').val( amountDue1.toFixed(2) );
		$('#totalAftertax').val(totaltax.toFixed(2) );
		
	}
}

$(document).on('change keyup blur','#st',function(){
	calculatest();
});
function calculatest(){

	st = $('#st').val();
	taxamount = 0;
	taxAmount1 = 0;
	taxAmount2 = 0;
	amountPaid = 0;
	subTotal = $('#subTotal').val();
	basictotal = $('#basictotal').val();
	taxAmount = $('#taxAmount').val();
	taxAmount1 = $('#taxAmount1').val();
	taxAmount2 = $('#taxAmount2').val();
	itotal = $('#itotal').val();
	amountPaid = $('#tp1').val();
	//totalAftertax = $('#taxAmount').val();
	//total1 = $('#gt').val();
	
	if(st != '' && typeof(st) != "undefined" ){
			taxAmount2 = parseFloat(itotal) * ( parseFloat(st) /100 );
		$('#taxAmount2').val(taxAmount2.toFixed(2));
	
		
		
		
		amountDue1 = parseFloat(itotal) + parseFloat( taxAmount2 );
		totaltax = parseFloat(subTotal) + parseFloat( taxAmount1 ) + parseFloat( taxAmount )+ parseFloat( taxAmount2 )+parseFloat( amountPaid )  ;
			$('#gt1').val( amountDue1.toFixed(2) );
		$('#totalAftertax').val(totaltax.toFixed(2));
		
	}else{
		
		$('#taxAmount2').val(0);
		
		amountDue1 = parseFloat(itotal);
		totaltax = parseFloat(subTotal) + parseFloat(taxAmount1) + parseFloat(taxAmount) + parseFloat(amountPaid);
		
				
	$('#gt1').val( amountDue1.toFixed(2) );
		$('#totalAftertax').val(totaltax.toFixed(2));
			}
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

