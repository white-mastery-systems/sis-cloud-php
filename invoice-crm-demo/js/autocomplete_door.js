

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
	  //alert("split="+splitsize[0]);
	  // alert("split="+splitsize[1]);
	   // alert("split="+splitsize[2]);
	  resd1=splitsize[0].search('"');
	 // alert("searchd1="+resd1);
	  ress1=splitsize[0].search("'");
	  //alert("searchs1="+ress1);
	  resd2=splitsize[1].search('"');
	 // alert("searchd2="+resd2);
	  ress2=splitsize[1].search("'");
	 // alert("searchs2="+ress2);
	  
	   resd3=splitsize[2].search('"');
	 //  alert("searchd3="+resd3);
	  ress3=splitsize[2].search("'");
	 //  alert("searchs3="+ress3);
	
	  
			
		 
	 if(resd1 >= 0)
	 {		 
	 
		 var resv = splitsize[0].slice(0,resd1);	
		  resv1 = resv.split("'");
		 //alert("result"+resv1[1]);
		 valued1 = parseFloat(resv1[1])/12; 
		  //alert("d1="+ valued1);
		 
	 }
	 else
	 {
		 valued1 = 0;
		  // alert("d1="+ valued1);
	 }
	 if(ress1 >= 0)
	 {
		  var resv = splitsize[0].slice(0,ress1);		 
		  values1 = parseFloat(resv)*1; 
		  
		    //alert( "s1="+values1);
	 }
 else
	 {
				 
		  values1 = 0; 
		// alert("s1="+values1);
	 }
	
	if(resd2 >= 0)
	 {
		 
		 var resv = splitsize[1].slice(0,resd2);
		   resv1 = resv.split("'");
		 //alert("result"+resv1[1]);
	     valued2 = parseFloat(resv1[1])/12;
		  // alert("d2="+ valued2);
	 }
	 else
	 {
		 
		 		 
	     valued2 = 0; 
		// alert( "d2="+svalued2);
	 } 
	 if(ress2 >= 0)
	 {
		  var resv = splitsize[1].slice(0,ress2);		 
		  values2 = parseFloat(resv)*1; 
		   // alert( "s2="+values2);
	 }
	  else
	 {
		 	 
		  values2 =0; 
		    //alert( "s2="+values2);
	 }
	  if(resd3 >= 0)
	 {
		
		 var resv = splitsize[2].slice(0,resd3);
	  resv1 = resv.split("'");
		 //alert("result"+resv1[1]);
		 valued3 = parseFloat(resv1[1])/12; 
		  // alert( "d3="+valued3);
	 }
	 else
	 {
		 				 
		 valued3 = 0; 
		   //alert( "d3="+valued3);
	 }
	 if(ress3 >= 0)
	 {
		  var resv = splitsize[2].slice(0,ress3);		 
		  values3 = parseFloat(resv)*1; 
		    //alert( "s3="+values3);
	 }
	 else
	 {
		 	 
		  values3 = 0; 
		   // alert( "s3="+values3);
	 }
	value1 = parseFloat(valued1) + parseFloat(values1);
	value2 = parseFloat(valued2) + parseFloat(values2);
	value3 = parseFloat(valued3)+parseFloat(values3); 
	
	// valuer = value1+value2+value3;
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
	  //alert("split="+splitsize[0]);
	  // alert("split="+splitsize[1]);
	   // alert("split="+splitsize[2]);
	  resd1=splitsize[0].search('"');
	 // alert("searchd1="+resd1);
	  ress1=splitsize[0].search("'");
	  //alert("searchs1="+ress1);
	  resd2=splitsize[1].search('"');
	 // alert("searchd2="+resd2);
	  ress2=splitsize[1].search("'");
	 // alert("searchs2="+ress2);
	  
	   resd3=splitsize[2].search('"');
	 //  alert("searchd3="+resd3);
	  ress3=splitsize[2].search("'");
	 //  alert("searchs3="+ress3);
	
	  
			
		 
	 if(resd1 >= 0)
	 {		 
	 
		 var resv = splitsize[0].slice(0,resd1);	
		  resv1 = resv.split("'");
		 //alert("result"+resv1[1]);
		 valued1 = parseFloat(resv1[1])/12; 
		  //alert("d1="+ valued1);
		 
	 }
	 else
	 {
		 valued1 = 0;
		  // alert("d1="+ valued1);
	 }
	 if(ress1 >= 0)
	 {
		  var resv = splitsize[0].slice(0,ress1);		 
		  values1 = parseFloat(resv)*1; 
		  
		    //alert( "s1="+values1);
	 }
 else
	 {
				 
		  values1 = 0; 
		// alert("s1="+values1);
	 }
	
	if(resd2 >= 0)
	 {
		 
		 var resv = splitsize[1].slice(0,resd2);
		   resv1 = resv.split("'");
		 //alert("result"+resv1[1]);
	     valued2 = parseFloat(resv1[1])/12;
		  // alert("d2="+ valued2);
	 }
	 else
	 {
		 
		 		 
	     valued2 = 0; 
		// alert( "d2="+svalued2);
	 } 
	 if(ress2 >= 0)
	 {
		  var resv = splitsize[1].slice(0,ress2);		 
		  values2 = parseFloat(resv)*1; 
		   // alert( "s2="+values2);
	 }
	  else
	 {
		 	 
		  values2 =0; 
		    //alert( "s2="+values2);
	 }
	  if(resd3 >= 0)
	 {
		
		 var resv = splitsize[2].slice(0,resd3);
	  resv1 = resv.split("'");
		 //alert("result"+resv1[1]);
		 valued3 = parseFloat(resv1[1])/12; 
		  // alert( "d3="+valued3);
	 }
	 else
	 {
		 				 
		 valued3 = 0; 
		   //alert( "d3="+valued3);
	 }
	 if(ress3 >= 0)
	 {
		  var resv = splitsize[2].slice(0,ress3);		 
		  values3 = parseFloat(resv)*1; 
		    //alert( "s3="+values3);
	 }
	 else
	 {
		 	 
		  values3 = 0; 
		   // alert( "s3="+values3);
	 }
	value1 = parseFloat(valued1) + parseFloat(values1);
	value2 = parseFloat(valued2) + parseFloat(values2);
	value3 = parseFloat(valued3)+parseFloat(values3); 
	
	// valuer = value1+value2+value3;
	valuer = (parseFloat(value1)*parseFloat(value2)*parseFloat(value3))*1;  
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

