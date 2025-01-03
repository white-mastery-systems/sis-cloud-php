

//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});



//autocomplete script
$(document).on('focus','.autocomplete_txtdoor',function(){
	type = $(this).data('type');
	//alert(type);
	if(type =='product_code' )autoTypeNo=0;
	if(type =='pro_name' )autoTypeNo=1; 	
	
	$(this).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : 'purchase_autocomplete.php',
				dataType: "json",
				method: 'post',
				data: {
				   name_startsWith: request.term,
				   type: type
				},
				 success: function( data ) {
					 response($.map( data, function( item ) {
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
			
			$('#itemQtyd_'+id[1]).val(1);
			$('#units_'+id[1]).val(names[7]);
			$('#sub_catname_'+id[1]).val(names[4]);
			$('#itemCode_'+id[1]).val(names[0]);
			$('#cat_name_'+id[1]).val(names[3]);
			$('#brand_'+id[1]).val(names[5]);
			$('#size_'+id[1]).val(names[6]);
				size = $('#size_'+id[1]).val();
splitsize = size.split("x");
valu = splitsize[0]+"-----"+splitsize[1]+"-----"+splitsize[2];
	  $('#itemQtyds_'+id[1]).val(valu);
	  resd1=splitsize[0].search('"');
	  ress1=splitsize[0].search("'");
	  resd2=splitsize[1].search('"');
	  ress2=splitsize[1].search("'");
	   resd3=splitsize[2].search('"');
	  ress3=splitsize[2].search("'");
	 if(resd1 > 0)
	 {
		 
		 var resv = splitsize[0].slice(0,resd1);		 
		 value1 = parseFloat(resv)/12; 
	 }
	 else if(ress1 > 0)
	 {
		  var resv = splitsize[0].slice(0,ress1);		 
		  value1 = parseFloat(resv)*1; 
	 }
	 if(resd2 > 0)
	 {
		 
		 var resv = splitsize[1].slice(0,resd2);		 
	     value2 = parseFloat(resv)/12; 
	 }
	 else if(ress2 > 0)
	 {
		  var resv = splitsize[1].slice(0,ress2);		 
		  value2 = parseFloat(resv)*1; 
	 }
	  if(resd3 > 0)
	 {
		 
		 var resv = splitsize[2].slice(0,resd3);		 
		 value3 = parseFloat(resv)/12; 
	 }
	 else if(ress3 > 0)
	 {
		  var resv = splitsize[2].slice(0,ress3);		 
		  value3 = parseFloat(resv)*1; 
	 }
	valuer = (parseFloat(value1)*parseFloat(value2)*parseFloat(value3))*1;  
	$('#itemQty_'+id[1]).val(valuer.toFixed(2));
	$('#itemLineTotal_'+id[1]).val( (valuer * names[2]).toFixed(2) );
	calculateTotal3();
		}		      	
	});
});

//price change
$(document).on('change keyup blur','.changesNo_dr',function(){
															
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	size = $('#size_'+id[1]).val();
splitsize = size.split("x");
valu = splitsize[0]+"-----"+splitsize[1]+"-----"+splitsize[2];
	  $('#itemQtyds_'+id[1]).val(valu);
	  resd1=splitsize[0].search('"');
	  ress1=splitsize[0].search("'");
	  resd2=splitsize[1].search('"');
	  ress2=splitsize[1].search("'");
	   resd3=splitsize[2].search('"');
	  ress3=splitsize[2].search("'");
	 if(resd1 > 0)
	 {
		 
		 var resv = splitsize[0].slice(0,resd1);		 
		 value1 = parseFloat(resv)/12; 
	 }
	 else if(ress1 > 0)
	 {
		  var resv = splitsize[0].slice(0,ress1);		 
		  value1 = parseFloat(resv)*1; 
	 }
	 if(resd2 > 0)
	 {
		 
		 var resv = splitsize[1].slice(0,resd2);		 
	     value2 = parseFloat(resv)/12; 
	 }
	 else if(ress2 > 0)
	 {
		  var resv = splitsize[1].slice(0,ress2);		 
		  value2 = parseFloat(resv)*1; 
	 }
	  if(resd3 > 0)
	 {
		 
		 var resv = splitsize[2].slice(0,resd3);		 
		 value3 = parseFloat(resv)/12; 
	 }
	 else if(ress3 > 0)
	 {
		  var resv = splitsize[2].slice(0,ress3);		 
		  value3 = parseFloat(resv)*1; 
	 }
	valuer = (parseFloat(value1)*parseFloat(value2)*parseFloat(value3));     
	quantity = $('#itemQtyd_'+id[1]).val();	
	qty = parseFloat(quantity) * parseFloat(valuer);
	$('#itemQty_'+id[1]).val(qty.toFixed(2));	
	price = $('#itemPrice_'+id[1]).val();
	totalamt = ((parseFloat(price)*parseFloat(qty))) ;
	if( quantity!='' && price !='' ) $('#itemLineTotal_'+id[1]).val(totalamt.toFixed(2));	
	calculateTotal3();
});

$(document).on('change keyup blur','#vatdr',function(){
	calculateTotal3();
});

//total price calculation 
function calculateTotal3(){
	subTotal = 0 ; total = 0; 
	$('.totalLinePricedr').each(function(){
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



//It restrict the non-numbers
var specialKeys = new Array();
specialKeys.push(8,46); //Backspace
function IsNumeric(e) {
    var keyCode = e.which ? e.which : e.keyCode;
    console.log( keyCode );
    var ret = ((keyCode >= 48 && keyCode <= 57) || specialKeys.indexOf(keyCode) != -1);
    return ret;
}

