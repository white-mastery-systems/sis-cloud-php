

//to check all checkboxes
$(document).on('change','#check_all',function(){
	$('input[class=case]:checkbox').prop("checked", $(this).is(':checked'));
});

//autocomplete script
$(document).on('focus','.autocomplete_txt1',function(){
	type = $(this).data('type');
	//alert(type);
	if(type =='product_code')autoTypeNo=0;
	if(type =='pro_name')autoTypeNo=1; 	
	
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
			
			$('#itemDesc_'+id[1]).val(names[1]);
				$('#itemCode_'+id[1]).val(names[0]);
	
			$('#itemPrice_'+id[1]).val(names[2]);
			$('#itemQty_'+id[1]).val(1);
			$('#itemLineTotal_'+id[1]).val( 1*names[2] );
			$('#itemVat_'+id[1]).val(0);
            $('#itemVatamount_'+id[1]).val(0);
			$('#units_'+id[1]).val(names[7]);
			$('#sub_catname_'+id[1]).val(names[4]);
		
			$('#size_'+id[1]).val(names[6]);
			$('#cat_name_'+id[1]).val(names[3]);
			$('#brand_'+id[1]).val(names[5]);
			
			calculateTotal1();
            
		}		      	
	});
});

//price change

//price change
$(document).on('change keyup blur','.changesNo1',function(){
	id_arr = $(this).attr('id');
	id = id_arr.split("_");
	quantity = $('#itemQty_'+id[1]).val();
	price = $('#itemPrice_'+id[1]).val();
	itemvat = $('#itemVat_'+id[1]).val();
	cal= (((parseFloat(price)*parseFloat(quantity)))*(parseFloat(itemvat) /100));
	//alert(cal);
   $('#itemVatamount_'+id[1]).val(cal);
    $('#itemtotalamount_'+id[1]).val((parseFloat(price)*parseFloat(quantity)).toFixed(2));
	totalamt = ((parseFloat(price)*parseFloat(quantity))) + cal;
	if( quantity!='' && price !='' && itemvat !='' ) $('#itemLineTotal_'+id[1]).val(totalamt.toFixed(2));	
	calculateTotal1();
    
   
});




//total price calculation 




$(document).on('change keyup blur','#tp2',function(){
	calculateTotal1();
});

$(document).on('change keyup blur','#tptax2',function(){
	calculateTotaltptax();
});


//total price calculation 
function calculateTotal1(){
	subTotal = 0 ; total = 0; 
	$('.totalLinePrice_s').each(function(){
		if($(this).val() != '' )subTotal += parseFloat( $(this).val() );
	});

    		$('#subTotal1').val( subTotal.toFixed(2) );
	tp2 = $('#tp2').val();
    
   
	if(tp2 != '' && typeof(tp2) != "undefined" ){
        
		tpamount = (parseFloat(subTotal) + parseFloat(tp2));
		$('#transportAmount_s').val(tpamount.toFixed(2));
		total = tpamount;
        
        
	}else{
		$('#transportAmount_s').val(0);
		total = subTotal;
	}
    calculateTotaltptax();
	$('#grandTotals').val( total.toFixed(2) );
    
  
}

function calculateTotaltptax(){
	tptaxamount = 0;
    tpamount2 = 0;
    totals = 0;
    subTotal= 0;
    tp2=0;
    tptax2=0;
    subTotal=$('#subTotal1').val();
	tp2 = $('#tp2').val();
    tptax2 = $('#tptax2').val();   
  
	if(tptax2 != '' && typeof(tptax2) != "undefined" ){
        tptaxamount = (parseFloat(tp2))*(parseFloat(tptax2)/100);
		tpamount2 = (parseFloat(subTotal) + parseFloat(tp2)+parseFloat(tptaxamount));
        console.log("totaltaxamount="+tptaxamount);		
		totals = tpamount2;
        $('#transporttaxAmount_s').val(totals.toFixed(2));
       
         console.log("total="+ totals)
	}
    else{
		//$('#tptax2').val(0)
        tpamount2 = (parseFloat(subTotal) + parseFloat(tp2));		
        totals = tpamount2;
        $('#transportAmount_s').val(totals.toFixed(2));
        $('#transporttaxAmount_s').val(totals.toFixed(2));
        console.log("total="+ totals);
        
	}
	$('#grandTotals').val(totals.toFixed(2));
  
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

